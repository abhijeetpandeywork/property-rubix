<?php
/**
 * Admin CRUD Template — reusable pattern for all admin modules.
 * This is an internal reference file, not a directly served page.
 *
 * Usage pattern (copy and adapt):
 *   1. require auth_check
 *   2. define $table, $pageTitle, $fields
 *   3. handle GET (list/edit/delete) and POST (save)
 *   4. render with header/footer includes
 */

// ── Generic CRUD helper functions ──────────────────────────────────────────

/**
 * Build a simple paginated list query.
 */
function crudList(PDO $pdo, string $table, int $perPage = 20, string $orderBy = 'id DESC', string $extraJoin = '', string $extraSelect = '', string $search = '', array $searchCols = []): array {
    $page   = max(1, (int)($_GET['page'] ?? 1));
    $offset = ($page - 1) * $perPage;

    $where = '1=1';
    $args  = [];
    if ($search && $searchCols) {
        $clauses = array_map(fn($c) => "$c LIKE ?", $searchCols);
        $where  .= ' AND (' . implode(' OR ', $clauses) . ')';
        foreach ($searchCols as $_) $args[] = "%$search%";
    }

    $total = $pdo->prepare("SELECT COUNT(*) FROM `$table` $extraJoin WHERE $where");
    $total->execute($args);
    $total = (int)$total->fetchColumn();

    $rows = $pdo->prepare("SELECT `$table`.* $extraSelect FROM `$table` $extraJoin WHERE $where ORDER BY $orderBy LIMIT ? OFFSET ?");
    $rows->execute(array_merge($args, [$perPage, $offset]));

    return [
        'rows'       => $rows->fetchAll(),
        'total'      => $total,
        'page'       => $page,
        'totalPages' => (int)ceil($total / $perPage),
        'perPage'    => $perPage,
    ];
}

/**
 * Generic delete handler.
 */
function crudDelete(PDO $pdo, string $table, int $id, string $redirectUrl): void {
    $pdo->prepare("DELETE FROM `$table` WHERE id=?")->execute([$id]);
    logAction('DELETE', $table, $id);
    header('Location: ' . $redirectUrl . '?deleted=1');
    exit;
}

/**
 * Flash message from query string.
 */
function flashMsg(): string {
    $msg = '';
    if (isset($_GET['saved'])) {
        $msg = "Swal.fire({ title: 'Success!', text: 'Thank you! Submitted successfully.', icon: 'success', confirmButtonColor: '#16a34a' });";
    } elseif (isset($_GET['deleted'])) {
        $msg = "Swal.fire({ title: 'Deleted!', text: 'Item has been removed.', icon: 'warning', confirmButtonColor: '#16a34a' });";
    }
    
    if ($msg) {
        return "<script src=\"https://cdn.jsdelivr.net/npm/sweetalert2@11\"></script><script>document.addEventListener('DOMContentLoaded', function() { $msg });</script>";
    }
    return '';
}
