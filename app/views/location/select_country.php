<?php
/**
 * Select Country / Region Page
 * Hero background: Admin → Settings → "Select Country Page – Hero Background Image"
 */
$hasBanner = !empty($heroBannerUrl);
?>
<style>
/* ── Hero Wrapper ───────────────────────────────────────────────── */
.scr-hero {
    position: relative;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    background: #f5f5f5; /* solid fallback — prevents any bleed-through */
    isolation: isolate;  /* creates a new stacking context */
}

<?php if ($hasBanner): ?>
/* ── Background image layer (only when set) ─────────────────────── */
.scr-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background-image: url('<?= e($heroBannerUrl) ?>');
    background-size: cover;
    background-position: center center;
    background-repeat: no-repeat;
    z-index: -2;
}
/* ── Light overlay for text readability ────────────────────────── */
.scr-hero::after {
    content: '';
    position: absolute;
    inset: 0;
    background: rgba(255, 255, 255, 0.50);
    z-index: -1;
}
<?php endif; ?>

/* ── Content ────────────────────────────────────────────────────── */
.scr-hero__content {
    flex-grow: 1;
    padding-top: 120px;
    padding-bottom: 60px;
    position: relative;
    z-index: 1;
}

.region-title {
    font-family: 'Inter', sans-serif;
    font-weight: 400;
    font-size: clamp(2rem, 5vw, 3rem);
    line-height: 1.1;
    color: #111;
    margin-bottom: 2.5rem;
    letter-spacing: -2px;
    text-transform: uppercase;
}
.region-title strong {
    font-weight: 900;
    display: block;
}
.continent-block {
    margin-bottom: 2rem;
}
.continent-name {
    font-weight: 700;
    font-size: 1rem;
    color: #111;
    border-bottom: 2px solid rgba(0,0,0,0.10);
    padding-bottom: 5px;
    margin-bottom: 0.6rem;
    text-transform: uppercase;
    letter-spacing: 1px;
}
.country-link {
    display: inline-block;
    color: #444;
    text-decoration: none;
    font-size: 0.95rem;
    margin-right: 1.5rem;
    margin-bottom: 0.4rem;
    transition: color 0.2s ease, transform 0.2s ease;
}
.country-link:hover {
    color: var(--pr-primary, #eab308);
    transform: translateX(3px);
}

/* ── "No banner" notice for admins ─────────────────────────────── */
.scr-no-banner-notice {
    position: absolute;
    top: 80px; right: 20px;
    background: #fff3cd;
    border: 1px solid #ffc107;
    border-radius: 8px;
    padding: 8px 14px;
    font-size: 0.8rem;
    color: #856404;
    z-index: 9999;
    display: flex;
    align-items: center;
    gap: 6px;
}
</style>

<div class="scr-hero">

    <?php if (!$hasBanner && isset($_SESSION['admin_id'])): ?>
        <div class="scr-no-banner-notice">
            <i class="fas fa-image"></i>
            No banner set. <a href="<?= BASE_URL ?>admin/settings/" class="ms-1 fw-bold text-warning">Upload in Admin → Settings</a>
        </div>
    <?php endif; ?>

    <div class="scr-hero__content container px-4 px-md-5">
        <div class="row">
            <div class="col-lg-8 col-xl-6">
                <h1 class="region-title">
                    Select<br>
                    <strong>Your Region</strong>
                </h1>

                <div class="regions-list mt-4">
                    <?php if (empty($regions)): ?>
                        <p class="text-muted">No regions available at the moment.</p>
                    <?php else: ?>
                        <?php foreach ($regions as $continent => $countries): ?>
                            <div class="continent-block anim-fade-up">
                                <div class="continent-name"><?= e($continent) ?></div>
                                <div class="d-flex flex-wrap">
                                    <?php foreach ($countries as $c): ?>
                                        <a href="<?= PUBLIC_URL ?>location/<?= e($c['slug']) ?>" class="country-link">
                                            <?= e($c['name']) ?>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
