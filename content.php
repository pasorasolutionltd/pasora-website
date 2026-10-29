<?php
/**
 * Loads the homepage content: schema defaults merged with the admin's
 * saved overrides (admin/content.local.json, git-ignored, survives deploys).
 *
 * Exposes:
 *   $SCHEMA  - the grouped field schema (for the admin editor)
 *   $C       - flat [key => value] of the live content
 *   c($key)  - raw value
 *   ch($key) - HTML-escaped value (use this in markup)
 *   clist($key) - array split from a comma-separated 'list' field
 */

if (!isset($SCHEMA)) {
    $SCHEMA = require __DIR__ . '/content-defaults.php';
}

$C = [];
foreach ($SCHEMA as $group) {
    foreach ($group['fields'] as $key => $def) {
        $C[$key] = $def[2];
    }
}

$overrideFile = __DIR__ . '/admin/content.local.json';
if (is_file($overrideFile)) {
    $saved = json_decode((string) @file_get_contents($overrideFile), true);
    if (is_array($saved)) {
        foreach ($saved as $k => $v) {
            if (array_key_exists($k, $C) && is_string($v)) {
                $C[$k] = $v;
            }
        }
    }
}

if (!function_exists('c')) {
    function c(string $key): string
    {
        global $C;
        return isset($C[$key]) ? (string) $C[$key] : '';
    }
    function ch(string $key): string
    {
        return htmlspecialchars(c($key), ENT_QUOTES, 'UTF-8');
    }
    function clist(string $key): array
    {
        $parts = array_map('trim', explode(',', c($key)));
        return array_values(array_filter($parts, static fn($p) => $p !== ''));
    }
}
