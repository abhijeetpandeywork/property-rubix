<?php /** Blog Listing */ 
  $featuredPost = !empty($posts) && empty($_GET['page']) ? array_shift($posts) : null;
?>

<!-- Premium Header Space -->
<div class="blog-header-hero" style="background: radial-gradient(circle at top right, rgba(234, 179, 8, 0.1) 0%, rgba(15, 23, 42, 1) 100%), #0f172a; padding: 80px 0 60px; text-align: center; color: #fff; position: relative; overflow: hidden; border-bottom: 4px solid var(--pr-primary);">
  <div class="hero-decorative-ring" style="position: absolute; top: -10%; right: -5%; width: 300px; height: 300px; border-radius: 50%; border: 2px dashed rgba(234, 179, 8, 0.2); pointer-events: none;"></div>
  <div class="hero-decorative-ring-2" style="position: absolute; bottom: -20%; left: -5%; width: 250px; height: 250px; border-radius: 50%; border: 1px dashed rgba(34, 197, 94, 0.15); pointer-events: none;"></div>
  
  <div class="container" style="max-width: 800px; position: relative; z-index: 2;">
    <span class="text-uppercase fw-bold text-warning" style="font-size: 0.8rem; letter-spacing: 3px; display: inline-block; margin-bottom: 12px; font-family: 'Inter', sans-serif;">Knowledge & Insights</span>
    <h1 class="fw-900 display-4 text-white mb-3" style="font-family: 'Outfit', 'Inter', sans-serif; letter-spacing: -1.5px; line-height: 1.1;">
      PropertyRubix <span style="color:var(--pr-primary)">Journal</span>
    </h1>
    <p class="text-white-50 fs-5 mx-auto mb-0" style="max-width: 600px; font-weight: 400; line-height: 1.6;">
      Stay ahead in real estate with science-backed layouts, smart home guides, and strategic market investments.
    </p>
  </div>
</div>

<div class="breadcrumb-section" style="background: #fafafa; border-bottom: 1px solid #eaeaea;">
  <div class="container-fluid px-3 px-md-5">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-0 py-3" style="font-size: 0.85rem;">
        <li class="breadcrumb-item"><a href="<?= PUBLIC_URL ?>" style="color: #64748b; text-decoration: none;">Home</a></li>
        <li class="breadcrumb-item active text-dark fw-600" aria-current="page">Blog</li>
      </ol>
    </nav>
  </div>
</div>

<div class="section py-5" style="background-color: #fafafa;">
  <div class="container-fluid px-3 px-md-5" style="max-width: 1300px; margin: 0 auto;">
    
    <div class="row g-5">
      <!-- Main Content -->
      <div class="col-lg-8 col-xl-9">
        
        <?php if ($featuredPost): ?>
        <!-- Featured Post Hero (Visual psychological anchor: Large high-contrast layout) -->
        <div class="featured-blog-card mb-5 rounded-4 overflow-hidden position-relative shadow-lg" style="min-height: 480px; display:flex; align-items:flex-end; border: 1px solid rgba(0,0,0,0.1); box-shadow: 0 20px 40px rgba(0,0,0,0.06) !important; background: #000;">
          <img src="<?= $featuredPost['cover_image'] ? upload($featuredPost['cover_image']) : 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=1200&q=80' ?>" 
               alt="<?= e($featuredPost['title']) ?>" 
               class="position-absolute w-100 h-100 object-fit-cover" style="top:0; left:0; z-index:0; opacity: 0.85;">
          <div class="featured-overlay position-absolute w-100 h-100" style="top:0; left:0; background: linear-gradient(to top, rgba(15,23,42,1) 0%, rgba(15,23,42,0.6) 40%, rgba(0,0,0,0) 100%); z-index:1;"></div>
          
          <div class="position-relative z-2 p-4 p-md-5 text-white w-100">
            <?php if ($featuredPost['category_name']): ?>
              <span class="badge mb-3 px-3 py-2 text-uppercase fw-800" style="background:var(--pr-primary); color:#000; letter-spacing:1.5px; font-size:0.75rem; border-radius: 4px;">
                <?= e($featuredPost['category_name']) ?>
              </span>
            <?php endif; ?>
            <h2 class="display-6 fw-900 mb-3 featured-title" style="font-family: 'Outfit', sans-serif; letter-spacing: -0.5px;">
              <a href="<?= PUBLIC_URL ?>blog/<?= e($featuredPost['slug']) ?>" class="text-white text-decoration-none text-glow-hover">
                <?= e($featuredPost['title']) ?>
              </a>
            </h2>
            <p class="fs-6 mb-4 text-white-50 d-none d-md-block" style="max-width:800px; line-height: 1.6;">
              <?= e(View::excerpt($featuredPost['excerpt'] ?? $featuredPost['body'], 180)) ?>
            </p>
            <div class="d-flex align-items-center gap-4 text-white-50" style="font-size:0.85rem; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 15px;">
              <span><i class="fas fa-user-circle text-warning me-1"></i> By <?= e($featuredPost['author']) ?></span>
              <span><i class="far fa-calendar-alt text-warning me-1"></i> <?= date('M j, Y', strtotime($featuredPost['published_at'])) ?></span>
            </div>
          </div>
        </div>
        <?php endif; ?>

        <?php if ($posts || $featuredPost): ?>
        <!-- Blog Cards Grid (Consistent pattern for easy visual scanning) -->
        <div class="row g-4 mb-5">
          <?php foreach ($posts as $post): ?>
          <div class="col-md-6 col-xl-4">
            <article class="blog-card h-100 shadow-sm border-0 rounded-4 overflow-hidden" style="background:#fff; border: 1px solid #eaeaea !important; display: flex; flex-direction: column;">
              <div class="blog-card-img position-relative" style="height:200px; overflow:hidden; background: #eaeaea;">
                <img src="<?= $post['cover_image'] ? upload($post['cover_image']) : 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=600&q=70' ?>"
                     alt="<?= e($post['title']) ?>" class="w-100 h-100 object-fit-cover">
                <?php if ($post['category_name']): ?>
                  <span class="position-absolute badge px-2.5 py-1.5" style="top:15px; left:15px; background: rgba(15,23,42,0.85); color:#fff; backdrop-filter: blur(8px); border: 1px solid rgba(255,255,255,0.15); font-size:0.65rem; font-weight:700; text-transform:uppercase; letter-spacing:1px; border-radius: 4px;"><?= e($post['category_name']) ?></span>
                <?php endif; ?>
              </div>
              
              <div class="p-4 d-flex flex-column flex-grow-1">
                <h3 class="fw-bold mb-2.5" style="font-size:1.15rem; line-height:1.4; font-family: 'Outfit', sans-serif;">
                  <a href="<?= PUBLIC_URL ?>blog/<?= e($post['slug']) ?>" class="text-dark text-decoration-none blog-link-hover"><?= e($post['title']) ?></a>
                </h3>
                <p class="text-muted flex-grow-1" style="font-size:0.9rem; line-height:1.6; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; height: 4.8em;">
                  <?= e(View::excerpt($post['excerpt'] ?? $post['body'], 120)) ?>
                </p>
                <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top text-muted" style="font-size:0.75rem; border-color: #f1f1f1 !important;">
                  <span><i class="fas fa-user-edit text-warning me-1"></i> <?= e($post['author']) ?></span>
                  <span><i class="far fa-clock text-warning me-1"></i> <?= e(View::timeAgo($post['published_at'])) ?></span>
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
        <div class="text-center py-5" style="background:#fff; border-radius:12px; border:1px solid #eaeaea;">
          <i class="fas fa-newspaper text-muted mb-3" style="font-size:3rem;"></i>
          <h4 class="text-muted fw-bold">No posts found for this category.</h4>
        </div>
        <?php endif; ?>
      </div>

      <!-- Sidebar -->
      <div class="col-lg-4 col-xl-3">
        
        <!-- Topics Box -->
        <div class="card border-0 shadow-sm rounded-4 mb-4 p-4" style="background:#fff; border:1px solid #eaeaea !important;">
          <h4 class="fw-bold mb-4" style="font-size:0.9rem; text-transform:uppercase; letter-spacing:1.5px; color:#000; border-bottom:2px solid var(--pr-primary); padding-bottom:8px; display: flex; align-items: center; gap: 8px;">
            <i class="fas fa-layer-group text-warning"></i> Browse Topics
          </h4>
          <ul class="list-unstyled mb-0">
            <li class="mb-2">
              <a href="<?= PUBLIC_URL ?>blog" class="d-flex justify-content-between align-items-center text-decoration-none py-2 px-2.5 rounded-3 <?= !$activeCat ? 'bg-dark text-white fw-bold' : 'text-dark bg-hover-light' ?> category-link" style="transition: all 0.2s; font-size: 0.9rem;">
                <span>All Insights</span>
                <span class="badge rounded-pill" style="background: <?= !$activeCat ? 'var(--pr-primary)' : '#eaeaea' ?>; color: <?= !$activeCat ? '#0f172a' : '#64748b' ?>; font-size: 0.75rem;">
                  <?= $total + ($activeCat ? 0 : ($featuredPost ? 1 : 0)) ?>
                </span>
              </a>
            </li>
            <?php foreach ($categories as $cat): ?>
            <li class="mb-2">
              <a href="?cat=<?= e($cat['slug']) ?>" class="d-flex justify-content-between align-items-center text-decoration-none py-2 px-2.5 rounded-3 <?= $activeCat===$cat['slug'] ? 'bg-dark text-white fw-bold' : 'text-dark bg-hover-light' ?> category-link" style="transition: all 0.2; font-size: 0.9rem;">
                <span><?= e($cat['name']) ?></span>
                <span class="badge rounded-pill" style="background: <?= $activeCat===$cat['slug'] ? 'var(--pr-primary)' : '#eaeaea' ?>; color: <?= $activeCat===$cat['slug'] ? '#0f172a' : '#64748b' ?>; font-size: 0.75rem;">
                  <?= (int)$cat['post_count'] ?>
                </span>
              </a>
            </li>
            <?php endforeach; ?>
          </ul>
        </div>

        <!-- Premium Newsletter Subscription CTA (Psychological trigger: Social Proof & Visual Contrast) -->
        <div class="card border-0 shadow-lg rounded-4 p-4 text-center text-white" style="background: linear-gradient(145deg, #0f172a 0%, #1e293b 100%); border-top: 4px solid var(--pr-primary);">
          <div class="mb-3">
            <div style="width: 60px; height: 60px; background: rgba(234,179,8,0.15); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
              <i class="fas fa-envelope-open-text" style="font-size:1.8rem; color:var(--pr-primary);"></i>
            </div>
          </div>
          <h4 class="fw-bold mb-2" style="font-family: 'Outfit', sans-serif;">Join 15K+ Smart Investors</h4>
          <p class="small mb-4" style="color:#94a3b8; line-height: 1.5;">Get curated real estate trends, layout psychology reviews, and investment reports delivered weekly.</p>
          
          <form onsubmit="event.preventDefault(); alert('Subscribed successfully!');">
            <div class="mb-3">
              <input type="email" class="form-control border-0 bg-dark-input text-white" placeholder="Your email address" required style="border-radius: 8px; padding: 12px; font-size: 0.9rem; background: rgba(255,255,255,0.07); border: 1px solid rgba(255,255,255,0.1) !important;">
            </div>
            <button class="btn btn-primary w-100 py-2.5 fw-bold" type="submit" style="background:var(--pr-primary); color:#000; border-radius: 8px; font-size: 0.9rem; transition: transform 0.2s;">
              Subscribe Free
            </button>
            <p class="mb-0 mt-3" style="font-size:0.7rem; color:#64748b;">No spam. Unsubscribe anytime in 1-click.</p>
          </form>
        </div>

      </div>
    </div>
  </div>
</div>

<style>
/* Custom animations & typography styles */
@import url('https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;800;900&family=Inter:wght@400;500;600;700;800&display=swap');

.blog-card {
  transition: all 0.35s cubic-bezier(0.25, 0.46, 0.45, 0.94);
}
.blog-card:hover {
  transform: translateY(-8px);
  box-shadow: 0 15px 30px rgba(15,23,42,0.08) !important;
  border-color: rgba(234,179,8,0.3) !important;
}
.blog-card-img img {
  transition: transform 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94);
}
.blog-card:hover .blog-card-img img {
  transform: scale(1.06);
}
.blog-link-hover {
  transition: color 0.2s ease;
}
.blog-link-hover:hover {
  color: var(--pr-primary) !important;
}
.bg-hover-light:hover {
  background-color: #f8f9fa !important;
  transform: translateX(4px);
}
.category-link {
  transition: all 0.2s ease;
}
.text-glow-hover {
  transition: text-shadow 0.3s ease, color 0.3s ease;
}
.text-glow-hover:hover {
  color: var(--pr-primary) !important;
}
</style>
