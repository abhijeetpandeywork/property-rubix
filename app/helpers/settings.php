<?php
/**
 * Settings helper — fetch from DB with fallback cache.
 */
function getSetting(string $key, string $default = ''): string {
    static $cache = [];
    if (!$cache) {
        try {
            $rows = db()->query("SELECT key_name, value FROM settings")->fetchAll();
            foreach ($rows as $r) $cache[$r['key_name']] = $r['value'];
        } catch (Throwable) {}
    }
    return $cache[$key] ?? $default;
}

function getBranding(): array {
    static $branding = [];
    if (!$branding) {
        try {
            $branding = db()->query("SELECT * FROM branding_settings LIMIT 1")->fetch() ?: [];
        } catch (Throwable) {}
    }
    return $branding ?: [
        'site_name' => 'PropertyRubix',
        'primary_color' => '#16a34a',
        'secondary_color' => '#0f172a',
        'tagline' => 'Find Your Perfect Property',
        'logo' => null,
    ];
}
