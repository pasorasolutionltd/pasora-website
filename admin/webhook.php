<?php
/**
 * Optional: GitHub push webhook → auto-deploy (git mode only).
 *
 * Use this ONLY if you deploy with SSH/git and want pushes to publish
 * automatically. If you use Hostinger's built-in hPanel Git auto-deploy,
 * you do not need this file.
 *
 * Setup:
 *   1. Set 'github_webhook_secret' and 'git_path' in admin/config.local.php.
 *   2. In GitHub → repo → Settings → Webhooks → Add webhook:
 *        Payload URL : https://your-domain/admin/webhook.php
 *        Content type: application/json
 *        Secret      : (the same secret)
 *        Events      : Just the push event
 */

declare(strict_types=1);

header('Content-Type: text/plain; charset=utf-8');

$configFile = __DIR__ . '/config.local.php';
$config     = is_file($configFile) ? (array) (require $configFile) : [];
$secret     = trim((string) ($config['github_webhook_secret'] ?? ''));
$gitPath    = (string) ($config['git_path'] ?? dirname(__DIR__));

function deny(int $code, string $msg): void
{
    http_response_code($code);
    echo $msg . "\n";
    exit;
}

if ($secret === '') {
    deny(403, 'Webhook disabled: no github_webhook_secret configured.');
}

$payload   = file_get_contents('php://input') ?: '';
$signature = $_SERVER['HTTP_X_HUB_SIGNATURE_256'] ?? '';
$expected  = 'sha256=' . hash_hmac('sha256', $payload, $secret);

if ($signature === '' || !hash_equals($expected, $signature)) {
    deny(401, 'Invalid signature.');
}

$event = $_SERVER['HTTP_X_GITHUB_EVENT'] ?? '';
if ($event === 'ping') {
    echo "pong\n";
    exit;
}
if ($event !== 'push') {
    echo "Ignored event: " . preg_replace('/[^a-z_]/', '', $event) . "\n";
    exit;
}

if (!function_exists('shell_exec')
    || in_array('shell_exec', array_map('trim', explode(',', (string) ini_get('disable_functions'))), true)) {
    deny(500, 'shell_exec is disabled; cannot auto-deploy on this host.');
}
if (!is_dir($gitPath . '/.git')) {
    deny(500, 'No .git folder at the configured git_path.');
}

$out = (string) shell_exec('cd ' . escapeshellarg($gitPath) . ' 2>&1 && git pull 2>&1');
$out = trim($out) ?: 'git produced no output.';

$log = '[' . date('Y-m-d H:i:s') . '] WEBHOOK: ' . str_replace(["\r", "\n"], ' ', $out) . "\n";
@file_put_contents(__DIR__ . '/deploy.log', $log, FILE_APPEND | LOCK_EX);

echo $out . "\n";
