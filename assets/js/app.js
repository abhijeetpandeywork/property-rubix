/**
 * PropertyRubix — Main Application JS
 * Handles: drawer, header scroll, AJAX forms, site-visit modal, smooth UI
 */
(function () {
  'use strict';

  /* ── Drawer ────────────────────────────────────────────── */
  const drawer  = document.getElementById('siteDrawer');
  const overlay = document.getElementById('drawerOverlay');
  const toggle  = document.getElementById('drawerToggle');
  const closeBtn = document.getElementById('drawerClose');

  function openDrawer() {
    drawer?.classList.add('open');
    overlay?.classList.add('visible');
    toggle?.classList.add('open');
    toggle?.setAttribute('aria-expanded', 'true');
    drawer?.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';
  }

  function closeDrawer() {
    drawer?.classList.remove('open');
    overlay?.classList.remove('visible');
    toggle?.classList.remove('open');
    toggle?.setAttribute('aria-expanded', 'false');
    drawer?.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
  }

  toggle?.addEventListener('click', openDrawer);
  closeBtn?.addEventListener('click', closeDrawer);
  overlay?.addEventListener('click', closeDrawer);

  // Close on Escape
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeDrawer();
  });

  // Close drawer when clicking a nav link (mobile SPA feel)
  drawer?.querySelectorAll('.drawer-link').forEach(link => {
    link.addEventListener('click', closeDrawer);
  });

  // Close drawer when site visit modal opens from inside drawer
  document.getElementById('drawerSiteVisit')?.addEventListener('click', closeDrawer);

  /* ── Header scroll effect ──────────────────────────────── */
  const header = document.getElementById('siteHeader');
  const searchBar = document.getElementById('headerSearchBar');
  const isHome = document.body.classList.contains('page-home');

  // Show search bar on homepage
  if (isHome && searchBar) searchBar.classList.add('visible');

  window.addEventListener('scroll', () => {
    if (!header) return;
    if (window.scrollY > 40) {
      header.classList.add('scrolled');
    } else {
      header.classList.remove('scrolled');
    }
  }, { passive: true });

  /* ── Site Visit Form (AJAX) ────────────────────────────── */
  const svForm = document.getElementById('siteVisitForm');
  svForm?.addEventListener('submit', async function (e) {
    e.preventDefault();

    if (!this.checkValidity()) {
      this.classList.add('was-validated');
      return;
    }

    const btn     = document.getElementById('svSubmitBtn');
    const btnText = document.getElementById('svBtnText');
    const loader  = document.getElementById('svBtnLoader');
    const result  = document.getElementById('svResult');

    btnText.classList.add('d-none');
    loader.classList.remove('d-none');
    btn.disabled = true;

    const data = new FormData(this);

    try {
      const res  = await fetch((window.BASE_URL || '/') + 'ajax/submit-site-visit', { method: 'POST', body: data, headers: { 'X-Requested-With': 'XMLHttpRequest' } });
      const json = await res.json();

      result.classList.remove('d-none', 'alert-danger', 'alert-success');
      result.classList.add('alert', json.success ? 'alert-success' : 'alert-danger');
      result.textContent = json.message;

      if (json.success) {
        this.reset();
        this.classList.remove('was-validated');
      }
    } catch {
      result.classList.remove('d-none');
      result.classList.add('alert', 'alert-danger');
      result.textContent = 'Something went wrong. Please try again.';
    } finally {
      btnText.classList.remove('d-none');
      loader.classList.add('d-none');
      btn.disabled = false;
    }
  });

  /* ── Footer Newsletter Form ────────────────────────────── */
  const nlForm = document.getElementById('footerNewsletterForm');
  nlForm?.addEventListener('submit', async function (e) {
    e.preventDefault();
    const data = new FormData(this);

    try {
      const res  = await fetch((window.BASE_URL || '/') + 'ajax/subscribe', { method: 'POST', body: data, headers: { 'X-Requested-With': 'XMLHttpRequest' } });
      const json = await res.json();
      showToast(json.message, json.success ? 'success' : 'error');
      if (json.success) this.reset();
    } catch {
      showToast('Failed to subscribe. Please try again.', 'error');
    }
  });

  /* ── "Book Site Visit" button on project pages ─────────── */
  document.querySelectorAll('[data-sv-project]').forEach(btn => {
    btn.addEventListener('click', () => {
      const name = btn.dataset.svProject || '';
      const input = document.getElementById('svProjectName');
      if (input) input.value = name;
    });
  });

  /* ── Toast notification ────────────────────────────────── */
  function showToast(msg, type = 'success') {
    let container = document.querySelector('.toast-container');
    if (!container) {
      container = document.createElement('div');
      container.className = 'toast-container';
      document.body.appendChild(container);
    }
    const toast = document.createElement('div');
    toast.className = 'toast-msg ' + type;
    toast.textContent = msg;
    container.appendChild(toast);

    requestAnimationFrame(() => {
      requestAnimationFrame(() => toast.classList.add('show'));
    });

    setTimeout(() => {
      toast.classList.remove('show');
      setTimeout(() => toast.remove(), 350);
    }, 3500);
  }

  window.showToast = showToast;

  /* ── Smooth scroll to anchor ───────────────────────────── */
  document.querySelectorAll('a[href^="#"]').forEach(a => {
    a.addEventListener('click', function (e) {
      const target = document.querySelector(this.getAttribute('href'));
      if (target) {
        e.preventDefault();
        const offset = parseInt(getComputedStyle(document.documentElement).getPropertyValue('--pr-header-h')) || 72;
        window.scrollTo({ top: target.getBoundingClientRect().top + window.scrollY - offset - 16, behavior: 'smooth' });
      }
    });
  });

  /* ── Tab system (country tabs on homepage) ─────────────── */
  function initTabs(containerSelector) {
    const containers = document.querySelectorAll(containerSelector);
    containers.forEach(container => {
      const buttons = container.querySelectorAll('[data-tab]');
      const panels  = container.querySelectorAll('[data-tab-panel]');

      buttons.forEach(btn => {
        btn.addEventListener('click', () => {
          const target = btn.dataset.tab;

          buttons.forEach(b => b.classList.remove('active'));
          panels.forEach(p => p.classList.remove('active'));

          btn.classList.add('active');
          container.querySelector(`[data-tab-panel="${target}"]`)?.classList.add('active');
        });
      });

      // Activate first tab
      buttons[0]?.click();
    });
  }
  initTabs('.tabs-container');

  /* ── Animate numbers on scroll (hero stats) ────────────── */
  function animateNumbers() {
    const nums = document.querySelectorAll('[data-count-to]');
    if (!nums.length) return;

    const observer = new IntersectionObserver(entries => {
      entries.forEach(entry => {
        if (!entry.isIntersecting) return;
        observer.unobserve(entry.target);

        const el  = entry.target;
        const end = parseInt(el.dataset.countTo, 10);
        const dur = 1500;
        const start = Date.now();

        function tick() {
          const elapsed = Date.now() - start;
          const progress = Math.min(elapsed / dur, 1);
          const ease = 1 - Math.pow(1 - progress, 3); // cubic ease-out
          el.textContent = Math.round(ease * end).toLocaleString();
          if (progress < 1) requestAnimationFrame(tick);
          else el.textContent = end.toLocaleString() + (el.dataset.countSuffix || '');
        }

        tick();
      });
    }, { threshold: 0.3 });

    nums.forEach(el => observer.observe(el));
  }
  animateNumbers();

  /* ── Init Swiper (called from page-specific script) ─────── */
  window.initGallerySwiper = function () {
    const thumbSwiper = new Swiper('.gallery-thumbs', {
      spaceBetween: 8,
      slidesPerView: 4,
      freeMode: true,
      watchSlidesProgress: true,
    });
    const mainSwiper = new Swiper('.gallery-main-swiper', {
      spaceBetween: 0,
      navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
      thumbs: { swiper: thumbSwiper },
    });
  };

  /* ── Related projects carousel ──────────────────────────── */
  window.initRelatedSwiper = function () {
    new Swiper('.related-swiper', {
      spaceBetween: 20,
      slidesPerView: 1,
      breakpoints: {
        576:  { slidesPerView: 2 },
        992:  { slidesPerView: 3 },
      },
      navigation: { nextEl: '.related-next', prevEl: '.related-prev' },
    });
  };

})();
