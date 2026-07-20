<?php
/**
 * Homepage View
 */
?>

<?php
$sliders = json_decode(getSetting('hero_sliders', '[]'), true) ?: [];
if (empty($sliders)) {
    $sliders = ['https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?w=1600&q=80'];
}
?>
<!-- ══ HERO ═══════════════════════════════════════════════════════════════ -->
<section class="hero text-center" aria-label="Hero banner">
  <div class="swiper hero-swiper" style="position: absolute; inset: 0; z-index: 0; width: 100%; height: 100%;">
    <div class="swiper-wrapper">
      <?php foreach ($sliders as $img): ?>
        <div class="swiper-slide">
          <div class="hero-bg" style="background-image: url('<?= str_starts_with($img, 'http') ? e($img) : upload($img) ?>'); width: 100%; height: 100%;"></div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
  
  <div class="container-fluid px-3 px-md-5 d-flex flex-column align-items-center justify-content-center" style="padding-top: 60px; position: relative; z-index: 2;">
    <div class="hero-content anim-fade-up w-100" style="max-width: 800px; padding: 0;">
      <h1 class="hero-headline text-white mb-4" style="font-size: clamp(2.5rem, 6vw, 4.5rem); font-weight: 800; text-shadow: 0 4px 6px rgba(0,0,0,0.3); line-height: 1.3;">
        The <span style="background: #2b2013; color: #fff; padding: 0 16px; border-radius: 8px; display: inline-block;">X Factor</span><br>
        in Property Search
      </h1>
      
      <div class="mt-4 mx-auto position-relative" style="max-width: 700px;" id="smartSearchContainer">
        <!-- Search Input -->
        <form action="<?= PUBLIC_URL ?>projects" method="get" class="d-flex align-items-center bg-white" style="border-radius: 50px; padding: 5px; box-shadow: 0 15px 35px rgba(0,0,0,0.2); border: 2px solid rgba(229,175,83,0.5);">
          <div class="px-3 text-muted"><i class="fas fa-search fs-5"></i></div>
          <input type="text" name="q" id="smartSearchInput" class="form-control border-0 shadow-none fw-500" placeholder="Search by city, developer, neighborhood, or project..." style="height: 55px; font-size: 1.15rem; background: transparent; color: #333;" autocomplete="off">
          <button type="submit" class="btn btn-primary rounded-pill px-4" style="height: 55px; font-weight: 600; font-size: 1.1rem; background: #8a6736; border: none;">Search</button>
        </form>

        <!-- Autocomplete Dropdown -->
        <div id="smartSearchDropdown" class="position-absolute w-100 bg-white text-start shadow-lg d-none overflow-hidden" style="top: 75px; left: 0; border-radius: 16px; z-index: 1000; border: 1px solid rgba(0,0,0,0.1); max-height: 400px; overflow-y: auto;">
            <!-- Content injected by JS -->
        </div>
      </div>
      
      <div class="my-4 text-white fw-bold" style="font-size: 1.4rem; text-shadow: 0 2px 4px rgba(0,0,0,0.6);">OR</div>
      
      <div class="d-flex justify-content-center gap-3 flex-wrap">
        <a href="<?= PUBLIC_URL ?>properties" class="btn btn-lg px-5 py-3 fw-bold shadow-lg" style="background: var(--pr-primary); color: white; border: 2px solid var(--pr-primary); border-radius: 50px; font-size: 1.15rem; transition: all 0.3s; backdrop-filter: blur(5px);">
          Explore Properties <i class="fas fa-home ms-2"></i>
        </a>
        <a href="<?= PUBLIC_URL ?>location" class="btn btn-lg px-5 py-3 fw-bold shadow-lg" style="background: rgba(255,255,255,0.1); color: white; border: 2px solid white; border-radius: 50px; font-size: 1.15rem; transition: all 0.3s; backdrop-filter: blur(5px);">
          Explore Locations <i class="fas fa-map-marked-alt ms-2"></i>
        </a>
      </div>
    </div>
  </div>
</section>

<?php ob_start(); ?>
<style>
/* Smart Search Custom Scrollbar & Hover */
#smartSearchDropdown::-webkit-scrollbar { width: 6px; }
#smartSearchDropdown::-webkit-scrollbar-thumb { background: rgba(0,0,0,0.1); border-radius: 10px; }
.smart-search-item { transition: background-color 0.2s; cursor: pointer; }
.smart-search-item:hover { background-color: rgba(229,175,83,0.1); }
</style>
<script>
document.addEventListener("DOMContentLoaded", function() {
    new Swiper(".hero-swiper", {
        loop: true,
        effect: "fade",
        autoplay: {
            delay: 4000,
            disableOnInteraction: false,
        },
        allowTouchMove: false
    });

    // Smart Search Autocomplete
    const searchInput = document.getElementById('smartSearchInput');
    const searchDropdown = document.getElementById('smartSearchDropdown');
    let debounceTimer;

    searchInput.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        const query = this.value.trim();

        if (query.length < 2) {
            searchDropdown.classList.add('d-none');
            return;
        }

        debounceTimer = setTimeout(() => {
            fetch(`<?= PUBLIC_URL ?>ajax/search?q=${encodeURIComponent(query)}`)
                .then(res => res.json())
                .then(data => {
                    searchDropdown.innerHTML = '';
                    if (data.results && data.results.length > 0) {
                        
                        // Group by type
                        const grouped = data.results.reduce((acc, curr) => {
                            if (!acc[curr.type]) acc[curr.type] = [];
                            acc[curr.type].push(curr);
                            return acc;
                        }, {});

                        let html = '';
                        for (const [type, items] of Object.entries(grouped)) {
                            html += `<div class="bg-light px-3 py-2 text-muted fw-bold" style="font-size:0.8rem; text-transform:uppercase; letter-spacing:1px; border-bottom:1px solid #eaeaea;">${type}</div>`;
                            items.forEach(item => {
                                const subtitleHtml = item.subtitle ? `<div class="text-muted" style="font-size:0.75rem;">${item.subtitle}</div>` : '';
                                html += `
                                <a href="${item.url}" class="smart-search-item text-decoration-none text-dark d-flex align-items-center px-3 py-2 border-bottom" style="border-color:#f5f5f5 !important;">
                                    <div class="d-flex align-items-center justify-content-center me-3" style="width:36px; height:36px; background:rgba(229,175,83,0.15); color:#8a6736; border-radius:50%;">
                                        <i class="${item.icon}"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold" style="font-size:0.95rem;">${item.title}</div>
                                        ${subtitleHtml}
                                    </div>
                                </a>
                                `;
                            });
                        }
                        searchDropdown.innerHTML = html;
                        searchDropdown.classList.remove('d-none');
                    } else {
                        searchDropdown.innerHTML = '<div class="p-3 text-muted text-center">No results found for "' + query + '"</div>';
                        searchDropdown.classList.remove('d-none');
                    }
                });
        }, 300); // 300ms debounce
    });

    // Hide dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !searchDropdown.contains(e.target)) {
            searchDropdown.classList.add('d-none');
        }
    });
});
</script>
<?php $extraScripts = ob_get_clean(); ?>
