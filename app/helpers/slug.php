<?php
/**
 * Slug helper — generate URL-safe slugs.
 */

function slugify(string $text): string {
    // Transliterate unicode to ASCII if intl extension is enabled
    if (function_exists('transliterator_transliterate')) {
        $text = transliterator_transliterate('Any-Latin; Latin-ASCII', $text) ?? $text;
    }
    
    // Basic fallback for common accented characters if transliterator failed or isn't available
    $text = str_replace(
        ['á','à','â','ä','ã','å','ç','é','è','ê','ë','í','ì','î','ï','ñ','ó','ò','ô','ö','õ','ú','ù','û','ü','ý','ÿ'],
        ['a','a','a','a','a','a','c','e','e','e','e','i','i','i','i','n','o','o','o','o','o','u','u','u','u','y','y'],
        mb_strtolower($text, 'UTF-8')
    );
    
    $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
    $text = preg_replace('/[\s-]+/', '-', trim($text));
    return trim($text, '-');
}

function uniqueSlug(string $table, string $baseSlug, ?int $excludeId = null): string {
    $pdo  = db();
    $slug = $baseSlug;
    $i    = 1;
    while (true) {
        $sql = "SELECT id FROM `$table` WHERE slug = ?";
        $args = [$slug];
        if ($excludeId) {
            $sql  .= ' AND id != ?';
            $args[] = $excludeId;
        }
        $row = $pdo->prepare($sql);
        $row->execute($args);
        if (!$row->fetch()) break;
        $slug = $baseSlug . '-' . $i++;
    }
    return $slug;
}
