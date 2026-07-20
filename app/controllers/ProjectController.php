<?php
class ProjectController extends Controller {

    public function listing(array $params = []): void {
        $pdo = db();

        // Filters from GET
        $q      = trim($_GET['q']      ?? '');
        $type   = $_GET['type']        ?? '';
        $status = $_GET['status']      ?? '';
        $cityId = (int)($_GET['city']  ?? 0);
        $budget = $_GET['budget']      ?? '';
        $sort   = $_GET['sort']        ?? 'featured';

        $where = ['1=1'];
        $args  = [];

        if ($q)      { $where[] = '(p.name LIKE ? OR p.address LIKE ? OR b.name LIKE ?)'; $args = array_merge($args, ["%$q%","%$q%","%$q%"]); }
        if ($type)   { $where[] = 'p.type = ?';     $args[] = $type; }
        if ($status) { $where[] = 'p.status = ?';   $args[] = $status; }
        if ($cityId) { $where[] = 'p.city_id = ?';  $args[] = $cityId; }
        if ($budget === 'under50l')   { $where[] = 'p.price_min < 5000000'; }
        elseif ($budget === '50l-1cr') { $where[] = 'p.price_min BETWEEN 5000000 AND 10000000'; }
        elseif ($budget === '1cr-3cr') { $where[] = 'p.price_min BETWEEN 10000000 AND 30000000'; }
        elseif ($budget === 'above3cr'){ $where[] = 'p.price_min > 30000000'; }

        $orderBy = match($sort) {
            'price_asc'  => 'p.price_min ASC',
            'price_desc' => 'p.price_min DESC',
            'newest'     => 'p.created_at DESC',
            default      => 'p.is_featured DESC, p.sort_order ASC',
        };

        $whereStr = implode(' AND ', $where);

        $totalStmt = $pdo->prepare(
            "SELECT COUNT(*) FROM projects p LEFT JOIN builders b ON b.id=p.builder_id WHERE $whereStr"
        );
        $totalStmt->execute($args);
        $total = (int)$totalStmt->fetchColumn();

        $pager = new Pagination($total, 12);
        $args2 = array_merge($args, [$pager->perPage, $pager->offset]);

        $projects = $pdo->prepare(
            "SELECT p.*, b.name AS builder_name, c.name AS city_name, s.name AS state_name, co.name AS country_name
             FROM projects p
             LEFT JOIN builders b  ON b.id = p.builder_id
             LEFT JOIN cities c    ON c.id = p.city_id
             LEFT JOIN states s    ON s.id = c.state_id
             LEFT JOIN countries co ON co.id = s.country_id
             WHERE $whereStr ORDER BY $orderBy LIMIT ? OFFSET ?"
        );
        $projects->execute($args2);

        $cities = $pdo->query("SELECT id, name FROM cities WHERE status='active' ORDER BY name")->fetchAll();

        $this->view('project/listing', [
            'pageTitle' => 'All Properties' . ($q ? " — \"$q\"" : ''),
            'metaDesc'  => 'Browse all residential, commercial, and plot projects on PropertyRubix.',
            'projects'  => $projects->fetchAll(),
            'pager'     => $pager,
            'total'     => $total,
            'cities'    => $cities,
            'filters'   => compact('q','type','status','cityId','budget','sort'),
        ]);
    }

    public function detail(array $params): void {
        $pdo  = db();
        $stmt = $pdo->prepare(
            "SELECT p.*, b.name AS builder_name, b.slug AS builder_slug, b.logo AS builder_logo,
                    b.description AS builder_desc, b.established_year, b.total_projects AS builder_total,
                    c.name AS city_name, c.slug AS city_slug,
                    s.name AS state_name, s.slug AS state_slug,
                    co.name AS country_name, co.slug AS country_slug
             FROM projects p
             LEFT JOIN builders b  ON b.id = p.builder_id
             LEFT JOIN cities c    ON c.id = p.city_id
             LEFT JOIN states s    ON s.id = c.state_id
             LEFT JOIN countries co ON co.id = s.country_id
             WHERE p.slug = ?"
        );
        $stmt->execute([$params['slug']]);
        $project = $stmt->fetch();

        if (!$project) { http_response_code(404); $this->view('errors/404', []); return; }

        $images    = $pdo->prepare("SELECT * FROM project_images WHERE project_id=? ORDER BY sort_order");
        $images->execute([$project['id']]);

        $amenities = $pdo->prepare("SELECT * FROM project_amenities WHERE project_id=?");
        $amenities->execute([$project['id']]);

        $floorPlans = $pdo->prepare("SELECT * FROM project_floor_plans WHERE project_id=? ORDER BY sort_order");
        $floorPlans->execute([$project['id']]);

        $reviews = $pdo->prepare("SELECT * FROM reviews WHERE project_id=? AND status='approved' ORDER BY created_at DESC LIMIT 5");
        $reviews->execute([$project['id']]);

        // Related projects (same city)
        $related = $pdo->prepare(
            "SELECT p.*, b.name AS builder_name, c.name AS city_name
             FROM projects p
             LEFT JOIN builders b ON b.id = p.builder_id
             LEFT JOIN cities c   ON c.id = p.city_id
             WHERE p.city_id = ? AND p.id != ?
             ORDER BY p.is_featured DESC LIMIT 6"
        );
        $related->execute([$project['city_id'], $project['id']]);

        $this->view('project/detail', [
            'pageTitle'  => ($project['meta_title'] ?: $project['name'] . ' in ' . $project['city_name']),
            'metaDesc'   => ($project['meta_description'] ?: View::excerpt($project['short_description'] ?? '', 155)),
            'project'    => $project,
            'images'     => $images->fetchAll(),
            'amenities'  => $amenities->fetchAll(),
            'floorPlans' => $floorPlans->fetchAll(),
            'reviews'    => $reviews->fetchAll(),
            'related'    => $related->fetchAll(),
            'headerLogo' => $project['builder_logo'],
            'headerTitle'=> $project['builder_name'],
        ]);
    }
}
