<?php /** Premium About Us View */ ?>
<div class="breadcrumb-section" style="background:var(--pr-secondary); border-bottom:1px solid rgba(255,255,255,0.1);">
  <div class="container-fluid px-3 px-md-4">
    <nav aria-label="breadcrumb"><ol class="breadcrumb mb-0">
      <li class="breadcrumb-item"><a href="<?= PUBLIC_URL ?>" style="color:var(--pr-primary);">Home</a></li>
      <li class="breadcrumb-item active" style="color:#fff;"><?= e($page['title']) ?></li>
    </ol></nav>
  </div>
</div>

<!-- Hero Section -->
<div class="about-hero position-relative">
  <img src="https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=1920&q=80" alt="About PropertyRubix" class="about-hero-bg">
  <div class="about-hero-overlay"></div>
  <div class="container-fluid px-3 px-md-5 position-relative z-1 h-100">
    <div class="row h-100 align-items-center">
      <div class="col-lg-8 text-white">
        <h1 class="display-3 fw-bold mb-4">Discover Your <span style="color:var(--pr-primary)">Dream Space</span></h1>
        <p class="lead mb-0" style="opacity: 0.9; max-width: 600px; font-size:1.25rem;">
          PropertyRubix is a leading real estate discovery platform connecting homebuyers, investors, and developers across India and the UAE.
        </p>
      </div>
    </div>
  </div>
</div>

<!-- Main Content & Stats -->
<div class="section py-5">
  <div class="container-fluid px-3 px-md-5">
    <div class="row align-items-center mb-5">
      <div class="col-lg-6 mb-4 mb-lg-0">
        <h2 class="fw-bold mb-4" style="font-size:2.5rem;">Who We Are</h2>
        <div style="width:60px; height:4px; background:var(--pr-primary); margin-bottom:1.5rem;"></div>
        <p class="text-muted" style="font-size:1.15rem; line-height:1.8;">
          Founded with a mission to make property discovery transparent, simple, and trustworthy, we have helped thousands of families find their dream homes. We democratize real estate information and empower buyers with the data they need to make informed decisions.
        </p>
      </div>
      <div class="col-lg-5 offset-lg-1">
        <div class="row g-4 text-center">
          <div class="col-6">
            <div class="stat-card p-4 rounded-4 shadow-sm" style="background:#fff; border:1px solid var(--pr-border);">
              <h3 class="fw-bold mb-1" style="color:var(--pr-primary); font-size:2.5rem;">10k+</h3>
              <p class="text-muted mb-0 fw-600">Satisfied Customers</p>
            </div>
          </div>
          <div class="col-6">
            <div class="stat-card p-4 rounded-4 shadow-sm" style="background:#fff; border:1px solid var(--pr-border);">
              <h3 class="fw-bold mb-1" style="color:var(--pr-primary); font-size:2.5rem;">500+</h3>
              <p class="text-muted mb-0 fw-600">Partner Developers</p>
            </div>
          </div>
          <div class="col-6">
            <div class="stat-card p-4 rounded-4 shadow-sm" style="background:#fff; border:1px solid var(--pr-border);">
              <h3 class="fw-bold mb-1" style="color:var(--pr-primary); font-size:2.5rem;">100%</h3>
              <p class="text-muted mb-0 fw-600">RERA Verified</p>
            </div>
          </div>
          <div class="col-6">
            <div class="stat-card p-4 rounded-4 shadow-sm" style="background:#fff; border:1px solid var(--pr-border);">
              <h3 class="fw-bold mb-1" style="color:var(--pr-primary); font-size:2.5rem;">24/7</h3>
              <p class="text-muted mb-0 fw-600">Expert Advisory</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Mission & Vision Cards -->
    <div class="row g-4 mb-5">
      <div class="col-md-6">
        <div class="about-card p-5 rounded-4 h-100">
          <div class="about-icon-wrap mb-4">
            <i class="fas fa-bullseye"></i>
          </div>
          <h3 class="fw-bold mb-3">Our Mission</h3>
          <p class="mb-0 text-muted" style="font-size:1.1rem; line-height:1.7;">
            To democratize real estate information and empower buyers with the data they need to make informed decisions in a complex market.
          </p>
        </div>
      </div>
      <div class="col-md-6">
        <div class="about-card p-5 rounded-4 h-100">
          <div class="about-icon-wrap mb-4">
            <i class="fas fa-eye"></i>
          </div>
          <h3 class="fw-bold mb-3">Our Vision</h3>
          <p class="mb-0 text-muted" style="font-size:1.1rem; line-height:1.7;">
            To be the most trusted real estate platform in South Asia and the Middle East, setting the standard for transparency and user experience.
          </p>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- FAQs -->
<?php if (!empty($faqs)): ?>
<div class="section pt-0 pb-5">
  <div class="container-fluid px-3 px-md-5">
    <div class="text-center mb-5">
      <h2 class="fw-bold" style="font-size:2.5rem;">Frequently Asked Questions</h2>
      <div style="width:60px; height:4px; background:var(--pr-primary); margin: 1.5rem auto 0;"></div>
    </div>
    <div class="row justify-content-center">
      <div class="col-lg-9">
        <div class="accordion premium-faq" id="faqAccordion">
          <?php foreach ($faqs as $i => $faq): ?>
          <div class="accordion-item mb-3">
            <h3 class="accordion-header" id="faqHead<?= $i ?>">
              <button class="accordion-button <?= $i > 0 ? 'collapsed' : '' ?>"
                      type="button" data-bs-toggle="collapse"
                      data-bs-target="#faqBody<?= $i ?>">
                <?= e($faq['question']) ?>
              </button>
            </h3>
            <div id="faqBody<?= $i ?>" class="accordion-collapse collapse <?= $i === 0 ? 'show' : '' ?>" data-bs-parent="#faqAccordion">
              <div class="accordion-body">
                <?= e($faq['answer']) ?>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>

<style>
/* Hero */
.about-hero {
  height: 60vh;
  min-height: 400px;
  max-height: 600px;
  overflow: hidden;
}
.about-hero-bg {
  position: absolute;
  top: 0; left: 0; width: 100%; height: 100%;
  object-fit: cover;
  z-index: 0;
}
.about-hero-overlay {
  position: absolute;
  top: 0; left: 0; width: 100%; height: 100%;
  background: linear-gradient(to right, rgba(26,26,26,0.95) 0%, rgba(26,26,26,0.5) 100%);
  z-index: 0;
}

/* Stats */
.stat-card {
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.stat-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 20px rgba(0,0,0,0.08) !important;
}

/* Mission & Vision Cards */
.about-card {
  background: #f8f9fa;
  border: 1px solid var(--pr-border);
  transition: all 0.3s ease;
}
.about-card:hover {
  background: #fff;
  border-color: var(--pr-primary);
  box-shadow: 0 15px 30px rgba(0,0,0,0.08);
  transform: translateY(-5px);
}
.about-icon-wrap {
  width: 60px;
  height: 60px;
  background: var(--pr-secondary);
  color: var(--pr-primary);
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 12px;
  font-size: 1.5rem;
  transition: all 0.3s ease;
}
.about-card:hover .about-icon-wrap {
  background: var(--pr-primary);
  color: var(--pr-secondary);
  transform: scale(1.1);
}

/* Premium FAQ */
.premium-faq .accordion-item {
  border: 1px solid var(--pr-border);
  border-radius: 12px !important;
  overflow: hidden;
  background: #fff;
  transition: all 0.3s ease;
}
.premium-faq .accordion-item:hover {
  border-color: rgba(235, 175, 75, 0.5);
  box-shadow: 0 5px 15px rgba(0,0,0,0.05);
}
.premium-faq .accordion-button {
  font-weight: 600;
  font-size: 1.1rem;
  padding: 1.25rem 1.5rem;
  color: var(--pr-secondary);
  background: #fff;
  box-shadow: none;
}
.premium-faq .accordion-button:not(.collapsed) {
  color: var(--pr-primary);
  background: var(--pr-secondary);
}
.premium-faq .accordion-button::after {
  filter: grayscale(1);
  transition: transform 0.3s ease;
}
.premium-faq .accordion-button:not(.collapsed)::after {
  filter: brightness(0) invert(1) sepia(100%) saturate(500%) hue-rotate(1deg) brightness(1.1);
}
.premium-faq .accordion-body {
  padding: 1.5rem;
  color: #64748b;
  font-size: 1.05rem;
  line-height: 1.7;
  border-top: 1px solid var(--pr-border);
}
</style>
