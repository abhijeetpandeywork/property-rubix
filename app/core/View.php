<?php
/**
 * View helper — static utility methods for templates.
 */
class View {

    /**
     * Format price range for display.
     */
    public static function priceRange(?float $min, ?float $max, bool $onRequest = false): string {
        if ($onRequest) return '<span class="text-primary fw-600">Price on Request</span>';
        if (!$min && !$max) return '<span class="text-muted">Price on Request</span>';

        $f = fn($n) => self::formatPrice($n);
        if ($min && $max && $min !== $max) return '₹' . $f($min) . ' – ₹' . $f($max);
        if ($min) return '₹' . $f($min) . ' Onwards';
        if ($max) return 'Up to ₹' . $f($max);
        return 'Price on Request';
    }

    public static function formatPrice(?float $n): string {
        if (!$n) return '0';
        if ($n >= 1_00_00_000)  return round($n / 1_00_00_000, 2) . ' Cr';
        if ($n >= 1_00_000)     return round($n / 1_00_000,    2) . ' L';
        return number_format((int)$n);
    }

    /**
     * Render star rating HTML.
     */
    public static function stars(int $rating, int $max = 5): string {
        $html = '';
        for ($i = 1; $i <= $max; $i++) {
            $html .= $i <= $rating ? '★' : '☆';
        }
        return '<span class="stars" aria-label="' . $rating . ' out of ' . $max . '">' . $html . '</span>';
    }

    /**
     * Truncate text to N characters.
     */
    public static function excerpt(string $text, int $length = 120): string {
        // Strip HTML tags for plain text excerpts
        $text = strip_tags($text);
        if (mb_strlen($text) <= $length) return $text;
        return mb_substr($text, 0, $length) . '…';
    }

    /**
     * Human-readable time ago (e.g. "3 days ago").
     */
    public static function timeAgo(?string $datetime): string {
        if (!$datetime) return '';
        $diff = time() - strtotime($datetime);
        if ($diff < 60)      return 'just now';
        if ($diff < 3600)    return floor($diff / 60) . ' min ago';
        if ($diff < 86400)   return floor($diff / 3600) . ' hr ago';
        if ($diff < 2592000) return floor($diff / 86400) . ' days ago';
        if ($diff < 31536000)return floor($diff / 2592000) . ' months ago';
        return floor($diff / 31536000) . ' years ago';
    }
}
