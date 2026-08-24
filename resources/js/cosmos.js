/* ==========================================================================
   cosmos — ambient runtime
   --------------------------------------------------------------------------
   Zero dependencies, zero build. Additive by design: the page is complete
   and usable without any of this. Source of truth lives here — mirror it:

       cp resources/js/cosmos.js js/cosmos.js

   1. star field   — 60-ish stars, barely drifting, pausing when hidden
   2. reveals      — IntersectionObserver fade-ups with gentle stagger
   3. reduced motion — everything below stands down automatically
   ========================================================================== */
(function () {
  'use strict';

  var root = document.documentElement;
  root.classList.add('cosmos-js');

  var reducedQuery = window.matchMedia('(prefers-reduced-motion: reduce)');
  function reduced() { return reducedQuery.matches; }

  /* ------------------------------------------------------------------ *
   * star field
   * ------------------------------------------------------------------ */
  function initStarfield() {
    var canvas = document.getElementById('starfield') ||
      document.querySelector('.starfield');
    if (!canvas || !canvas.getContext) return;

    var ctx = canvas.getContext('2d');
    var COLORS = ['#ffffff', '#a5b4fc', '#c4b5fd'];
    var dpr = Math.min(window.devicePixelRatio || 1, 2);
    var stars = [];
    var width = 0, height = 0;
    var rafId = null;
    var last = 0;

    function resize() {
      width = window.innerWidth;
      height = window.innerHeight;
      canvas.width = Math.round(width * dpr);
      canvas.height = Math.round(height * dpr);
      ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
      build();
      if (reduced()) draw(0);        // one static frame, then rest
    }

    function build() {
      // ~60 stars per 1440×900 viewport, scaled by area, kept quiet
      var count = Math.round(60 * (width * height) / (1440 * 900));
      count = Math.max(36, Math.min(110, count));
      stars = [];
      for (var i = 0; i < count; i++) {
        var r = 0.5 + Math.random() * Math.random() * 1.5; // bias tiny (0.5–2px)
        stars.push({
          x: Math.random() * width,
          y: Math.random() * height,
          r: r,
          base: 0.1 + Math.random() * 0.4,                 // opacity 0.1–0.5
          twinkle: 0.0002 + Math.random() * 0.0004,        // slow sine speed
          phase: Math.random() * Math.PI * 2,
          vx: -0.05 + Math.random() * 0.03,                // barely moving
          vy: -0.05 + Math.random() * 0.025,
          color: COLORS[(Math.random() * COLORS.length) | 0]
        });
      }
    }

    function draw(dt) {
      ctx.clearRect(0, 0, width, height);
      for (var i = 0; i < stars.length; i++) {
        var s = stars[i];
        if (dt > 0) {
          s.x += s.vx * dt;
          s.y += s.vy * dt;
          if (s.y < -4) s.y = height + 4;
          if (s.y > height + 4) s.y = -4;
          if (s.x < -4) s.x = width + 4;
          if (s.x > width + 4) s.x = -4;
        }
        var t = performance.now();
        var alpha = s.base * (0.72 + 0.28 * Math.sin(t * s.twinkle * 1000 * 0.001 + s.phase));
        ctx.globalAlpha = Math.max(0.05, Math.min(0.5, alpha));
        ctx.fillStyle = s.color;
        ctx.beginPath();
        ctx.arc(s.x, s.y, s.r, 0, Math.PI * 2);
        ctx.fill();
      }
      ctx.globalAlpha = 1;
    }

    function loop(now) {
      rafId = null;
      var dt = Math.min(48, now - last || 16);
      last = now;
      draw(dt);
      if (!reduced()) rafId = requestAnimationFrame(loop);
    }

    var resizeTimer = null;
    window.addEventListener('resize', function () {
      clearTimeout(resizeTimer);
      resizeTimer = setTimeout(resize, 200);
    }, { passive: true });

    document.addEventListener('visibilitychange', function () {
      if (document.hidden) {
        if (rafId) { cancelAnimationFrame(rafId); rafId = null; }
      } else if (!rafId && !reduced()) {
        last = performance.now();
        rafId = requestAnimationFrame(loop);
      }
    });

    reducedQuery.addEventListener && reducedQuery.addEventListener('change', function (e) {
      if (e.matches) {
        if (rafId) { cancelAnimationFrame(rafId); rafId = null; }
        draw(0);
      } else if (!rafId) {
        last = performance.now();
        rafId = requestAnimationFrame(loop);
      }
    });

    resize();
    if (!reduced()) rafId = requestAnimationFrame(loop);
  }

  /* ------------------------------------------------------------------ *
   * scroll reveals — fade up, 700ms, staggered 90ms
   * ------------------------------------------------------------------ */
  function initReveals() {
    var items = Array.prototype.slice.call(document.querySelectorAll('.reveal'));

    // auto-stagger: children of a [data-stagger] container inherit delays
    Array.prototype.forEach.call(
      document.querySelectorAll('[data-stagger]'),
      function (group) {
        var step = parseFloat(group.getAttribute('data-stagger')) || 90;
        Array.prototype.forEach.call(group.children, function (child, i) {
          if (child.classList.contains('reveal') && !child.style.getPropertyValue('--reveal-delay')) {
            child.style.setProperty('--reveal-delay', (i * step) + 'ms');
          }
        });
      }
    );

    var visible = function (el) { el.classList.add('is-visible'); };

    if (reduced() || !('IntersectionObserver' in window)) {
      items.forEach(visible);
      return;
    }

    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          visible(entry.target);
          io.unobserve(entry.target);
        }
      });
    }, { rootMargin: '0px 0px -8% 0px', threshold: 0.08 });

    items.forEach(function (el) { io.observe(el); });
  }

  /* ------------------------------------------------------------------ *
   * boot
   * ------------------------------------------------------------------ */
  function init() {
    initStarfield();
    initReveals();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
