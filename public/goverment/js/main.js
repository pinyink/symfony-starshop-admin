/* ==========================================================================
   Pemerintah Kabupaten Bandung Barat — Portal Homepage
   Interactions, accessibility, mobile nav, charts
   ========================================================================== */
(function () {
  'use strict';

  /* ----------------------------------------------------------------------
     Mobile drawer
     ---------------------------------------------------------------------- */
  const drawer = document.getElementById('mobileDrawer');
  const backdrop = document.getElementById('drawerBackdrop');
  const openBtn = document.getElementById('mobileToggle');
  const closeBtn = document.getElementById('drawerClose');

  function openDrawer() {
    if (!drawer) return;
    drawer.classList.add('is-open');
    backdrop.classList.add('is-open');
    document.body.style.overflow = 'hidden';
    drawer.setAttribute('aria-hidden', 'false');
    openBtn.setAttribute('aria-expanded', 'true');
  }
  function closeDrawer() {
    if (!drawer) return;
    drawer.classList.remove('is-open');
    backdrop.classList.remove('is-open');
    document.body.style.overflow = '';
    drawer.setAttribute('aria-hidden', 'true');
    openBtn.setAttribute('aria-expanded', 'false');
  }
  if (openBtn) openBtn.addEventListener('click', openDrawer);
  if (closeBtn) closeBtn.addEventListener('click', closeDrawer);
  if (backdrop) backdrop.addEventListener('click', closeDrawer);
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && drawer && drawer.classList.contains('is-open')) closeDrawer();
  });

  /* ----------------------------------------------------------------------
     Mobile nav: expandable sub-items
     ---------------------------------------------------------------------- */
  document.querySelectorAll('[data-mobile-toggle]').forEach(function (toggle) {
    toggle.addEventListener('click', function (e) {
      e.preventDefault();
      const target = document.getElementById(toggle.getAttribute('data-mobile-toggle'));
      if (!target) return;
      const expanded = target.style.display === 'block';
      target.style.display = expanded ? 'none' : 'block';
      toggle.setAttribute('aria-expanded', String(!expanded));
      const icon = toggle.querySelector('.chevron');
      if (icon) icon.style.transform = expanded ? '' : 'rotate(90deg)';
    });
  });

  /* ----------------------------------------------------------------------
     Accessibility toggles (text size, high contrast)
     ---------------------------------------------------------------------- */
  const a11yControls = document.querySelectorAll('[data-a11y]');
  a11yControls.forEach(function (ctrl) {
    ctrl.addEventListener('click', function (e) {
      e.preventDefault();
      const action = ctrl.getAttribute('data-a11y');
      if (action === 'contrast') {
        document.body.classList.toggle('a11y-high-contrast');
        const active = document.body.classList.contains('a11y-high-contrast');
        ctrl.setAttribute('aria-pressed', String(active));
        try { localStorage.setItem('a11y-contrast', String(active)); } catch (_) {}
      } else if (action === 'larger') {
        document.body.classList.toggle('a11y-large-text');
        const active = document.body.classList.contains('a11y-large-text');
        ctrl.setAttribute('aria-pressed', String(active));
        try { localStorage.setItem('a11y-larger', String(active)); } catch (_) {}
      } else if (action === 'reset') {
        document.body.classList.remove('a11y-high-contrast', 'a11y-large-text');
        document.querySelectorAll('[data-a11y]').forEach(function (c) { c.setAttribute('aria-pressed', 'false'); });
        try { localStorage.removeItem('a11y-contrast'); localStorage.removeItem('a11y-larger'); } catch (_) {}
      }
    });
  });
  // Restore preferences
  try {
    if (localStorage.getItem('a11y-contrast') === 'true') document.body.classList.add('a11y-high-contrast');
    if (localStorage.getItem('a11y-larger') === 'true') document.body.classList.add('a11y-large-text');
  } catch (_) {}

  /* ----------------------------------------------------------------------
     Scroll-to-top
     ---------------------------------------------------------------------- */
  const toTop = document.getElementById('scrollTop');
  if (toTop) {
    window.addEventListener('scroll', function () {
      if (window.scrollY > 600) toTop.classList.add('is-visible');
      else toTop.classList.remove('is-visible');
    }, { passive: true });
    toTop.addEventListener('click', function () {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  }

  /* ----------------------------------------------------------------------
     Header shrink on scroll
     ---------------------------------------------------------------------- */
  const header = document.querySelector('.header');
  if (header) {
    window.addEventListener('scroll', function () {
      if (window.scrollY > 40) header.classList.add('is-scrolled');
      else header.classList.remove('is-scrolled');
    }, { passive: true });
  }

  /* ----------------------------------------------------------------------
     Animated number counters (statistics)
     ---------------------------------------------------------------------- */
  function animateCounter(el) {
    const target = parseFloat(el.getAttribute('data-target'));
    const suffix = el.getAttribute('data-suffix') || '';
    const decimals = parseInt(el.getAttribute('data-decimals') || '0', 10);
    if (isNaN(target)) return;
    if (el.dataset.counterStarted === '1') return;
    el.dataset.counterStarted = '1';
    const duration = 1400;
    const start = performance.now();
    function tick(now) {
      const p = Math.min((now - start) / duration, 1);
      const eased = 1 - Math.pow(1 - p, 3);
      const val = target * eased;
      el.textContent = val.toLocaleString('id-ID', { minimumFractionDigits: decimals, maximumFractionDigits: decimals }) + suffix;
      if (p < 1) requestAnimationFrame(tick);
    }
    requestAnimationFrame(tick);
  }
  function isInViewport(el) {
    const r = el.getBoundingClientRect();
    const vh = window.innerHeight || document.documentElement.clientHeight;
    return r.top < vh && r.bottom > 0;
  }
  const counters = document.querySelectorAll('[data-target]');
  // Trigger all counters immediately so values are present even on fast page captures
  counters.forEach(animateCounter);
  // IntersectionObserver kept as a fallback for any element added later
  if ('IntersectionObserver' in window) {
    const io = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) { animateCounter(entry.target); io.unobserve(entry.target); }
      });
    }, { threshold: 0.1, rootMargin: '0px 0px -10% 0px' });
    counters.forEach(function (c) { io.observe(c); });
  }

  /* ----------------------------------------------------------------------
     Bar chart (pure CSS/SVG, no library)
     ---------------------------------------------------------------------- */
  function renderBarChart() {
    const chart = document.getElementById('serviceChart');
    if (!chart) return;
    // Placeholder dataset — replace with verified institutional data
    const data = [
      { label: 'KTP', value: 78 },
      { label: 'KK', value: 64 },
      { label: 'Akta', value: 52 },
      { label: 'Izin', value: 41 },
      { label: 'Aspirasi', value: 88 },
      { label: 'Info', value: 73 }
    ];
    const max = Math.max.apply(null, data.map(function (d) { return d.value; }));
    chart.innerHTML = '<div class="bar-chart">' + data.map(function (d) {
      const h = (d.value / max) * 100;
      return '<div class="bar-chart__item">' +
        '<div class="bar-chart__bar-area">' +
          '<div class="bar-chart__bar" style="height:' + h + '%">' +
            '<span class="bar-chart__value">' + d.value + '%</span>' +
          '</div>' +
        '</div>' +
        '<span class="bar-chart__label">' + d.label + '</span>' +
      '</div>';
    }).join('') + '</div>';
  }
  renderBarChart();

  /* ----------------------------------------------------------------------
     Donut chart (SVG)
     ---------------------------------------------------------------------- */
  function renderDonut() {
    const el = document.getElementById('donutChart');
    if (!el) return;
    // Placeholder value — replace with verified institutional data
    const value = 86;
    const radius = 70;
    const circ = 2 * Math.PI * radius;
    const offset = circ - (value / 100) * circ;
    el.innerHTML =
      '<svg class="donut__svg" width="180" height="180" viewBox="0 0 180 180">' +
        '<circle cx="90" cy="90" r="' + radius + '" fill="none" stroke="#eef2f7" stroke-width="16"/>' +
        '<circle cx="90" cy="90" r="' + radius + '" fill="none" stroke="#1e4d82" stroke-width="16" ' +
          'stroke-linecap="round" stroke-dasharray="' + circ + '" stroke-dashoffset="' + offset + '"/>' +
      '</svg>' +
      '<div class="donut__center">' +
        '<span class="donut__num" data-target="86" data-suffix="%">0%</span>' +
        '<span class="donut__label">Tingkat Layanan</span>' +
      '</div>';
    // re-bind counter for the donut number
    const donutNum = el.querySelector('[data-target]');
    if (donutNum) { animateCounter(donutNum); }
  }
  renderDonut();

  /* ----------------------------------------------------------------------
     Hero search + header search submit (demo)
     ---------------------------------------------------------------------- */
  document.querySelectorAll('[data-search-form]').forEach(function (form) {
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      const input = form.querySelector('input');
      if (!input || !input.value.trim()) return;
      // In production, route to the real search results page.
      window.location.hash = 'q=' + encodeURIComponent(input.value.trim());
    });
  });

  /* ----------------------------------------------------------------------
     Active nav link based on scroll position (scrollspy-lite)
     ---------------------------------------------------------------------- */
  const sections = document.querySelectorAll('section[id]');
  const navLinks = document.querySelectorAll('.main-nav__link[href^="#"]');
  if (sections.length && navLinks.length && 'IntersectionObserver' in window) {
    const spy = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          const id = entry.target.getAttribute('id');
          navLinks.forEach(function (l) {
            const match = l.getAttribute('href') === '#' + id;
            l.classList.toggle('is-active', match);
          });
        }
      });
    }, { rootMargin: '-30% 0px -60% 0px' });
    sections.forEach(function (s) { spy.observe(s); });
  }
})();
