<?php
class PageController extends Controller {

    public function show(array $params): void {
        $pdo  = db();
        $slug = $params['slug'] ?? '';
        $stmt = $pdo->prepare("SELECT * FROM pages WHERE slug=? AND status='published'");
        $stmt->execute([$slug]);
        $page = $stmt->fetch();
        if (!$page) { http_response_code(404); $this->view('errors/404', []); return; }

        // Load FAQs for contact-adjacent pages
        $faqs = [];
        if ($slug === 'about-us') {
            $faqs = $pdo->query("SELECT * FROM faqs WHERE status='active' ORDER BY sort_order LIMIT 6")->fetchAll();
        }

        $viewName = 'page/generic';
        if ($slug === 'about-us') {
            $viewName = 'page/about_us';
        } elseif ($slug === 'advertise-with-us') {
            $viewName = 'page/advertise';
        } elseif ($slug === 'privacy-policy') {
            $viewName = 'page/privacy';
        } elseif ($slug === 'terms-conditions') {
            $viewName = 'page/terms';
        }

        $this->view($viewName, [
            'pageTitle' => ($page['meta_title'] ?: $page['title'] . ' | PropertyRubix'),
            'metaDesc'  => ($page['meta_description'] ?: ''),
            'page'      => $page,
            'faqs'      => $faqs,
        ]);
    }

    public function contact(array $params = []): void {
        $faqs = db()->query("SELECT * FROM faqs WHERE status='active' ORDER BY sort_order")->fetchAll();

        $this->view('page/contact', [
            'pageTitle' => 'Contact Us | PropertyRubix',
            'metaDesc'  => 'Get in touch with PropertyRubix for property enquiries, site visits, or partnership opportunities.',
            'faqs'      => $faqs,
        ]);
    }
}
