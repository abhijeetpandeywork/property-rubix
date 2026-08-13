<?php
require_once APP_PATH . 'core/View.php';

class HomeController extends Controller {

    public function index(array $params = []): void {
        $pdo = db();

        $activeLocation = getActiveLocation();
        $countryId = $activeLocation['country']['id'] ?? 0;
        $cityId = $activeLocation['city']['id'] ?? 0;

        // Featured projects
        $projQuery = "
             SELECT p.*, b.name AS builder_name, c.name AS city_name,
                    s.name AS state_name, co.name AS country_name, co.slug AS country_slug, s.slug AS state_slug, c.slug AS city_slug
             FROM projects p
             LEFT JOIN builders b ON b.id = p.builder_id
             LEFT JOIN cities c   ON c.id = p.city_id
             LEFT JOIN states s   ON s.id = c.state_id
             LEFT JOIN countries co ON co.id = s.country_id
             WHERE p.is_featured = 1 
        ";
        $projParams = [];
        
        if ($cityId) {
            $projQuery .= " AND c.id = ? ";
            $projParams[] = $cityId;
        } else {
            $projQuery .= " AND co.id = ? ";
            $projParams[] = $countryId;
        }
        $projQuery .= " ORDER BY p.sort_order, p.created_at DESC LIMIT 9";
        
        $stmtProj = $pdo->prepare($projQuery);
        $stmtProj->execute($projParams);
        $featuredProjects = $stmtProj->fetchAll();

        // Builders for this country (or active city)
        $buildQuery = "
             SELECT b.*, co.name AS country_name, co.slug AS country_slug
             FROM builders b
             LEFT JOIN countries co ON co.id = b.country_id
             WHERE b.status = 'active'
        ";
        $buildParams = [];
        
        if ($cityId) {
            // Only builders who have projects in this city
            $buildQuery .= " AND b.id IN (SELECT DISTINCT builder_id FROM projects WHERE city_id = ?) ";
            $buildParams[] = $cityId;
        } else {
            $buildQuery .= " AND b.country_id = ? ";
            $buildParams[] = $countryId;
        }
        $buildQuery .= " ORDER BY co.sort_order, b.name LIMIT 40";
        
        $stmtBuild = $pdo->prepare($buildQuery);
        $stmtBuild->execute($buildParams);
        $builders = $stmtBuild->fetchAll();

        // Cities for the active country (for tabs)
        $stmtCities = $pdo->prepare(
            "SELECT co.id, co.name, co.slug, co.flag_icon,
                    GROUP_CONCAT(DISTINCT CONCAT(c.name,'|',c.slug,'|',s.slug) ORDER BY c.name SEPARATOR ';;') AS cities_raw
             FROM countries co
             JOIN states s ON s.country_id = co.id
             JOIN cities c ON c.state_id = s.id
             WHERE co.status = 'active' AND c.status = 'active' AND co.id = ?
             GROUP BY co.id
             ORDER BY co.sort_order
             LIMIT 4"
        );
        $stmtCities->execute([$countryId]);
        $countries = $stmtCities->fetchAll();

        // Parse cities per country
        foreach ($countries as &$country) {
            $cities = [];
            if ($country['cities_raw']) {
                foreach (explode(';;', $country['cities_raw']) as $item) {
                    [$cityName, $citySlug, $stateSlug] = explode('|', $item);
                    $cities[] = ['name' => $cityName, 'slug' => $citySlug, 'state_slug' => $stateSlug, 'country_slug' => $country['slug']];
                }
            }
            $country['cities'] = $cities;
        }
        unset($country);

        // Testimonials
        $testimonials = $pdo->query(
            "SELECT * FROM testimonials WHERE status = 'active' ORDER BY sort_order LIMIT 6"
        )->fetchAll();

        // Stats
        $stats = [
            ['num' => $pdo->query("SELECT COUNT(*) FROM projects")->fetchColumn(), 'suffix' => '+', 'label' => 'Projects Listed'],
            ['num' => $pdo->query("SELECT COUNT(*) FROM builders WHERE status='active'")->fetchColumn(), 'suffix' => '+', 'label' => 'Trusted Developers'],
            ['num' => $pdo->query("SELECT COUNT(*) FROM cities WHERE status='active'")->fetchColumn(), 'suffix' => '+', 'label' => 'Cities Covered'],
            ['num' => (int)getSetting('happy_families_count', 10000), 'suffix' => '+', 'label' => 'Happy Families'],
        ];

        // Recent blog posts
        $blogPosts = $pdo->query(
            "SELECT bp.*, bc.name AS category_name, bc.slug AS category_slug
             FROM blog_posts bp
             LEFT JOIN blog_categories bc ON bc.id = bp.category_id
             WHERE bp.status = 'published'
             ORDER BY bp.published_at DESC
             LIMIT 3"
        )->fetchAll();

        $this->view('home/index', [
            'pageTitle'        => getSetting('home_seo_title', 'Find Your Perfect Property in India & UAE'),
            'metaDesc'         => getSetting('home_seo_desc', 'Discover verified residential, commercial & plot projects across India, UAE, USA and Canada. RERA registered, trusted developers.'),
            'bodyClass'        => 'page-home',
            'featuredProjects' => $featuredProjects,
            'builders'         => $builders,
            'countries'        => $countries,
            'testimonials'     => $testimonials,
            'stats'            => $stats,
            'blogPosts'        => $blogPosts,
        ]);
    }
}
