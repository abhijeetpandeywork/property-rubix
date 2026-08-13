<?php
/**
 * Location Helper
 * Handles auto-detecting and managing the active user country
 */

function initUserLocation() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // If active country is already set manually or previously auto-detected, keep it
    if (!empty($_SESSION['active_country_id']) && !empty($_SESSION['active_country_slug'])) {
        return;
    }

    $pdo = db();
    $countryCode = null;

    // 1. Try Cloudflare Header (Fastest, most reliable if on CF)
    if (!empty($_SERVER['HTTP_CF_IPCOUNTRY'])) {
        $countryCode = strtoupper($_SERVER['HTTP_CF_IPCOUNTRY']);
    } 
    // 2. Fallback to free IP API
    else {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '';
        if ($ip && $ip !== '127.0.0.1' && $ip !== '::1') {
            try {
                // Timeout of 2 seconds so page load isn't blocked if API goes down
                $ctx = stream_context_create(['http' => ['timeout' => 2]]);
                $json = @file_get_contents("http://ip-api.com/json/{$ip}?fields=countryCode", false, $ctx);
                if ($json) {
                    $data = json_decode($json, true);
                    if (!empty($data['countryCode'])) {
                        $countryCode = strtoupper($data['countryCode']);
                    }
                }
            } catch (Exception $e) {
                // API failed, fallback will trigger
            }
        }
    }

    // If we got a code (e.g. 'IN', 'AE'), find it in DB
    if ($countryCode) {
        $stmt = $pdo->prepare("SELECT * FROM countries WHERE iso_code = ? AND status = 'active' LIMIT 1");
        $stmt->execute([$countryCode]);
        $country = $stmt->fetch();
        
        // If we don't have an exact iso_code match, try name (if iso_code is missing in DB schema)
        // Wait, does our DB have iso_code? Let's check or fallback.
        if (!$country) {
            // Some databases only have 'name' or 'slug'. Let's map common ones if iso_code is missing
            $map = ['IN' => 'india', 'AE' => 'uae', 'US' => 'usa', 'CA' => 'canada', 'GB' => 'uk'];
            if (isset($map[$countryCode])) {
                $stmt = $pdo->prepare("SELECT * FROM countries WHERE slug = ? AND status = 'active' LIMIT 1");
                $stmt->execute([$map[$countryCode]]);
                $country = $stmt->fetch();
            }
        }
    }

    // 3. Fallback to default (India) if detection failed or country not found
    if (empty($country)) {
        $stmt = $pdo->prepare("SELECT * FROM countries WHERE slug = 'india' AND status = 'active' LIMIT 1");
        $stmt->execute();
        $country = $stmt->fetch();
        
        // If even India isn't active, just get the first active country
        if (!$country) {
            $stmt = $pdo->prepare("SELECT * FROM countries WHERE status = 'active' ORDER BY sort_order ASC LIMIT 1");
            $stmt->execute();
            $country = $stmt->fetch();
        }
    }

    // Set session
    if ($country) {
        $_SESSION['active_country_id']   = $country['id'];
        $_SESSION['active_country_name'] = $country['name'];
        $_SESSION['active_country_slug'] = $country['slug'];
        $_SESSION['active_country_flag'] = $country['flag_icon'] ?? '';
    }
}

function getActiveCountry() {
    if (empty($_SESSION['active_country_id'])) {
        initUserLocation();
    }
    return [
        'id'   => $_SESSION['active_country_id'] ?? null,
        'name' => $_SESSION['active_country_name'] ?? 'Global',
        'slug' => $_SESSION['active_country_slug'] ?? '',
        'flag' => $_SESSION['active_country_flag'] ?? ''
    ];
}

function setActiveCountryBySlug($slug) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    $pdo = db();
    $stmt = $pdo->prepare("SELECT * FROM countries WHERE slug = ? AND status = 'active' LIMIT 1");
    $stmt->execute([$slug]);
    $country = $stmt->fetch();
    
    if ($country) {
        $_SESSION['active_country_id']   = $country['id'];
        $_SESSION['active_country_name'] = $country['name'];
        $_SESSION['active_country_slug'] = $country['slug'];
        $_SESSION['active_country_flag'] = $country['flag_icon'] ?? '';
        return true;
    }
    
    return false;
}

function getCountryPhoneCodeOptions() {
    $activeCountry = getActiveCountry();
    $activeSlug = $activeCountry['slug'];
    $defaultPhoneCode = '+91';
    if ($activeSlug === 'uae') $defaultPhoneCode = '+971';
    if ($activeSlug === 'usa' || $activeSlug === 'canada') $defaultPhoneCode = '+1';
    if ($activeSlug === 'uk') $defaultPhoneCode = '+44';

    $options = [
        '+91' => '🇮🇳 +91',
        '+1' => '🇺🇸/🇨🇦 +1',
        '+44' => '🇬🇧 +44',
        '+971' => '🇦🇪 +971',
        '+61' => '🇦🇺 +61',
        '+65' => '🇸🇬 +65',
        '+60' => '🇲🇾 +60',
        '+49' => '🇩🇪 +49',
        '+33' => '🇫🇷 +33',
        '+966' => '🇸🇦 +966',
        '+974' => '🇶🇦 +974',
        '+965' => '🇰🇼 +965',
        '+880' => '🇧🇩 +880',
        '+92' => '🇵🇰 +92',
        '+977' => '🇳🇵 +977'
    ];

    $html = '';
    foreach ($options as $val => $label) {
        $selected = ($val === $defaultPhoneCode) ? 'selected' : '';
        $html .= "<option value=\"$val\" $selected>$label</option>\n";
    }
    return $html;
}
