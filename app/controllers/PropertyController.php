<?php

class PropertyController extends Controller {

    public function listing() {
        $pdo = db();
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $limit = 12;
        $offset = ($page - 1) * $limit;

        $sql = "SELECT p.*, pr.name as project_name, c.name as city_name, loc.name as location_area, b.name as builder_name
                FROM properties p 
                LEFT JOIN projects pr ON p.project_id = pr.id
                LEFT JOIN cities c ON p.city_id = c.id
                LEFT JOIN localities loc ON p.locality_id = loc.id
                LEFT JOIN builders b ON p.builder_id = b.id
                WHERE p.status = 'Active'";
        
        $params = [];

        // Search
        if (!empty($_GET['q'])) {
            $sql .= " AND (p.title LIKE ? OR pr.name LIKE ? OR c.name LIKE ? OR loc.name LIKE ? OR b.name LIKE ? OR p.property_type LIKE ? OR p.bedrooms LIKE ?)";
            $q = '%' . $_GET['q'] . '%';
            $params = array_merge($params, [$q, $q, $q, $q, $q, $q, $q]);
        }

        // Count for pagination
        $countSql = preg_replace('/SELECT .*? FROM/is', 'SELECT COUNT(*) FROM', $sql);
        $stmtCount = $pdo->prepare($countSql);
        $stmtCount->execute($params);
        $total = $stmtCount->fetchColumn();

        // Fetch properties
        $sql .= " ORDER BY p.is_featured DESC, p.created_at DESC LIMIT $limit OFFSET $offset";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $properties = $stmt->fetchAll();

        $pager = new Pagination($total, $limit, $page);

        $this->view('property/listing', [
            'properties' => $properties,
            'pager' => $pager,
            'search' => $_GET['q'] ?? ''
        ]);
    }

    public function detail($params) {
        $slug = $params['slug'] ?? '';
        $pdo = db();
        $stmt = $pdo->prepare("
            SELECT p.*, 
                   pr.name as project_name, pr.slug as project_slug, pr.type as project_type,
                   c.name as city_name, loc.name as location_area, b.name as builder_name, b.logo as builder_logo
            FROM properties p 
            LEFT JOIN projects pr ON p.project_id = pr.id
            LEFT JOIN cities c ON p.city_id = c.id
            LEFT JOIN localities loc ON p.locality_id = loc.id
            LEFT JOIN builders b ON p.builder_id = b.id
            WHERE p.slug = ? AND p.status = 'Active'
        ");
        $stmt->execute([$slug]);
        $property = $stmt->fetch();

        if (!$property) {
            header("HTTP/1.0 404 Not Found");
            echo "Property not found.";
            exit;
        }

        $this->view('property/detail', [
            'property' => $property,
            'headerLogo' => $property['builder_logo'] ?? '',
            'headerTitle' => $property['builder_name'] ?? ''
        ]);
    }
}
