<?php /** Blog Listing */ 
  $featuredPost = !empty($posts) && empty($_GET['page']) ? array_shift($posts) : null;
?>
<div class="breadcrumb-section" style="background:var(--pr-secondary); border-bottom:1px solid rgba(255,255,255,0.05);">
  <div class="container-fluid px-3 px-md-4">
    <nav aria-label="breadcrumb"><ol class="breadcrumb mb-0">
      <li class="breadcrumb-item"><a href="<?= PUBLIC_URL ?>" style="color:var(--pr-primary)">Home</a></li>
      <li class="breadcrumb-item active text-white">Blog</li>
    </ol></nav>
  </div>
</div>

<div class="section pt-5">
  <div class="container-fluid px-3 px-md-5">
    
    <!-- Page Header -->
    <div class="row mb-5">
      <div class="col-12 text-center">
        <h1 class="fw-800 display-4 mb-3">Real Estate <span style="color:var(--pr-primary)">Insights</span></h1>
        <p class="text-muted fs-5 mx-auto" style="max-width: 600px;">Stay updated with the latest market trends, investment guides, and expert advice.</p>
        <div style="width:60px; height:4px; background:var(--pr-primary); margin: 1.5rem auto 0;"></div>
      </div>
    </div>

    <div class="row g-5">
      <!-- Main Content -->
      <div class="col-lg-8 col-xl-9">
        
        <?php if ($featuredPost): ?>
        <!-- Featured Post Hero -->
        <div class="featured-blog-card mb-5 rounded-4 overflow-hidden position-relative shadow-lg" style="min-height: 400px; display:flex; align-items:flex-end;">
          <img src="<?= $featuredPost['cover_image'] ? upload($featuredPost['cover_image']) : 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=1200&q=80' ?>" 
               alt="<?= e($featuredPost['title']) ?>" 
               class="position-absolute w-100 h-100 object-fit-cover" style="top:0; left:0; z-index:0;">
          <div class="featured-overlay position-absolute w-100 h-100" style="top:0; left:0; background: linear-gradient(to top, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0.2) 100%); z-index:1;"></div>
          
          <div class="position-relative z-2 p-4 p-md-5 text-white w-100">
            <?php if ($featuredPost['category_name']): ?>
              <a href="?cat=<?= e($featuredPost['category_slug']) ?>" class="badge mb-3 px-3 py-2 text-uppercase fw-bold" style="background:var(--pr-primary); color:var(--pr-secondary); letter-spacing:1px; font-size:0.75rem; text-decoration:none;">
                <?= e($featuredPost['category_name']) ?>
              </a>
            <?php endif; ?>
            <h2 class="display-5 fw-bold mb-3 featured-title"><a href="<?= PUBLIC_URL ?>blog/<?= e($featuredPost['slug']) ?>" class="text-white text-decoration-none"><?= e($featuredPost['title']) ?></a></h2>
            <p class="fs-5 mb-4 opacity-75 d-none d-md-block" style="max-width:800px;"><?= e(View::excerpt($featuredPost['excerpt'] ?? $featuredPost['body'], 180)) ?></p>
            <div class="d-flex align-items-center gap-4" style="font-size:0.9rem; color:var(--pr-primary);">
              <span><i class="fas fa-user-circle me-1"></i> <?= e($featuredPost['author']) ?></span>
              <span><i class="fas fa-calendar-alt me-1"></i> <?= date('M j, Y', strtotime($featuredPost['published_at'])) ?></span>
            </div>
          </div>
        </div>
        <?php endif; ?>

        <?php if ($posts || $featuredPost): ?>
        <div class="row g-4 mb-5">
          <?php foreach ($posts as $post): ?>
          <div class="col-md-6 col-xl-4">
            <article class="blog-card h-100 shadow-sm border-0 rounded-4 overflow-hidden" style="background:#fff; transition: transform 0.3s, box-shadow 0.3s;">
              <div class="blog-card-img position-relative" style="height:220px; overflow:hidden;">
                <img src="<?= $post['cover_image'] ? upload($post['cover_image']) : 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=600&q=70' ?>"
                     alt="<?= e($post['title']) ?>" class="w-100 h-100 object-fit-cover" style="transition:transform 0.5s;">
                <?php if ($post['category_name']): ?>
                  <span class="position-absolute badge px-3 py-2" style="bottom:15px; left:15px; background:var(--pr-primary); color:var(--pr-secondary); font-size:0.7rem; font-weight:700; text-transform:uppercase; letter-spacing:1px;"><?= e($post['category_name']) ?></span>
                <?php endif; ?>
              </div>
              <div class="p-4 d-flex flex-column" style="height: calc(100% - 220px);">
                <h3 class="fw-bold mb-3" style="font-size:1.25rem; line-height:1.4;">
                  <a href="<?= PUBLIC_URL ?>blog/<?= e($post['slug']) ?>" class="text-dark text-decoration-none blog-link-hover"><?= e($post['title']) ?></a>
                </h3>
                <p class="text-muted flex-grow-1" style="font-size:0.95rem; line-height:1.6;">
                  <?= e(View::excerpt($post['excerpt'] ?? $post['body'], 100)) ?>
                </p>
                <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top text-muted" style="font-size:0.85rem;">
                  <span><i class="fas fa-user me-1"></i> <?= e($post['author']) ?></span>
                  <span><i class="far fa-clock me-1"></i> <?= e(View::timeAgo($post['published_at'])) ?></span>
                </div>
              </div>
            </article>
          </div>
          <?php endforeach; ?>
        </div>
        <div class="d-flex justify-content-center">
          <?= $pager->render() ?>
        </div>
        <?php else: ?>
        <div class="text-center py-5">
          <i class="fas fa-newspaper text-muted mb-3" style="font-size:3rem;"></i>
          <h4 class="text-muted">No posts found for this category.</h4>
        </div>
        <?php endif; ?>
      </div>

      <!-- Sidebar -->
      <div class="col-lg-4 col-xl-3">
        
        <!-- Search / Categories -->
        <div class="card border-0 shadow-sm rounded-4 mb-4 p-4" style="background:#f8f9fa;">
          <h4 class="fw-bold mb-4" style="font-size:1.1rem; text-transform:uppercase; letter-spacing:1px; border-bottom:2px solid var(--pr-border); padding-bottom:10px;">
            <i class="fas fa-tags me-2" style="color:var(--pr-primary)"></i> Topics
          </h4>
          <ul class="list-unstyled mb-0">
            <li class="mb-3">
              <a href="<?= PUBLIC_URL ?>blog" class="d-flex justify-content-between align-items-center text-decoration-none text-<?= !$activeCat ? 'primary fw-bold' : 'dark' ?> category-link">
                <span>All Insights</span><span class="badge rounded-pill" style="background:<?= !$activeCat ? 'var(--pr-primary)' : 'var(--pr-border)' ?>; color:<?= !$activeCat ? 'var(--pr-secondary)' : '#64748b' ?>;"><?= $total + ($activeCat ? 0 : ($featuredPost?1:0)) ?></span>
              </a>
            </li>
            <?php foreach ($categories as $cat): ?>
            <li class="mb-3">
              <a href="?cat=<?= e($cat['slug']) ?>" class="d-flex justify-content-between align-items-center text-decoration-none text-<?= $activeCat===$cat['slug'] ? 'primary fw-bold' : 'dark' ?> category-link">
                <span><?= e($cat['name']) ?></span><span class="badge rounded-pill" style="background:<?= $activeCat===$cat['slug'] ? 'var(--pr-primary)' : 'var(--pr-border)' ?>; color:<?= $activeCat===$cat['slug'] ? 'var(--pr-secondary)' : '#64748b' ?>;"><?= (int)$cat['post_count'] ?></span>
              </a>
            </li>
            <?php endforeach; ?>
          </ul>
        </div>

        <!-- Newsletter CTA -->
        <div class="card border-0 shadow-lg rounded-4 p-4 text-center text-white" style="background: var(--pr-secondary);">
          <div class="mb-3">
            <i class="fas fa-envelope-open-text" style="font-size:2.5rem; color:var(--pr-primary);"></i>
          </div>
          <h4 class="fw-bold mb-3">Never Miss an Update!</h4>
          <p class="small mb-4" style="color:#a0aec0;">Get the latest real estate news, investment tips, and market reports delivered straight to your inbox.</p>
          <form onsubmit="event.preventDefault(); alert('Subscribed successfully!');">
            <div class="input-group mb-3">
              <input type="email" class="form-control border-0" placeholder="Your email address" required style="border-radius: 8px 0 0 8px; padding: 12px;">
              <button class="btn" type="submit" style="background:var(--pr-primary); color:var(--pr-secondary); border-radius: 0 8px 8px 0; font-weight:700;"><i class="fas fa-paper-plane"></i></button>
            </div>
            <p class="mb-0" style="font-size:0.75rem; color:#718096;">We respect your privacy. No spam.</p>
          </form>
        </div>

      </div>
    </div>
  </div>
</div>

<style>
.featured-blog-card:hover img {
  transform: scale(1.05);
  transition: transform 0.7s ease;
}
.featured-blog-card img {
  transition: transform 0.7s ease;
}
.featured-title a:hover {
  color: var(--pr-primary) !important;
  transition: color 0.2s ease;
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
.category-link {
  transition: all 0.2s ease;
}
.category-link:hover {
  color: var(--pr-primary) !important;
  transform: translateX(5px);
}
</style>
