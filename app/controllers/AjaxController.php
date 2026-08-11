<?php
class AjaxController extends Controller {

    public function submitEnquiry(array $params = []): void {
        if (!$this->isPost()) {
            $this->json(['success' => false, 'message' => 'Invalid request.'], 400);
        }

        // Honeypot
        if (!empty($_POST['hp_name'])) {
            $this->json(['success' => true, 'message' => 'Thank you! We will contact you shortly.']);
        }

        $name       = trim($_POST['name']         ?? '');
        $phone      = trim($_POST['phone']        ?? '');
        $email      = trim($_POST['email']        ?? '');
        $message    = trim($_POST['message']      ?? '');
        $projectName= trim($_POST['project_name'] ?? '');
        $propertyId = (int)($_POST['property_id'] ?? 0);

        if (!$name || !$phone) {
            $this->json(['success' => false, 'message' => 'Name and phone number are required.']);
            return;
        }

        // Build payload
        $payload = json_encode(array_filter([
            'message'      => $message,
            'project_name' => $projectName,
            'property_id'  => $propertyId ?: null,
            'source'       => $_SERVER['HTTP_REFERER'] ?? ''
        ]));

        try {
            $pdo = db();

            // Save to submissions table — form_type is varchar(50), any value OK
            $pdo->prepare(
                "INSERT INTO submissions (form_type, name, email, phone, payload, ip_address)
                 VALUES (?, ?, ?, ?, ?, ?)"
            )->execute([
                'enquiry',
                $name,
                $email ?: null,
                $phone,
                $payload,
                $_SERVER['REMOTE_ADDR'] ?? null,
            ]);

            // Save to leads — source MUST be one of the ENUM values:
            // 'site_visit_form','contact_form','whatsapp','call','chatbot','import'
            $leadMsg = $message ?: ($projectName ? "Enquiry for: $projectName" : 'Website enquiry');
            $pdo->prepare(
                "INSERT INTO leads (name, email, phone, source, message, ip_address) VALUES (?,?,?,?,?,?)"
            )->execute([
                $name,
                $email ?: null,
                $phone,
                'contact_form',
                $leadMsg,
                $_SERVER['REMOTE_ADDR'] ?? null,
            ]);

        } catch (\Throwable $e) {
            error_log('Enquiry form error: ' . $e->getMessage());
            // Do NOT return failure to user — they filled the form correctly
        }

        $this->json(['success' => true, 'message' => '✅ Thank you! Our team will contact you within 24 hours.']);
    }

    public function submitSiteVisit(array $params = []): void {
        if (!$this->isPost()) {
            $this->json(['success' => false, 'message' => 'Invalid request.'], 400);
        }

        if (!empty($_POST['hp_name'])) {
            $this->json(['success' => true, 'message' => 'Booking confirmed!']);
        }

        $name      = trim($_POST['name']         ?? '');
        $phone     = trim($_POST['phone']         ?? '');
        $email     = trim($_POST['email']         ?? '');
        $visitDate = $_POST['visit_date']          ?? '';
        $visitTime = $_POST['visit_time']          ?? '';
        $project   = trim($_POST['project_name']   ?? '');

        if (!$name || !$phone || !$visitDate) {
            $this->json(['success' => false, 'message' => 'Name, phone, and visit date are required.']);
            return;
        }

        try {
            $pdo = db();
            $pdo->prepare(
                "INSERT INTO submissions (form_type, name, email, phone, payload, ip_address)
                 VALUES ('site_visit', ?, ?, ?, ?, ?)"
            )->execute([
                $name, $email ?: null, $phone,
                json_encode(['visit_date' => $visitDate, 'visit_time' => $visitTime, 'project' => $project]),
                $_SERVER['REMOTE_ADDR'] ?? null,
            ]);

            // source ENUM: 'site_visit_form','contact_form','whatsapp','call','chatbot','import'
            $pdo->prepare(
                "INSERT INTO leads (name, email, phone, source, message, ip_address) VALUES (?,?,?,?,?,?)"
            )->execute([
                $name, $email ?: null, $phone,
                'site_visit_form',
                "Site visit requested for $project on $visitDate" . ($visitTime ? " at $visitTime" : ''),
                $_SERVER['REMOTE_ADDR'] ?? null,
            ]);
        } catch (\Throwable $e) {
            error_log('SiteVisit form error: ' . $e->getMessage());
        }

        $this->json(['success' => true, 'message' => '🎉 Site visit booked! We\'ll confirm within 4 hours.']);
    }

    public function subscribe(array $params = []): void {
        $email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
        if (!$email) {
            $this->json(['success' => false, 'message' => 'Please enter a valid email address.']);
        }

        $pdo = db();
        $existing = $pdo->prepare("SELECT id, status FROM subscribers WHERE email=?");
        $existing->execute([$email]);
        $row = $existing->fetch();

        if ($row) {
            if ($row['status'] === 'active') {
                $this->json(['success' => false, 'message' => 'You\'re already subscribed! ✓']);
            }
            $pdo->prepare("UPDATE subscribers SET status='active' WHERE id=?")->execute([$row['id']]);
        } else {
            $pdo->prepare("INSERT INTO subscribers (email) VALUES (?)")->execute([$email]);
        }

        $this->json(['success' => true, 'message' => '🎉 Subscribed successfully! Welcome aboard.']);
    }
    public function search(array $params = []): void {
        header('Content-Type: application/json');
        $q = trim($_GET['q'] ?? '');
        if (strlen($q) < 2) {
            echo json_encode(['results' => []]);
            return;
        }

        $pdo = db();
        $qLike = '%' . $q . '%';
        $results = [];

        // ── BHK / Configuration shortcut detection ───────────────────────
        // If user types "2 bhk", "3 bhk mumbai", "studio flat", etc.
        // show a direct filter shortcut at the TOP of results.
        $bhkNum    = null;
        $isStudio  = false;
        $qRemainder = $q; // residual text after removing the BHK part

        if (preg_match('/(\d+)\s*bhk/i', $q, $m)) {
            $bhkNum     = $m[1];
            $qRemainder = trim(preg_replace('/\d+\s*bhk\s*,?\s*/i', '', $q));
        } elseif (preg_match('/\bstudio\b/i', $q)) {
            $isStudio   = true;
            $qRemainder = trim(preg_replace('/\bstudio\b\s*,?\s*/i', '', $q));
        }

        if ($bhkNum !== null || $isStudio) {
            $bhkVal   = $isStudio ? 'studio' : $bhkNum;
            $bhkLabel = $isStudio ? 'Studio' : "{$bhkNum} BHK";
            $baseUrl  = PUBLIC_URL . 'projects?bhk=' . urlencode($bhkVal);
            if ($qRemainder) $baseUrl .= '&q=' . urlencode($qRemainder);

            // BHK-only shortcut
            $results[] = [
                'type'     => 'Configuration',
                'icon'     => 'fas fa-bed',
                'title'    => "Show all {$bhkLabel} projects",
                'subtitle' => 'Filter by configuration',
                'url'      => PUBLIC_URL . 'projects?bhk=' . urlencode($bhkVal),
            ];

            // BHK + location shortcut (if remainder looks like a city/locality)
            if ($qRemainder) {
                $results[] = [
                    'type'     => 'Configuration',
                    'icon'     => 'fas fa-map-marker-alt',
                    'title'    => "{$bhkLabel} in \"" . ucwords($qRemainder) . '"',
                    'subtitle' => 'Search by config + location',
                    'url'      => $baseUrl,
                ];
            }
        }
        // ─────────────────────────────────────────────────────────────────

        // 1. Search Countries
        $stmt = $pdo->prepare("SELECT name, slug FROM countries WHERE status='active' AND name LIKE ? LIMIT 3");
        $stmt->execute([$qLike]);
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $results[] = [
                'type' => 'Country',
                'icon' => 'fas fa-globe',
                'title' => $row['name'],
                'url' => PUBLIC_URL . 'location/' . $row['slug']
            ];
        }

        // 2. Search Cities (join with country for URL)
        $stmt = $pdo->prepare("
            SELECT c.name, c.slug, co.slug as country_slug, s.slug as state_slug 
            FROM cities c 
            JOIN states s ON s.id = c.state_id 
            JOIN countries co ON co.id = s.country_id 
            WHERE c.status='active' AND c.name LIKE ? LIMIT 5
        ");
        $stmt->execute([$qLike]);
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $cityEntry = [
                'type' => 'City',
                'icon' => 'fas fa-city',
                'title' => $row['name'] . ', ' . strtoupper($row['country_slug']),
                'url' => PUBLIC_URL . 'location/' . $row['country_slug'] . '/' . $row['state_slug'] . '/' . $row['slug']
            ];
            // If BHK detected, also add a BHK-in-City shortcut
            if ($bhkNum !== null || $isStudio) {
                $bhkVal   = $isStudio ? 'studio' : $bhkNum;
                $bhkLabel = $isStudio ? 'Studio' : "{$bhkNum} BHK";
                $results[] = [
                    'type'     => 'Configuration',
                    'icon'     => 'fas fa-bed',
                    'title'    => "{$bhkLabel} in " . $row['name'],
                    'subtitle' => 'Filter projects by BHK + city',
                    'url'      => PUBLIC_URL . 'projects?bhk=' . urlencode($bhkVal) . '&q=' . urlencode($row['name']),
                ];
            }
            $results[] = $cityEntry;
        }

        // 3. Search Localities
        $stmt = $pdo->prepare("
            SELECT l.name, l.slug, c.slug as city_slug, s.slug as state_slug, co.slug as country_slug 
            FROM localities l 
            JOIN cities c ON c.id = l.city_id 
            JOIN states s ON s.id = c.state_id 
            JOIN countries co ON co.id = s.country_id 
            WHERE l.status='active' AND l.name LIKE ? LIMIT 5
        ");
        $stmt->execute([$qLike]);
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $results[] = [
                'type' => 'Neighborhood',
                'icon' => 'fas fa-map-marker-alt',
                'title' => $row['name'] . ', ' . ucfirst(str_replace('-', ' ', $row['city_slug'])),
                'url' => PUBLIC_URL . 'location/' . $row['country_slug'] . '/' . $row['state_slug'] . '/' . $row['city_slug'] . '/' . $row['slug']
            ];
        }

        // 4. Search Developers
        $stmt = $pdo->prepare("SELECT name, slug FROM builders WHERE status='active' AND name LIKE ? LIMIT 4");
        $stmt->execute([$qLike]);
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $devEntry = [
                'type' => 'Developer',
                'icon' => 'fas fa-hard-hat',
                'title' => $row['name'],
                'url' => PUBLIC_URL . 'developer/' . $row['slug']
            ];
            // If BHK detected, also add BHK-by-developer shortcut
            if ($bhkNum !== null || $isStudio) {
                $bhkVal   = $isStudio ? 'studio' : $bhkNum;
                $bhkLabel = $isStudio ? 'Studio' : "{$bhkNum} BHK";
                $results[] = [
                    'type'     => 'Configuration',
                    'icon'     => 'fas fa-bed',
                    'title'    => "{$bhkLabel} by " . $row['name'],
                    'subtitle' => 'Filter by BHK + developer',
                    'url'      => PUBLIC_URL . 'projects?bhk=' . urlencode($bhkVal) . '&q=' . urlencode($row['name']),
                ];
            }
            $results[] = $devEntry;
        }

        // 5. Search Projects (by name AND unit_types)
        $stmt = $pdo->prepare("
            SELECT p.name, p.slug, p.unit_types, l.name as locality_name, c.name as city_name 
            FROM projects p 
            LEFT JOIN localities l ON p.locality_id = l.id
            LEFT JOIN cities c ON p.city_id = c.id
            WHERE p.status IN ('upcoming','under_construction','ready_to_move','new_launch') 
              AND (p.name LIKE ? OR p.unit_types LIKE ?) LIMIT 6
        ");
        $stmt->execute([$qLike, $qLike]);
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $loc = $row['locality_name'] ? $row['locality_name'] . ', ' : '';
            $subtext = $loc . ($row['city_name'] ?? '');
            if ($row['unit_types']) $subtext = ($subtext ? $subtext . ' · ' : '') . $row['unit_types'];
            $results[] = [
                'type' => 'Project',
                'icon' => 'fas fa-building',
                'title' => $row['name'],
                'subtitle' => $subtext,
                'url' => PUBLIC_URL . 'project/' . $row['slug']
            ];
        }

        echo json_encode(['results' => $results]);
    }
}
