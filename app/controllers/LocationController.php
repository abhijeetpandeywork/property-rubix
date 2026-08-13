<?php
class LocationController extends Controller {

    public function index(array $params = []): void {
        $pdo = db();
        // Count projects per country by joining states, cities, and projects
        // Count projects per country by joining states, cities, and projects
        $countries = $pdo->query("
            SELECT co.*, COUNT(DISTINCT p.id) AS project_count 
            FROM countries co
            LEFT JOIN states s ON s.country_id = co.id
            LEFT JOIN cities c ON c.state_id = s.id
            LEFT JOIN projects p ON p.city_id = c.id
            WHERE co.status='active' 
            GROUP BY co.id 
            ORDER BY co.sort_order
        ")->fetchAll();

        $this->view('location/index', [
            'pageTitle'  => 'Search Properties by Location',
            'metaDesc'   => 'Browse real estate projects by country, state, and city across India, UAE, USA & Canada.',
            'countries'  => $countries,
        ]);
    }

    public function selectLocation(array $params = []): void {
        $pdo = db();
        
        // Fetch active countries
        $countries = $pdo->query("
            SELECT id, name, slug, flag_icon 
            FROM countries 
            WHERE status='active' 
            ORDER BY sort_order ASC, name ASC
        ")->fetchAll();
        
        // Fetch popular active cities (limit 24 for the grid)
        $popularCities = $pdo->query("
            SELECT c.id, c.name, c.slug, co.name AS country_name, co.flag_icon
            FROM cities c
            JOIN states s ON s.id = c.state_id
            JOIN countries co ON co.id = s.country_id
            WHERE c.status='active' 
            ORDER BY c.sort_order ASC, c.name ASC
            LIMIT 24
        ")->fetchAll();

        $grouped = [];
        foreach ($countries as $c) {
            $continent = !empty($c['continent']) ? $c['continent'] : 'Other';
            if (!isset($grouped[$continent])) {
                $grouped[$continent] = [];
            }
            $grouped[$continent][] = $c;
        }

        // Real stats from DB
        $countryCount  = count($countries);
        $projectCount  = 0;
        $cityCount     = 0;
        try {
            $projectCount  = (int)$pdo->query("SELECT COUNT(*) FROM projects")->fetchColumn();
            $cityCount     = (int)$pdo->query("SELECT COUNT(*) FROM cities")->fetchColumn();
        } catch (Exception $e) {}

        $happyFamilies = getSetting('happy_families_count') ?: '10,000';

        $this->view('location/select_location', [
            'pageTitle'      => 'Select Your Location',
            'metaDesc'       => 'Select your city or country to browse local properties.',
            'countries'      => $countries,
            'popularCities'  => $popularCities,
            'heroBannerUrl'  => getSetting('select_country_banner')
                                ? upload(getSetting('select_country_banner'))
                                : asset('img/world-map.png'),
            'stats' => [
                ['num' => $cityCount . '+',  'label' => 'Cities'],
                ['num' => $projectCount . '+', 'label' => 'Projects'],
                ['num' => $happyFamilies . '+', 'label' => 'Happy Families'],
                ['num' => $countryCount,    'label' => 'Countries'],
            ],
        ]);
    }

    public function setCountry(array $params = []): void {
        $slug = $params['slug'] ?? '';
        if ($slug) {
            setActiveCountryBySlug($slug);
        }
        header("Location: " . PUBLIC_URL);
        exit;
    }

    public function setLocation(array $params = []): void {
        $slug = $params['slug'] ?? '';
        if ($slug) {
            setActiveCityBySlug($slug);
        }
        header("Location: " . PUBLIC_URL);
        exit;
    }

    public function country(array $params): void {
        $pdo     = db();
        $country = $pdo->prepare("SELECT * FROM countries WHERE slug=? AND status='active'");
        $country->execute([$params['country']]);
        $country = $country->fetch();

        if (!$country) { http_response_code(404); $this->view('errors/404', []); return; }

        $states = $pdo->prepare(
            "SELECT s.*, 
                    COUNT(DISTINCT c.id) AS city_count,
                    COUNT(DISTINCT p.id) AS project_count
             FROM states s
             LEFT JOIN cities c ON c.state_id = s.id AND c.status='active'
             LEFT JOIN projects p ON p.city_id = c.id
             WHERE s.country_id=?
             GROUP BY s.id ORDER BY s.name"
        );
        $states->execute([$country['id']]);
        $states = $states->fetchAll();

        $this->view('location/country', [
            'pageTitle' => 'Properties in ' . $country['name'],
            'metaDesc'  => 'Explore real estate projects in ' . $country['name'],
            'country'   => $country,
            'states'    => $states,
        ]);
    }

    public function state(array $params): void {
        $pdo = db();
        $stmt = $pdo->prepare(
            "SELECT s.*, co.name AS country_name, co.slug AS country_slug
             FROM states s JOIN countries co ON co.id = s.country_id
             WHERE s.slug=? AND co.slug=?"
        );
        $stmt->execute([$params['state'], $params['country']]);
        $state = $stmt->fetch();
        if (!$state) { http_response_code(404); $this->view('errors/404', []); return; }

        $cities = $pdo->prepare(
            "SELECT c.*, COUNT(DISTINCT p.id) AS project_count FROM cities c
             LEFT JOIN projects p ON p.city_id = c.id
             WHERE c.state_id=? AND c.status='active'
             GROUP BY c.id ORDER BY c.sort_order, c.name"
        );
        $cities->execute([$state['id']]);

        $this->view('location/state', [
            'pageTitle' => 'Properties in ' . $state['name'],
            'metaDesc'  => 'Browse cities in ' . $state['name'] . ' for real estate projects.',
            'state'     => $state,
            'cities'    => $cities->fetchAll(),
        ]);
    }

    public function city(array $params): void {
        $pdo  = db();
        $stmt = $pdo->prepare(
            "SELECT c.*, s.name AS state_name, s.slug AS state_slug,
                    co.name AS country_name, co.slug AS country_slug
             FROM cities c
             JOIN states s ON s.id = c.state_id
             JOIN countries co ON co.id = s.country_id
             WHERE c.slug=? AND s.slug=? AND co.slug=?"
        );
        $stmt->execute([$params['city'], $params['state'], $params['country']]);
        $city = $stmt->fetch();
        if (!$city) { http_response_code(404); $this->view('errors/404', []); return; }

        // Total project count for this city (matches what state page shows)
        $totalStmt = $pdo->prepare("SELECT COUNT(*) FROM projects WHERE city_id = ?");
        $totalStmt->execute([$city['id']]);
        $totalProjects = (int)$totalStmt->fetchColumn();

        // Get localities with their REAL per-locality project counts from admin data
        // Match on locality_id OR legacy location_area string for backward compatibility
        $localitiesStmt = $pdo->prepare(
            "SELECT l.id, l.name AS location_area, l.slug,
                    COUNT(p.id) AS locality_project_count
             FROM localities l
             LEFT JOIN projects p ON (p.locality_id = l.id OR p.location_area = l.name) AND p.city_id = ?
             WHERE l.city_id = ? AND l.status = 'active'
             GROUP BY l.id ORDER BY l.sort_order, l.name"
        );
        $localitiesStmt->execute([$city['id'], $city['id']]);
        $localities = $localitiesStmt->fetchAll(PDO::FETCH_ASSOC);

        $this->view('location/city', [
            'pageTitle'     => 'Neighborhoods in ' . $city['name'],
            'metaDesc'      => 'Explore localities in ' . $city['name'] . ', ' . $city['state_name'],
            'city'          => $city,
            'localities'    => $localities,
            'totalProjects' => $totalProjects,
        ]);
    }

    public function locality(array $params): void {
        $pdo  = db();
        $stmt = $pdo->prepare(
            "SELECT c.*, s.name AS state_name, s.slug AS state_slug,
                    co.name AS country_name, co.slug AS country_slug
             FROM cities c
             JOIN states s ON s.id = c.state_id
             JOIN countries co ON co.id = s.country_id
             WHERE c.slug=? AND s.slug=? AND co.slug=?"
        );
        $stmt->execute([$params['city'], $params['state'], $params['country']]);
        $city = $stmt->fetch();
        if (!$city) { http_response_code(404); $this->view('errors/404', []); return; }

        // Find the locality from localities table
        $locStmt = $pdo->prepare("SELECT id, name, slug FROM localities WHERE city_id=? AND slug=? AND status='active'");
        $locStmt->execute([$city['id'], $params['locality']]);
        $matchedLoc = $locStmt->fetch();
        
        if (!$matchedLoc) { http_response_code(404); $this->view('errors/404', []); return; }
        
        $matchedLocality = $matchedLoc['name'];
        $localityId = $matchedLoc['id'];

        // Filters
        $type   = $_GET['type']   ?? '';
        $status = $_GET['status'] ?? '';
        $budget = $_GET['budget'] ?? '';

        // Show projects strictly assigned to this locality in admin panel (via ID or legacy string name)
        $where = ['p.city_id = ?', '(p.locality_id = ? OR p.location_area = ?)'];
        $args  = [$city['id'], $localityId, $matchedLocality];

        if ($type)   { $where[] = 'p.type = ?';   $args[] = $type; }
        if ($status) { $where[] = 'p.status = ?';  $args[] = $status; }
        if ($budget === 'under50l') { $where[] = 'p.price_min < 5000000'; }
        elseif ($budget === '50l-1cr') { $where[] = 'p.price_min BETWEEN 5000000 AND 10000000'; }
        elseif ($budget === '1cr-3cr') { $where[] = 'p.price_min BETWEEN 10000000 AND 30000000'; }
        elseif ($budget === 'above3cr') { $where[] = 'p.price_min > 30000000'; }

        $whereStr  = implode(' AND ', $where);
        $totalStmt = $pdo->prepare("SELECT COUNT(*) FROM projects p WHERE $whereStr");
        $totalStmt->execute($args);
        $total = (int)$totalStmt->fetchColumn();

        $pager = new Pagination($total, 12);
        $args2 = array_merge($args, [$pager->perPage, $pager->offset]);

        $projects = $pdo->prepare(
            "SELECT p.*, b.name AS builder_name FROM projects p
             LEFT JOIN builders b ON b.id = p.builder_id
             WHERE $whereStr
             ORDER BY p.locality_id DESC, p.is_featured DESC, p.sort_order ASC
             LIMIT ? OFFSET ?"
        );
        $projects->execute($args2);

        $this->view('location/locality', [
            'pageTitle'  => 'Properties in ' . $matchedLocality . ', ' . $city['name'],
            'metaDesc'   => 'Explore real estate projects in ' . $matchedLocality . ', ' . $city['name'],
            'city'       => $city,
            'locality'   => $matchedLocality,
            'projects'   => $projects->fetchAll(),
            'pager'      => $pager,
            'filters'    => compact('type', 'status', 'budget'),
        ]);
    }
}
