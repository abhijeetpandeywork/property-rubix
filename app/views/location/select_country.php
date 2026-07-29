<?php
/**
 * Select Country / Region Page
 * Background: controlled from Admin → Settings → "Select Country Page Hero Background Image"
 */
?>
<style>
.select-region-hero {
    position: relative;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    overflow: hidden;
}
/* The background image layer */
.select-region-hero__bg {
    position: absolute;
    inset: 0;
    background-image: url('<?= e($heroBannerUrl) ?>');
    background-size: cover;
    background-position: center center;
    background-repeat: no-repeat;
    z-index: 0;
}
/* Light white overlay for readability */
.select-region-hero__overlay {
    position: absolute;
    inset: 0;
    background: rgba(255, 255, 255, 0.55);
    backdrop-filter: blur(2px);
    z-index: 1;
}
/* Content sits on top */
.select-region-hero__content {
    position: relative;
    z-index: 2;
    flex-grow: 1;
    padding-top: 110px;
    padding-bottom: 60px;
}
.region-title {
    font-family: 'Inter', sans-serif;
    font-weight: 400;
    font-size: clamp(2rem, 5vw, 3rem);
    line-height: 1.1;
    color: #111;
    margin-bottom: 2.5rem;
    letter-spacing: -1px;
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
    font-size: 1.05rem;
    color: #111;
    border-bottom: 2px solid rgba(0,0,0,0.08);
    padding-bottom: 4px;
    margin-bottom: 0.6rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
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
</style>

<div class="select-region-hero">
    <div class="select-region-hero__bg"></div>
    <div class="select-region-hero__overlay"></div>
    <div class="select-region-hero__content container px-4 px-md-5">
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
