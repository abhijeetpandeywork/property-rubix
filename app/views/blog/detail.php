<?php /** Blog Detail View */ ?>
<!-- Cinematic Header -->
<div class="position-relative" style="height: 60vh; min-height: 400px; max-height: 600px; overflow: hidden; margin-top: -1px;">
  <img src="<?= $post['cover_image'] ? upload($post['cover_image']) : 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=1920&q=80' ?>" 
       alt="<?= e($post['title']) ?>" class="position-absolute w-100 h-100 object-fit-cover" style="top:0; left:0; z-index:0; filter: brightness(0.6);">
  <div class="position-absolute w-100 h-100" style="top:0; left:0; background: linear-gradient(to top, var(--pr-secondary) 0%, transparent 100%); z-index:1;"></div>
  
  <div class="container-fluid px-3 px-md-5 position-relative z-2 h-100 d-flex flex-column justify-content-end pb-5">
    <div class="row justify-content-center">
      <div class="col-lg-9 col-xl-8 text-center text-white">
        
        <nav aria-label="breadcrumb" class="mb-4 d-flex justify-content-center">
          <ol class="breadcrumb mb-0" style="background: rgba(255,255,255,0.1); padding: 8px 16px; border-radius: 50px; backdrop-filter: blur(10px);">
            <li class="breadcrumb-item"><a href="<?= PUBLIC_URL ?>" class="text-white text-decoration-none opacity-75">Home</a></li>
            <li class="breadcrumb-item"><a href="<?= PUBLIC_URL ?>blog" class="text-white text-decoration-none opacity-75">Blog</a></li>
            <li class="breadcrumb-item active text-white" aria-current="page"><?= e($post['category_name']) ?></li>
          </ol>
        </nav>

        <?php if ($post['category_name']): ?>
          <a href="<?= PUBLIC_URL ?>blog?cat=<?= e($post['category_slug']) ?>" class="badge mb-3 px-3 py-2 text-uppercase fw-bold" style="background:var(--pr-primary); color:var(--pr-secondary); letter-spacing:1px; font-size:0.8rem; text-decoration:none;">
            <?= e($post['category_name']) ?>
          </a>
        <?php endif; ?>
        
        <h1 class="display-4 fw-bold mb-4" style="line-height:1.2;"><?= e($post['title']) ?></h1>
        
        <div class="d-flex align-items-center justify-content-center gap-4" style="font-size:1rem; color: var(--pr-primary);">
          <span class="d-flex align-items-center"><i class="fas fa-user-circle fs-4 me-2"></i> <?= e($post['author']) ?></span>
          <span class="d-flex align-items-center"><i class="fas fa-calendar-alt fs-5 me-2"></i> <?= date('M j, Y', strtotime($post['published_at'])) ?></span>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="section py-5 bg-white">
  <div class="container-fluid px-3 px-md-5">
    <div class="row justify-content-center">
      
      <!-- Article Content -->
      <div class="col-lg-8 col-xl-7">
        
        <!-- Post body -->
        <div class="blog-content" style="font-size: 1.15rem; line-height: 2; color: #334155; font-family: 'Merriweather', Georgia, serif;">
          <?= $post['body'] ?>
        </div>

        <!-- Share -->
        <div class="mt-5 pt-4 border-top">
          <h4 class="fw-bold mb-3" style="font-size: 1.1rem; text-transform: uppercase; letter-spacing: 1px;">Share this article</h4>
          <?php
          $shareUrl   = urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
          $shareTitle = urlencode($post['title']);
          ?>
          <div class="d-flex gap-2 flex-wrap">
            <a href="https://www.facebook.com/sharer/sharer.php?u=<?= $shareUrl ?>" target="_blank" class="btn text-white d-flex align-items-center rounded-pill px-4" style="background:#1877f2;"><i class="fab fa-facebook-f me-2"></i> Facebook</a>
            <a href="https://twitter.com/intent/tweet?url=<?= $shareUrl ?>&text=<?= $shareTitle ?>" target="_blank" class="btn text-white d-flex align-items-center rounded-pill px-4" style="background:#000;"><i class="fab fa-x-twitter me-2"></i> Twitter</a>
            <a href="https://wa.me/?text=<?= $shareTitle ?>%20<?= $shareUrl ?>" target="_blank" class="btn text-white d-flex align-items-center rounded-pill px-4" style="background:#25d366;"><i class="fab fa-whatsapp me-2"></i> WhatsApp</a>
            <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?= $shareUrl ?>&title=<?= $shareTitle ?>" target="_blank" class="btn text-white d-flex align-items-center rounded-pill px-4" style="background:#0a66c2;"><i class="fab fa-linkedin-in me-2"></i> LinkedIn</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Related Posts -->
<?php if ($related): ?>
<div class="section py-5" style="background:#f8f9fa; border-top: 1px solid var(--pr-border);">
  <div class="container-fluid px-3 px-md-5">
    <div class="text-center mb-5">
      <h2 class="fw-bold h2 mb-3">Related Articles</h2>
      <div style="width:60px; height:4px; background:var(--pr-primary); margin: 0 auto;"></div>
    </div>
    
    <div class="row g-4 justify-content-center">
      <?php foreach ($related as $rp): ?>
      <div class="col-md-6 col-lg-4">
        <article class="blog-card h-100 shadow-sm border-0 rounded-4 overflow-hidden" style="background:#fff; transition: transform 0.3s, box-shadow 0.3s;">
          <div class="blog-card-img position-relative" style="height:200px; overflow:hidden;">
            <img src="<?= $rp['cover_image'] ? upload($rp['cover_image']) : 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=600&q=70' ?>"
                 alt="<?= e($rp['title']) ?>" class="w-100 h-100 object-fit-cover" style="transition:transform 0.5s;">
            <?php if ($rp['category_name']): ?>
              <span class="position-absolute badge px-3 py-2" style="bottom:15px; left:15px; background:var(--pr-primary); color:var(--pr-secondary); font-size:0.7rem; font-weight:700; text-transform:uppercase; letter-spacing:1px;"><?= e($rp['category_name']) ?></span>
            <?php endif; ?>
          </div>
          <div class="p-4 d-flex flex-column" style="height: calc(100% - 200px);">
            <h3 class="fw-bold mb-3" style="font-size:1.15rem; line-height:1.4;">
              <a href="<?= PUBLIC_URL ?>blog/<?= e($rp['slug']) ?>" class="text-dark text-decoration-none blog-link-hover"><?= e($rp['title']) ?></a>
            </h3>
            <div class="d-flex justify-content-between align-items-center mt-auto pt-3 border-top text-muted" style="font-size:0.85rem;">
              <span><i class="fas fa-user me-1"></i> <?= e($rp['author']) ?></span>
              <span><i class="far fa-clock me-1"></i> <?= e(View::timeAgo($rp['published_at'])) ?></span>
            </div>
          </div>
        </article>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>
<?php endif; ?>

<style>
.blog-content p {
  margin-bottom: 1.5rem;
}
.blog-content h2, .blog-content h3 {
  color: var(--pr-secondary);
  font-family: inherit;
  font-weight: 700;
  margin-top: 2.5rem;
  margin-bottom: 1.25rem;
}
.blog-card:hover {
  transform: translateY(-8px);
  box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
}
.blog-card:hover .blog-card-img img {
  transform: scale(1.1);
}
.blog-link-hover:hover {
  color: var(--pr-primary) !important;
}
</style>
