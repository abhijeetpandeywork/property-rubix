<?php
class BlogController extends Controller {

    public function listing(array $params = []): void {
        $pdo = db();

        $catSlug = $_GET['cat'] ?? '';
        $where   = ["bp.status = 'published'"];
        $args    = [];

        if ($catSlug) {
            $where[] = 'bc.slug = ?';
            $args[]  = $catSlug;
        }
        $whereStr = implode(' AND ', $where);

        $total = $pdo->prepare("SELECT COUNT(*) FROM blog_posts bp LEFT JOIN blog_categories bc ON bc.id=bp.category_id WHERE $whereStr");
        $total->execute($args);
        $total = (int)$total->fetchColumn();

        $pager = new Pagination($total, 9);
        $args2 = array_merge($args, [$pager->perPage, $pager->offset]);

        $posts = $pdo->prepare(
            "SELECT bp.*, bc.name AS category_name, bc.slug AS category_slug
             FROM blog_posts bp
             LEFT JOIN blog_categories bc ON bc.id = bp.category_id
             WHERE $whereStr
             ORDER BY bp.published_at DESC
             LIMIT ? OFFSET ?"
        );
        $posts->execute($args2);

        $categories = $pdo->query(
            "SELECT bc.*, COUNT(bp.id) AS post_count
             FROM blog_categories bc
             LEFT JOIN blog_posts bp ON bp.category_id=bc.id AND bp.status='published'
             GROUP BY bc.id HAVING post_count > 0 ORDER BY bc.name"
        )->fetchAll();

        $this->view('blog/listing', [
            'pageTitle'  => 'Real Estate Blog | PropertyRubix',
            'metaDesc'   => 'Expert insights, market trends, and property buying guides from PropertyRubix.',
            'posts'      => $posts->fetchAll(),
            'pager'      => $pager,
            'total'      => $total,
            'categories' => $categories,
            'activeCat'  => $catSlug,
        ]);
    }

    public function detail(array $params): void {
        $pdo  = db();
        $stmt = $pdo->prepare(
            "SELECT bp.*, bc.name AS category_name, bc.slug AS category_slug
             FROM blog_posts bp
             LEFT JOIN blog_categories bc ON bc.id = bp.category_id
             WHERE bp.slug = ? AND bp.status = 'published'"
        );
        $stmt->execute([$params['slug']]);
        $post = $stmt->fetch();
        if (!$post) { http_response_code(404); $this->view('errors/404', []); return; }

        // Related posts (same category)
        $related = $pdo->prepare(
            "SELECT bp.*, bc.name AS category_name FROM blog_posts bp
             LEFT JOIN blog_categories bc ON bc.id=bp.category_id
             WHERE bp.status='published' AND bp.id != ? AND bp.category_id = ?
             ORDER BY bp.published_at DESC LIMIT 3"
        );
        $related->execute([$post['id'], $post['category_id']]);

        $this->view('blog/detail', [
            'pageTitle'  => ($post['meta_title'] ?: $post['title'] . ' | PropertyRubix Blog'),
            'metaDesc'   => ($post['meta_description'] ?: View::excerpt($post['excerpt'] ?? $post['body'], 155)),
            'post'       => $post,
            'related'    => $related->fetchAll(),
        ]);
    }
}
