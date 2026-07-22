<?php
/**
 * PropertyRubix — API Builder Controller
 */

class ApiBuilderController extends ApiBaseController {

    /**
     * GET /api/v1/builders
     * List all developers/builders.
     */
    public function index(array $params = []): void {
        $this->authenticate('listings:read');
        $pdo = db();

        $page  = max(1, (int)($_GET['page'] ?? 1));
        $limit = min(100, max(1, (int)($_GET['limit'] ?? 20)));
        $offset = ($page - 1) * $limit;

        $stmtCount = $pdo->query("SELECT COUNT(*) FROM builders WHERE status = 'active'");
        $total = (int)$stmtCount->fetchColumn();

        $stmt = $pdo->prepare("
            SELECT id, name, slug, logo, description, website, established_year, total_projects, created_at
            FROM builders
            WHERE status = 'active'
            ORDER BY name ASC
            LIMIT ? OFFSET ?
        ");
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->bindValue(2, $offset, PDO::PARAM_INT);
        $stmt->execute();
        $builders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($builders as &$b) {
            $b['id'] = (int)$b['id'];
            $b['total_projects'] = (int)$b['total_projects'];
            $b['established_year'] = $b['established_year'] !== null ? (int)$b['established_year'] : null;
        }

        $meta = [
            'total_records' => $total,
            'page'          => $page,
            'per_page'      => $limit,
            'total_pages'   => ceil($total / $limit)
        ];

        $this->apiSuccess($builders, $meta);
    }
}
