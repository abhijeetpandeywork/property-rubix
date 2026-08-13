<?php
/**
 * Select Location Page — Premium Design (City Level)
 * Psychology: Authority + Clarity + Action
 */
$hasBanner = !empty($heroBannerUrl);
$stats     = $stats ?? [];
?>
<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');

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

.scr-overlay {
    position: absolute;
    inset: 0;
    z-index: -1;
    <?php if ($hasBanner): ?>
    background: linear-gradient(
        100deg,
        rgba(8,8,12,0.92) 0%,
        rgba(8,8,12,0.75) 40%,
        rgba(8,8,12,0.40) 75%,
        rgba(8,8,12,0.15) 100%
    );
    <?php endif; ?>
}

.scr-inner {
    position: relative;
    z-index: 1;
    flex: 1;
    display: flex;
    align-items: flex-start;
    padding: 130px 6% 80px;
    gap: 80px;
}

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
    font-size: 0.95rem;
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

.scr-right {
    flex: 1;
    min-width: 0;
    padding-top: 6px;
}

/* ── Live Search Bar ── */
.scr-search-wrap {
    position: relative;
    margin-bottom: 40px;
    animation: scrFadeUp 0.3s ease forwards;
}
.scr-search-input {
    width: 100%;
    background: rgba(255,255,255,0.1);
    backdrop-filter: blur(12px);
    border: 1px solid rgba(255,255,255,0.25);
    border-radius: 50px;
    padding: 18px 24px 18px 54px;
    color: #fff;
    font-size: 1.1rem;
    font-weight: 500;
    outline: none;
    transition: all 0.3s ease;
    box-shadow: 0 4px 20px rgba(0,0,0,0.2);
}
.scr-search-input::placeholder { color: rgba(255,255,255,0.5); }
.scr-search-input:focus {
    background: rgba(255,255,255,0.15);
    border-color: #f7cb46;
    box-shadow: 0 0 0 4px rgba(247,203,70,0.2), 0 4px 20px rgba(0,0,0,0.3);
}
.scr-search-icon {
    position: absolute;
    left: 22px;
    top: 50%;
    transform: translateY(-50%);
    color: #f7cb46;
    font-size: 1.2rem;
}

/* ── Section Labels ── */
.scr-section-label {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 0.75rem;
    font-weight: 800;
    letter-spacing: 3.5px;
    text-transform: uppercase;
    color: rgba(255,255,255,0.60);
    margin-bottom: 20px;
    text-shadow: 0 1px 4px rgba(0,0,0,0.5);
}
.scr-section-label::after {
    content: '';
    flex: 1;
    height: 1px;
    background: rgba(255,255,255,0.12);
}

/* ── Popular Cities Grid ── */
.scr-cities-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    gap: 16px;
    margin-bottom: 50px;
}
.scr-city-card {
    position: relative;
    background: rgba(255,255,255,0.08);
    border: 1px solid rgba(255,255,255,0.15);
    border-radius: 16px;
    padding: 16px;
    text-decoration: none;
    color: #fff;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    backdrop-filter: blur(12px);
    transition: all 0.25s ease;
    overflow: hidden;
    animation: scrFadeUp 0.5s ease forwards;
    opacity: 0;
}
.scr-city-card:nth-child(1) { animation-delay: 0.1s; }
.scr-city-card:nth-child(2) { animation-delay: 0.15s; }
.scr-city-card:nth-child(3) { animation-delay: 0.2s; }
.scr-city-card:nth-child(4) { animation-delay: 0.25s; }
.scr-city-card:nth-child(5) { animation-delay: 0.3s; }
.scr-city-card:nth-child(6) { animation-delay: 0.35s; }

.scr-city-card:hover {
    background: rgba(247,203,70,0.15);
    border-color: rgba(247,203,70,0.6);
    transform: translateY(-4px);
    box-shadow: 0 10px 24px rgba(0,0,0,0.4), 0 0 20px rgba(247,203,70,0.2);
    color: #fff;
}
.scr-city-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: rgba(255,255,255,0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 12px;
    font-size: 1.2rem;
    color: #f7cb46;
}
.scr-city-name {
    font-size: 1.05rem;
    font-weight: 700;
    margin-bottom: 4px;
    letter-spacing: -0.2px;
}
.scr-city-country {
    font-size: 0.75rem;
    color: rgba(255,255,255,0.5);
    text-transform: uppercase;
    letter-spacing: 1px;
    font-weight: 600;
}

/* ── Country Pills ── */
.scr-pills {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
}
.scr-pill {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    background: rgba(255,255,255,0.06);
    border: 1px solid rgba(255,255,255,0.12);
    border-radius: 50px;
    text-decoration: none;
    color: rgba(255,255,255,0.85);
    font-size: 0.9rem;
    font-weight: 600;
    letter-spacing: 0.2px;
    transition: all 0.22s ease;
}
.scr-pill:hover {
    background: rgba(255,255,255,0.15);
    border-color: #fff;
    color: #fff;
    transform: translateY(-2px);
}

@keyframes scrFadeUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

/* ── Stats bar ── */
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

@media (max-width: 860px) {
    .scr-inner {
        flex-direction: column;
        gap: 30px;
        padding: 110px 6% 60px;
    }
    .scr-left { flex: none; width: 100%; position: static; }
    .scr-headline { font-size: 2.8rem; }
    .scr-subtext { max-width: 100%; }
    .scr-cities-grid { grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); }
}
@media (max-width: 480px) {
    .scr-headline { font-size: 2.2rem; }
    .scr-stat-num { font-size: 1.5rem; }
    .scr-cities-grid { grid-template-columns: repeat(2, 1fr); }
    .scr-city-card { padding: 12px; }
}
</style>

<div class="scr-page">
    <div class="scr-bg"></div>
    <div class="scr-overlay"></div>

    <div class="scr-inner">
        <!-- LEFT — Headline -->
        <div class="scr-left">
            <div class="scr-eyebrow">
                <i class="fas fa-map-marker-alt"></i>
                Location Selector
            </div>

            <h1 class="scr-headline">
                Select<br>
                <strong>Your<br><span class="accent">City</span></strong>
            </h1>

            <p class="scr-subtext">
                Discover hyper-localized premium real estate. Choose your city to tailor your entire experience.
            </p>
            <div class="scr-divider"></div>
        </div>

        <!-- RIGHT — Search & Grid -->
        <div class="scr-right">
            
            <div class="scr-search-wrap">
                <i class="fas fa-search scr-search-icon"></i>
                <input type="text" class="scr-search-input" id="locSearchInput" placeholder="Search for your city or country...">
            </div>

            <div class="scr-section-label">Popular Cities</div>
            
            <div class="scr-cities-grid" id="locCitiesList">
                <?php if (!empty($popularCities)): ?>
                    <?php foreach ($popularCities as $city): ?>
                        <a href="<?= PUBLIC_URL ?>set-location/<?= e($city['slug']) ?>" class="scr-city-card" data-name="<?= strtolower(e($city['name'])) ?>">
                            <div class="scr-city-icon">
                                <?php if (!empty($city['flag_icon'])): ?>
                                    <span style="font-size:1.4rem;"><?= e($city['flag_icon']) ?></span>
                                <?php else: ?>
                                    <i class="fas fa-building"></i>
                                <?php endif; ?>
                            </div>
                            <div class="scr-city-name"><?= e($city['name']) ?></div>
                            <div class="scr-city-country"><?= e($city['country_name']) ?></div>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-muted w-100">No popular cities found.</p>
                <?php endif; ?>
            </div>

            <div class="scr-section-label">Browse by Country</div>
            <div class="scr-pills" id="locCountriesList">
                <?php if (!empty($countries)): ?>
                    <?php foreach ($countries as $c): ?>
                        <a href="<?= PUBLIC_URL ?>set-country/<?= e($c['slug']) ?>" class="scr-pill" data-name="<?= strtolower(e($c['name'])) ?>">
                            <?= e($c['flag_icon'] ?? '🌐') ?> <?= e($c['name']) ?>
                        </a>
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
    const searchInput = document.getElementById('locSearchInput');
    const cities = document.querySelectorAll('#locCitiesList .scr-city-card');
    const countries = document.querySelectorAll('#locCountriesList .scr-pill');

    searchInput.addEventListener('input', function(e) {
        const query = e.target.value.toLowerCase().trim();
        
        cities.forEach(city => {
            if (city.getAttribute('data-name').includes(query) || city.querySelector('.scr-city-country').innerText.toLowerCase().includes(query)) {
                city.style.display = '';
            } else {
                city.style.display = 'none';
            }
        });
        
        countries.forEach(country => {
            if (country.getAttribute('data-name').includes(query)) {
                country.style.display = '';
            } else {
                country.style.display = 'none';
            }
        });
    });
});
</script>
