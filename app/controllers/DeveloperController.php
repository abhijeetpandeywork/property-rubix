<?php
class DeveloperController extends Controller {

    public function index(array $params = []): void {
        $pdo = db();

        // Count projects per builder
        $builders = $pdo->query(
            "SELECT b.*, co.name AS country_name, co.slug AS country_slug,
                    COUNT(p.id) AS project_count
             FROM builders b
             LEFT JOIN countries co ON co.id = b.country_id
             LEFT JOIN projects p   ON p.builder_id = b.id
             WHERE b.status = 'active'
             GROUP BY b.id
             ORDER BY co.sort_order, project_count DESC"
        )->fetchAll();

        // Group by country
        $byCountry = [];
        foreach ($builders as $b) {
            $cn = $b['country_name'] ?: 'Other';
            $byCountry[$cn][] = $b;
        }

        $this->view('developer/index', [
            'pageTitle'  => 'Real Estate Developers | PropertyRubix',
            'metaDesc'   => 'Explore projects from India\'s and UAE\'s most trusted real estate developers.',
            'builders'   => $builders,
            'byCountry'  => $byCountry,
        ]);
    }

    public function profile(array $params): void {
        $pdo = db();
        $stmt = $pdo->prepare(
            "SELECT b.*, co.name AS country_name FROM builders b
             LEFT JOIN countries co ON co.id = b.country_id
             WHERE b.slug=? AND b.status='active'"
        );
        $stmt->execute([$params['slug']]);
        $builder = $stmt->fetch();
        if (!$builder) { http_response_code(404); $this->view('errors/404', []); return; }

        $projects = $pdo->prepare(
            "SELECT p.*, c.name AS city_name, s.name AS state_name
             FROM projects p
             LEFT JOIN cities c  ON c.id = p.city_id
             LEFT JOIN states s  ON s.id = c.state_id
             WHERE p.builder_id=?
             ORDER BY p.is_featured DESC, p.created_at DESC"
        );
        $projects->execute([$builder['id']]);

        $this->view('developer/profile', [
            'pageTitle'  => $builder['name'] . ' Projects | PropertyRubix',
            'metaDesc'   => 'View all projects by ' . $builder['name'] . '. Find the best residential and commercial properties.',
            'builder'    => $builder,
            'projects'   => $projects->fetchAll(),
        ]);
    }
}
