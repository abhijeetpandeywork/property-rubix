<?php /** Contact Us Page */ ?>
<!-- Hero Section -->
<div class="position-relative" style="height: 50vh; min-height: 350px; max-height: 500px; overflow: hidden; margin-top: -1px;">
  <img src="https://images.unsplash.com/photo-1512453979798-5ea266f8880c?w=1920&q=80" 
       alt="Global Headquarters" class="position-absolute w-100 h-100 object-fit-cover" style="top:0; left:0; z-index:0; filter: brightness(0.5);">
  <div class="position-absolute w-100 h-100" style="top:0; left:0; background: linear-gradient(to top, var(--pr-secondary) 0%, transparent 100%); z-index:1;"></div>
  
  <div class="container-fluid px-3 px-md-5 position-relative z-2 h-100 d-flex flex-column justify-content-center pb-4 text-center text-white">
    <nav aria-label="breadcrumb" class="mb-3 d-flex justify-content-center">
      <ol class="breadcrumb mb-0" style="background: rgba(255,255,255,0.1); padding: 8px 16px; border-radius: 50px; backdrop-filter: blur(10px);">
        <li class="breadcrumb-item"><a href="<?= PUBLIC_URL ?>" class="text-white text-decoration-none opacity-75">Home</a></li>
        <li class="breadcrumb-item active text-white" aria-current="page">Contact Us</li>
      </ol>
    </nav>
    <h1 class="display-4 fw-bold mb-3">Connect With Our <span style="color:var(--pr-primary)">Experts</span></h1>
    <p class="fs-5 opacity-75 mx-auto" style="max-width: 600px;">Our global advisory team is ready to help you navigate your real estate journey with unparalleled market intelligence.</p>
  </div>
</div>

<div class="section py-5 bg-white">
  <div class="container-fluid px-3 px-md-5">
    
    <?php
    $phone1 = getSetting('phone_primary','+91 98765 43210');
    $phone2 = getSetting('phone_secondary','+91 91234 56789');
    $email1 = getSetting('email_primary','info@propertyrubix.com');
    $wa     = getSetting('whatsapp_number','919876543210');
    $addr1  = getSetting('address_1','Noida, UP');
    $addr2  = getSetting('address_2','Gurugram, HR');
    $fb = getSetting('social_facebook','#');
    $tw = getSetting('social_twitter','#');
    $yt = getSetting('social_youtube','#');
    ?>

    <div class="row g-5">
      <!-- Contact Info Cards -->
      <div class="col-lg-5">
        <div class="pe-lg-4">
          <h2 class="fw-bold h2 mb-4">Our Global Offices</h2>
          <div style="width:60px; height:4px; background:var(--pr-primary); margin-bottom: 2rem;"></div>
          <p class="text-muted mb-5" style="font-size: 1.1rem; line-height: 1.7;">
            Whether you are looking to invest in a luxury penthouse or find the perfect family home, our dedicated agents are available to assist you across multiple time zones.
          </p>

          <div class="d-flex flex-column gap-4">
            
            <a href="tel:<?= e(preg_replace('/[^+\d]/','', $phone1)) ?>" class="contact-info-card d-flex align-items-center gap-4 p-4 rounded-4 text-decoration-none">
              <div class="contact-icon">
                <i class="fas fa-phone-alt"></i>
              </div>
              <div>
                <p class="small text-uppercase fw-bold text-muted mb-1" style="letter-spacing: 1px;">Direct Line</p>
                <h4 class="fw-bold mb-0 text-dark"><?= e($phone1) ?></h4>
              </div>
            </a>

            <a href="https://wa.me/<?= e($wa) ?>" target="_blank" class="contact-info-card d-flex align-items-center gap-4 p-4 rounded-4 text-decoration-none">
              <div class="contact-icon" style="color: #25d366; background: rgba(37,211,102,0.1);">
                <i class="fab fa-whatsapp"></i>
              </div>
              <div>
                <p class="small text-uppercase fw-bold text-muted mb-1" style="letter-spacing: 1px;">WhatsApp</p>
                <h4 class="fw-bold mb-0 text-dark">+<?= e($wa) ?></h4>
              </div>
            </a>

            <a href="mailto:<?= e($email1) ?>" class="contact-info-card d-flex align-items-center gap-4 p-4 rounded-4 text-decoration-none">
              <div class="contact-icon">
                <i class="fas fa-envelope"></i>
              </div>
              <div>
                <p class="small text-uppercase fw-bold text-muted mb-1" style="letter-spacing: 1px;">Email Us</p>
                <h4 class="fw-bold mb-0 text-dark"><?= e($email1) ?></h4>
              </div>
            </a>

            <div class="contact-info-card d-flex align-items-center gap-4 p-4 rounded-4">
              <div class="contact-icon">
                <i class="fas fa-map-marker-alt"></i>
              </div>
              <div>
                <p class="small text-uppercase fw-bold text-muted mb-1" style="letter-spacing: 1px;">Headquarters</p>
                <h5 class="fw-bold mb-1 text-dark"><?= e($addr1) ?></h5>
                <p class="text-muted mb-0"><?= e($addr2) ?></p>
              </div>
            </div>

          </div>

          <!-- Social -->
          <div class="mt-5">
            <p class="fw-bold text-uppercase text-muted mb-3" style="letter-spacing: 1px;">Follow Our Journey</p>
            <div class="d-flex gap-3">
              <a href="<?= e($fb) ?>" target="_blank" class="social-btn facebook"><i class="fab fa-facebook-f"></i></a>
              <a href="<?= e($tw) ?>" target="_blank" class="social-btn twitter"><i class="fab fa-x-twitter"></i></a>
              <a href="<?= e($yt) ?>" target="_blank" class="social-btn youtube"><i class="fab fa-youtube"></i></a>
            </div>
          </div>

        </div>
      </div>

      <!-- Contact form -->
      <div class="col-lg-7">
        <div class="card border-0 rounded-4 p-4 p-md-5 h-100 contact-form-card position-relative overflow-hidden" style="background:#fff; z-index:2;">
          <div class="position-absolute" style="top:0; left:0; width:100%; height:5px; background: var(--pr-primary);"></div>
          
          <h3 class="fw-bold mb-2 h3">Send an Inquiry</h3>
          <p class="text-muted mb-4">Please fill out the form below and one of our agents will contact you shortly.</p>
          
          <form id="contactPageForm" novalidate>
            <?= csrfField() ?>
            <input type="text" name="hp_name" style="display:none" tabindex="-1">
            <div class="row g-4">
              
              <div class="col-md-6">
                <div class="form-floating">
                  <input type="text" class="form-control custom-input" name="name" id="nameInput" placeholder="John Doe" required>
                  <label for="nameInput">Full Name *</label>
                </div>
              </div>
              
              <div class="col-md-6">
                <div class="form-floating">
                  <input type="tel" class="form-control custom-input" name="phone" id="phoneInput" placeholder="+1 234 567 8900" required>
                  <label for="phoneInput">Phone Number *</label>
                </div>
              </div>
              
              <div class="col-12">
                <div class="form-floating">
                  <input type="email" class="form-control custom-input" name="email" id="emailInput" placeholder="john@example.com">
                  <label for="emailInput">Email Address</label>
                </div>
              </div>
              
              <div class="col-12">
                <div class="form-floating">
                  <textarea class="form-control custom-input" name="message" id="messageInput" placeholder="Tell us how we can help you…" style="height: 150px"></textarea>
                  <label for="messageInput">Your Message</label>
                </div>
              </div>
              
              <div class="col-12 mt-4">
                <button type="submit" class="btn w-100 fw-bold py-3 text-uppercase send-btn" style="letter-spacing: 1px;">
                  <i class="fas fa-paper-plane me-2"></i> Send Message
                </button>
              </div>
              
              <div id="contactResult" class="col-12 d-none mt-3 rounded-3 border-0"></div>
            </div>
          </form>
        </div>
      </div>

    </div>
  </div>
</div>

<!-- Map Section -->
<div class="section-sm p-0 bg-white">
  <div class="container-fluid px-0">
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d14008.114827184497!2d77.32095495!3d28.5815617!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x390ce5a43173357b%3A0x37ffce30c87205d5!2sNoida%2C%20Uttar%20Pradesh!5e0!3m2!1sen!2sin!4v1700000000000!5m2!1sen!2sin" 
            width="100%" height="450" style="border:0; filter: grayscale(100%) contrast(1.2) brightness(0.8);" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
  </div>
</div>

<!-- FAQs -->
<?php if ($faqs): ?>
<div class="section py-5" style="background:#f8f9fa;">
  <div class="container-fluid px-3 px-md-5">
    <div class="text-center mb-5">
      <h2 class="fw-bold h2 mb-3">Frequently Asked Questions</h2>
      <div style="width:60px; height:4px; background:var(--pr-primary); margin: 0 auto;"></div>
    </div>
    <div class="row justify-content-center">
      <div class="col-lg-9">
        <div class="accordion premium-faq" id="faqAcc">
          <?php foreach (array_slice($faqs,0,6) as $i=>$f): ?>
          <div class="accordion-item mb-3">
            <h3 class="accordion-header" id="faqHead<?= $i ?>">
              <button class="accordion-button <?= $i>0?'collapsed':'' ?>" type="button"
                      data-bs-toggle="collapse" data-bs-target="#cFaq<?= $i ?>">
                <?= e($f['question']) ?>
              </button>
            </h3>
            <div id="cFaq<?= $i ?>" class="accordion-collapse collapse <?= $i===0?'show':'' ?>" data-bs-parent="#faqAcc">
              <div class="accordion-body">
                <?= e($f['answer']) ?>
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
/* Info Cards */
.contact-info-card {
  background: #f8f9fa;
  border: 1px solid var(--pr-border);
  transition: all 0.3s ease;
}
.contact-info-card:hover {
  background: #fff;
  border-color: var(--pr-primary);
  box-shadow: 0 10px 25px rgba(0,0,0,0.05);
  transform: translateX(5px);
}
.contact-icon {
  width: 50px;
  height: 50px;
  background: rgba(235, 175, 75, 0.1);
  color: var(--pr-primary);
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.25rem;
  transition: all 0.3s ease;
}
.contact-info-card:hover .contact-icon {
  background: var(--pr-primary);
  color: var(--pr-secondary);
  transform: scale(1.1);
}

/* Social Buttons */
.social-btn {
  width: 44px; height: 44px;
  border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  color: white; font-size: 1.1rem;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.social-btn.facebook { background: #1877f2; }
.social-btn.twitter { background: #000; }
.social-btn.youtube { background: #ff0000; }
.social-btn:hover {
  transform: translateY(-3px);
  box-shadow: 0 5px 15px rgba(0,0,0,0.2);
  color: white;
}

/* Form Styles */
.contact-form-card {
  box-shadow: 0 20px 50px rgba(0,0,0,0.08) !important;
}
.custom-input {
  background: #f8f9fa;
  border: 1px solid transparent;
  border-bottom: 2px solid var(--pr-border);
  border-radius: 8px 8px 0 0;
  box-shadow: none !important;
}
.custom-input:focus {
  background: #fff;
  border-color: transparent;
  border-bottom-color: var(--pr-primary);
}
.form-floating label {
  color: #64748b;
}
.send-btn {
  background: var(--pr-primary);
  color: var(--pr-secondary);
  transition: all 0.3s ease;
}
.send-btn:hover {
  background: var(--pr-secondary);
  color: var(--pr-primary);
}

/* Premium FAQ (Reused from About Us) */
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

<?php
$ajaxUrl = PUBLIC_URL . 'ajax/submit-enquiry';
ob_start();
?>
<script>
document.getElementById("contactPageForm")?.addEventListener("submit", async function(e) {
  e.preventDefault();
  if (!this.checkValidity()) { this.classList.add("was-validated"); return; }
  const btn = this.querySelector("button[type=submit]");
  btn.disabled = true;
  btn.innerHTML = `<i class="fas fa-spinner fa-spin me-2"></i> Sending...`;
  const data = new FormData(this);
  const result = document.getElementById("contactResult");
  try {
    const res  = await fetch("<?= $ajaxUrl ?>", { method:"POST", body:data, headers:{"X-Requested-With":"XMLHttpRequest"} });
    const json = await res.json();
    result.classList.remove("d-none","alert-success","alert-danger");
    result.classList.add("alert", json.success ? "alert-success" : "alert-danger", "p-3");
    result.innerHTML = `<i class="fas ${json.success ? "fa-check-circle text-success" : "fa-exclamation-circle text-danger"} me-2"></i> ${json.message}`;
    if (json.success) { this.reset(); this.classList.remove("was-validated"); }
  } catch { 
    result.classList.remove("d-none"); 
    result.classList.add("alert","alert-danger", "p-3"); 
    result.innerHTML = `<i class="fas fa-exclamation-circle text-danger me-2"></i> Failed. Please try again.`; 
  }
  finally { 
    btn.disabled = false;
    btn.innerHTML = `Send Message <i class="fas fa-paper-plane ms-2"></i>`;
  }
});
</script>
<?php
$extraScripts = ob_get_clean();
?>
