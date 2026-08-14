<?php
/**
 * Select Region Page — Luxury Continent-Based Architecture
 * Original Premium Left/Right Split Layout with Clear Background Map & Animated Continent Pills
 */
$hasBanner = !empty($heroBannerUrl);
$stats     = $stats ?? [];
$activeSlug= strtolower($activeLocation['country']['slug'] ?? '');
?>
<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Outfit:wght@400;600;800;900&display=swap');

/* ── Page shell ─ */
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

/* ── Background image — High clarity ─────────────────────── */
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

/* ── Dark gradient overlay — left darker for vertical text, right transparent for map clarity ── */
.scr-overlay {
    position: absolute;
    inset: 0;
    z-index: -1;
    <?php if ($hasBanner): ?>
    background: linear-gradient(
        100deg,
        rgba(8,8,12,0.92) 0%,
        rgba(8,8,12,0.70) 35%,
        rgba(8,8,12,0.30) 70%,
        rgba(8,8,12,0.12) 100%
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

/* ── LEFT: Vertical Headline column ───────────────── */
.scr-left {
    flex: 0 0 320px;
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
    max-width: 280px;
}

.scr-divider {
    width: 48px;
    height: 3px;
    background: linear-gradient(90deg, #f7cb46, transparent);
    border-radius: 2px;
    margin-top: 28px;
}

/* ── RIGHT: Continents & Countries ─────────────────────── */
.scr-right {
    flex: 1;
    min-width: 0;
    padding-top: 6px;
}

/* Search bar */
.scr-search-wrap {
    position: relative;
    margin-bottom: 30px;
    max-width: 500px;
}
.scr-search-input {
    width: 100%;
    height: 50px;
    background: rgba(255, 255, 255, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 50px;
    padding: 0 20px 0 48px;
    color: #ffffff;
    font-size: 0.95rem;
    outline: none;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
}
.scr-search-input:focus {
    background: rgba(255, 255, 255, 0.15);
    border-color: #f7cb46;
    box-shadow: 0 0 20px rgba(247, 203, 70, 0.25);
}
.scr-search-icon {
    position: absolute;
    left: 18px;
    top: 50%;
    transform: translateY(-50%);
    color: #f7cb46;
    font-size: 1rem;
    pointer-events: none;
}

.scr-continent {
    margin-bottom: 34px;
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
    font-size: 0.70rem;
    font-weight: 800;
    letter-spacing: 3.5px;
    text-transform: uppercase;
    color: rgba(255,255,255,0.60);
    margin-bottom: 14px;
    text-shadow: 0 1px 4px rgba(0,0,0,0.5);
}
.scr-continent-label::after {
    content: '';
    flex: 1;
    height: 1px;
    background: rgba(255,255,255,0.15);
}

.scr-pills {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
}

/* Country pill — glassmorphic animated button */
.scr-pill {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 12px 24px;
    background: rgba(255,255,255,0.08);
    border: 1px solid rgba(255,255,255,0.18);
    border-radius: 50px;
    text-decoration: none;
    color: #ffffff;
    font-size: 0.95rem;
    font-weight: 600;
    letter-spacing: 0.2px;
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    text-shadow: 0 1px 6px rgba(0,0,0,0.6);
    box-shadow: 0 4px 15px rgba(0,0,0,0.25);
    transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    position: relative;
    overflow: hidden;
}
.scr-pill::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(247,203,70,0.25) 0%, transparent 65%);
    opacity: 0;
    transition: opacity 0.25s ease;
}
.scr-pill:hover {
    background: rgba(247,203,70,0.2);
    border-color: rgba(247,203,70,0.85);
    color: #ffffff;
    transform: translateY(-3px) scale(1.02);
    box-shadow: 0 10px 28px rgba(247,203,70,0.3), 0 4px 12px rgba(0,0,0,0.35);
    text-decoration: none;
}
.scr-pill:hover::before { opacity: 1; }

.scr-pill-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #f7cb46;
    flex-shrink: 0;
    box-shadow: 0 0 8px rgba(247,203,70,0.85);
    transition: transform 0.25s ease;
}
.scr-pill:hover .scr-pill-dot {
    transform: scale(1.3);
    box-shadow: 0 0 14px rgba(247,203,70,1);
}

.scr-pill-active-tag {
    font-size: 0.65rem;
    font-weight: 800;
    background: #22c55e;
    color: #fff;
    padding: 2px 7px;
    border-radius: 12px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-left: 4px;
}

/* ── Stats bar ───────────────── */
.scr-stats {
    position: relative;
    z-index: 1;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    border-top: 1px solid rgba(255,255,255,0.10);
    background: rgba(8,8,12,0.75);
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
    .scr-pill { font-size: 0.88rem; padding: 10px 18px; }
    .scr-stat-num { font-size: 1.5rem; }
}
</style>

<div class="scr-page">
    <div class="scr-bg"></div>
    <div class="scr-overlay"></div>

    <div class="scr-inner">

        <!-- LEFT — Headline vertically aligned -->
        <div class="scr-left">
            <div class="scr-eyebrow">
                <i class="fas fa-globe"></i>
                Global Properties
            </div>

            <h1 class="scr-headline">
                Select<br>
                <strong>Your<br><span class="accent">Region</span></strong>
            </h1>

            <p class="scr-subtext">
                Explore premium real estate across continents. Choose your region to tailor your experience.
            </p>
            <div class="scr-divider"></div>
        </div>

        <!-- RIGHT — Search & Continents list -->
        <div class="scr-right">
            
            <div class="scr-search-wrap">
                <i class="fas fa-search scr-search-icon"></i>
                <input type="text" class="scr-search-input" id="regionSearchInput" placeholder="Search country or continent...">
            </div>

            <div id="continentsList">
                <?php if (empty($continents)): ?>
                    <p style="color:rgba(255,255,255,0.6)">No regions available at the moment.</p>
                <?php else: ?>
                    <?php foreach ($continents as $continentName => $countryList): ?>
                        <div class="scr-continent" data-continent="<?= strtolower(e($continentName)) ?>">
                            <div class="scr-continent-label">
                                <?= e($continentName) ?>
                            </div>
                            <div class="scr-pills">
                                <?php foreach ($countryList as $c): ?>
                                    <?php $isCurrent = ($activeSlug === strtolower($c['slug'])); ?>
                                    <a href="<?= PUBLIC_URL ?>set-country/<?= e($c['slug']) ?>" 
                                       class="scr-pill" 
                                       data-name="<?= strtolower(e($c['name'])) ?>"
                                       data-continent="<?= strtolower(e($continentName)) ?>"
                                       title="Explore <?= e($c['name']) ?> Properties">
                                        <span class="scr-pill-dot"></span>
                                        <span class="scr-pill-flag"><?= e($c['flag_icon'] ?: '🌐') ?></span>
                                        <span class="scr-pill-name"><?= e($c['name']) ?></span>
                                        <?php if ($isCurrent): ?>
                                            <span class="scr-pill-active-tag">Active</span>
                                        <?php endif; ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('regionSearchInput');
    const continents  = document.querySelectorAll('.scr-continent');

    searchInput.addEventListener('input', function(e) {
        const query = e.target.value.toLowerCase().trim();

        continents.forEach(cont => {
            let hasMatch = false;
            const pills = cont.querySelectorAll('.scr-pill');

            pills.forEach(pill => {
                const name = pill.getAttribute('data-name');
                const continentName = pill.getAttribute('data-continent');

                if (name.includes(query) || continentName.includes(query)) {
                    pill.style.display = 'inline-flex';
                    hasMatch = true;
                } else {
                    pill.style.display = 'none';
                }
            });

            if (hasMatch) {
                cont.style.display = '';
            } else {
                cont.style.display = 'none';
            }
        });
    });
});
</script>
