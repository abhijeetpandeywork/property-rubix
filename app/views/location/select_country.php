<?php
/**
 * Select Country / Region Page — Premium Card Design
 * Matches the state/city card design from country.php / state.php
 * Stats: Real DB data from LocationController
 */
$hasBanner = !empty($heroBannerUrl);
$stats     = $stats ?? [];
?>
<style>
@import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&family=Inter:wght@400;500;600;700;800&display=swap');

/* ── Page wrapper — prevents bleed-through ── */
.scr-page {
    position: relative;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    background: #0d1117;
    isolation: isolate;
    font-family: 'Inter', sans-serif;
    overflow: hidden;
}

/* ── Background ──────────────────────────── */
.scr-bg {
    position: absolute;
    inset: 0;
    z-index: -2;
    <?php if ($hasBanner): ?>
    background-image: url('<?= e($heroBannerUrl) ?>');
    background-size: cover;
    background-position: center 30%;
    background-repeat: no-repeat;
    <?php else: ?>
    background: linear-gradient(135deg,#0f172a 0%,#1e293b 60%,#0f172a 100%);
    <?php endif; ?>
}
.scr-overlay {
    position: absolute;
    inset: 0;
    z-index: -1;
    <?php if ($hasBanner): ?>
    background: linear-gradient(
        170deg,
        rgba(8,8,14,0.94) 0%,
        rgba(8,8,14,0.80) 45%,
        rgba(8,8,14,0.55) 100%
    );
    <?php endif; ?>
}

/* ── Hero title section ──────────────────── */
.scr-hero-section {
    position: relative;
    z-index: 1;
    text-align: center;
    padding: 130px 5% 60px;
}
.scr-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(247,203,70,0.15);
    border: 1px solid rgba(247,203,70,0.40);
    border-radius: 50px;
    padding: 7px 18px;
    font-size: 0.70rem;
    font-weight: 700;
    letter-spacing: 2.5px;
    text-transform: uppercase;
    color: #f7cb46;
    margin-bottom: 22px;
}
.scr-main-title {
    font-family: 'Outfit', sans-serif;
    font-size: clamp(2.4rem, 5vw, 4rem);
    font-weight: 800;
    color: #ffffff;
    letter-spacing: -2px;
    line-height: 1.05;
    margin-bottom: 16px;
    text-shadow: 0 4px 30px rgba(0,0,0,0.4);
}
.scr-main-title .accent { color: #f7cb46; }
.scr-desc {
    color: rgba(255,255,255,0.65);
    font-size: 1.05rem;
    max-width: 520px;
    margin: 0 auto;
    line-height: 1.65;
}

/* ── Continent section header ────────────── */
.scr-section {
    position: relative;
    z-index: 1;
    padding: 0 5% 50px;
}
.scr-continent-header {
    display: flex;
    align-items: center;
    gap: 14px;
    margin-bottom: 24px;
}
.scr-continent-header-label {
    font-size: 0.70rem;
    font-weight: 800;
    letter-spacing: 3.5px;
    text-transform: uppercase;
    color: rgba(255,255,255,0.45);
    white-space: nowrap;
}
.scr-continent-header::after {
    content: '';
    flex: 1;
    height: 1px;
    background: rgba(255,255,255,0.10);
}

/* ── Cards grid ─────────────────────────── */
.scr-cards-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 18px;
}

/* ── Country Card — exact style as state/city cards ── */
.scr-card-link {
    text-decoration: none;
    display: block;
    height: 100%;
}
.scr-card {
    border-radius: 16px;
    padding: 28px 22px 22px;
    background: rgba(255,255,255,0.92);
    border: 1px solid rgba(255,255,255,0.85);
    box-shadow: 0 8px 30px rgba(0,0,0,0.18);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    position: relative;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    height: 100%;
}

/* Animated top border on hover — same as state cards */
.scr-card-border {
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 4px;
    background: linear-gradient(90deg, #f7cb46, #d49830);
    transform: scaleX(0);
    transform-origin: left;
    transition: transform 0.4s ease;
    border-radius: 16px 16px 0 0;
}
.scr-card-link:hover .scr-card-border { transform: scaleX(1); }
.scr-card-link:hover .scr-card {
    transform: translateY(-10px);
    box-shadow: 0 24px 50px rgba(247,203,70,0.18), 0 8px 20px rgba(0,0,0,0.15);
    border-color: rgba(247,203,70,0.40);
    background: #ffffff;
}

/* Card icon */
.scr-card-icon {
    width: 52px;
    height: 52px;
    border-radius: 14px;
    background: rgba(247,203,70,0.10);
    color: #f7cb46;
    font-size: 1.4rem;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.4s ease;
    margin-bottom: 4px;
    flex-shrink: 0;
}
.scr-card-link:hover .scr-card-icon {
    background: linear-gradient(135deg, #f7cb46, #d49830);
    color: #ffffff;
    transform: rotate(10deg) scale(1.1);
    box-shadow: 0 10px 22px rgba(247,203,70,0.35);
}

/* Card badge */
.scr-card-badge {
    font-size: 0.68rem;
    font-weight: 700;
    background: #f8f9fa;
    border: 1px solid #eaeaea;
    border-radius: 50px;
    padding: 3px 10px;
    color: #555;
    white-space: nowrap;
}

/* Card content */
.scr-card-name {
    font-family: 'Outfit', sans-serif;
    font-size: 1.25rem;
    font-weight: 800;
    color: #111;
    margin-bottom: 0;
    margin-top: 18px;
    transition: color 0.3s ease;
    line-height: 1.2;
}
.scr-card-link:hover .scr-card-name { color: #c9920b; }

.scr-card-cta {
    font-size: 0.78rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    color: #64748b;
    margin-top: 12px;
    display: flex;
    align-items: center;
    gap: 6px;
    transition: color 0.3s ease;
}
.scr-card-cta i {
    transition: transform 0.3s ease;
    font-size: 0.72rem;
}
.scr-card-link:hover .scr-card-cta { color: #f7cb46; }
.scr-card-link:hover .scr-card-cta i { transform: translateX(6px); }

/* Staggered animation */
.scr-card-link {
    opacity: 0;
    transform: translateY(20px);
    animation: crdFadeUp 0.45s ease forwards;
}
@keyframes crdFadeUp {
    to { opacity: 1; transform: translateY(0); }
}

/* ── Stats bar ───────────────────────────── */
.scr-stats {
    position: relative;
    z-index: 1;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(110px, 1fr));
    border-top: 1px solid rgba(255,255,255,0.08);
    background: rgba(8,8,12,0.65);
    backdrop-filter: blur(24px);
    -webkit-backdrop-filter: blur(24px);
    margin-top: auto;
}
.scr-stat {
    padding: 22px 18px;
    text-align: center;
    border-right: 1px solid rgba(255,255,255,0.06);
}
.scr-stat:last-child { border-right: none; }
.scr-stat-num {
    display: block;
    font-size: 1.7rem;
    font-weight: 800;
    color: #f7cb46;
    line-height: 1;
    text-shadow: 0 0 20px rgba(247,203,70,0.4);
}
.scr-stat-label {
    display: block;
    font-size: 0.68rem;
    font-weight: 600;
    color: rgba(255,255,255,0.45);
    text-transform: uppercase;
    letter-spacing: 1.5px;
    margin-top: 5px;
}

/* ── Responsive ─────────────────────────── */
@media (max-width: 768px) {
    .scr-hero-section { padding-top: 110px; }
    .scr-cards-grid { grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 14px; }
    .scr-card { padding: 22px 16px 18px; }
}
@media (max-width: 480px) {
    .scr-cards-grid { grid-template-columns: 1fr 1fr; }
    .scr-main-title { font-size: 2.2rem; }
}
</style>

<div class="scr-page">
    <div class="scr-bg"></div>
    <div class="scr-overlay"></div>

    <!-- Hero Title -->
    <div class="scr-hero-section">
        <div class="scr-eyebrow">
            <i class="fas fa-globe-americas"></i>
            Global Properties
        </div>
        <h1 class="scr-main-title">
            Select Your <span class="accent">Region</span>
        </h1>
        <p class="scr-desc">
            Explore premium real estate across continents. Choose your country to discover curated projects and luxury developments.
        </p>
    </div>

    <!-- Continent Groups with Cards -->
    <?php if (empty($regions)): ?>
        <div class="scr-section text-center">
            <p style="color:rgba(255,255,255,0.55)">No regions available at the moment.</p>
        </div>
    <?php else: ?>
        <?php
        $cardIndex = 0;
        foreach ($regions as $continent => $countries):
        ?>
        <div class="scr-section">
            <div class="scr-continent-header">
                <span class="scr-continent-header-label"><?= e($continent) ?></span>
            </div>
            <div class="scr-cards-grid">
                <?php foreach ($countries as $c):
                    $cardIndex++;
                    $delay = round(0.05 * $cardIndex, 2);
                    $cityCount = $c['city_count'] ?? 0;
                ?>
                    <a href="<?= PUBLIC_URL ?>location/<?= e($c['slug']) ?>"
                       class="scr-card-link"
                       style="animation-delay: <?= $delay ?>s;">
                        <div class="scr-card">
                            <!-- Hover border -->
                            <div class="scr-card-border"></div>

                            <!-- Top row: icon + badge -->
                            <div style="display:flex; align-items:flex-start; justify-content:space-between; gap:8px;">
                                <div class="scr-card-icon">
                                    <?php if (!empty($c['flag_icon'])): ?>
                                        <i class="<?= e($c['flag_icon']) ?>"></i>
                                    <?php else: ?>
                                        <i class="fas fa-map-marked-alt"></i>
                                    <?php endif; ?>
                                </div>
                                <?php if ($cityCount > 0): ?>
                                    <span class="scr-card-badge"><?= $cityCount ?> <?= $cityCount == 1 ? 'CITY' : 'CITIES' ?></span>
                                <?php endif; ?>
                            </div>

                            <!-- Name + CTA -->
                            <div class="mt-auto">
                                <h3 class="scr-card-name"><?= e($c['name']) ?></h3>
                                <div class="scr-card-cta">
                                    Explore Region
                                    <i class="fas fa-arrow-right"></i>
                                </div>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <!-- Real Stats Bar -->
    <?php if (!empty($stats)): ?>
    <div class="scr-stats">
        <?php foreach ($stats as $s): ?>
            <div class="scr-stat">
                <span class="scr-stat-num"><?= htmlspecialchars((string)$s['num']) ?></span>
                <span class="scr-stat-label"><?= htmlspecialchars($s['label']) ?></span>
            </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>
