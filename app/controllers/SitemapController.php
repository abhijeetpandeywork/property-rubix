<?php
class SitemapController extends Controller {

    public function xml(array $params = []): void {
        $pdo = db();
        $baseUrl = PUBLIC_URL;

        header("Content-Type: application/xml; charset=utf-8");
        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        // Static routes
        $staticUrls = [
            '', 'projects', 'properties', 'location', 'developer', 'blog', 'contact-us',
            'about-us', 'privacy-policy', 'terms-conditions', 'advertise-with-us'
        ];

        foreach ($staticUrls as $url) {
            echo "  <url>\n";
            echo "    <loc>" . htmlspecialchars($baseUrl . $url) . "</loc>\n";
            echo "    <changefreq>daily</changefreq>\n";
            echo "    <priority>" . ($url === '' ? '1.0' : '0.8') . "</priority>\n";
            echo "  </url>\n";
        }

        // Projects
        $projects = $pdo->query("SELECT slug, updated_at FROM projects WHERE status='published'")->fetchAll();
        foreach ($projects as $p) {
            echo "  <url>\n";
            echo "    <loc>" . htmlspecialchars($baseUrl . 'project/' . $p['slug']) . "</loc>\n";
            echo "    <lastmod>" . date('c', strtotime($p['updated_at'])) . "</lastmod>\n";
            echo "    <priority>0.9</priority>\n";
            echo "  </url>\n";
        }

        // Properties
        $properties = $pdo->query("SELECT slug, updated_at FROM properties WHERE status='Active'")->fetchAll();
        foreach ($properties as $p) {
            echo "  <url>\n";
            echo "    <loc>" . htmlspecialchars($baseUrl . 'property/' . $p['slug']) . "</loc>\n";
            echo "    <lastmod>" . date('c', strtotime($p['updated_at'])) . "</lastmod>\n";
            echo "    <priority>0.9</priority>\n";
            echo "  </url>\n";
        }

        // Builders
        $builders = $pdo->query("SELECT slug FROM builders WHERE status='active'")->fetchAll();
        foreach ($builders as $b) {
            echo "  <url>\n";
            echo "    <loc>" . htmlspecialchars($baseUrl . 'developer/' . $b['slug']) . "</loc>\n";
            echo "    <priority>0.7</priority>\n";
            echo "  </url>\n";
        }

        // Blogs
        $blogs = $pdo->query("SELECT slug, updated_at FROM blog_posts WHERE status='published'")->fetchAll();
        foreach ($blogs as $b) {
            echo "  <url>\n";
            echo "    <loc>" . htmlspecialchars($baseUrl . 'blog/' . $b['slug']) . "</loc>\n";
            echo "    <lastmod>" . date('c', strtotime($b['updated_at'])) . "</lastmod>\n";
            echo "    <priority>0.7</priority>\n";
            echo "  </url>\n";
        }

        echo "</urlset>\n";
    }
}
