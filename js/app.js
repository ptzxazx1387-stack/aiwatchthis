/* ==========================================================================
   سامانه کمپین — UI runtime
   منبع اصلی. پس از تغییر کپی کنید:  cp resources/js/app.js js/app.js
   بدون وابستگی، بدون build. همه‌ی قابلیت‌ها «افزودنی» هستند: صفحه بدون
   جاوااسکریپت هم کامل و قابل استفاده است.
   ========================================================================== */
(function () {
  'use strict';

  var root = document.documentElement;
  root.classList.remove('no-js');

  var reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* ---------------------------------------------------------------- utils */
  function $(sel, ctx) { return (ctx || document).querySelector(sel); }
  function $$(sel, ctx) { return Array.prototype.slice.call((ctx || document).querySelectorAll(sel)); }

  function on(el, ev, fn, opts) { if (el) el.addEventListener(ev, fn, opts); }

  // برای جست‌وجوی فارسی: یکسان‌سازی ی/ک عربی، حذف نیم‌فاصله و اعراب، ارقام فارسی → لاتین
  var FA_DIGITS = '۰۱۲۳۴۵۶۷۸۹';
  var AR_DIGITS = '٠١٢٣٤٥٦٧٨٩';
  function fold(s) {
    if (!s) return '';
    s = String(s).toLowerCase();
    var out = '';
    for (var i = 0; i < s.length; i++) {
      var c = s[i];
      var fi = FA_DIGITS.indexOf(c); if (fi > -1) { out += fi; continue; }
      var ai = AR_DIGITS.indexOf(c); if (ai > -1) { out += ai; continue; }
      if (c === 'ي' || c === 'ﻯ' || c === 'ى') { out += 'ی'; continue; }
      if (c === 'ك') { out += 'ک'; continue; }
      if (c === 'ة') { out += 'ه'; continue; }
      if (c === '‌' || c === '‏' || c === '‎') { continue; }
      if (c >= 'ً' && c <= 'ْ') { continue; }
      out += c;
    }
    return out.replace(/\s+/g, ' ').trim();
  }

  function toFaDigits(s) {
    return String(s).replace(/[0-9]/g, function (d) { return FA_DIGITS[+d]; });
  }

  /* -------------------------------------------------------------- entrances
     یک بار، وقتی ۸۰٪ ویوپورت را رد کرد. هرگز در اسکرول برگشتی تکرار نمی‌شود. */
  (function reveals() {
    var items = $$('[data-reveal]');
    if (!items.length) return;

    if (reduced || !('IntersectionObserver' in window)) {
      items.forEach(function (el) { el.setAttribute('data-shown', 'true'); });
      return;
    }

    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (!entry.isIntersecting) return;
        var el = entry.target;
        var siblings = el.parentElement ? $$('[data-reveal]', el.parentElement) : [el];
        var index = Math.max(0, siblings.indexOf(el));
        el.style.setProperty('--reveal-delay', Math.min(index, 9) * 80 + 'ms');
        el.setAttribute('data-shown', 'true');
        io.unobserve(el);
      });
    }, { rootMargin: '0px 0px -20% 0px', threshold: 0.01 });

    items.forEach(function (el) { io.observe(el); });
  })();

  /* ------------------------------------------------------- topbar condense */
  (function topbar() {
    var bar = $('[data-topbar]');
    if (!bar) return;
    var progress = $('[data-scroll-progress]');

    function frame() {
      var y = window.scrollY || 0;
      bar.setAttribute('data-condensed', y > 12 ? 'true' : 'false');
      if (progress) {
        var max = document.body.scrollHeight - window.innerHeight;
        progress.style.transform = 'scaleX(' + (max > 40 ? Math.min(1, y / max) : 0) + ')';
      }
    }
    var ticking = false;
    on(window, 'scroll', function () {
      if (ticking) return;
      ticking = true;
      window.requestAnimationFrame(function () { frame(); ticking = false; });
    }, { passive: true });
    frame();
  })();

  /* ------------------------------------------------------------ rail drawer */
  (function rail() {
    var body = document.body;
    function setRail(open) {
      body.setAttribute('data-rail', open ? 'open' : 'closed');
      $$('[data-rail-toggle]').forEach(function (b) { b.setAttribute('aria-expanded', open ? 'true' : 'false'); });
      if (open) { var first = $('.rail__nav .navlink'); if (first) first.focus({ preventScroll: true }); }
    }
    $$('[data-rail-toggle]').forEach(function (btn) {
      on(btn, 'click', function () { setRail(body.getAttribute('data-rail') !== 'open'); });
    });
    on($('[data-rail-scrim]'), 'click', function () { setRail(false); });
    on(document, 'keydown', function (e) {
      if (e.key === 'Escape' && body.getAttribute('data-rail') === 'open') setRail(false);
    });
    // انتخاب یک لینک در موبایل، کشو را می‌بندد
    $$('.rail__nav a').forEach(function (a) {
      on(a, 'click', function () { if (window.innerWidth <= 1000) setRail(false); });
    });
  })();

  /* ----------------------------------------------------------------- toasts */
  (function toasts() {
    $$('.toast').forEach(function (t) {
      var life = parseInt(t.getAttribute('data-life') || '6000', 10);
      var timer = setTimeout(function () { dismiss(t); }, life);
      on(t, 'mouseenter', function () { clearTimeout(timer); });
      on($('[data-toast-close]', t), 'click', function () { clearTimeout(timer); dismiss(t); });
    });
    function dismiss(t) {
      t.setAttribute('data-leaving', 'true');
      setTimeout(function () { t.remove(); }, reduced ? 0 : 240);
    }
  })();

  /* ------------------------------------------------------ modal plumbing */
  var openModals = [];

  function openModal(el) {
    if (!el) return;
    el.hidden = false;
    el.removeAttribute('data-leaving');
    document.body.style.overflow = 'hidden';
    openModals.push(el);
    var focusable = firstFocusable(el);
    if (focusable) focusable.focus({ preventScroll: true });
  }

  function closeModal(el) {
    if (!el || el.hidden) return;
    el.setAttribute('data-leaving', 'true');
    var done = function () {
      el.hidden = true;
      el.removeAttribute('data-leaving');
      openModals = openModals.filter(function (m) { return m !== el; });
      if (!openModals.length) document.body.style.overflow = '';
      var opener = el.__opener;
      if (opener && document.contains(opener)) opener.focus({ preventScroll: true });
    };
    if (reduced) done(); else setTimeout(done, 240);
  }

  function firstFocusable(el) {
    return $('input:not([type=hidden]), textarea, select, button, [href]', el);
  }

  on(document, 'click', function (e) {
    var scrim = e.target.closest ? e.target.closest('.modal__scrim, [data-modal-close]') : null;
    if (!scrim) return;
    var modal = scrim.closest('.modal');
    if (modal) { e.preventDefault(); closeModal(modal); }
  });

  on(document, 'keydown', function (e) {
    if (e.key !== 'Escape' || !openModals.length) return;
    closeModal(openModals[openModals.length - 1]);
  });

  // نگه‌داشتن فوکوس داخل دیالوگ باز
  on(document, 'keydown', function (e) {
    if (e.key !== 'Tab' || !openModals.length) return;
    var modal = openModals[openModals.length - 1];
    var items = $$('a[href], button:not([disabled]), input:not([type=hidden]), select, textarea, [tabindex]:not([tabindex="-1"])', modal)
      .filter(function (el) { return el.offsetParent !== null; });
    if (!items.length) return;
    var first = items[0], last = items[items.length - 1];
    if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus(); }
    else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus(); }
  });

  /* --------------------------------------------- confirm before submitting
     هر فرم/دکمه‌ی «data-confirm» به‌جای confirm() مرورگر، یک دیالوگ آرام
     می‌گیرد. اگر JS نباشد فرم مثل قبل ارسال می‌شود. */
  (function confirmations() {
    var modal = $('[data-confirm-modal]');
    if (!modal) return;
    var titleEl = $('[data-confirm-title]', modal);
    var bodyEl = $('[data-confirm-body]', modal);
    var okEl = $('[data-confirm-ok]', modal);
    var pending = null;

    on(document, 'submit', function (e) {
      var form = e.target;
      if (!form.matches || !form.matches('[data-confirm]')) return;
      if (form.__confirmed) { form.__confirmed = false; return; }
      e.preventDefault();
      pending = form;
      titleEl.textContent = form.getAttribute('data-confirm-title') || 'مطمئن هستید؟';
      bodyEl.textContent = form.getAttribute('data-confirm') || '';
      okEl.textContent = form.getAttribute('data-confirm-ok') || 'بله، انجام بده';
      okEl.className = 'btn ' + (form.getAttribute('data-confirm-tone') === 'rose' ? 'btn--rose' : '');
      modal.__opener = document.activeElement;
      openModal(modal);
    });

    on(okEl, 'click', function () {
      var form = pending;
      closeModal(modal);
      if (!form) return;
      form.__confirmed = true;
      // requestSubmit تا اعتبارسنجی HTML هم اجرا شود
      if (form.requestSubmit) form.requestSubmit(); else form.submit();
    });
  })();

  /* ------------------------------------------------- «ثبت دلیل» ---------
     فرم‌های [data-reason-form] یک فیلد دلیلِ پنهان دارند. با JS، به‌جای
     ارسال مستقیم، یک دیالوگ کوچک باز می‌شود و مقدار را در همان فیلد
     می‌گذارد. بدون JS فرم مثل همیشه ارسال می‌شود و اعتبارسنجی سمت سرور
     کار خودش را می‌کند — هیچ مسیری بن‌بست نیست. */
  (function reasons() {
    var modal = $('[data-reason-modal]');
    if (!modal) return;

    var titleEl = $('[data-reason-title]', modal);
    var labelEl = $('[data-reason-label]', modal);
    var inputEl = $('[data-reason-input]', modal);
    var errEl = $('[data-reason-err]', modal);
    var okEl = $('[data-reason-ok]', modal);
    var pending = null;
    var field = null;

    on(document, 'submit', function (e) {
      var form = e.target;
      if (!form.matches || !form.matches('[data-reason-form]')) return;
      if (form.__reasoned) { form.__reasoned = false; return; }

      field = $('[data-reason-field]', form);
      if (!field) return;

      e.preventDefault();
      pending = form;

      titleEl.textContent = form.getAttribute('data-reason-title') || 'ثبت دلیل';
      labelEl.textContent = form.getAttribute('data-reason-label') || 'دلیل';
      okEl.textContent = form.getAttribute('data-reason-ok') || 'ثبت';
      okEl.className = 'btn ' + (form.getAttribute('data-reason-tone') === 'rose' ? 'btn--rose' : '');
      inputEl.value = field.value || '';
      inputEl.placeholder = form.getAttribute('data-reason-placeholder') || 'کوتاه و روشن بنویسید.';
      errEl.hidden = true;

      modal.__opener = document.activeElement;
      openModal(modal);
    });

    on(okEl, 'click', function () {
      if (!pending) return;
      var required = pending.getAttribute('data-reason-required') === 'true';

      if (required && !inputEl.value.trim()) {
        errEl.hidden = false;
        inputEl.focus();
        return;
      }

      field.value = inputEl.value;
      var form = pending;
      pending = null;
      closeModal(modal);
      form.__reasoned = true;
      if (form.requestSubmit) form.requestSubmit(); else form.submit();
    });
  })();

  /* ------------------------------------------------------------- popovers */
  (function popovers() {
    $$('[data-popover-trigger]').forEach(function (btn) {
      var wrap = btn.closest('.has-popover');
      var pop = $('.popover', wrap);
      if (!pop) return;
      on(btn, 'click', function (e) {
        e.stopPropagation();
        var willOpen = pop.hidden;
        $$('.popover').forEach(function (p) { p.hidden = true; });
        pop.hidden = !willOpen;
        btn.setAttribute('aria-expanded', willOpen ? 'true' : 'false');
      });
    });
    on(document, 'click', function (e) {
      $$('.popover').forEach(function (p) {
        if (!p.hidden && !p.contains(e.target)) {
          p.hidden = true;
          var t = $('[data-popover-trigger]', p.closest('.has-popover'));
          if (t) t.setAttribute('aria-expanded', 'false');
        }
      });
    });
    on(document, 'keydown', function (e) {
      if (e.key === 'Escape') $$('.popover').forEach(function (p) { p.hidden = true; });
    });
  })();

  /* ------------------------------------------------------ command palette */
  (function palette() {
    var modal = $('[data-palette]');
    var dataEl = $('#palette-data');
    if (!modal || !dataEl) return;

    var items = [];
    try { items = JSON.parse(dataEl.textContent) || []; } catch (err) { items = []; }
    items.forEach(function (it) { it._k = fold(it.label + ' ' + (it.keywords || '') + ' ' + (it.group || '')); });

    var input = $('[data-palette-input]', modal);
    var list = $('[data-palette-list]', modal);
    var cursor = 0;
    var shown = [];

    function render(q) {
      var f = fold(q);
      shown = f ? items.filter(function (it) { return it._k.indexOf(f) > -1; }) : items.slice();
      cursor = 0;
      if (!shown.length) {
        list.innerHTML = '<div class="empty empty--tight"><div class="empty__title">چیزی پیدا نشد</div>' +
          '<div class="empty__body">عبارت دیگری را امتحان کنید.</div></div>';
        return;
      }
      var html = '';
      var lastGroup = null;
      shown.forEach(function (it, i) {
        if (it.group && it.group !== lastGroup) {
          html += '<div class="palette__group">' + esc(it.group) + '</div>';
          lastGroup = it.group;
        }
        html += '<a class="palette__item" href="' + esc(it.url) + '" data-i="' + i + '"' +
          (i === 0 ? ' data-active="true"' : '') + '>' +
          '<span>' + esc(it.label) + '</span>' +
          (it.hint ? '<span class="palette__sub">' + esc(it.hint) + '</span>' : '') +
          '</a>';
      });
      list.innerHTML = html;
    }

    function esc(s) {
      return String(s == null ? '' : s).replace(/[&<>"']/g, function (c) {
        return ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' })[c];
      });
    }

    function move(delta) {
      var links = $$('.palette__item', list);
      if (!links.length) return;
      links[cursor] && links[cursor].removeAttribute('data-active');
      cursor = (cursor + delta + links.length) % links.length;
      links[cursor].setAttribute('data-active', 'true');
      links[cursor].scrollIntoView({ block: 'nearest' });
    }

    function open() {
      modal.__opener = document.activeElement;
      render('');
      input.value = '';
      openModal(modal);
    }

    $$('[data-palette-open]').forEach(function (b) { on(b, 'click', open); });

    on(document, 'keydown', function (e) {
      var mod = e.metaKey || e.ctrlKey;
      if (mod && (e.key === 'k' || e.key === 'K' || e.code === 'KeyK')) {
        e.preventDefault();
        modal.hidden ? open() : closeModal(modal);
        return;
      }
      // «/» وقتی در یک فیلد نیستیم
      if (e.key === '/' && modal.hidden && !/^(INPUT|TEXTAREA|SELECT)$/.test((document.activeElement || {}).tagName || '')) {
        e.preventDefault();
        open();
      }
    });

    on(input, 'input', function () { render(input.value); });
    on(input, 'keydown', function (e) {
      if (e.key === 'ArrowDown') { e.preventDefault(); move(1); }
      else if (e.key === 'ArrowUp') { e.preventDefault(); move(-1); }
      else if (e.key === 'Enter') {
        var links = $$('.palette__item', list);
        if (links[cursor]) { e.preventDefault(); window.location.href = links[cursor].getAttribute('href'); }
      }
    });
    on(list, 'mousemove', function (e) {
      var item = e.target.closest ? e.target.closest('.palette__item') : null;
      if (!item) return;
      var i = parseInt(item.getAttribute('data-i'), 10);
      if (i === cursor) return;
      $$('.palette__item', list).forEach(function (l) { l.removeAttribute('data-active'); });
      item.setAttribute('data-active', 'true');
      cursor = i;
    });
  })();

  /* -------------------------------------------------------- copy to clipboard */
  (function copy() {
    $$('[data-copy]').forEach(function (btn) {
      on(btn, 'click', function () {
        var text = btn.getAttribute('data-copy');
        var done = function () {
          var old = btn.getAttribute('aria-label') || '';
          btn.setAttribute('data-copied', 'true');
          btn.setAttribute('aria-label', 'کپی شد');
          setTimeout(function () {
            btn.removeAttribute('data-copied');
            btn.setAttribute('aria-label', old);
          }, 1600);
        };
        if (navigator.clipboard && navigator.clipboard.writeText) {
          navigator.clipboard.writeText(text).then(done, function () {});
        } else {
          var ta = document.createElement('textarea');
          ta.value = text; document.body.appendChild(ta); ta.select();
          try { document.execCommand('copy'); done(); } catch (err) {}
          ta.remove();
        }
      });
    });
  })();

  /* -------------------------------------------- استان → شهر (وابسته، AJAX) */
  (function geo() {
    var province = $('[data-geo-province]');
    var city = $('[data-geo-city]');
    if (!province || !city) return;
    var url = province.getAttribute('data-geo-url');
    var blank = city.getAttribute('data-blank-label') || 'همه شهرها';

    on(province, 'change', function () {
      city.innerHTML = '<option value="">' + blank + '</option>';
      if (!province.value) return;
      city.disabled = true;
      fetch(url + '?province_id=' + encodeURIComponent(province.value), { headers: { 'Accept': 'application/json' } })
        .then(function (r) { return r.json(); })
        .then(function (rows) {
          (rows || []).forEach(function (c) {
            var o = document.createElement('option');
            o.value = c.id; o.textContent = c.name;
            city.appendChild(o);
          });
        })
        .catch(function () {})
        .then(function () { city.disabled = false; });
    });
  })();

  /* ------------------------------------------- فیلترهایی که خودکار اعمال می‌شوند */
  (function autosubmit() {
    $$('[data-autosubmit]').forEach(function (el) {
      on(el, 'change', function () { if (el.form) el.form.submit(); });
    });
  })();

  /* --------------------------------------- نمایش هزارگان زیر فیلدهای مبلغ */
  (function money() {
    $$('[data-money]').forEach(function (input) {
      var out = document.getElementById(input.getAttribute('data-money'));
      if (!out) return;
      function paint() {
        var n = parseInt(String(input.value).replace(/\D/g, ''), 10);
        out.textContent = isNaN(n) || n <= 0 ? '' : toFaDigits(n.toLocaleString('en-US')) + ' تومان';
      }
      on(input, 'input', paint);
      paint();
    });
  })();

  /* ----------------------------- برآورد هزینه‌ی کمپین، همان لحظه که تایپ می‌شود */
  (function cost() {
    var price = $('[data-cost-price]');
    var capacity = $('[data-cost-capacity]');
    var total = $('[data-cost-total]');
    if (!price || !capacity || !total) return;

    function paint() {
      var p = parseFloat(price.value) || 0;
      var c = parseFloat(capacity.value) || 0;
      var n = Math.round(p * c);
      total.textContent = n > 0 ? toFaDigits(n.toLocaleString('en-US')) + ' تومان' : '—';
    }
    on(price, 'input', paint);
    on(capacity, 'input', paint);
    paint();
  })();

  /* -------------------------------- پیش‌نمایش تصویر پیش از آپلود اسکرین‌شات */
  (function filePreview() {
    $$('[data-preview]').forEach(function (input) {
      var box = document.getElementById(input.getAttribute('data-preview'));
      if (!box) return;
      on(input, 'change', function () {
        var f = input.files && input.files[0];
        box.innerHTML = '';
        if (!f) { box.hidden = true; return; }
        var img = document.createElement('img');
        img.alt = 'پیش‌نمایش اسکرین‌شات';
        img.src = URL.createObjectURL(f);
        on(img, 'load', function () { URL.revokeObjectURL(img.src); });
        box.appendChild(img);
        box.hidden = false;
      });
    });
  })();

  /* --------------------------- ذخیره‌ی ناخودآگاه فرم‌های بلند در همین مرورگر */
  (function draft() {
    $$('[data-draft]').forEach(function (form) {
      var key = 'draft:' + form.getAttribute('data-draft');
      var fields = $$('input[name], textarea[name], select[name]', form)
        .filter(function (f) { return !/password|_token|file/i.test(f.type + ' ' + f.name); });

      try {
        var saved = JSON.parse(localStorage.getItem(key) || 'null');
        if (saved) {
          fields.forEach(function (f) {
            if (saved[f.name] != null && f.value === '') f.value = saved[f.name];
          });
        }
      } catch (err) {}

      var t;
      fields.forEach(function (f) {
        on(f, 'input', function () {
          clearTimeout(t);
          t = setTimeout(function () {
            var data = {};
            fields.forEach(function (x) { data[x.name] = x.value; });
            try { localStorage.setItem(key, JSON.stringify(data)); } catch (err) {}
          }, 400);
        });
      });

      on(form, 'submit', function () { try { localStorage.removeItem(key); } catch (err) {} });
    });
  })();
})();
