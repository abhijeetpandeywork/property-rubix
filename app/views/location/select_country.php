<style>
    .select-region-container {
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        background: #ffffff;
        /* World map background (to be uploaded by user as world-map.png) */
        background-image: url('<?= asset('img/world-map.png') ?>?v=<?= time() ?>');
        background-size: cover;
        background-position: center center;
        background-repeat: no-repeat;
        background-attachment: fixed;
        position: relative;
    }
    /* Overlay to ensure text readability if map is too dark, though user's map is very light gray */
    .select-region-overlay {
        position: absolute;
        inset: 0;
        background: rgba(255, 255, 255, 0.4);
        z-index: 1;
    }
    .select-region-content {
        position: relative;
        z-index: 2;
        flex-grow: 1;
        padding-top: 100px; /* Space for header */
        padding-bottom: 50px;
    }
    .region-title {
        font-family: 'Inter', sans-serif;
        font-weight: 400;
        font-size: 2.5rem;
        line-height: 1.1;
        color: #111;
        margin-bottom: 3rem;
        letter-spacing: -1px;
        text-transform: uppercase;
    }
    .region-title span {
        font-weight: 800;
        display: block;
    }
    
    .continent-block {
        margin-bottom: 2.5rem;
    }
    .continent-name {
        font-weight: 700;
        font-size: 1.1rem;
        color: #111;
        margin-bottom: 0.5rem;
    }
    .country-link {
        display: inline-block;
        color: #555;
        text-decoration: none;
        font-size: 0.95rem;
        margin-right: 1.5rem;
        margin-bottom: 0.5rem;
        transition: color 0.2s ease;
    }
    .country-link:hover {
        color: var(--pr-primary, #eab308);
    }
</style>

<div class="select-region-container">
    <div class="select-region-overlay"></div>
    <div class="select-region-content container px-4 px-md-5">
        <div class="row">
            <div class="col-lg-8 col-xl-6">
                <h1 class="region-title">
                    SELECT<br>
                    <span>YOUR REGION</span>
                </h1>
                
                <div class="regions-list mt-5">
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
