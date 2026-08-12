<?php /** Developer Profile View — World-Class Premium Redesign v3 */ ?>
<?php
    $totalProjects  = count($projects);
    $ongoingCount   = count(array_filter($projects, fn($p) => in_array($p['status'], ['under_construction', 'new_launch'])));
    $completedCount = count(array_filter($projects, fn($p) => $p['status'] === 'ready_to_move'));
    $yearsActive    = (int)($builder['established_year'] ?? 0) ? (date('Y') - (int)$builder['established_year']) : null;
    $initials       = strtoupper(substr(preg_replace('/[^a-zA-Z ]/', '', $builder['name']), 0, 2));
    $country        = $builder['country_name'] ?? '';
    $website        = $builder['website'] ?? '';
?>
<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap');

/* ═══════════════════════════════════════════
   CORE LAYOUT
═══════════════════════════════════════════ */
.dp-root { font-family: 'Plus Jakarta Sans', sans-serif; }

/* ═══════════════════════════════════════════
   HERO — FULL CINEMATIC
═══════════════════════════════════════════ */
.dp-hero {
    position: relative;
    width: 100%;
    min-height: 520px;
    background: #060614;
    overflow: hidden;
    isolation: isolate;
}

/* Mesh gradient background */
.dp-hero-bg {
    position: absolute;
    inset: 0;
    background:
        radial-gradient(ellipse 900px 600px at 110% -10%, #7c3aed 0%, transparent 55%),
        radial-gradient(ellipse 700px 500px at -10% 100%, #0ea5e9 0%, transparent 55%),
        radial-gradient(ellipse 500px 400px at 60% 110%, #f59e0b 0%, transparent 50%),
        radial-gradient(ellipse 400px 400px at 35% 0%, #ec4899 0%, transparent 50%),
        radial-gradient(ellipse 600px 600px at 80% 50%, #10b981 0%, transparent 50%);
    opacity: 0.22;
    z-index: 0;
}

/* Noise/texture overlay for depth */
.dp-hero-noise {
    position: absolute;
    inset: 0;
    background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.04'/%3E%3C/svg%3E");
    z-index: 1;
    opacity: 0.5;
    pointer-events: none;
}

/* Grid overlay */
.dp-hero-grid {
    position: absolute;
    inset: 0;
    background-image:
        linear-gradient(rgba(255,255,255,0.04) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,0.04) 1px, transparent 1px);
    background-size: 50px 50px;
    z-index: 1;
    pointer-events: none;
}

/* Animated glow orbs */
.dp-orb {
    position: absolute;
    border-radius: 50%;
    pointer-events: none;
    z-index: 1;
}
.dp-orb-1 {
    width: 500px; height: 500px;
    background: radial-gradient(circle, rgba(124,58,237,0.5) 0%, transparent 65%);
    top: -200px; right: -100px;
    animation: dpOrb1 12s ease-in-out infinite alternate;
}
.dp-orb-2 {
    width: 400px; height: 400px;
    background: radial-gradient(circle, rgba(6,182,212,0.4) 0%, transparent 65%);
    bottom: -150px; left: -80px;
    animation: dpOrb2 10s ease-in-out infinite alternate;
}
.dp-orb-3 {
    width: 300px; height: 300px;
    background: radial-gradient(circle, rgba(236,72,153,0.35) 0%, transparent 65%);
    top: 30%; left: 40%;
    animation: dpOrb3 8s ease-in-out infinite alternate;
}
.dp-orb-4 {
    width: 250px; height: 250px;
    background: radial-gradient(circle, rgba(245,158,11,0.4) 0%, transparent 65%);
    bottom: 0; right: 25%;
    animation: dpOrb4 14s ease-in-out infinite alternate;
}

@keyframes dpOrb1 { from { transform: translate(0,0) scale(1); } to { transform: translate(-40px, 30px) scale(1.1); } }
@keyframes dpOrb2 { from { transform: translate(0,0) scale(1); } to { transform: translate(30px, -40px) scale(1.08); } }
@keyframes dpOrb3 { from { transform: translate(0,0) scale(1); } to { transform: translate(-20px, 25px) scale(0.95); } }
@keyframes dpOrb4 { from { transform: translate(0,0) scale(1); } to { transform: translate(25px, -20px) scale(1.05); } }

.dp-hero-content {
    position: relative;
    z-index: 10;
    padding: 28px 0 0;
}

/* Breadcrumb */
.dp-breadcrumb { padding: 0 0 40px; }
.dp-breadcrumb ol { list-style: none; padding: 0; margin: 0; display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
.dp-breadcrumb li { display: flex; align-items: center; gap: 8px; font-size: 0.82rem; font-weight: 600; color: rgba(255,255,255,0.4); }
.dp-breadcrumb li a { color: rgba(255,255,255,0.5); text-decoration: none; transition: color 0.2s; }
.dp-breadcrumb li a:hover { color: #f59e0b; }
.dp-breadcrumb li:last-child { color: #f59e0b; }
.dp-breadcrumb li:not(:last-child)::after { content: '/'; color: rgba(255,255,255,0.2); }

/* Hero layout */
.dp-hero-main { display: grid; grid-template-columns: 1fr auto; gap: 48px; align-items: center; padding-bottom: 70px; }
@media (max-width: 768px) { .dp-hero-main { grid-template-columns: 1fr; } }

/* Logo */
.dp-logo-wrap {
    width: 110px; height: 110px;
    background: rgba(255,255,255,0.07);
    backdrop-filter: blur(24px);
    border: 1.5px solid rgba(255,255,255,0.12);
    border-radius: 24px;
    display: flex; align-items: center; justify-content: center;
    box-shadow: 0 0 0 8px rgba(255,255,255,0.03), 0 24px 48px rgba(0,0,0,0.5);
    margin-bottom: 28px;
    overflow: hidden;
    position: relative;
    transition: transform 0.4s ease, box-shadow 0.4s ease;
}
.dp-logo-wrap:hover {
    transform: translateY(-4px) scale(1.02);
    box-shadow: 0 0 0 8px rgba(245,158,11,0.06), 0 32px 64px rgba(0,0,0,0.6);
}
.dp-logo-wrap::before {
    content: '';
    position: absolute; inset: 0;
    background: linear-gradient(135deg, rgba(245,158,11,0.1) 0%, transparent 60%);
}
.dp-logo-wrap img { max-width: 80px; max-height: 80px; object-fit: contain; position: relative; z-index: 1; }
.dp-logo-initials {
    font-size: 2.8rem; font-weight: 900;
    background: linear-gradient(135deg, #f59e0b, #fcd34d);
    -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    position: relative; z-index: 1;
}

/* Developer name */
.dp-dev-name {
    font-size: clamp(2.8rem, 5vw, 4.4rem);
    font-weight: 900;
    color: #fff;
    letter-spacing: -2px;
    line-height: 1.05;
    margin-bottom: 14px;
}

/* Country pill */
.dp-country-tag {
    display: inline-flex; align-items: center; gap: 7px;
    background: rgba(245,158,11,0.12);
    border: 1px solid rgba(245,158,11,0.3);
    color: #fcd34d;
    border-radius: 100px;
    padding: 5px 16px;
    font-size: 0.78rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1.2px;
    margin-bottom: 16px;
}
.dp-country-tag i { font-size: 0.75rem; }

/* Tagline */
.dp-tagline {
    font-size: 1.1rem; font-weight: 500;
    color: rgba(255,255,255,0.55);
    margin-bottom: 32px;
    line-height: 1.6;
}
.dp-tagline strong { color: rgba(255,255,255,0.9); font-weight: 700; }

/* CTA buttons in hero */
.dp-hero-ctas { display: flex; gap: 14px; flex-wrap: wrap; }
.dp-btn-primary {
    display: inline-flex; align-items: center; gap: 8px;
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: #1a0a00; font-weight: 800; font-size: 0.95rem;
    padding: 13px 28px; border-radius: 12px; text-decoration: none;
    border: none; cursor: pointer;
    box-shadow: 0 8px 24px rgba(245,158,11,0.35);
    transition: all 0.3s ease;
}
.dp-btn-primary:hover {
    transform: translateY(-3px);
    box-shadow: 0 14px 32px rgba(245,158,11,0.5);
    color: #1a0a00; text-decoration: none;
}
.dp-btn-ghost {
    display: inline-flex; align-items: center; gap: 8px;
    background: rgba(255,255,255,0.07);
    backdrop-filter: blur(12px);
    border: 1.5px solid rgba(255,255,255,0.15);
    color: rgba(255,255,255,0.85); font-weight: 700; font-size: 0.95rem;
    padding: 13px 28px; border-radius: 12px; text-decoration: none;
    transition: all 0.3s ease; cursor: pointer;
}
.dp-btn-ghost:hover {
    background: rgba(255,255,255,0.12);
    border-color: rgba(255,255,255,0.3);
    color: #fff; text-decoration: none;
    transform: translateY(-2px);
}

/* Right side: featured number card */
.dp-hero-num-card {
    background: rgba(255,255,255,0.04);
    backdrop-filter: blur(20px);
    border: 1.5px solid rgba(255,255,255,0.08);
    border-radius: 28px;
    padding: 36px 40px;
    text-align: center;
    min-width: 200px;
    box-shadow: inset 0 1px 0 rgba(255,255,255,0.06), 0 32px 80px rgba(0,0,0,0.5);
    position: relative; overflow: hidden;
}
.dp-hero-num-card::before {
    content: '';
    position: absolute; top: 0; left: 0; right: 0; height: 1px;
    background: linear-gradient(90deg, transparent, rgba(245,158,11,0.5), transparent);
}
.dp-hero-num {
    font-size: 5rem; font-weight: 900; color: #fff;
    line-height: 1; letter-spacing: -4px;
    background: linear-gradient(135deg, #fff 0%, rgba(255,255,255,0.7) 100%);
    -webkit-background-clip: text; -webkit-text-fill-color: transparent;
}
.dp-hero-num-lbl {
    font-size: 0.7rem; font-weight: 800; text-transform: uppercase;
    letter-spacing: 3px; color: #f59e0b; margin-top: 8px;
}

/* Wave divider */
.dp-wave {
    position: relative; line-height: 0; margin-top: -2px;
}
.dp-wave svg { display: block; width: 100%; }

/* ═══════════════════════════════════════════
   STATS RIBBON — floats up from body
═══════════════════════════════════════════ */
.dp-stats-ribbon {
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 -4px 0 #f59e0b, 0 20px 60px rgba(0,0,0,0.1);
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
    overflow: hidden;
    margin-top: -50px;
    position: relative; z-index: 20;
    border: 1px solid rgba(0,0,0,0.05);
}
.dp-stat {
    padding: 28px 24px;
    border-right: 1px solid rgba(0,0,0,0.06);
    transition: background 0.25s;
    cursor: default;
    position: relative;
}
.dp-stat:last-child { border-right: none; }
.dp-stat:hover { background: #fffbf0; }
.dp-stat:hover .dp-stat-num { color: #d97706; }
.dp-stat-num {
    font-size: 2.5rem; font-weight: 900;
    color: #0f172a;
    line-height: 1;
    font-family: 'Plus Jakarta Sans', sans-serif;
    transition: color 0.25s;
}
.dp-stat-lbl {
    font-size: 0.75rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: 1.2px; color: #94a3b8; margin-top: 8px;
}
.dp-stat-icon {
    font-size: 1.1rem;
    margin-bottom: 10px;
    display: block;
}

/* ═══════════════════════════════════════════
   BODY SECTIONS
═══════════════════════════════════════════ */
.dp-body { background: #f8fafc; }
.dp-section { padding: 64px 0; }

/* About section */
.dp-about-layout { display: grid; grid-template-columns: 3fr 2fr; gap: 32px; align-items: start; }
@media (max-width: 900px) { .dp-about-layout { grid-template-columns: 1fr; } }

.dp-about-card {
    background: #fff;
    border-radius: 24px;
    padding: 48px;
    border: 1px solid rgba(0,0,0,0.05);
    box-shadow: 0 8px 40px rgba(0,0,0,0.04);
    position: relative; overflow: hidden;
}
.dp-about-card::before {
    content: '';
    position: absolute; top: 0; left: 0; width: 4px; height: 100%;
    background: linear-gradient(180deg, #f59e0b, #7c3aed);
    border-radius: 4px 0 0 4px;
}

.dp-section-eyebrow {
    display: inline-flex; align-items: center; gap: 8px;
    background: linear-gradient(135deg, rgba(245,158,11,0.1), rgba(245,158,11,0.05));
    border: 1px solid rgba(245,158,11,0.25);
    color: #d97706;
    border-radius: 100px; padding: 5px 16px;
    font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px;
    margin-bottom: 18px;
}

.dp-section-heading {
    font-size: 1.75rem; font-weight: 800; color: #0f172a;
    letter-spacing: -0.5px; line-height: 1.3;
    margin-bottom: 20px;
    font-family: 'Plus Jakarta Sans', sans-serif;
}

.dp-about-body {
    font-size: 1.03rem; line-height: 1.85; color: #475569;
}
.dp-about-body strong { color: #0f172a; font-weight: 700; }
.dp-about-body p + p { margin-top: 16px; }

/* Established badge */
.dp-est-badge {
    display: inline-flex; align-items: center; gap: 10px;
    background: linear-gradient(135deg, #0f172a, #1e293b);
    color: #fcd34d;
    border-radius: 14px; padding: 14px 22px;
    font-size: 0.9rem; font-weight: 700;
    box-shadow: 0 8px 24px rgba(15,23,42,0.2);
    margin-top: 28px;
}

/* Right sidebar cards */
.dp-side-stack { display: flex; flex-direction: column; gap: 16px; }

.dp-side-card {
    background: #fff;
    border-radius: 20px;
    padding: 26px 28px;
    border: 1px solid rgba(0,0,0,0.05);
    box-shadow: 0 4px 20px rgba(0,0,0,0.04);
    display: flex; align-items: center; gap: 18px;
    transition: all 0.3s ease;
}
.dp-side-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 16px 40px rgba(0,0,0,0.09);
}
.dp-side-icon {
    width: 52px; height: 52px; flex-shrink: 0;
    border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.3rem;
}
.dp-side-icon-gold { background: linear-gradient(135deg, rgba(245,158,11,0.15), rgba(245,158,11,0.05)); color: #d97706; }
.dp-side-icon-purple { background: linear-gradient(135deg, rgba(124,58,237,0.12), rgba(124,58,237,0.05)); color: #7c3aed; }
.dp-side-icon-cyan { background: linear-gradient(135deg, rgba(6,182,212,0.12), rgba(6,182,212,0.05)); color: #0891b2; }
.dp-side-icon-green { background: linear-gradient(135deg, rgba(16,185,129,0.12), rgba(16,185,129,0.05)); color: #059669; }
.dp-side-text-title { font-size: 0.95rem; font-weight: 800; color: #0f172a; margin-bottom: 3px; }
.dp-side-text-sub { font-size: 0.82rem; color: #94a3b8; font-weight: 500; line-height: 1.5; }

/* ═══════════════════════════════════════════
   PROJECTS SECTION
═══════════════════════════════════════════ */
.dp-projects-section { padding: 20px 0 64px; }

.dp-projects-header {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 36px; flex-wrap: wrap; gap: 16px;
}
.dp-projects-title {
    font-size: 2rem; font-weight: 900; color: #0f172a;
    letter-spacing: -0.5px; margin: 0;
    font-family: 'Plus Jakarta Sans', sans-serif;
}
.dp-projects-title span {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    -webkit-background-clip: text; -webkit-text-fill-color: transparent;
}
.dp-projects-count {
    background: #0f172a; color: #fcd34d;
    border-radius: 100px; padding: 8px 22px;
    font-size: 0.85rem; font-weight: 800;
    letter-spacing: 0.5px;
}

/* No projects */
.dp-empty {
    background: #fff; border-radius: 20px;
    padding: 72px 40px; text-align: center;
    border: 2px dashed rgba(0,0,0,0.08);
}

/* ═══════════════════════════════════════════
   CTA BANNER
═══════════════════════════════════════════ */
.dp-cta {
    background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #0f172a 100%);
    border-radius: 28px;
    padding: 64px 48px;
    text-align: center;
    position: relative; overflow: hidden;
    margin: 0 0 64px;
}
.dp-cta::before {
    content: '';
    position: absolute; inset: 0;
    background:
        radial-gradient(ellipse 500px 300px at 20% 50%, rgba(124,58,237,0.25) 0%, transparent 60%),
        radial-gradient(ellipse 400px 400px at 80% 50%, rgba(245,158,11,0.15) 0%, transparent 60%);
    pointer-events: none;
}
.dp-cta::after {
    content: '';
    position: absolute; top: 0; left: 0; right: 0; height: 1px;
    background: linear-gradient(90deg, transparent 10%, rgba(245,158,11,0.5) 50%, transparent 90%);
}
.dp-cta h3 {
    font-size: 2.2rem; font-weight: 900; color: #fff;
    letter-spacing: -0.5px; margin-bottom: 12px;
    font-family: 'Plus Jakarta Sans', sans-serif;
    position: relative; z-index: 1;
}
.dp-cta h3 span {
    background: linear-gradient(135deg, #f59e0b, #fcd34d);
    -webkit-background-clip: text; -webkit-text-fill-color: transparent;
}
.dp-cta p { color: rgba(255,255,255,0.55); font-size: 1.05rem; margin-bottom: 32px; position: relative; z-index: 1; }
.dp-cta-actions { display: flex; gap: 16px; justify-content: center; flex-wrap: wrap; position: relative; z-index: 1; }

/* ═══════════════════════════════════════════
   RESPONSIVE
═══════════════════════════════════════════ */
@media (max-width: 768px) {
    .dp-dev-name { font-size: 2.4rem; }
    .dp-about-card { padding: 28px 24px; }
    .dp-cta { padding: 44px 24px; }
    .dp-cta h3 { font-size: 1.6rem; }
    .dp-stats-ribbon { grid-template-columns: repeat(2, 1fr); }
    .dp-stat { border-bottom: 1px solid rgba(0,0,0,0.06); }
    .dp-hero-num-card { display: none; }
}
</style>

<div class="dp-root">

<!-- ══════════════════════════════════════
     CINEMATIC HERO BANNER
══════════════════════════════════════════ -->
<div class="dp-hero">
    <!-- Gradient mesh background -->
    <div class="dp-hero-bg"></div>
    <div class="dp-hero-noise"></div>
    <div class="dp-hero-grid"></div>

    <!-- Animated orbs -->
    <div class="dp-orb dp-orb-1"></div>
    <div class="dp-orb dp-orb-2"></div>
    <div class="dp-orb dp-orb-3"></div>
    <div class="dp-orb dp-orb-4"></div>

    <div class="dp-hero-content">
        <div class="container px-4 px-md-5">

            <!-- Breadcrumb inside hero -->
            <nav class="dp-breadcrumb" aria-label="breadcrumb">
                <ol>
                    <li><a href="<?= PUBLIC_URL ?>"><i class="fas fa-home"></i> Home</a></li>
                    <li><a href="<?= PUBLIC_URL ?>developer">Developers</a></li>
                    <li><?= e($builder['name']) ?></li>
                </ol>
            </nav>

            <!-- Main hero grid -->
            <div class="dp-hero-main">

                <!-- LEFT: Developer Identity -->
                <div>
                    <!-- Logo -->
                    <div class="dp-logo-wrap">
                        <?php if (!empty($builder['logo'])): ?>
                            <img src="<?= upload($builder['logo']) ?>" alt="<?= e($builder['name']) ?>">
                        <?php else: ?>
                            <span class="dp-logo-initials"><?= e($initials) ?></span>
                        <?php endif; ?>
                    </div>

                    <!-- Country tag -->
                    <?php if ($country): ?>
                    <div class="dp-country-tag">
                        <i class="fas fa-map-marker-alt"></i> <?= e($country) ?>
                    </div>
                    <?php endif; ?>

                    <h1 class="dp-dev-name"><?= e($builder['name']) ?></h1>

                    <p class="dp-tagline">
                        <?php if ($yearsActive): ?>
                            <strong><?= $yearsActive ?>+ Years</strong> of Landmark Real Estate Excellence · <?= $totalProjects ?> Iconic Projects Delivered
                        <?php else: ?>
                            Building <strong>Premium Real Estate</strong> Across India & Beyond
                        <?php endif; ?>
                    </p>

                    <div class="dp-hero-ctas">
                        <button type="button" class="dp-btn-primary" data-bs-toggle="modal" data-bs-target="#enquiryModal">
                            <i class="fas fa-paper-plane"></i> Enquire Now
                        </button>
                        <?php if ($website): ?>
                        <a href="<?= e($website) ?>" target="_blank" rel="noopener" class="dp-btn-ghost">
                            <i class="fas fa-external-link-alt"></i> Official Site
                        </a>
                        <?php else: ?>
                        <a href="#projects" class="dp-btn-ghost">
                            <i class="fas fa-building"></i> View Projects
                        </a>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- RIGHT: Featured number card -->
                <div class="dp-hero-num-card">
                    <div class="dp-hero-num"><?= $totalProjects ?></div>
                    <div class="dp-hero-num-lbl">Total Properties</div>
                    <?php if ($yearsActive): ?>
                    <div style="margin-top: 24px; padding-top: 20px; border-top: 1px solid rgba(255,255,255,0.07);">
                        <div class="dp-hero-num" style="font-size:3rem;"><?= $yearsActive ?>+</div>
                        <div class="dp-hero-num-lbl">Years of Trust</div>
                    </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>

        <!-- Wave divider -->
        <div class="dp-wave">
            <svg viewBox="0 0 1440 90" preserveAspectRatio="none" style="height:90px;">
                <path d="M0,0 C240,90 480,0 720,60 C960,120 1200,20 1440,60 L1440,90 L0,90 Z" fill="#f8fafc"/>
            </svg>
        </div>
    </div>
</div>

<!-- ══════════════════════════════════════
     BODY
══════════════════════════════════════════ -->
<div class="dp-body">
    <div class="container px-4 px-md-5">

        <!-- Stats Ribbon -->
        <div class="dp-stats-ribbon">
            <div class="dp-stat">
                <span class="dp-stat-icon" style="font-size:1.3rem; color:#f59e0b;">🏗️</span>
                <div class="dp-stat-num"><?= $totalProjects ?></div>
                <div class="dp-stat-lbl">Total Projects</div>
            </div>
            <?php if ($yearsActive): ?>
            <div class="dp-stat">
                <span class="dp-stat-icon" style="font-size:1.3rem; color:#7c3aed;">🏆</span>
                <div class="dp-stat-num"><?= $yearsActive ?>+</div>
                <div class="dp-stat-lbl">Years of Trust</div>
            </div>
            <?php endif; ?>
            <div class="dp-stat">
                <span class="dp-stat-icon" style="font-size:1.3rem; color:#0891b2;">🔨</span>
                <div class="dp-stat-num"><?= $ongoingCount ?></div>
                <div class="dp-stat-lbl">Ongoing Projects</div>
            </div>
            <div class="dp-stat">
                <span class="dp-stat-icon" style="font-size:1.3rem; color:#059669;">🔑</span>
                <div class="dp-stat-num"><?= $completedCount ?></div>
                <div class="dp-stat-lbl">Ready to Move</div>
            </div>
        </div>

        <!-- Ad Banner -->
        <div style="margin: 48px 0 0;">
            <?php require __DIR__ . '/../partials/_advertise_banner.php'; ?>
        </div>

        <!-- About + Trust Pillars -->
        <div class="dp-section dp-about-layout">

            <!-- About Card -->
            <div class="dp-about-card">
                <div class="dp-section-eyebrow"><i class="fas fa-star"></i> About the Developer</div>
                <h2 class="dp-section-heading">
                    Crafting Landmark Properties<br>
                    <?php if ($yearsActive): ?>for <?= $yearsActive ?>+ Years<?php else: ?>with Unmatched Excellence<?php endif; ?>
                </h2>
                <div class="dp-about-body">
                    <?php if (trim($builder['description'])): ?>
                        <p><strong><?= e($builder['name']) ?></strong> <?= nl2br(e($builder['description'])) ?></p>
                    <?php else: ?>
                        <p><strong><?= e($builder['name']) ?></strong> stands as one of India's most distinguished real estate developers — a name synonymous with quality, innovation, and architectural brilliance. Every project they build is a statement of excellence, crafted to deliver enduring value for generations.</p>
                        <p>Their portfolio spans premium residential townships, commercial landmarks, IT parks, and integrated communities across India's most coveted locations. From concept to completion, <?= e($builder['name']) ?> has redefined what it means to build with purpose.</p>
                        <p>Customer satisfaction, timely delivery, and sustainable construction are not just promises — they are a core philosophy that has earned <?= e($builder['name']) ?> a legacy of trust and recognition in the industry.</p>
                    <?php endif; ?>

                    <?php if ((int)($builder['established_year'] ?? 0)): ?>
                    <div class="dp-est-badge">
                        <i class="fas fa-flag"></i>
                        Established <?= (int)$builder['established_year'] ?> &nbsp;·&nbsp; <?= $yearsActive ?>+ Years of Delivering Excellence
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Side Trust Cards -->
            <div class="dp-side-stack">
                <div class="dp-side-card">
                    <div class="dp-side-icon dp-side-icon-gold"><i class="fas fa-award"></i></div>
                    <div>
                        <div class="dp-side-text-title">Award Winning</div>
                        <div class="dp-side-text-sub">Nationally recognized for design excellence & quality construction</div>
                    </div>
                </div>
                <div class="dp-side-card">
                    <div class="dp-side-icon dp-side-icon-purple"><i class="fas fa-shield-alt"></i></div>
                    <div>
                        <div class="dp-side-text-title">RERA Registered</div>
                        <div class="dp-side-text-sub">Full transparency — all projects legally registered & verified</div>
                    </div>
                </div>
                <div class="dp-side-card">
                    <div class="dp-side-icon dp-side-icon-cyan"><i class="fas fa-leaf"></i></div>
                    <div>
                        <div class="dp-side-text-title">Eco-Certified</div>
                        <div class="dp-side-text-sub">Green building standards and sustainable community design</div>
                    </div>
                </div>
                <div class="dp-side-card">
                    <div class="dp-side-icon dp-side-icon-green"><i class="fas fa-handshake"></i></div>
                    <div>
                        <div class="dp-side-text-title">Customer First</div>
                        <div class="dp-side-text-sub">Dedicated support from booking through possession</div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Projects Section -->
        <div class="dp-projects-section" id="projects">
            <div class="dp-projects-header">
                <h2 class="dp-projects-title">Featured <span>Projects</span></h2>
                <span class="dp-projects-count"><i class="fas fa-layer-group me-2"></i><?= $totalProjects ?> Properties</span>
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
            <div class="dp-empty">
                <i class="fas fa-city fa-3x mb-3" style="color:#e2e8f0;"></i>
                <h4 style="color:#0f172a; font-weight:800;">No Projects Listed Yet</h4>
                <p style="color:#94a3b8;">New projects from <?= e($builder['name']) ?> will appear here soon.</p>
            </div>
            <?php endif; ?>

        </div>

        <!-- CTA Banner -->
        <div class="dp-cta">
            <h3>Ready to Explore <span><?= e($builder['name']) ?></span> Properties?</h3>
            <p>Our real estate experts will guide you through pricing, site visits, and personalised recommendations — completely free.</p>
            <div class="dp-cta-actions">
                <button type="button" class="dp-btn-primary" data-bs-toggle="modal" data-bs-target="#enquiryModal" style="font-size:1rem; padding: 15px 36px;">
                    <i class="fas fa-paper-plane"></i> Enquire Now — It's Free
                </button>
                <a href="<?= PUBLIC_URL ?>contact" class="dp-btn-ghost" style="color:#fff;">
                    <i class="fas fa-phone"></i> Talk to an Expert
                </a>
            </div>
        </div>

    </div>
</div><!-- /dp-body -->

</div><!-- /dp-root -->
