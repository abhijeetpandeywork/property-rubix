<?php
/**
 * Reusable Property Card Component
 * Expects $p (project array)
 */
$img = !empty($p['thumbnail_image']) ? upload($p['thumbnail_image']) : (!empty($p['banner_image']) ? upload($p['banner_image']) : 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?w=400&q=70');

$statusLabel = str_replace('_', ' ', ucfirst($p['status']));
$priceStr = View::priceRange($p['price_min'], $p['price_max'], (bool)$p['price_on_request']);
if ($priceStr !== 'Price on Request' && !str_contains($priceStr, '-')) {
    $priceStr .= ' Onwards';
}

$cityName = !empty($p['city_name']) ? $p['city_name'] : (!empty($p['location_area']) ? $p['location_area'] : 'Unknown');

$area = !empty($p['area_range']) ? $p['area_range'] : 'N/A';
$projSize = !empty($p['total_area']) ? 'Approximately ' . $p['total_area'] : 'N/A';
$type = !empty($p['type']) ? ucfirst($p['type']) : 'Apartment';
$configs = !empty($p['unit_types']) ? $p['unit_types'] : 'N/A';
?>

<article class="p-card glass-card">
    <div class="p-card-img-wrapper">
        <a href="<?= PUBLIC_URL ?>project/<?= e($p['slug']) ?>">
            <img src="<?= e($img) ?>" alt="<?= e($p['name']) ?>">
        </a>
        <div class="p-card-badge-tl"><?= e($statusLabel) ?></div>
        <div class="p-card-badge-br">Artistic Impression</div>
    </div>
    
    <div class="p-card-body">
        <h3 class="p-card-title"><a href="<?= PUBLIC_URL ?>project/<?= e($p['slug']) ?>"><?= e($p['name']) ?></a></h3>
        
        <div class="p-card-rating">
            <i class="fas fa-star text-warning"></i><i class="fas fa-star text-warning"></i><i class="fas fa-star text-warning"></i><i class="fas fa-star text-warning"></i><i class="fas fa-star text-warning"></i>
            <span>5.0 Rating</span>
        </div>

        <div class="p-card-location">
            <i class="fas fa-map-marker-alt"></i> <?= e($cityName) ?>
        </div>

        <div class="p-card-price">
            <span class="text-dark me-2" style="font-weight:500;font-size:0.95rem;">Price</span> <?= $priceStr ?>
        </div>

        <div class="p-card-grid">
            <div class="p-card-grid-item">
                <i class="fas fa-vector-square text-muted"></i> 
                <span><?= e($area) ?></span>
            </div>
            <div class="p-card-grid-item">
                <i class="fas fa-layer-group text-muted"></i> 
                <span><?= e($projSize) ?></span>
            </div>
            <div class="p-card-grid-item">
                <i class="fas fa-tags text-muted"></i> 
                <span><?= e($type) ?></span>
            </div>
            <div class="p-card-grid-item">
                <i class="fas fa-home text-muted"></i> 
                <span><?= e($configs) ?></span>
            </div>
        </div>
    </div>
    
    <div class="p-card-footer">
        <a href="<?= PUBLIC_URL ?>project/<?= e($p['slug']) ?>" class="p-btn p-btn-primary">
            <i class="fas fa-eye me-1"></i> View
        </a>
        <a href="<?= PUBLIC_URL ?>project/<?= e($p['slug']) ?>#enquiry" class="p-btn p-btn-primary">
            <i class="fas fa-car me-1"></i> Visit
        </a>
        <a href="tel:<?= preg_replace('/[^+\d]/', '', getSetting('phone_primary', '+91 9876543210')) ?>" class="p-btn p-btn-icon" title="Call">
            <i class="fas fa-phone-alt"></i>
        </a>
        <a href="https://wa.me/<?= getSetting('whatsapp_number', '919876543210') ?>" class="p-btn p-btn-icon" target="_blank" title="WhatsApp">
            <i class="fab fa-whatsapp" style="font-size: 1.15rem;"></i>
        </a>
    </div>
</article>
