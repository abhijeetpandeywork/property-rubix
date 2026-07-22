<?php
/**
 * PropertyRubix — API Property Controller
 */

class ApiPropertyController extends ApiBaseController {

    /**
     * GET /api/v1/properties
     * Fetch, filter, and paginate property listings.
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
        $project_id = $_GET['project_id'] ?? null;
        $builder_id = $_GET['builder_id'] ?? null;
        $citySlug   = trim($_GET['city_slug'] ?? '');
        $localSlug  = trim($_GET['locality_slug'] ?? '');
        $propType   = trim($_GET['property_type'] ?? '');
        $listType   = trim($_GET['listing_type'] ?? '');
        $priceMin   = $_GET['price_min'] ?? null;
        $priceMax   = $_GET['price_max'] ?? null;
        $bedrooms   = $_GET['bedrooms'] ?? null;
        $bathrooms  = $_GET['bathrooms'] ?? null;
        $featured   = $_GET['is_featured'] ?? null;

        $where = ["p.status = 'Active'"];
        $args  = [];

        if ($q) {
            $where[] = '(p.title LIKE ? OR p.description LIKE ? OR p.address LIKE ?)';
            $args = array_merge($args, ["%$q%", "%$q%", "%$q%"]);
        }
        if ($project_id !== null) {
            $where[] = 'p.project_id = ?';
            $args[]  = (int)$project_id;
        }
        if ($builder_id !== null) {
            $where[] = 'p.builder_id = ?';
            $args[]  = (int)$builder_id;
        }
        if ($citySlug) {
            $where[] = 'c.slug = ?';
            $args[]  = $citySlug;
        }
        if ($localSlug) {
            $where[] = 'loc.slug = ?';
            $args[]  = $localSlug;
        }
        if ($propType) {
            $where[] = 'p.property_type = ?';
            $args[]  = $propType;
        }
        if ($listType) {
            $where[] = 'p.listing_type = ?';
            $args[]  = $listType;
        }
        if ($priceMin !== null) {
            $where[] = 'p.price >= ?';
            $args[]  = (float)$priceMin;
        }
        if ($priceMax !== null) {
            $where[] = 'p.price <= ?';
            $args[]  = (float)$priceMax;
        }
        if ($bedrooms !== null) {
            $where[] = 'p.bedrooms = ?';
            $args[]  = (int)$bedrooms;
        }
        if ($bathrooms !== null) {
            $where[] = 'p.bathrooms = ?';
            $args[]  = (int)$bathrooms;
        }
        if ($featured !== null) {
            $where[] = 'p.is_featured = ?';
            $args[]  = (int)$featured;
        }

        $whereStr = implode(' AND ', $where);

        // Fetch total count
        $countQuery = "
            SELECT COUNT(*) 
            FROM properties p
            LEFT JOIN cities c ON c.id = p.city_id
            LEFT JOIN localities loc ON loc.id = p.locality_id
            WHERE $whereStr
        ";
        $totalStmt = $pdo->prepare($countQuery);
        $totalStmt->execute($args);
        $total = (int)$totalStmt->fetchColumn();

        // Fetch properties
        $selectQuery = "
            SELECT p.id, p.title, p.slug, p.property_type, p.listing_type, p.market_type, p.possession_status,
                   p.price, p.price_display_override, p.price_unit, p.is_gst_inclusive, p.bedrooms, p.bathrooms,
                   p.balconies, p.parking_spaces, p.super_built_up_area, p.built_up_area, p.carpet_area, p.area_unit,
                   p.furnishing_status, p.facing, p.address, p.pincode, p.latitude, p.longitude, p.is_featured,
                   p.thumbnail_image, p.created_at, p.updated_at,
                   pr.name AS project_name, pr.slug AS project_slug,
                   b.name AS builder_name, b.slug AS builder_slug, 
                   c.name AS city_name, c.slug AS city_slug,
                   loc.name AS locality_name, loc.slug AS locality_slug
            FROM properties p
            LEFT JOIN projects pr ON pr.id = p.project_id
            LEFT JOIN builders b ON b.id = p.builder_id
            LEFT JOIN cities c ON c.id = p.city_id
            LEFT JOIN localities loc ON loc.id = p.locality_id
            WHERE $whereStr 
            ORDER BY p.is_featured DESC, p.id DESC
            LIMIT ? OFFSET ?
        ";

        $stmt = $pdo->prepare($selectQuery);
        $paramIndex = 1;
        foreach ($args as $val) {
            $stmt->bindValue($paramIndex++, $val);
        }
        $stmt->bindValue($paramIndex++, $limit, PDO::PARAM_INT);
        $stmt->bindValue($paramIndex++, $offset, PDO::PARAM_INT);
        $stmt->execute();
        $properties = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Normalize data values
        foreach ($properties as &$prop) {
            $prop['id'] = (int)$prop['id'];
            $prop['price'] = $prop['price'] !== null ? (float)$prop['price'] : null;
            $prop['is_gst_inclusive'] = (int)$prop['is_gst_inclusive'];
            $prop['bedrooms'] = $prop['bedrooms'] !== null ? (int)$prop['bedrooms'] : null;
            $prop['bathrooms'] = $prop['bathrooms'] !== null ? (int)$prop['bathrooms'] : null;
            $prop['balconies'] = $prop['balconies'] !== null ? (int)$prop['balconies'] : null;
            $prop['parking_spaces'] = $prop['parking_spaces'] !== null ? (int)$prop['parking_spaces'] : null;
            $prop['super_built_up_area'] = $prop['super_built_up_area'] !== null ? (int)$prop['super_built_up_area'] : null;
            $prop['built_up_area'] = $prop['built_up_area'] !== null ? (int)$prop['built_up_area'] : null;
            $prop['carpet_area'] = $prop['carpet_area'] !== null ? (int)$prop['carpet_area'] : null;
            $prop['is_featured'] = (int)$prop['is_featured'];
            $prop['latitude'] = $prop['latitude'] !== null ? (float)$prop['latitude'] : null;
            $prop['longitude'] = $prop['longitude'] !== null ? (float)$prop['longitude'] : null;
        }

        $meta = [
            'total_records' => $total,
            'page'          => $page,
            'per_page'      => $limit,
            'total_pages'   => ceil($total / $limit)
        ];

        $this->apiSuccess($properties, $meta);
    }

    /**
     * GET /api/v1/properties/{id}
     * Retrieve single property resource metadata.
     */
    public function show(array $params): void {
        $this->authenticate('listings:read');
        $pdo = db();

        $id = $params['id'] ?? '';
        $isNumeric = is_numeric($id);

        $query = "
            SELECT p.*,
                   pr.name AS project_name, pr.slug AS project_slug,
                   b.name AS builder_name, b.slug AS builder_slug, b.logo AS builder_logo,
                   c.name AS city_name, c.slug AS city_slug,
                   loc.name AS locality_name, loc.slug AS locality_slug
            FROM properties p
            LEFT JOIN projects pr ON pr.id = p.project_id
            LEFT JOIN builders b ON b.id = p.builder_id
            LEFT JOIN cities c ON c.id = p.city_id
            LEFT JOIN localities loc ON loc.id = p.locality_id
            WHERE " . ($isNumeric ? "p.id = ?" : "p.slug = ?") . " AND p.status = 'Active'
            LIMIT 1
        ";

        $stmt = $pdo->prepare($query);
        $stmt->execute([$id]);
        $property = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$property) {
            $this->apiError("Property listing not found.", 404);
        }

        // Cast fields
        $property['id'] = (int)$property['id'];
        $property['project_id'] = $property['project_id'] !== null ? (int)$property['project_id'] : null;
        $property['builder_id'] = $property['builder_id'] !== null ? (int)$property['builder_id'] : null;
        $property['city_id'] = $property['city_id'] !== null ? (int)$property['city_id'] : null;
        $property['locality_id'] = $property['locality_id'] !== null ? (int)$property['locality_id'] : null;
        $property['price'] = $property['price'] !== null ? (float)$property['price'] : null;
        $property['is_gst_inclusive'] = (int)$property['is_gst_inclusive'];
        $property['vastu_compliant'] = (int)$property['vastu_compliant'];
        $property['bedrooms'] = $property['bedrooms'] !== null ? (int)$property['bedrooms'] : null;
        $property['bathrooms'] = $property['bathrooms'] !== null ? (int)$property['bathrooms'] : null;
        $property['balconies'] = $property['balconies'] !== null ? (int)$property['balconies'] : null;
        $property['parking_spaces'] = $property['parking_spaces'] !== null ? (int)$property['parking_spaces'] : null;
        $property['super_built_up_area'] = $property['super_built_up_area'] !== null ? (int)$property['super_built_up_area'] : null;
        $property['built_up_area'] = $property['built_up_area'] !== null ? (int)$property['built_up_area'] : null;
        $property['carpet_area'] = $property['carpet_area'] !== null ? (int)$property['carpet_area'] : null;
        $property['is_featured'] = (int)$property['is_featured'];
        $property['latitude'] = $property['latitude'] !== null ? (float)$property['latitude'] : null;
        $property['longitude'] = $property['longitude'] !== null ? (float)$property['longitude'] : null;

        // Parse JSON lists
        try {
            $property['gallery_images'] = json_decode($property['gallery_images'] ?? '[]', true) ?: [];
        } catch (Throwable $e) {
            $property['gallery_images'] = [];
        }
        try {
            $property['floor_plan_images'] = json_decode($property['floor_plan_images'] ?? '[]', true) ?: [];
        } catch (Throwable $e) {
            $property['floor_plan_images'] = [];
        }

        $this->apiSuccess($property);
    }
}
