<?php /** Generic CMS page view (About, Privacy, Terms, Advertise) */ ?>
<div class="breadcrumb-section">
  <div class="container-fluid px-3 px-md-4">
    <nav aria-label="breadcrumb"><ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?= PUBLIC_URL ?>">Home</a></li>
      <li class="breadcrumb-item active"><?= e($page['title']) ?></li>
    </ol></nav>
  </div>
</div>

<div class="section">
  <div class="container-fluid px-3 px-md-4">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="divider-bar"></div>
        <h1 class="section-title mb-4"><?= e($page['title']) ?></h1>
        <div class="page-content" style="line-height:1.85;color:var(--pr-text)">
          <?= $page['body'] ?>
        </div>

        <?php if ($faqs): ?>
        <div class="mt-5">
          <h2 class="h4 fw-700 mb-4">Frequently Asked Questions</h2>
          <div class="accordion" id="faqAccordion">
            <?php foreach ($faqs as $i => $faq): ?>
            <div class="accordion-item border-0 mb-2">
              <h3 class="accordion-header" id="faqHead<?= $i ?>">
                <button class="accordion-button <?= $i > 0 ? 'collapsed' : '' ?> rounded-3 fw-600"
                        type="button" data-bs-toggle="collapse"
                        data-bs-target="#faqBody<?= $i ?>">
                  <?= e($faq['question']) ?>
                </button>
              </h3>
              <div id="faqBody<?= $i ?>" class="accordion-collapse collapse <?= $i === 0 ? 'show' : '' ?>">
                <div class="accordion-body text-muted"><?= e($faq['answer']) ?></div>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
