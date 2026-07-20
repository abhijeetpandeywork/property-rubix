<?php
/**
 * Advertise With Us Page (Premium Marketing View)
 */
?>

<style>
/* --- Advanced Psychological UI Styling for Ads --- */
.adv-hero {
    position: relative;
    padding: 120px 0 140px;
    background: #0d0d0f;
    color: white;
    overflow: hidden;
}
.adv-hero::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    background: url('https://images.unsplash.com/photo-1560518883-ce09059eeffa?q=80&w=1973&auto=format&fit=crop') center/cover;
    opacity: 0.15;
    z-index: 1;
}
.adv-hero-content {
    position: relative;
    z-index: 2;
    max-width: 800px;
    margin: 0 auto;
}
.adv-title {
    font-size: clamp(2.5rem, 5vw, 4rem);
    font-weight: 900;
    line-height: 1.15;
    margin-bottom: 25px;
    letter-spacing: -1px;
}
.adv-subtitle {
    font-size: 1.25rem;
    color: rgba(255,255,255,0.75);
    font-weight: 400;
    line-height: 1.6;
    margin-bottom: 40px;
}

/* Stats Section */
.adv-stats-wrapper {
    position: relative;
    z-index: 3;
    margin-top: -60px;
}
.adv-stat-card {
    background: rgba(25, 25, 28, 0.95);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255,255,255,0.05);
    border-radius: 20px;
    padding: 30px 20px;
    text-align: center;
    box-shadow: 0 20px 40px rgba(0,0,0,0.2);
    transition: transform 0.3s ease;
    height: 100%;
}
.adv-stat-card:hover {
    transform: translateY(-8px);
    border-color: rgba(229,175,83,0.3);
}
.adv-stat-num {
    font-size: 2.5rem;
    font-weight: 900;
    color: var(--pr-primary);
    margin-bottom: 5px;
}
.adv-stat-label {
    font-size: 0.95rem;
    color: rgba(255,255,255,0.7);
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 1px;
}

/* Bento Features Grid */
.adv-features {
    padding: 100px 0;
    background: #fafafa;
}
.adv-feature-card {
    background: #fff;
    border-radius: 24px;
    padding: 40px 30px;
    border: 1px solid rgba(0,0,0,0.04);
    box-shadow: 0 10px 30px rgba(0,0,0,0.03);
    height: 100%;
    transition: all 0.3s ease;
}
.adv-feature-card:hover {
    box-shadow: 0 20px 50px rgba(0,0,0,0.08);
    transform: translateY(-5px);
}
.adv-icon-box {
    width: 60px;
    height: 60px;
    background: rgba(229,175,83,0.1);
    color: var(--pr-primary);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.6rem;
    margin-bottom: 25px;
}
.adv-feature-card h3 {
    font-size: 1.4rem;
    font-weight: 800;
    margin-bottom: 15px;
    color: #111;
}
.adv-feature-card p {
    color: #666;
    line-height: 1.6;
    margin-bottom: 0;
}

/* Lead Gen Form */
.adv-contact-section {
    padding: 100px 0;
    background: #fff;
}
.adv-glass-form {
    background: #fff;
    border-radius: 24px;
    padding: 50px;
    box-shadow: 0 25px 60px rgba(0,0,0,0.08);
    border: 1px solid rgba(0,0,0,0.04);
}
.adv-input {
    background: #f8f9fa;
    border: 1px solid #eaeaea;
    border-radius: 12px;
    padding: 15px 20px;
    font-size: 1.05rem;
    font-weight: 500;
}
.adv-input:focus {
    background: #fff;
    border-color: var(--pr-primary);
    box-shadow: 0 0 0 4px rgba(229,175,83,0.15);
}
</style>

<!-- Hero Section -->
<section class="adv-hero text-center">
    <div class="container px-3">
        <div class="adv-hero-content anim-fade-up">
            <span style="display:inline-block; padding: 6px 16px; background: rgba(229,175,83,0.15); color: var(--pr-primary); border-radius: 50px; font-weight: 700; font-size: 0.85rem; letter-spacing: 1px; margin-bottom: 20px; text-transform: uppercase;">Partner With Us</span>
            <h1 class="adv-title">Amplify Your Reach. <br><span style="color: var(--pr-primary);">Connect with Millions.</span></h1>
            <p class="adv-subtitle">PropertyRubix is India’s fastest-growing luxury real estate platform. Showcase your premium projects, generate high-intent leads, and dominate the market with our tailored advertising solutions.</p>
            <a href="#contact" class="btn btn-primary btn-lg fw-bold px-5 py-3 shadow-lg" style="border-radius: 50px; font-size: 1.1rem;">Start Advertising Today</a>
        </div>
    </div>
</section>

<!-- Stats Section -->
<div class="adv-stats-wrapper">
    <div class="container px-3">
        <div class="row g-4 justify-content-center">
            <div class="col-md-4 col-sm-6 anim-fade-up" style="animation-delay: 0.1s;">
                <div class="adv-stat-card">
                    <div class="adv-stat-num">2.5M+</div>
                    <div class="adv-stat-label">Monthly Active Buyers</div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6 anim-fade-up" style="animation-delay: 0.2s;">
                <div class="adv-stat-card">
                    <div class="adv-stat-num">50k+</div>
                    <div class="adv-stat-label">Verified Premium Leads</div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6 anim-fade-up" style="animation-delay: 0.3s;">
                <div class="adv-stat-card">
                    <div class="adv-stat-num">4.5x</div>
                    <div class="adv-stat-label">Higher Conversion Rate</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Features Section -->
<section class="adv-features">
    <div class="container px-3">
        <div class="text-center mb-5 anim-fade-up">
            <h2 class="fw-bold mb-3" style="font-size: 2.5rem; letter-spacing: -0.5px;">Premium Advertising Solutions</h2>
            <p class="text-muted mx-auto" style="max-width: 600px; font-size: 1.1rem;">We offer highly targeted, psychologically optimized advertising formats designed specifically for the luxury real estate market.</p>
        </div>
        
        <div class="row g-4">
            <div class="col-lg-6 anim-fade-up">
                <div class="adv-feature-card">
                    <div class="adv-icon-box"><i class="fas fa-crown"></i></div>
                    <h3>Featured Project Spotlight</h3>
                    <p>Place your project at the very top of search results and location pages. Capture 80% of the initial traffic with our exclusive featured slots, complete with immersive 100vh hero galleries.</p>
                </div>
            </div>
            <div class="col-lg-6 anim-fade-up" style="animation-delay: 0.1s;">
                <div class="adv-feature-card">
                    <div class="adv-icon-box"><i class="fas fa-ad"></i></div>
                    <h3>High-Impact Display Banners</h3>
                    <p>Dominate the visual real estate of our platform with beautiful, edge-to-edge display banners on key landing pages. Perfect for brand awareness and major new project launches.</p>
                </div>
            </div>
            <div class="col-lg-6 anim-fade-up" style="animation-delay: 0.2s;">
                <div class="adv-feature-card">
                    <div class="adv-icon-box"><i class="fas fa-envelope-open-text"></i></div>
                    <h3>Targeted Email Campaigns</h3>
                    <p>Reach high-net-worth individuals directly in their inbox. Our curated newsletter boasts a 35% open rate, ensuring your project is seen by serious buyers actively looking to invest.</p>
                </div>
            </div>
            <div class="col-lg-6 anim-fade-up" style="animation-delay: 0.3s;">
                <div class="adv-feature-card">
                    <div class="adv-icon-box"><i class="fas fa-bullseye"></i></div>
                    <h3>Behavioral Re-targeting</h3>
                    <p>We use advanced psychology and machine learning to re-target users who showed interest in similar properties, ensuring your advertising budget is spent on users with the highest intent.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact/Lead Gen Section -->
<section id="contact" class="adv-contact-section">
    <div class="container px-3">
        <div class="row align-items-center g-5">
            <div class="col-lg-5 anim-fade-up">
                <h2 class="fw-bold mb-4" style="font-size: 3rem; line-height: 1.1; letter-spacing:-1px;">Ready to grow your sales pipeline?</h2>
                <p class="text-muted mb-4" style="font-size: 1.15rem; line-height: 1.7;">Get in touch with our dedicated advertising specialists. We will analyze your goals and propose a custom marketing strategy that maximizes your ROI.</p>
                
                <div class="d-flex align-items-center mb-4 p-4 bg-light rounded-4">
                    <div class="me-3 text-primary fs-3"><i class="fas fa-phone-alt" style="color: var(--pr-primary)"></i></div>
                    <div>
                        <div class="text-muted fw-bold text-uppercase" style="font-size: 0.8rem;">Direct Sales Line</div>
                        <div class="fw-bold fs-4 text-dark"><?= e(getSetting('phone_primary', '+91 98765 43210')) ?></div>
                    </div>
                </div>
                
                <div class="d-flex align-items-center p-4 bg-light rounded-4">
                    <div class="me-3 text-primary fs-3"><i class="fas fa-envelope" style="color: var(--pr-primary)"></i></div>
                    <div>
                        <div class="text-muted fw-bold text-uppercase" style="font-size: 0.8rem;">Email Us</div>
                        <div class="fw-bold fs-5 text-dark"><?= e(getSetting('email_primary', 'ads@propertyrubix.com')) ?></div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-7 anim-fade-up" style="animation-delay: 0.2s;">
                <div class="adv-glass-form">
                    <h3 class="fw-bold mb-4">Request a Media Kit</h3>
                    <form action="#" method="POST" onsubmit="event.preventDefault(); alert('Thank you! Our advertising team will contact you shortly.');">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small">Full Name</label>
                                <input type="text" class="form-control adv-input" placeholder="John Doe" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small">Company / Developer Name</label>
                                <input type="text" class="form-control adv-input" placeholder="XYZ Real Estate" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small">Email Address</label>
                                <input type="email" class="form-control adv-input" placeholder="john@example.com" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small">Phone Number</label>
                                <input type="tel" class="form-control adv-input" placeholder="+91 98765 43210" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold text-muted small">What are your advertising goals?</label>
                                <textarea class="form-control adv-input" rows="4" placeholder="Tell us about the projects you want to promote..."></textarea>
                            </div>
                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-primary w-100 py-3 fw-bold fs-5" style="border-radius: 12px; box-shadow: 0 10px 25px rgba(229,175,83,0.3);">Submit Inquiry</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
