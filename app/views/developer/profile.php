<?php /** Developer Profile View — Premium Redesign */ ?>
<?php
    $totalProjects  = count($projects);
    $ongoingCount   = count(array_filter($projects, fn($p) => in_array($p['status'], ['under_construction', 'new_launch'])));
    $completedCount = count(array_filter($projects, fn($p) => $p['status'] === 'ready_to_move'));
    $yearsActive    = (int)($builder['established_year'] ?? 0) ? (date('Y') - (int)$builder['established_year']) : null;
    $hasBanner      = !empty($builder['logo']);
    $initials       = strtoupper(substr(preg_replace('/[^a-zA-Z ]/', '', $builder['name']), 0, 2));
?>
<style>
/* ── Google Font ──────────────────────────────────── */
@import url('https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&display=swap');

/* ── Variables ────────────────────────────────────── */
:root {
    --dev-gold:     #c9a84c;
    --dev-gold-lt:  #f0d080;
    --dev-dark:     #0d0d1a;
    --dev-dark2:    #1a1a2e;
    --dev-charcoal: #2c2c3e;
    --dev-text-muted: #a0a0b8;
    --dev-border:   rgba(255,255,255,0.08);
    --dev-glass:    rgba(255,255,255,0.05);
    --dev-glass2:   rgba(255,255,255,0.1);
}

/* ── Font Override ────────────────────────────────── */
.dev-pg { font-family: 'Outfit', sans-serif; }

/* ── Hero Banner ──────────────────────────────────── */
.dev-hero {
    position: relative;
    width: 100%;
    min-height: 480px;
    background: linear-gradient(150deg, #0a0a18 0%, #12122a 50%, #0d0d1e 100%);
    overflow: hidden;
    display: flex;
    align-items: flex-end;
    padding-bottom: 0;
}

/* Grid lines overlay */
.dev-hero::after {
    content: '';
    position: absolute;
    inset: 0;
    background-image:
        linear-gradient(rgba(255,255,255,0.025) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,0.025) 1px, transparent 1px);
    background-size: 60px 60px;
    z-index: 0;
    pointer-events: none;
}

.dev-hero-inner {
    position: relative;
    z-index: 2;
    width: 100%;
    padding: 0 0 0;
}

/* ── Colourful Floating Blobs ─────────────────────── */
.dev-blob {
    position: absolute;
    border-radius: 50%;
    filter: blur(80px);
    pointer-events: none;
    animation: devBlobFloat 8s ease-in-out infinite;
}
.dev-blob-1 {
    width: 380px; height: 380px;
    background: radial-gradient(circle, rgba(139,92,246,0.45) 0%, transparent 70%);
    top: -80px; right: 5%;
    animation-delay: 0s;
    animation-duration: 9s;
}
.dev-blob-2 {
    width: 300px; height: 300px;
    background: radial-gradient(circle, rgba(6,182,212,0.35) 0%, transparent 70%);
    top: 40%; left: -60px;
    animation-delay: 2s;
    animation-duration: 11s;
}
.dev-blob-3 {
    width: 250px; height: 250px;
    background: radial-gradient(circle, rgba(236,72,153,0.3) 0%, transparent 70%);
    bottom: 10%; right: 20%;
    animation-delay: 4s;
    animation-duration: 8s;
}
.dev-blob-4 {
    width: 200px; height: 200px;
    background: radial-gradient(circle, rgba(234,179,8,0.3) 0%, transparent 70%);
    top: 20%; left: 25%;
    animation-delay: 1s;
    animation-duration: 12s;
}
.dev-blob-5 {
    width: 180px; height: 180px;
    background: radial-gradient(circle, rgba(34,197,94,0.25) 0%, transparent 70%);
    bottom: 20%; left: 10%;
    animation-delay: 3s;
    animation-duration: 10s;
}
.dev-blob-6 {
    width: 160px; height: 160px;
    background: radial-gradient(circle, rgba(249,115,22,0.3) 0%, transparent 70%);
    top: 60%; right: 8%;
    animation-delay: 5s;
    animation-duration: 7s;
}

@keyframes devBlobFloat {
    0%, 100% { transform: translate(0, 0) scale(1); }
    33%       { transform: translate(20px, -25px) scale(1.05); }
    66%       { transform: translate(-15px, 15px) scale(0.97); }
}

/* ── Breadcrumb inside Hero ───────────────────────── */
.dev-hero-breadcrumb {
    padding: 22px 0 0;
    margin-bottom: 0;
}
.dev-hero-breadcrumb .breadcrumb {
    margin-bottom: 0;
    background: none;
    padding: 0;
}
.dev-hero-breadcrumb .breadcrumb-item a {
    color: rgba(255,255,255,0.55);
    text-decoration: none;
    font-size: 0.85rem;
    font-weight: 600;
    transition: color 0.2s;
}
.dev-hero-breadcrumb .breadcrumb-item a:hover { color: var(--dev-gold-lt); }
.dev-hero-breadcrumb .breadcrumb-item.active {
    color: var(--dev-gold-lt);
    font-weight: 700;
    font-size: 0.85rem;
}
.dev-hero-breadcrumb .breadcrumb-item + .breadcrumb-item::before {
    color: rgba(255,255,255,0.3);
    content: '/';
}

/* Floating sparkle decorations */
.dev-sparkle {
    position: absolute;
    border-radius: 50%;
    background: var(--dev-gold);
    opacity: 0;
    animation: devSparkle 4s ease-in-out infinite;
}
.dev-sparkle:nth-child(1) { width:4px; height:4px; top:15%; left:8%;  animation-delay: 0s;   }
.dev-sparkle:nth-child(2) { width:6px; height:6px; top:40%; left:20%; animation-delay: 1.2s; }
.dev-sparkle:nth-child(3) { width:3px; height:3px; top:70%; left:15%; animation-delay: 2.5s; }
.dev-sparkle:nth-child(4) { width:5px; height:5px; top:25%; right:15%; animation-delay: 0.7s; }
.dev-sparkle:nth-child(5) { width:4px; height:4px; top:60%; right:25%; animation-delay: 1.8s; }
.dev-sparkle:nth-child(6) { width:7px; height:7px; top:80%; right:10%; animation-delay: 3.2s; }

@keyframes devSparkle {
    0%,100% { opacity:0; transform:scale(0.5); }
    50%      { opacity:0.8; transform:scale(1.2); }
}

/* ── Logo ─────────────────────────────────────────── */
.dev-logo-halo {
    position: relative;
    width: 130px;
    height: 130px;
    margin-bottom: 28px;
}
.dev-logo-halo::before {
    content: '';
    position: absolute;
    inset: -8px;
    border-radius: 32px;
    background: conic-gradient(from 0deg, var(--dev-gold), transparent 40%, var(--dev-gold) 80%, transparent);
    animation: devRotateHalo 4s linear infinite;
    opacity: 0.6;
}
@keyframes devRotateHalo { to { transform: rotate(360deg); } }

.dev-logo-inner {
    position: relative;
    z-index: 1;
    width: 130px;
    height: 130px;
    background: rgba(255,255,255,0.08);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255,255,255,0.15);
    border-radius: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 20px 60px rgba(0,0,0,0.5), inset 0 1px 0 rgba(255,255,255,0.1);
    overflow: hidden;
}
.dev-logo-inner img { max-width: 90px; max-height: 90px; object-fit: contain; }
.dev-logo-fallback-txt {
    font-size: 3rem;
    font-weight: 900;
    background: linear-gradient(135deg, var(--dev-gold) 0%, var(--dev-gold-lt) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

/* ── Hero Text ────────────────────────────────────── */
.dev-hero-name {
    font-family: 'Outfit', sans-serif;
    font-size: clamp(2.4rem, 5vw, 3.8rem);
    font-weight: 900;
    color: #fff;
    letter-spacing: -1.5px;
    line-height: 1.1;
    margin-bottom: 12px;
}
.dev-hero-tagline {
    font-size: 1.15rem;
    color: var(--dev-text-muted);
    font-weight: 500;
    letter-spacing: 0.3px;
    margin-bottom: 32px;
}
.dev-hero-tagline span {
    color: var(--dev-gold);
    font-weight: 700;
}

/* ── Stat Pills in Hero ───────────────────────────── */
.dev-hero-pills {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 0;
}
.dev-pill {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(255,255,255,0.07);
    backdrop-filter: blur(12px);
    border: 1px solid rgba(255,255,255,0.12);
    border-radius: 100px;
    padding: 8px 20px;
    font-size: 0.9rem;
    font-weight: 700;
    color: #fff;
    transition: all 0.3s ease;
}
.dev-pill:hover {
    background: rgba(201,168,76,0.15);
    border-color: rgba(201,168,76,0.4);
    color: var(--dev-gold-lt);
    transform: translateY(-2px);
}
.dev-pill i { color: var(--dev-gold); font-size: 0.85rem; }

/* ── Hero Wave Divider ────────────────────────────── */
.dev-hero-wave {
    position: relative;
    margin-top: 60px;
    line-height: 0;
}
.dev-hero-wave svg { display: block; width: 100%; }

/* ── Body Section ─────────────────────────────────── */
.dev-body { background: #f4f5f8; min-height: 400px; }

/* ── Stats Bar ────────────────────────────────────── */
.dev-stats-bar {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 0;
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 8px 40px rgba(0,0,0,0.07);
    overflow: hidden;
    border: 1px solid rgba(0,0,0,0.04);
    margin-top: -60px;
    position: relative;
    z-index: 5;
}
.dev-stat-item {
    padding: 28px 32px;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    border-right: 1px solid rgba(0,0,0,0.06);
    transition: background 0.3s ease;
    cursor: default;
}
.dev-stat-item:last-child { border-right: none; }
.dev-stat-item:hover { background: #fafbff; }
.dev-stat-icon {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    background: linear-gradient(135deg, rgba(201,168,76,0.15), rgba(201,168,76,0.05));
    color: var(--dev-gold);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    margin-bottom: 12px;
}
.dev-stat-num {
    font-size: 2.2rem;
    font-weight: 900;
    color: var(--dev-dark2);
    line-height: 1;
    font-family: 'Outfit', sans-serif;
}
.dev-stat-lbl {
    font-size: 0.82rem;
    font-weight: 700;
    color: #8a8a9e;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-top: 6px;
}

/* ── About Card ───────────────────────────────────── */
.dev-about-card {
    background: #fff;
    border-radius: 20px;
    padding: 44px 48px;
    box-shadow: 0 8px 40px rgba(0,0,0,0.05);
    border: 1px solid rgba(0,0,0,0.04);
    position: relative;
    overflow: hidden;
}
.dev-about-card::before {
    content: '"';
    position: absolute;
    top: -20px;
    left: 30px;
    font-size: 12rem;
    font-weight: 900;
    color: rgba(201,168,76,0.05);
    line-height: 1;
    font-family: Georgia, serif;
    pointer-events: none;
}
.dev-about-label {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: linear-gradient(135deg, rgba(201,168,76,0.12), rgba(201,168,76,0.06));
    color: var(--dev-gold);
    border: 1px solid rgba(201,168,76,0.25);
    border-radius: 100px;
    padding: 6px 18px;
    font-size: 0.8rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    margin-bottom: 20px;
}
.dev-about-title {
    font-size: 1.9rem;
    font-weight: 800;
    color: var(--dev-dark2);
    line-height: 1.25;
    margin-bottom: 20px;
    font-family: 'Outfit', sans-serif;
}
.dev-about-text {
    font-size: 1.05rem;
    line-height: 1.9;
    color: #555570;
}
.dev-about-text strong { color: var(--dev-dark2); font-weight: 700; }

/* ── Established Badge ────────────────────────────── */
.dev-est-badge {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    background: var(--dev-dark2);
    color: var(--dev-gold-lt);
    border-radius: 12px;
    padding: 14px 24px;
    font-size: 1rem;
    font-weight: 700;
    box-shadow: 0 8px 24px rgba(13,13,26,0.25);
    margin-top: 28px;
}
.dev-est-badge i { font-size: 1.2rem; }

/* ── Trust Pillars ────────────────────────────────── */
.dev-trust-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
}
.dev-trust-card {
    background: #fff;
    border-radius: 16px;
    padding: 24px;
    border: 1px solid rgba(0,0,0,0.05);
    box-shadow: 0 4px 20px rgba(0,0,0,0.04);
    transition: all 0.3s ease;
    text-align: center;
}
.dev-trust-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 16px 40px rgba(0,0,0,0.09);
}
.dev-trust-icon {
    width: 56px;
    height: 56px;
    border-radius: 16px;
    background: linear-gradient(135deg, var(--dev-dark2), var(--dev-charcoal));
    color: var(--dev-gold);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.4rem;
    margin: 0 auto 16px;
}
.dev-trust-title {
    font-size: 0.95rem;
    font-weight: 800;
    color: var(--dev-dark2);
    margin-bottom: 6px;
    font-family: 'Outfit', sans-serif;
}
.dev-trust-text {
    font-size: 0.82rem;
    color: #8a8a9e;
    line-height: 1.6;
}

/* ── Projects Section ─────────────────────────────── */
.dev-projects-section { padding: 60px 0; }
.dev-section-header {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    margin-bottom: 36px;
    flex-wrap: wrap;
    gap: 16px;
}
.dev-section-title {
    font-size: 2rem;
    font-weight: 800;
    color: var(--dev-dark2);
    font-family: 'Outfit', sans-serif;
    letter-spacing: -0.5px;
    position: relative;
    padding-bottom: 12px;
    margin-bottom: 0;
}
.dev-section-title::after {
    content: '';
    position: absolute;
    bottom: 0; left: 0;
    width: 48px; height: 4px;
    background: linear-gradient(90deg, var(--dev-gold), var(--dev-gold-lt));
    border-radius: 4px;
}
.dev-count-badge {
    background: linear-gradient(135deg, var(--dev-dark2), var(--dev-charcoal));
    color: var(--dev-gold-lt);
    border-radius: 100px;
    padding: 8px 22px;
    font-size: 0.9rem;
    font-weight: 700;
    letter-spacing: 0.3px;
}

/* ── CTA Banner ───────────────────────────────────── */
.dev-cta-banner {
    background: linear-gradient(135deg, var(--dev-dark2) 0%, var(--dev-charcoal) 100%);
    border-radius: 24px;
    padding: 60px 48px;
    text-align: center;
    position: relative;
    overflow: hidden;
    margin: 60px 0;
}
.dev-cta-banner::before {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(ellipse 500px 300px at 70% 50%, rgba(201,168,76,0.15) 0%, transparent 70%);
    pointer-events: none;
}
.dev-cta-banner h3 {
    font-size: 2rem;
    font-weight: 800;
    color: #fff;
    font-family: 'Outfit', sans-serif;
    margin-bottom: 12px;
}
.dev-cta-banner p { color: var(--dev-text-muted); font-size: 1.05rem; margin-bottom: 28px; }
.dev-cta-btn {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    background: linear-gradient(135deg, var(--dev-gold) 0%, #e0b84a 100%);
    color: var(--dev-dark2);
    font-weight: 800;
    font-size: 1.05rem;
    padding: 16px 40px;
    border-radius: 14px;
    text-decoration: none;
    transition: all 0.3s ease;
    box-shadow: 0 8px 24px rgba(201,168,76,0.35);
}
.dev-cta-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 16px 36px rgba(201,168,76,0.5);
    color: var(--dev-dark2);
    text-decoration: none;
}

/* ── Empty State ──────────────────────────────────── */
.dev-empty {
    background: #fff;
    border-radius: 20px;
    padding: 70px 40px;
    text-align: center;
    border: 2px dashed rgba(0,0,0,0.08);
}

/* ── Responsive ───────────────────────────────────── */
@media (max-width: 768px) {
    .dev-hero-name  { font-size: 2rem; }
    .dev-about-card { padding: 28px 24px; }
    .dev-cta-banner { padding: 40px 24px; }
    .dev-stats-bar  { grid-template-columns: repeat(2,1fr); }
    .dev-stat-item  { border-right: none; border-bottom: 1px solid rgba(0,0,0,0.06); }
}
</style>

<div class="dev-pg">

<!-- ══════════ CINEMATIC HERO BANNER (breadcrumb inside) ══════════ -->
<div class="dev-hero">
    <!-- Colourful animated blobs -->
    <div class="dev-blob dev-blob-1"></div>
    <div class="dev-blob dev-blob-2"></div>
    <div class="dev-blob dev-blob-3"></div>
    <div class="dev-blob dev-blob-4"></div>
    <div class="dev-blob dev-blob-5"></div>
    <div class="dev-blob dev-blob-6"></div>
    <!-- Sparkle particles -->
    <div class="dev-sparkle"></div>
    <div class="dev-sparkle"></div>
    <div class="dev-sparkle"></div>
    <div class="dev-sparkle"></div>
    <div class="dev-sparkle"></div>
    <div class="dev-sparkle"></div>

    <div class="dev-hero-inner">
        <!-- Breadcrumb now INSIDE hero -->
        <div class="container px-4 px-md-5">
            <nav aria-label="breadcrumb" class="dev-hero-breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= PUBLIC_URL ?>"><i class="fas fa-home me-1"></i> Home</a></li>
                <li class="breadcrumb-item"><a href="<?= PUBLIC_URL ?>developer">Developers</a></li>
                <li class="breadcrumb-item active"><?= e($builder['name']) ?></li>
              </ol>
            </nav>
        </div>

        <div class="container px-4 px-md-5" style="padding-top:40px;">
            <div class="row align-items-center">
                <div class="col-lg-7 mb-5">

                    <!-- Rotating Gold Halo Logo -->
                    <div class="dev-logo-halo">
                        <div class="dev-logo-inner">
                            <?php if ($builder['logo']): ?>
                                <img src="<?= upload($builder['logo']) ?>" alt="<?= e($builder['name']) ?>">
                            <?php else: ?>
                                <span class="dev-logo-fallback-txt"><?= e($initials) ?></span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <h1 class="dev-hero-name"><?= e($builder['name']) ?></h1>
                    <p class="dev-hero-tagline">
                        <?php if ($yearsActive): ?>
                            <span><?= $yearsActive ?>+ Years</span> of Landmark Real Estate Excellence
                        <?php else: ?>
                            Building <span>Premium</span> Real Estate Across India & Beyond
                        <?php endif; ?>
                    </p>

                    <!-- Quick stat pills -->
                    <div class="dev-hero-pills">
                        <span class="dev-pill"><i class="fas fa-building"></i> <?= $totalProjects ?> Projects</span>
                        <?php if ($ongoingCount): ?><span class="dev-pill"><i class="fas fa-hard-hat"></i> <?= $ongoingCount ?> Ongoing</span><?php endif; ?>
                        <?php if ($completedCount): ?><span class="dev-pill"><i class="fas fa-check-circle"></i> <?= $completedCount ?> Ready to Move</span><?php endif; ?>
                        <?php if (!empty($builder['country_name'])): ?><span class="dev-pill"><i class="fas fa-globe"></i> <?= e($builder['country_name']) ?></span><?php endif; ?>
                    </div>
                </div>

                <!-- Right: Hero visual accent -->
                <div class="col-lg-5 d-none d-lg-flex justify-content-end align-items-center pb-4">
                    <div style="width:260px; height:260px; border-radius:40px; background: rgba(201,168,76,0.07); border:1px solid rgba(201,168,76,0.12); display:flex; align-items:center; justify-content:center; backdrop-filter:blur(10px); position:relative; overflow:hidden;">
                        <div style="position:absolute; inset:0; background: radial-gradient(circle at 60% 40%, rgba(201,168,76,0.15) 0%, transparent 60%);"></div>
                        <div style="text-align:center; position:relative; z-index:1;">
                            <div style="font-size:5rem; font-weight:900; color:rgba(201,168,76,0.25); font-family:'Outfit',sans-serif; line-height:1;"><?= $totalProjects ?></div>
                            <div style="font-size:0.75rem; font-weight:800; color:rgba(201,168,76,0.5); text-transform:uppercase; letter-spacing:3px;">Properties</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Wave transition -->
        <div class="dev-hero-wave">
            <svg viewBox="0 0 1440 80" preserveAspectRatio="none" height="80">
                <path d="M0,40 C360,90 1080,-10 1440,40 L1440,80 L0,80 Z" fill="#f4f5f8"/>
            </svg>
        </div>
    </div>
</div>

<!-- ══════════ BODY ══════════ -->
<div class="dev-body">
    <div class="container px-4 px-md-5">

        <!-- ─ Floating Stats Bar ─ -->
        <div class="dev-stats-bar mb-5">
            <div class="dev-stat-item">
                <div class="dev-stat-icon"><i class="fas fa-building"></i></div>
                <div class="dev-stat-num"><?= $totalProjects ?></div>
                <div class="dev-stat-lbl">Total Projects</div>
            </div>
            <?php if ($yearsActive): ?>
            <div class="dev-stat-item">
                <div class="dev-stat-icon"><i class="fas fa-clock"></i></div>
                <div class="dev-stat-num"><?= $yearsActive ?>+</div>
                <div class="dev-stat-lbl">Years of Trust</div>
            </div>
            <?php endif; ?>
            <div class="dev-stat-item">
                <div class="dev-stat-icon"><i class="fas fa-hard-hat"></i></div>
                <div class="dev-stat-num"><?= $ongoingCount ?></div>
                <div class="dev-stat-lbl">Ongoing Projects</div>
            </div>
            <div class="dev-stat-item">
                <div class="dev-stat-icon"><i class="fas fa-key"></i></div>
                <div class="dev-stat-num"><?= $completedCount ?></div>
                <div class="dev-stat-lbl">Ready to Move</div>
            </div>
        </div>

        <!-- ─ Ad Banner ─ -->
        <div class="mb-5">
            <?php require __DIR__ . '/../partials/_advertise_banner.php'; ?>
        </div>

        <!-- ─ About + Trust Pillars ─ -->
        <div class="row g-4 mb-5 align-items-start">
            <div class="col-lg-7">
                <div class="dev-about-card">
                    <div class="dev-about-label"><i class="fas fa-star-half-alt"></i> About the Developer</div>
                    <h2 class="dev-about-title">Crafting Landmark Properties <br>for <?= $yearsActive ? $yearsActive . '+ Years' : 'Generations' ?></h2>
                    <div class="dev-about-text">
                        <?php if (trim($builder['description'])): ?>
                            <p><strong><?= e($builder['name']) ?></strong> <?= nl2br(e($builder['description'])) ?></p>
                        <?php else: ?>
                            <p><strong><?= e($builder['name']) ?></strong> is one of the most distinguished real estate developers in the country, with an enduring legacy of crafting exceptional residential, commercial, and township projects that stand as benchmarks for quality, design, and innovation.</p>
                            <p>With a portfolio spanning major cities and featuring world-class amenities, <?= e($builder['name']) ?> brings together architectural excellence and superior construction quality to deliver homes that people are truly proud to call their own.</p>
                            <p>Committed to sustainability, customer satisfaction, and timely delivery, the company continues to lead the real estate sector by building communities that offer both luxury and lasting value.</p>
                        <?php endif; ?>
                    </div>
                    <?php if ((int)($builder['established_year'] ?? 0)): ?>
                    <div class="dev-est-badge">
                        <i class="fas fa-flag"></i> Established in <?= (int)$builder['established_year'] ?> — <?= $yearsActive ?> Years of Excellence
                    </div>
                    <?php endif; ?>
                    <?php if (!empty($builder['website'])): ?>
                    <a href="<?= e($builder['website']) ?>" target="_blank" rel="noopener" class="dev-cta-btn mt-4 d-inline-flex" style="margin-top:20px !important;">
                        <i class="fas fa-globe"></i> Visit Official Website
                    </a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="dev-trust-grid">
                    <div class="dev-trust-card">
                        <div class="dev-trust-icon"><i class="fas fa-award"></i></div>
                        <div class="dev-trust-title">Award Winning</div>
                        <div class="dev-trust-text">Recognized nationally for excellence in design and quality</div>
                    </div>
                    <div class="dev-trust-card">
                        <div class="dev-trust-icon"><i class="fas fa-shield-alt"></i></div>
                        <div class="dev-trust-title">RERA Compliant</div>
                        <div class="dev-trust-text">All projects registered and fully transparent with buyers</div>
                    </div>
                    <div class="dev-trust-card">
                        <div class="dev-trust-icon"><i class="fas fa-leaf"></i></div>
                        <div class="dev-trust-title">Eco-Friendly</div>
                        <div class="dev-trust-text">Committed to green, sustainable building practices</div>
                    </div>
                    <div class="dev-trust-card">
                        <div class="dev-trust-icon"><i class="fas fa-handshake"></i></div>
                        <div class="dev-trust-title">Customer First</div>
                        <div class="dev-trust-text">Dedicated customer care from booking to possession</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ─ Projects Section ─ -->
        <div class="dev-projects-section" id="projects">
            <div class="dev-section-header">
                <h2 class="dev-section-title">Featured Projects</h2>
                <span class="dev-count-badge"><i class="fas fa-layer-group me-2"></i><?= $totalProjects ?> Properties</span>
            </div>

            <?php if ($projects): ?>
            <div class="row g-4 mb-5">
                <?php foreach ($projects as $p): ?>
                <div class="col-lg-6 col-xl-4">
                    <?php require __DIR__ . '/../partials/_property_card.php'; ?>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <div class="dev-empty">
                <i class="fas fa-city fa-3x mb-3" style="color:#d0d0e0;"></i>
                <h4 class="fw-bold mb-2" style="color:var(--dev-dark2);">No Projects Listed Yet</h4>
                <p class="text-muted">Check back soon — new projects from <?= e($builder['name']) ?> are being added.</p>
            </div>
            <?php endif; ?>
        </div>

        <!-- ─ CTA Bottom Banner ─ -->
        <div class="dev-cta-banner">
            <h3>Interested in a <?= e($builder['name']) ?> Property?</h3>
            <p>Talk to our experts and get personalised pricing, brochures, and site visit assistance — absolutely free.</p>
            <a href="<?= PUBLIC_URL ?>contact" class="dev-cta-btn">
                <i class="fas fa-paper-plane"></i> Enquire Now — It's Free
            </a>
        </div>

    </div><!-- /container -->
</div><!-- /dev-body -->

</div><!-- /dev-pg -->
