<?php
/**
 * Select Country / Region Page — Premium Redesign
 * Psychology: Authority (globe visual) + Clarity (strong hierarchy) + Action (glowing CTA pills)
 * Background: Admin → Settings → "Select Country Page – Hero Background Image"
 */
$hasBanner = !empty($heroBannerUrl);
?>
<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');

/* ── Reset bleed from other page elements ───── */
.scr-page {
    position: relative;
    min-height: 100vh;
    font-family: 'Inter', sans-serif;
    overflow: hidden;
    isolation: isolate;
    display: flex;
    flex-direction: column;
    background: #0a0a0a;
}

/* ── Background image ─────────────────────── */
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
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
    <?php endif; ?>
}

/* ── Subtle dark gradient overlay — keeps map visible but text readable ── */
.scr-overlay {
    position: absolute;
    inset: 0;
    z-index: -1;
    <?php if ($hasBanner): ?>
    background: linear-gradient(
        105deg,
        rgba(10,10,10,0.72) 0%,
        rgba(10,10,10,0.40) 50%,
        rgba(10,10,10,0.18) 100%
    );
    <?php else: ?>
    background: transparent;
    <?php endif; ?>
}

/* ── Main layout ──────────────────────────── */
.scr-inner {
    position: relative;
    z-index: 1;
    flex: 1;
    display: flex;
    align-items: flex-start;
    padding: 130px 5% 80px;
    gap: 60px;
}

/* ── Left column — headline ─────────────── */
.scr-left {
    flex: 0 0 auto;
    width: 320px;
    position: sticky;
    top: 130px;
}

.scr-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(247,203,70,0.12);
    border: 1px solid rgba(247,203,70,0.35);
    border-radius: 50px;
    padding: 6px 16px;
    font-size: 0.72rem;
    font-weight: 700;
    letter-spacing: 2.5px;
    text-transform: uppercase;
    color: #f7cb46;
    margin-bottom: 24px;
}
.scr-eyebrow i { font-size: 0.85rem; }

.scr-headline {
    font-size: clamp(2.6rem, 4vw, 4rem);
    font-weight: 300;
    line-height: 1.05;
    color: #ffffff;
    letter-spacing: -2px;
    margin-bottom: 0;
}
.scr-headline strong {
    font-weight: 900;
    display: block;
    color: #ffffff;
    background: linear-gradient(90deg, #ffffff 0%, #f7cb46 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.scr-subtext {
    margin-top: 20px;
    font-size: 0.88rem;
    font-weight: 400;
    color: rgba(255,255,255,0.55);
    line-height: 1.6;
}

.scr-divider {
    width: 50px;
    height: 3px;
    background: linear-gradient(90deg, #f7cb46, transparent);
    border-radius: 2px;
    margin-top: 28px;
}

/* ── Right column — regions ─────────────── */
.scr-right {
    flex: 1;
    min-width: 0;
    padding-top: 8px;
}

.scr-continent {
    margin-bottom: 36px;
    opacity: 0;
    transform: translateY(24px);
    animation: fadeUp 0.5s ease forwards;
}
.scr-continent:nth-child(1) { animation-delay: 0.1s; }
.scr-continent:nth-child(2) { animation-delay: 0.2s; }
.scr-continent:nth-child(3) { animation-delay: 0.3s; }
.scr-continent:nth-child(4) { animation-delay: 0.4s; }
.scr-continent:nth-child(5) { animation-delay: 0.5s; }
.scr-continent:nth-child(6) { animation-delay: 0.6s; }

@keyframes fadeUp {
    to { opacity: 1; transform: translateY(0); }
}

.scr-continent-label {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 0.72rem;
    font-weight: 800;
    letter-spacing: 3px;
    text-transform: uppercase;
    color: rgba(255,255,255,0.40);
    margin-bottom: 14px;
}
.scr-continent-label::after {
    content: '';
    flex: 1;
    height: 1px;
    background: rgba(255,255,255,0.10);
}

.scr-country-pills {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

/* The core country pill */
.scr-pill {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 11px 22px;
    background: rgba(255,255,255,0.07);
    border: 1px solid rgba(255,255,255,0.14);
    border-radius: 50px;
    text-decoration: none;
    color: rgba(255,255,255,0.85);
    font-size: 0.9rem;
    font-weight: 500;
    letter-spacing: 0.2px;
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    transition:
        background 0.25s ease,
        border-color 0.25s ease,
        color 0.25s ease,
        transform 0.25s ease,
        box-shadow 0.25s ease;
    position: relative;
    overflow: hidden;
}

.scr-pill::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(247,203,70,0.18) 0%, transparent 60%);
    opacity: 0;
    transition: opacity 0.25s ease;
}

.scr-pill:hover {
    background: rgba(247,203,70,0.15);
    border-color: rgba(247,203,70,0.6);
    color: #ffffff;
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(247,203,70,0.20);
    text-decoration: none;
}
.scr-pill:hover::before { opacity: 1; }

.scr-pill-arrow {
    font-size: 0.7rem;
    opacity: 0;
    transform: translateX(-4px);
    transition: opacity 0.2s, transform 0.2s;
    color: #f7cb46;
}
.scr-pill:hover .scr-pill-arrow {
    opacity: 1;
    transform: translateX(0);
}

/* ── Bottom stats strip ─────────────────── */
.scr-stats {
    position: relative;
    z-index: 1;
    display: flex;
    gap: 0;
    border-top: 1px solid rgba(255,255,255,0.08);
    background: rgba(0,0,0,0.30);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    padding: 0 5%;
}
.scr-stat {
    flex: 1;
    padding: 22px 24px;
    border-right: 1px solid rgba(255,255,255,0.06);
    text-align: center;
}
.scr-stat:last-child { border-right: none; }
.scr-stat-num {
    font-size: 1.5rem;
    font-weight: 800;
    color: #f7cb46;
    display: block;
    line-height: 1;
}
.scr-stat-label {
    font-size: 0.72rem;
    font-weight: 500;
    color: rgba(255,255,255,0.45);
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-top: 4px;
    display: block;
}

/* ── Admin notice ───────────────────────── */
.scr-admin-notice {
    position: fixed;
    top: 90px; right: 20px;
    background: #fff3cd;
    border: 1px solid #ffc107;
    border-radius: 8px;
    padding: 10px 16px;
    font-size: 0.8rem;
    color: #856404;
    z-index: 9999;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

/* ── Responsive ─────────────────────────── */
@media (max-width: 900px) {
    .scr-inner {
        flex-direction: column;
        padding: 110px 6% 60px;
        gap: 32px;
    }
    .scr-left {
        width: 100%;
        position: static;
    }
    .scr-headline { font-size: 2.8rem; }
    .scr-stats { flex-wrap: wrap; }
    .scr-stat { flex: 1 1 50%; }
}
@media (max-width: 480px) {
    .scr-headline { font-size: 2.2rem; }
    .scr-pill { font-size: 0.85rem; padding: 10px 18px; }
}
</style>

<?php if (!$hasBanner && isset($_SESSION['admin_id'])): ?>
<div class="scr-admin-notice">
    <i class="fas fa-image me-1"></i> No banner set.
    <a href="<?= BASE_URL ?>admin/settings/" class="fw-bold ms-1">Upload in Admin → Settings</a>
</div>
<?php endif; ?>

<div class="scr-page">
    <div class="scr-bg"></div>
    <div class="scr-overlay"></div>

    <div class="scr-inner">
        <!-- Left: Headline -->
        <div class="scr-left">
            <div class="scr-eyebrow">
                <i class="fas fa-globe-americas"></i>
                Global Properties
            </div>
            <h1 class="scr-headline">
                Select<br>
                <strong>Your<br>Region</strong>
            </h1>
            <p class="scr-subtext">
                Explore premium real estate across continents. Find your ideal home, wherever in the world you want to be.
            </p>
            <div class="scr-divider"></div>
        </div>

        <!-- Right: Continent blocks -->
        <div class="scr-right">
            <?php if (empty($regions)): ?>
                <p style="color:rgba(255,255,255,0.5);">No regions available at the moment.</p>
            <?php else: ?>
                <?php foreach ($regions as $continent => $countries): ?>
                    <div class="scr-continent">
                        <div class="scr-continent-label">
                            <?= e($continent) ?>
                        </div>
                        <div class="scr-country-pills">
                            <?php foreach ($countries as $c): ?>
                                <a href="<?= PUBLIC_URL ?>location/<?= e($c['slug']) ?>" class="scr-pill">
                                    <?= e($c['name']) ?>
                                    <i class="fas fa-arrow-right scr-pill-arrow"></i>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Bottom stats bar -->
    <div class="scr-stats">
        <div class="scr-stat">
            <span class="scr-stat-num">4</span>
            <span class="scr-stat-label">Countries</span>
        </div>
        <div class="scr-stat">
            <span class="scr-stat-num">500+</span>
            <span class="scr-stat-label">Projects</span>
        </div>
        <div class="scr-stat">
            <span class="scr-stat-num">10K+</span>
            <span class="scr-stat-label">Happy Families</span>
        </div>
        <div class="scr-stat">
            <span class="scr-stat-num">RERA</span>
            <span class="scr-stat-label">Registered</span>
        </div>
    </div>
</div>
