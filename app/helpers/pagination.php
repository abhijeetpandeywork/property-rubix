<?php
/**
 * Pagination helper.
 */

class Pagination {
    public int $total;
    public int $perPage;
    public int $currentPage;
    public int $totalPages;
    public int $offset;

    public function __construct(int $total, int $perPage = 12, ?int $currentPage = null) {
        $this->total       = $total;
        $this->perPage     = max(1, $perPage);
        $this->currentPage = max(1, $currentPage ?? (int)($_GET['page'] ?? 1));
        $this->totalPages  = (int)ceil($total / $this->perPage);
        $this->currentPage = min($this->currentPage, max(1, $this->totalPages));
        $this->offset      = ($this->currentPage - 1) * $this->perPage;
    }

    public function hasPages(): bool {
        return $this->totalPages > 1;
    }

    /** Render Bootstrap 5 pagination links */
    public function render(string $baseUrl = ''): string {
        if (!$this->hasPages()) return '';

        $base   = $baseUrl ?: strtok($_SERVER['REQUEST_URI'], '?');
        $params = $_GET;

        $html = '<nav aria-label="Page navigation"><ul class="pagination justify-content-center flex-wrap gap-1">';

        // Prev
        if ($this->currentPage > 1) {
            $params['page'] = $this->currentPage - 1;
            $html .= '<li class="page-item"><a class="page-link" href="' . $base . '?' . http_build_query($params) . '">‹ Prev</a></li>';
        }

        // Pages
        $range = range(max(1, $this->currentPage - 2), min($this->totalPages, $this->currentPage + 2));
        if (!in_array(1, $range)) {
            $params['page'] = 1;
            $html .= '<li class="page-item"><a class="page-link" href="' . $base . '?' . http_build_query($params) . '">1</a></li>';
            if (!in_array(2, $range)) $html .= '<li class="page-item disabled"><span class="page-link">…</span></li>';
        }
        foreach ($range as $p) {
            $params['page'] = $p;
            $active = $p === $this->currentPage ? ' active' : '';
            $html  .= '<li class="page-item' . $active . '"><a class="page-link" href="' . $base . '?' . http_build_query($params) . '">' . $p . '</a></li>';
        }
        if (!in_array($this->totalPages, $range)) {
            if (!in_array($this->totalPages - 1, $range)) $html .= '<li class="page-item disabled"><span class="page-link">…</span></li>';
            $params['page'] = $this->totalPages;
            $html .= '<li class="page-item"><a class="page-link" href="' . $base . '?' . http_build_query($params) . '">' . $this->totalPages . '</a></li>';
        }

        // Next
        if ($this->currentPage < $this->totalPages) {
            $params['page'] = $this->currentPage + 1;
            $html .= '<li class="page-item"><a class="page-link" href="' . $base . '?' . http_build_query($params) . '">Next ›</a></li>';
        }

        $html .= '</ul></nav>';
        return $html;
    }
}
