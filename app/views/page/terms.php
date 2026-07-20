<?php
/**
 * Terms and Conditions (Premium Reassuring View)
 */
?>
<style>
.legal-hero {
    padding: 100px 0 80px;
    background: linear-gradient(135deg, #0f0f11 0%, #1a1a1c 100%);
    color: white;
    text-align: center;
    position: relative;
    overflow: hidden;
}
.legal-hero::before {
    content: '';
    position: absolute;
    top: -50%; left: -50%; width: 200%; height: 200%;
    background: radial-gradient(circle, rgba(229,175,83,0.05) 0%, rgba(0,0,0,0) 70%);
    pointer-events: none;
}
.legal-icon {
    font-size: 3rem;
    color: var(--pr-primary);
    margin-bottom: 20px;
}
.legal-title {
    font-size: 3rem;
    font-weight: 900;
    letter-spacing: -1px;
    margin-bottom: 15px;
}
.legal-subtitle {
    font-size: 1.15rem;
    color: rgba(255,255,255,0.7);
    max-width: 600px;
    margin: 0 auto;
}

.legal-content-wrapper {
    background: #fafafa;
    padding: 80px 0;
}
.legal-card {
    background: #fff;
    border-radius: 24px;
    padding: 50px;
    box-shadow: 0 15px 40px rgba(0,0,0,0.04);
    border: 1px solid rgba(0,0,0,0.02);
}
.legal-card h1, .legal-card h2, .legal-card h3 {
    font-weight: 800;
    color: #111;
    margin-top: 40px;
    margin-bottom: 20px;
}
.legal-card h1 { font-size: 2rem; margin-top: 0; }
.legal-card h2 { font-size: 1.5rem; }
.legal-card p, .legal-card li {
    font-size: 1.05rem;
    line-height: 1.8;
    color: #555;
    margin-bottom: 15px;
}
.legal-card ul {
    padding-left: 20px;
    margin-bottom: 30px;
}
.trust-badge {
    background: rgba(229,175,83,0.1);
    color: var(--pr-primary-dark);
    padding: 20px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    gap: 15px;
    margin-top: 40px;
    font-weight: 600;
}
.trust-badge i { font-size: 1.5rem; }

.legal-sidebar {
    position: sticky;
    top: 100px;
}
.contact-legal-card {
    background: #fff;
    border-radius: 20px;
    padding: 30px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    border: 1px solid #eaeaea;
    text-align: center;
}
</style>

<div class="legal-hero anim-fade-up">
    <div class="container px-3 position-relative" style="z-index: 10;">
        <i class="fas fa-file-signature legal-icon"></i>
        <h1 class="legal-title">Terms & Conditions</h1>
        <p class="legal-subtitle">Please read these terms carefully. They outline the rules, responsibilities, and guidelines for using the PropertyRubix platform.</p>
    </div>
</div>

<div class="legal-content-wrapper">
    <div class="container px-3">
        <div class="row justify-content-center g-5">
            <div class="col-lg-8 anim-fade-up" style="animation-delay: 0.1s;">
                <div class="legal-card">
                    <div class="d-flex justify-content-between align-items-center mb-5 pb-4 border-bottom">
                        <span class="text-muted fw-bold text-uppercase" style="font-size: 0.85rem; letter-spacing: 1px;">Effective Date: <?php echo date('F d, Y'); ?></span>
                        <a href="javascript:window.print()" class="text-muted text-decoration-none hover-primary"><i class="fas fa-print"></i> Print</a>
                    </div>
                    
                    <div class="page-content">
                        <?= $page['body'] ?>
                    </div>

                    <div class="trust-badge">
                        <i class="fas fa-handshake"></i>
                        <div>
                            <div>Fair Use & Transparency</div>
                            <div class="text-muted fw-normal" style="font-size: 0.9rem;">We believe in transparent, honest, and mutually beneficial relationships with our users.</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 anim-fade-up" style="animation-delay: 0.2s;">
                <div class="legal-sidebar">
                    <div class="contact-legal-card">
                        <div style="width:60px; height:60px; background:rgba(229,175,83,0.1); color:var(--pr-primary); border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:1.5rem; margin: 0 auto 20px;">
                            <i class="fas fa-balance-scale"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Legal Inquiries?</h4>
                        <p class="text-muted mb-4" style="font-size: 0.95rem;">If you require clarification on any of our terms or wish to report a violation, please contact our legal department.</p>
                        <a href="mailto:legal@propertyrubix.com" class="btn btn-outline-primary w-100 fw-bold rounded-pill">Contact Legal Team</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
