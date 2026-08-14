<?php
/**
 * Select Region Page — Luxury Continent-Based Architecture
 * Allows visitors to seamlessly choose their continent & country
 */
$hasBanner      = !empty($heroBannerUrl);
$stats          = $stats ?? [];
$activeCountry  = $activeLocation['country'] ?? [];
$activeSlug     = strtolower($activeCountry['slug'] ?? '');
?>
<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&family=Outfit:wght@400;600;800;900&display=swap');

.scr-page {
    position: relative;
    min-height: 100vh;
    font-family: 'Plus Jakarta Sans', sans-serif;
    overflow-x: hidden;
    display: flex;
    flex-direction: column;
    background: #080a10;
    color: #ffffff;
}

.scr-bg {
    position: absolute;
    inset: 0;
    z-index: 0;
    <?php if ($hasBanner): ?>
    background-image: url('<?= e($heroBannerUrl) ?>');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    opacity: 0.15;
    <?php else: ?>
    background: radial-gradient(circle at 80% 20%, rgba(247, 203, 70, 0.08) 0%, transparent 50%),
                radial-gradient(circle at 20% 80%, rgba(30, 58, 138, 0.2) 0%, transparent 60%),
                #080a10;
    <?php endif; ?>
}

.scr-inner {
    position: relative;
    z-index: 1;
    flex: 1;
    display: flex;
    flex-direction: column;
    padding: 130px 6% 80px;
    max-width: 1400px;
    margin: 0 auto;
    width: 100%;
}

.scr-header-wrap {
    margin-bottom: 45px;
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    align-items: flex-end;
    gap: 30px;
}

.scr-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(247,203,70,0.12);
    border: 1px solid rgba(247,203,70,0.35);
    border-radius: 50px;
    padding: 7px 18px;
    font-size: 0.75rem;
    font-weight: 700;
    letter-spacing: 2px;
    text-transform: uppercase;
    color: #f7cb46;
    margin-bottom: 16px;
}

.scr-headline {
    font-family: 'Outfit', sans-serif;
    font-size: clamp(2.4rem, 4.5vw, 3.8rem);
    font-weight: 800;
    line-height: 1.1;
    color: #ffffff;
    letter-spacing: -1px;
    margin: 0;
}
.scr-headline .accent {
    color: #f7cb46;
}

.scr-search-box {
    position: relative;
    width: 100%;
    max-width: 440px;
}
.scr-search-input {
    width: 100%;
    height: 56px;
    background: rgba(255, 255, 255, 0.07);
    border: 1.5px solid rgba(255, 255, 255, 0.15);
    border-radius: 50px;
    padding: 0 24px 0 54px;
    color: #ffffff;
    font-size: 1rem;
    outline: none;
    transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    backdrop-filter: blur(10px);
}
.scr-search-input:focus {
    background: rgba(255, 255, 255, 0.12);
    border-color: #f7cb46;
    box-shadow: 0 0 25px rgba(247, 203, 70, 0.25);
}
.scr-search-icon {
    position: absolute;
    left: 20px;
    top: 50%;
    transform: translateY(-50%);
    color: #f7cb46;
    font-size: 1.15rem;
    pointer-events: none;
}

/* ── Continent Sections ── */
.scr-continent-sec {
    margin-bottom: 45px;
}
.scr-continent-title {
    font-family: 'Outfit', sans-serif;
    font-size: 1.35rem;
    font-weight: 700;
    color: #ffffff;
    letter-spacing: 0.5px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 12px;
}
.scr-continent-title::after {
    content: '';
    flex: 1;
    height: 1px;
    background: linear-gradient(90deg, rgba(255,255,255,0.15), transparent);
}

.scr-country-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 20px;
}

.scr-country-card {
    position: relative;
    background: rgba(255, 255, 255, 0.035);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 20px;
    padding: 24px;
    text-decoration: none;
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: space-between;
    transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
    backdrop-filter: blur(12px);
    overflow: hidden;
}
.scr-country-card::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(247, 203, 70, 0.15) 0%, transparent 100%);
    opacity: 0;
    transition: opacity 0.35s ease;
}
.scr-country-card:hover {
    transform: translateY(-5px);
    border-color: rgba(247, 203, 70, 0.6);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.5), 0 0 25px rgba(247, 203, 70, 0.18);
    color: #ffffff;
}
.scr-country-card:hover::before {
    opacity: 1;
}

.scr-card-left {
    display: flex;
    align-items: center;
    gap: 18px;
    position: relative;
    z-index: 1;
}
.scr-flag-badge {
    width: 52px;
    height: 52px;
    border-radius: 14px;
    background: rgba(255, 255, 255, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.15);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.8rem;
    transition: transform 0.3s ease;
}
.scr-country-card:hover .scr-flag-badge {
    transform: scale(1.1) rotate(4deg);
    background: rgba(247, 203, 70, 0.2);
    border-color: #f7cb46;
}

.scr-country-info h3 {
    margin: 0 0 4px;
    font-size: 1.2rem;
    font-weight: 700;
    letter-spacing: -0.2px;
    color: #ffffff;
}
.scr-country-meta {
    font-size: 0.8rem;
    color: rgba(255, 255, 255, 0.55);
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
}
.scr-country-meta span {
    color: #f7cb46;
}

.scr-card-action {
    position: relative;
    z-index: 1;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.06);
    display: flex;
    align-items: center;
    justify-content: center;
    color: rgba(255, 255, 255, 0.6);
    font-size: 0.9rem;
    transition: all 0.3s ease;
}
.scr-country-card:hover .scr-card-action {
    background: #f7cb46;
    color: #080a10;
    transform: translateX(4px);
}

.scr-active-tag {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    background: #22c55e;
    color: #ffffff;
    font-size: 0.65rem;
    font-weight: 800;
    padding: 3px 8px;
    border-radius: 20px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-left: 8px;
}

/* ── Stats Bar ── */
.scr-stats {
    position: relative;
    z-index: 1;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    background: rgba(10, 12, 18, 0.85);
    backdrop-filter: blur(20px);
}
.scr-stat {
    padding: 24px 20px;
    text-align: center;
    border-right: 1px solid rgba(255, 255, 255, 0.08);
}
.scr-stat:last-child { border-right: none; }
.scr-stat-num {
    display: block;
    font-family: 'Outfit', sans-serif;
    font-size: 1.9rem;
    font-weight: 800;
    color: #f7cb46;
    line-height: 1;
}
.scr-stat-label {
    display: block;
    font-size: 0.72rem;
    font-weight: 700;
    color: rgba(255, 255, 255, 0.5);
    text-transform: uppercase;
    letter-spacing: 1.5px;
    margin-top: 6px;
}

@media (max-width: 768px) {
    .scr-inner {
        padding: 100px 5% 50px;
    }
    .scr-header-wrap {
        flex-direction: column;
        align-items: flex-start;
        gap: 20px;
    }
    .scr-search-box {
        max-width: 100%;
    }
    .scr-country-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="scr-page">
    <div class="scr-bg"></div>

    <div class="scr-inner">
        
        <!-- Header & Search -->
        <div class="scr-header-wrap">
            <div>
                <div class="scr-eyebrow">
                    <i class="fas fa-globe"></i>
                    Global Real Estate Hub
                </div>
                <h1 class="scr-headline">
                    Search by <span class="accent">Region</span>
                </h1>
                <p class="text-white-50 mt-2 mb-0" style="font-size: 1.05rem;">
                    Select your country or region to discover local properties and developers.
                </p>
            </div>

            <div class="scr-search-box">
                <i class="fas fa-search scr-search-icon"></i>
                <input type="text" class="scr-search-input" id="regionSearchInput" placeholder="Search country or continent...">
            </div>
        </div>

        <!-- Continents & Countries -->
        <div id="regionsContainer">
            <?php if (!empty($continents)): ?>
                <?php foreach ($continents as $continentName => $countryList): ?>
                    <div class="scr-continent-sec" data-continent="<?= strtolower(e($continentName)) ?>">
                        <div class="scr-continent-title">
                            <span><?= e($continentName) ?></span>
                        </div>

                        <div class="scr-country-grid">
                            <?php foreach ($countryList as $c): ?>
                                <?php $isCurrent = ($activeSlug === strtolower($c['slug'])); ?>
                                <a href="<?= PUBLIC_URL ?>set-country/<?= e($c['slug']) ?>" 
                                   class="scr-country-card" 
                                   data-name="<?= strtolower(e($c['name'])) ?>"
                                   data-continent="<?= strtolower(e($continentName)) ?>"
                                   title="Browse <?= e($c['name']) ?> Properties">
                                    <div class="scr-card-left">
                                        <div class="scr-flag-badge">
                                            <?= e($c['flag_icon'] ?: '🌐') ?>
                                        </div>
                                        <div class="scr-country-info">
                                            <h3>
                                                <?= e($c['name']) ?>
                                                <?php if ($isCurrent): ?>
                                                    <span class="scr-active-tag">Active</span>
                                                <?php endif; ?>
                                            </h3>
                                            <div class="scr-country-meta">
                                                <span><?= (int)($c['project_count'] ?? 0) ?></span> Properties Listed
                                            </div>
                                        </div>
                                    </div>
                                    <div class="scr-card-action">
                                        <i class="fas fa-arrow-right"></i>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-white-50">No regions found.</p>
            <?php endif; ?>
        </div>

    </div>

    <!-- Bottom Real Stats -->
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
    const sections    = document.querySelectorAll('.scr-continent-sec');
    const cards       = document.querySelectorAll('.scr-country-card');

    searchInput.addEventListener('input', function(e) {
        const query = e.target.value.toLowerCase().trim();

        sections.forEach(sec => {
            let hasVisibleCard = false;
            const secCards = sec.querySelectorAll('.scr-country-card');

            secCards.forEach(card => {
                const name = card.getAttribute('data-name');
                const cont = card.getAttribute('data-continent');

                if (name.includes(query) || cont.includes(query)) {
                    card.style.display = 'flex';
                    hasVisibleCard = true;
                } else {
                    card.style.display = 'none';
                }
            });

            if (hasVisibleCard) {
                sec.style.display = '';
            } else {
                sec.style.display = 'none';
            }
        });
    });
});
</script>
