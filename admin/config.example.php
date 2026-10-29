<?php
/**
 * Pasora admin — configuration TEMPLATE.
 *
 * Do NOT put real secrets in this file. Copy it to `config.local.php`
 * (which is git-ignored) — or just open /admin/ once and use the
 * first-run "Create password" screen, which writes config.local.php for you.
 *
 *   cp admin/config.example.php admin/config.local.php
 */

return [

    // bcrypt/argon2 hash of your admin password.
    // Leave empty to trigger the first-run setup screen at /admin/.
    // To generate manually:  php -r "echo password_hash('YOUR-PASSWORD', PASSWORD_DEFAULT);"
    'password_hash' => '',

    // How the "Deploy now" button publishes the latest code.
    //   'webhook' = send an HTTPS POST to a deploy URL (recommended on
    //               Hostinger shared hosting — no SSH needed).
    //   'git'     = run `git pull` on the server (needs SSH/git + a .git
    //               folder in the deploy path; Business/Cloud plans).
    'deploy_mode' => 'webhook',

    // For 'webhook' mode: the URL the button POSTs to. Paste the
    // "auto-deployment" webhook URL from hPanel → Git, or a GitHub
    // Actions dispatch URL. Keep this secret.
    'deploy_hook_url' => '',

    // For 'git' mode: the repo root on the server (where .git lives).
    // On Hostinger this is usually your public_html, i.e. the folder above /admin.
    'git_path' => dirname(__DIR__),

    // Optional: shared secret for the push auto-deploy receiver
    // (admin/webhook.php). Must match the secret you set on the
    // GitHub webhook. Leave empty to disable that endpoint.
    'github_webhook_secret' => '',

    // Shown in the admin header.
    'site_name' => 'Pasora Solution Ltd',

    // Lock the account for this many seconds after 5 failed logins.
    'lockout_seconds' => 300,
];
