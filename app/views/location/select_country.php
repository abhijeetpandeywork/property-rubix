<?php
/**
 * Select Country / Region Page
 * Variables: $regions (grouped countries by continent)
 */
$headerLogo = getSetting('header_logo', '');
$headerTitle = getSetting('header_title', '');
$siteName = getSetting('site_name') ?: 'PropertyRubix';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?> | <?= e($siteName) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>?v=<?= time() ?>">
    <style>
        body, html {
            height: 100%;
            margin: 0;
            background-color: #f9f9f9;
        }
        .select-region-container {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background: #ffffff;
            /* World map background (to be uploaded by user as world-map.png) */
            background-image: url('<?= asset('img/world-map.png') ?>');
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
</head>
<body>

<?php require APP_PATH . 'views/layouts/header.php'; ?>

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

<?php require APP_PATH . 'views/layouts/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>window.BASE_URL = '<?= PUBLIC_URL ?>';</script>
<script src="<?= asset('js/app.js') ?>?v=<?= time() ?>"></script>
</body>
</html>
