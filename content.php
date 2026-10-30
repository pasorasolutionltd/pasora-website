<?php
/**
 * Loads the homepage content: schema defaults merged with the admin's
 * saved overrides (admin/content.local.json, git-ignored, survives deploys).
 *
 * Exposes:
 *   $SCHEMA  - the grouped field schema (scalar fields, for the admin editor)
 *   $C       - flat [key => value] of the live scalar content
 *   c($key)/ch($key)/clist($key) - scalar helpers
 *   services_list($which) - clean IT/Travelling card list for the public site
 *   services_raw($which)  - stored list as-is (for the admin editor)
 *   svc_icon($name)/svc_icon_paths() - shared card-icon library
 *   $CONTENT_OVERRIDES - raw decoded overrides (incl. the service arrays)
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

$CONTENT_OVERRIDES = [];
$overrideFile = __DIR__ . '/admin/content.local.json';
if (is_file($overrideFile)) {
    $saved = json_decode((string) @file_get_contents($overrideFile), true);
    if (is_array($saved)) {
        $CONTENT_OVERRIDES = $saved;
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

if (!function_exists('svc_icon')) {
    /** Card-icon library: name => SVG inner paths. Shared by the site and the admin picker. */
    function svc_icon_paths(): array
    {
        return [
            'code'      => '<polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/>',
            'cloud'     => '<path d="M17.5 19a4.5 4.5 0 1 0-.44-8.98A6 6 0 1 0 6 16.5"/><path d="M12 12v9"/><path d="m8 17 4 4 4-4"/>',
            'shield'    => '<path d="M12 22s8-3 8-10V5l-8-3-8 3v7c0 7 8 10 8 10z"/><rect x="9" y="10" width="6" height="5" rx="1"/><path d="M10 10V8a2 2 0 1 1 4 0v2"/>',
            'screen'    => '<rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/><circle cx="12" cy="10" r="3"/>',
            'support'   => '<path d="M3 18v-6a9 9 0 0 1 18 0v6"/><path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3zM3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z"/>',
            'plane'     => '<line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/>',
            'visa'      => '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>',
            'hotel'     => '<path d="M3 21h18"/><path d="M5 21V7l8-4v18"/><path d="M19 21V11l-6-4"/><line x1="9" y1="9" x2="9.01" y2="9"/><line x1="9" y1="13" x2="9.01" y2="13"/>',
            'map'       => '<polygon points="1 6 1 22 8 18 16 22 23 18 23 2 16 6 8 2 1 6"/><line x1="8" y1="2" x2="8" y2="18"/><line x1="16" y1="6" x2="16" y2="22"/>',
            'globe'     => '<circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>',
            'briefcase' => '<rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>',
            'phone'     => '<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z"/>',
            'calendar'  => '<rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>',
            'car'       => '<path d="M5 17H3v-5l2-5h12l2 5v5h-2"/><path d="M9 17h6"/><circle cx="7.5" cy="17" r="1.5"/><circle cx="16.5" cy="17" r="1.5"/>',
            'star'      => '<polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>',
        ];
    }
    function svc_icon(string $name): string
    {
        $p = svc_icon_paths();
        $body = $p[$name] ?? $p['star'];
        return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">' . $body . '</svg>';
    }
}

if (!function_exists('services_list')) {
    function default_service_list(string $which): array
    {
        if ($which === 'travel') {
            return [
                ['icon' => 'plane', 'title' => 'Flight Booking', 'desc' => 'Find and book the right flights for your trip, with the best routes and fares for your schedule and budget.', 'bullets' => ['Local & international', 'Best-fare search', 'Flexible dates']],
                ['icon' => 'visa', 'title' => 'Visa Assistance', 'desc' => 'End-to-end help with visa applications, documentation and appointments for a smooth, stress-free approval.', 'bullets' => ['Application support', 'Document review', 'Appointment booking']],
                ['icon' => 'hotel', 'title' => 'Hotel Reservations', 'desc' => 'Comfortable, well-located stays booked for you at competitive rates, from short trips to extended stays.', 'bullets' => ['Worldwide hotels', 'Vetted options', 'Group bookings']],
                ['icon' => 'map', 'title' => 'Tour Packages', 'desc' => 'Curated tour and holiday packages planned around where you want to go and what you want to experience.', 'bullets' => ['Custom itineraries', 'Guided tours', 'Group & solo']],
            ];
        }
        return [
            ['icon' => 'code', 'title' => 'Software Development', 'desc' => 'Custom web, mobile and enterprise applications built around the way you work. We stay with your project from the first sketch to launch and beyond.', 'bullets' => ['Web & mobile apps', 'Enterprise systems', 'API & integrations']],
            ['icon' => 'cloud', 'title' => 'Cloud Solutions', 'desc' => 'Migrate, host and optimise in the cloud with secure, scalable infrastructure that keeps your team productive from anywhere.', 'bullets' => ['Cloud migration', 'Hosting & backups', 'Cost optimisation']],
            ['icon' => 'shield', 'title' => 'Cyber Security', 'desc' => 'Protect your data, systems and reputation with proactive security assessments, monitoring and incident response.', 'bullets' => ['Security audits', 'Threat monitoring', 'Staff awareness training']],
            ['icon' => 'screen', 'title' => 'IT Consulting', 'desc' => 'Honest, practical advice on strategy, systems and digital transformation, so every technology decision moves your business forward.', 'bullets' => ['IT strategy & roadmaps', 'Digital transformation', 'Vendor selection']],
            ['icon' => 'support', 'title' => 'IT Support & Maintenance', 'desc' => 'A responsive helpdesk and preventive maintenance that keep your devices, networks and systems running day and night.', 'bullets' => ['24/7 helpdesk', 'Network & hardware care', 'Managed IT services']],
        ];
    }

    /** Stored list as-is (for the admin editor; keeps blank rows). Migrates old fixed fields once. */
    function services_raw(string $which): array
    {
        global $CONTENT_OVERRIDES;
        $key = $which === 'travel' ? 'travel_services' : 'it_services';
        if (isset($CONTENT_OVERRIDES[$key]) && is_array($CONTENT_OVERRIDES[$key])) {
            return array_values($CONTENT_OVERRIDES[$key]);
        }
        $list   = default_service_list($which);
        $prefix = $which === 'travel' ? 'travel' : 'service';
        $count  = $which === 'travel' ? 4 : 5;
        for ($i = 1; $i <= $count; $i++) {
            if (!isset($CONTENT_OVERRIDES["{$prefix}{$i}_title"])) {
                continue;
            }
            $idx = $i - 1;
            if (!isset($list[$idx])) {
                $list[$idx] = ['icon' => 'star', 'title' => '', 'desc' => '', 'bullets' => []];
            }
            $list[$idx]['title'] = (string) $CONTENT_OVERRIDES["{$prefix}{$i}_title"];
            if (isset($CONTENT_OVERRIDES["{$prefix}{$i}_desc"])) {
                $list[$idx]['desc'] = (string) $CONTENT_OVERRIDES["{$prefix}{$i}_desc"];
            }
            $b = [];
            foreach (['b1', 'b2', 'b3'] as $bk) {
                if (isset($CONTENT_OVERRIDES["{$prefix}{$i}_{$bk}"])) {
                    $b[] = (string) $CONTENT_OVERRIDES["{$prefix}{$i}_{$bk}"];
                }
            }
            if ($b) {
                $list[$idx]['bullets'] = $b;
            }
        }
        return $list;
    }

    /** Clean list for the public site: drops blank-title rows and empty bullets. */
    function services_list(string $which): array
    {
        $out = [];
        foreach (services_raw($which) as $s) {
            if (!is_array($s)) {
                continue;
            }
            $title = trim((string) ($s['title'] ?? ''));
            if ($title === '') {
                continue;
            }
            $bullets = array_values(array_filter(
                array_map(static fn($b) => trim((string) $b), (array) ($s['bullets'] ?? [])),
                static fn($b) => $b !== ''
            ));
            $out[] = [
                'icon'    => (string) ($s['icon'] ?? 'star'),
                'title'   => $title,
                'desc'    => trim((string) ($s['desc'] ?? '')),
                'bullets' => $bullets,
            ];
        }
        return $out;
    }
}
