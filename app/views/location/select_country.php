<?php
/**
 * Select Country / Region Page — Premium Design v3
 * Psychology: Authority + Clarity + Action
 * Text: High contrast white on dark bg, clearly legible
 * Stats: Real DB data passed from LocationController
 */
$hasBanner = !empty($heroBannerUrl);
$stats     = $stats ?? [];
?>
<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');

/* ── Page shell — prevents any bleed-through ─ */
.scr-page {
    position: relative;
    min-height: 100vh;
    font-family: 'Inter', sans-serif;
    overflow: hidden;
    isolation: isolate;
    display: flex;
    flex-direction: column;
    background: #0d1117;
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
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #0f172a 100%);
    <?php endif; ?>
}

/* ── Dark gradient overlay — left side darker for text, right lighter to show map ── */
.scr-overlay {
    position: absolute;
    inset: 0;
    z-index: -1;
    <?php if ($hasBanner): ?>
    background: linear-gradient(
        100deg,
        rgba(8,8,12,0.88) 0%,
        rgba(8,8,12,0.60) 40%,
        rgba(8,8,12,0.25) 75%,
        rgba(8,8,12,0.10) 100%
    );
    <?php endif; ?>
}

/* ── Inner layout ────────────────────────── */
.scr-inner {
    position: relative;
    z-index: 1;
    flex: 1;
    display: flex;
    align-items: flex-start;
    padding: 130px 6% 80px;
    gap: 80px;
}

/* ── LEFT: Headline column ───────────────── */
.scr-left {
    flex: 0 0 300px;
    position: sticky;
    top: 130px;
}

.scr-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(247,203,70,0.15);
    border: 1px solid rgba(247,203,70,0.40);
    border-radius: 50px;
    padding: 7px 16px;
    font-size: 0.70rem;
    font-weight: 700;
    letter-spacing: 2.5px;
    text-transform: uppercase;
    color: #f7cb46;
    margin-bottom: 28px;
}

.scr-headline {
    font-size: clamp(2.8rem, 4.5vw, 4.2rem);
    font-weight: 300;
    line-height: 1.0;
    color: #ffffff;
    letter-spacing: -2px;
    text-shadow: 0 2px 20px rgba(0,0,0,0.6);
    margin: 0;
}
.scr-headline strong {
    font-weight: 900;
    display: block;
    color: #ffffff;
    /* Bright & visible — not a faint gradient */
    text-shadow: 0 0 40px rgba(247,203,70,0.3), 0 2px 20px rgba(0,0,0,0.8);
}
.scr-headline .accent {
    color: #f7cb46;
}

.scr-subtext {
    margin-top: 22px;
    font-size: 0.9rem;
    font-weight: 400;
    color: rgba(255,255,255,0.75);
    line-height: 1.65;
    text-shadow: 0 1px 6px rgba(0,0,0,0.5);
    max-width: 260px;
}

.scr-divider {
    width: 48px;
    height: 3px;
    background: linear-gradient(90deg, #f7cb46, transparent);
    border-radius: 2px;
    margin-top: 28px;
}

/* ── RIGHT: Regions ─────────────────────── */
.scr-right {
    flex: 1;
    min-width: 0;
    padding-top: 6px;
}

.scr-continent {
    margin-bottom: 38px;
    opacity: 0;
    transform: translateY(20px);
    animation: scrFadeUp 0.5s ease forwards;
}
.scr-continent:nth-child(1) { animation-delay: 0.10s; }
.scr-continent:nth-child(2) { animation-delay: 0.20s; }
.scr-continent:nth-child(3) { animation-delay: 0.30s; }
.scr-continent:nth-child(4) { animation-delay: 0.40s; }
.scr-continent:nth-child(5) { animation-delay: 0.50s; }
.scr-continent:nth-child(6) { animation-delay: 0.60s; }

@keyframes scrFadeUp {
    to { opacity: 1; transform: translateY(0); }
}

.scr-continent-label {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 0.68rem;
    font-weight: 800;
    letter-spacing: 3.5px;
    text-transform: uppercase;
    color: rgba(255,255,255,0.50);
    margin-bottom: 14px;
    text-shadow: 0 1px 4px rgba(0,0,0,0.5);
}
.scr-continent-label::after {
    content: '';
    flex: 1;
    height: 1px;
    background: rgba(255,255,255,0.12);
}

.scr-pills {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

/* Country pill — glassmorphism, clearly readable */
.scr-pill {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 11px 22px;
    background: rgba(255,255,255,0.10);
    border: 1px solid rgba(255,255,255,0.20);
    border-radius: 50px;
    text-decoration: none;
    /* BRIGHT white text — very readable */
    color: #ffffff;
    font-size: 0.92rem;
    font-weight: 600;
    letter-spacing: 0.2px;
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    text-shadow: 0 1px 6px rgba(0,0,0,0.6);
    box-shadow: 0 2px 12px rgba(0,0,0,0.20);
    transition:
        background 0.22s ease,
        border-color 0.22s ease,
        transform 0.22s ease,
        box-shadow 0.22s ease;
    position: relative;
    overflow: hidden;
}
.scr-pill::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(247,203,70,0.20) 0%, transparent 65%);
    opacity: 0;
    transition: opacity 0.22s ease;
}
.scr-pill:hover {
    background: rgba(247,203,70,0.18);
    border-color: rgba(247,203,70,0.70);
    color: #ffffff;
    transform: translateY(-3px);
    box-shadow: 0 10px 28px rgba(247,203,70,0.25), 0 4px 12px rgba(0,0,0,0.30);
    text-decoration: none;
}
.scr-pill:hover::before { opacity: 1; }

.scr-pill-dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: #f7cb46;
    flex-shrink: 0;
    box-shadow: 0 0 6px rgba(247,203,70,0.8);
    transition: box-shadow 0.22s ease;
}
.scr-pill:hover .scr-pill-dot {
    box-shadow: 0 0 12px rgba(247,203,70,1);
}

/* ── Stats bar — real data ───────────────── */
.scr-stats {
    position: relative;
    z-index: 1;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    border-top: 1px solid rgba(255,255,255,0.10);
    background: rgba(8,8,12,0.65);
    backdrop-filter: blur(24px);
    -webkit-backdrop-filter: blur(24px);
}

.scr-stat {
    padding: 24px 20px;
    text-align: center;
    border-right: 1px solid rgba(255,255,255,0.08);
}
.scr-stat:last-child { border-right: none; }

.scr-stat-num {
    display: block;
    font-size: 1.8rem;
    font-weight: 800;
    color: #f7cb46;
    line-height: 1;
    text-shadow: 0 0 20px rgba(247,203,70,0.4);
}
.scr-stat-label {
    display: block;
    font-size: 0.70rem;
    font-weight: 600;
    color: rgba(255,255,255,0.50);
    text-transform: uppercase;
    letter-spacing: 1.5px;
    margin-top: 5px;
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
    box-shadow: 0 4px 15px rgba(0,0,0,0.12);
}

/* ── Responsive ─────────────────────────── */
@media (max-width: 860px) {
    .scr-inner {
        flex-direction: column;
        gap: 30px;
        padding: 110px 6% 60px;
    }
    .scr-left { flex: none; width: 100%; position: static; }
    .scr-headline { font-size: 2.8rem; }
    .scr-subtext { max-width: 100%; }
}
@media (max-width: 480px) {
    .scr-headline { font-size: 2.2rem; }
    .scr-pill { font-size: 0.86rem; padding: 10px 18px; }
    .scr-stat-num { font-size: 1.5rem; }
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

        <!-- LEFT — Headline -->
        <div class="scr-left">
            <div class="scr-eyebrow">
                <i class="fas fa-globe-americas"></i>
                Global Properties
            </div>

            <h1 class="scr-headline">
                Select<br>
                <strong>Your<br><span class="accent">Region</span></strong>
            </h1>

            <p class="scr-subtext">
                Explore premium real estate across continents. Find your perfect home, wherever in the world.
            </p>
            <div class="scr-divider"></div>
        </div>

        <!-- RIGHT — Continent / country list -->
        <div class="scr-right">
            <?php if (empty($regions)): ?>
                <p style="color:rgba(255,255,255,0.6)">No regions available at the moment.</p>
            <?php else: ?>
                <?php foreach ($regions as $continent => $countries): ?>
                    <div class="scr-continent">
                        <div class="scr-continent-label">
                            <?= e($continent) ?>
                        </div>
                        <div class="scr-pills">
                            <?php foreach ($countries as $c): ?>
                                <a href="<?= PUBLIC_URL ?>location/<?= e($c['slug']) ?>" class="scr-pill">
                                    <span class="scr-pill-dot"></span>
                                    <?= e($c['name']) ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- BOTTOM — Real stats bar -->
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
