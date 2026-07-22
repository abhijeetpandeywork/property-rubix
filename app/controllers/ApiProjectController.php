<?php
/**
 * PropertyRubix — API Project Controller
 */

class ApiProjectController extends ApiBaseController {

    /**
     * GET /api/v1/projects
     * Fetch, filter, and search project records.
     */
    public function index(array $params = []): void {
        $this->authenticate('listings:read');
        $pdo = db();

        // Pagination parameters
        $page  = max(1, (int)($_GET['page'] ?? 1));
        $limit = min(100, max(1, (int)($_GET['limit'] ?? 20)));
        $offset = ($page - 1) * $limit;

        // Search & filter criteria
        $q          = trim($_GET['q'] ?? '');
        $type       = trim($_GET['type'] ?? '');
        $status     = trim($_GET['status'] ?? '');
        $citySlug   = trim($_GET['city_slug'] ?? '');
        $priceMin   = $_GET['price_min'] ?? null;
        $priceMax   = $_GET['price_max'] ?? null;
        $featured   = $_GET['is_featured'] ?? null;
        $rera       = $_GET['rera_verified'] ?? null;

        $where = ['1=1'];
        $args  = [];

        if ($q) {
            $where[] = '(p.name LIKE ? OR p.short_description LIKE ? OR p.address LIKE ? OR b.name LIKE ?)';
            $args = array_merge($args, ["%$q%", "%$q%", "%$q%", "%$q%"]);
        }
        if ($type) {
            $where[] = 'p.type = ?';
            $args[]  = $type;
        }
        if ($status) {
            $where[] = 'p.status = ?';
            $args[]  = $status;
        }
        if ($citySlug) {
            $where[] = 'c.slug = ?';
            $args[]  = $citySlug;
        }
        if ($priceMin !== null) {
            $where[] = 'p.price_min >= ?';
            $args[]  = (float)$priceMin;
        }
        if ($priceMax !== null) {
            $where[] = 'p.price_max <= ?';
            $args[]  = (float)$priceMax;
        }
        if ($featured !== null) {
            $where[] = 'p.is_featured = ?';
            $args[]  = (int)$featured;
        }
        if ($rera !== null) {
            $where[] = 'p.rera_verified = ?';
            $args[]  = (int)$rera;
        }

        $whereStr = implode(' AND ', $where);

        // Fetch total count
        $countQuery = "
            SELECT COUNT(*) 
            FROM projects p
            LEFT JOIN builders b ON b.id = p.builder_id
            LEFT JOIN cities c ON c.id = p.city_id
            WHERE $whereStr
        ";
        $totalStmt = $pdo->prepare($countQuery);
        $totalStmt->execute($args);
        $total = (int)$totalStmt->fetchColumn();

        // Fetch projects
        $selectQuery = "
            SELECT p.id, p.name, p.slug, p.type, p.status, p.price_min, p.price_max, p.price_on_request,
                   p.unit_types, p.area_range, p.rera_id, p.rera_verified, p.address, p.location_area,
                   p.latitude, p.longitude, p.short_description, p.banner_image, p.thumbnail_image, 
                   p.brochure_pdf, p.possession_date, p.is_featured, p.created_at, p.updated_at,
                   b.name AS builder_name, b.slug AS builder_slug, 
                   c.name AS city_name, c.slug AS city_slug,
                   s.name AS state_name, s.slug AS state_slug,
                   co.name AS country_name, co.slug AS country_slug
            FROM projects p
            LEFT JOIN builders b ON b.id = p.builder_id
            LEFT JOIN cities c ON c.id = p.city_id
            LEFT JOIN states s ON s.id = c.state_id
            LEFT JOIN countries co ON co.id = s.country_id
            WHERE $whereStr 
            ORDER BY p.is_featured DESC, p.sort_order ASC, p.id DESC
            LIMIT ? OFFSET ?
        ";

        $stmt = $pdo->prepare($selectQuery);
        // Execute PDO statement with limit/offset using bindValue to ensure integer type
        $paramIndex = 1;
        foreach ($args as $val) {
            $stmt->bindValue($paramIndex++, $val);
        }
        $stmt->bindValue($paramIndex++, $limit, PDO::PARAM_INT);
        $stmt->bindValue($paramIndex++, $offset, PDO::PARAM_INT);
        $stmt->execute();
        $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Normalize decimals & numbers
        foreach ($projects as &$p) {
            $p['id'] = (int)$p['id'];
            $p['price_min'] = $p['price_min'] !== null ? (float)$p['price_min'] : null;
            $p['price_max'] = $p['price_max'] !== null ? (float)$p['price_max'] : null;
            $p['price_on_request'] = (int)$p['price_on_request'];
            $p['rera_verified'] = (int)$p['rera_verified'];
            $p['is_featured'] = (int)$p['is_featured'];
            $p['latitude'] = $p['latitude'] !== null ? (float)$p['latitude'] : null;
            $p['longitude'] = $p['longitude'] !== null ? (float)$p['longitude'] : null;
        }

        $meta = [
            'total_records' => $total,
            'page'          => $page,
            'per_page'      => $limit,
            'total_pages'   => ceil($total / $limit)
        ];

        $this->apiSuccess($projects, $meta);
    }

    /**
     * GET /api/v1/projects/{id}
     * Fetch a single project record (images, floor plans, amenities, and developer).
     */
    public function show(array $params): void {
        $this->authenticate('listings:read');
        $pdo = db();

        $id = $params['id'] ?? '';
        $isNumeric = is_numeric($id);

        $query = "
            SELECT p.*, 
                   b.name AS builder_name, b.slug AS builder_slug, b.logo AS builder_logo,
                   b.description AS builder_desc, b.established_year, b.total_projects AS builder_total,
                   c.name AS city_name, c.slug AS city_slug,
                   s.name AS state_name, s.slug AS state_slug,
                   co.name AS country_name, co.slug AS country_slug
            FROM projects p
            LEFT JOIN builders b ON b.id = p.builder_id
            LEFT JOIN cities c ON c.id = p.city_id
            LEFT JOIN states s ON s.id = c.state_id
            LEFT JOIN countries co ON co.id = s.country_id
            WHERE " . ($isNumeric ? "p.id = ?" : "p.slug = ?") . "
            LIMIT 1
        ";

        $stmt = $pdo->prepare($query);
        $stmt->execute([$id]);
        $project = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$project) {
            $this->apiError("Project not found.", 404);
        }

        // Clean & cast base entity
        $project['id'] = (int)$project['id'];
        $project['builder_id'] = $project['builder_id'] !== null ? (int)$project['builder_id'] : null;
        $project['city_id'] = $project['city_id'] !== null ? (int)$project['city_id'] : null;
        $project['price_min'] = $project['price_min'] !== null ? (float)$project['price_min'] : null;
        $project['price_max'] = $project['price_max'] !== null ? (float)$project['price_max'] : null;
        $project['price_on_request'] = (int)$project['price_on_request'];
        $project['rera_verified'] = (int)$project['rera_verified'];
        $project['is_featured'] = (int)$project['is_featured'];
        $project['sort_order'] = (int)$project['sort_order'];
        $project['latitude'] = $project['latitude'] !== null ? (float)$project['latitude'] : null;
        $project['longitude'] = $project['longitude'] !== null ? (float)$project['longitude'] : null;

        // Fetch relational entities
        // Images
        $imgStmt = $pdo->prepare("SELECT id, image_path, alt_text, sort_order FROM project_images WHERE project_id = ? ORDER BY sort_order");
        $imgStmt->execute([$project['id']]);
        $project['images'] = $imgStmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($project['images'] as &$img) {
            $img['id'] = (int)$img['id'];
            $img['sort_order'] = (int)$img['sort_order'];
        }

        // Amenities
        $amStmt = $pdo->prepare("SELECT id, amenity_name, icon FROM project_amenities WHERE project_id = ?");
        $amStmt->execute([$project['id']]);
        $project['amenities'] = $amStmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($project['amenities'] as &$am) {
            $am['id'] = (int)$am['id'];
        }

        // Floor plans
        $fpStmt = $pdo->prepare("SELECT id, plan_name, configuration, area, price, price_numeric, image, sort_order FROM project_floor_plans WHERE project_id = ? ORDER BY sort_order");
        $fpStmt->execute([$project['id']]);
        $project['floor_plans'] = $fpStmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($project['floor_plans'] as &$fp) {
            $fp['id'] = (int)$fp['id'];
            $fp['price_numeric'] = $fp['price_numeric'] !== null ? (float)$fp['price_numeric'] : null;
            $fp['sort_order'] = (int)$fp['sort_order'];
        }

        // FAQs
        $faqStmt = $pdo->prepare("SELECT id, question, answer, sort_order FROM faqs WHERE status = 'active' ORDER BY sort_order");
        $faqStmt->execute();
        $project['faqs'] = $faqStmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($project['faqs'] as &$faq) {
            $faq['id'] = (int)$faq['id'];
            $faq['sort_order'] = (int)$faq['sort_order'];
        }

        $this->apiSuccess($project);
    }
}
