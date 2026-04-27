/**
 * Chillna - Main JavaScript
 * 訪問看護ステーション ちるな
 */
(function () {
  'use strict';

  var header = document.getElementById('header');
  var hamburger = document.getElementById('hamburger');
  var globalNav = document.getElementById('globalNav');
  var backToTop = document.getElementById('backToTop');

  // ========== Header Scroll Effect ==========
  function handleScroll() {
    if (window.scrollY > 50) {
      header.classList.add('is-scrolled');
    } else {
      header.classList.remove('is-scrolled');
    }
    if (backToTop) {
      if (window.scrollY > 500) {
        backToTop.classList.add('is-visible');
      } else {
        backToTop.classList.remove('is-visible');
      }
    }
  }
  window.addEventListener('scroll', handleScroll, { passive: true });

  // ========== Hamburger Menu ==========
  if (hamburger && globalNav) {
    hamburger.addEventListener('click', function () {
      this.classList.toggle('is-active');
      globalNav.classList.toggle('is-active');
      document.body.style.overflow = globalNav.classList.contains('is-active') ? 'hidden' : '';
    });
    globalNav.querySelectorAll('.header__nav-link, .header__nav-subitem').forEach(function (link) {
      link.addEventListener('click', function () {
        hamburger.classList.remove('is-active');
        globalNav.classList.remove('is-active');
        document.body.style.overflow = '';
      });
    });
  }

  // ========== Dropdown Menu (サービス) ==========
  document.querySelectorAll('[data-dropdown]').forEach(function (dropdown) {
    var trigger = dropdown.querySelector('.header__nav-trigger');
    if (!trigger) return;
    trigger.addEventListener('click', function (e) {
      e.stopPropagation();
      dropdown.classList.toggle('is-open');
    });
  });
  // Close dropdown on outside click
  document.addEventListener('click', function (e) {
    document.querySelectorAll('[data-dropdown].is-open').forEach(function (d) {
      if (!d.contains(e.target)) d.classList.remove('is-open');
    });
  });

  // ========== Smooth Scroll ==========
  document.querySelectorAll('a[href^="#"]').forEach(function (anchor) {
    anchor.addEventListener('click', function (e) {
      var targetId = this.getAttribute('href');
      if (targetId === '#') return;
      var target = document.querySelector(targetId);
      if (!target) return;
      e.preventDefault();
      var headerHeight = header ? header.offsetHeight : 0;
      window.scrollTo({
        top: target.getBoundingClientRect().top + window.pageYOffset - headerHeight,
        behavior: 'smooth'
      });
    });
  });

  // ========== Fade-in on Scroll ==========
  var fadeElements = document.querySelectorAll('.fade-in');
  if ('IntersectionObserver' in window) {
    var observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });
    fadeElements.forEach(function (el) { observer.observe(el); });
  } else {
    fadeElements.forEach(function (el) { el.classList.add('is-visible'); });
  }

  // ========== Active Nav Highlight ==========
  var sections = document.querySelectorAll('section[id]');
  var navLinks = document.querySelectorAll('.header__nav-link');
  function highlightNav() {
    var scrollPos = window.scrollY + (header ? header.offsetHeight : 0) + 100;
    sections.forEach(function (section) {
      var top = section.offsetTop;
      var bottom = top + section.offsetHeight;
      var id = section.getAttribute('id');
      if (scrollPos >= top && scrollPos < bottom) {
        navLinks.forEach(function (link) {
          link.classList.remove('is-active');
          if (link.getAttribute('href') === '#' + id) {
            link.classList.add('is-active');
          }
        });
      }
    });
  }
  window.addEventListener('scroll', highlightNav, { passive: true });

  // ========== Hero Slideshow ==========
  var slides = document.querySelectorAll('.hero__slide');
  if (slides.length > 1) {
    var currentSlide = 0;
    setInterval(function() {
      slides[currentSlide].classList.remove('hero__slide--active');
      currentSlide = (currentSlide + 1) % slides.length;
      slides[currentSlide].classList.add('hero__slide--active');
    }, 5000);
  }

  // ========== Lightbox (写真クリックで拡大表示) ==========
  function initLightbox() {
    var targets = document.querySelectorAll('.svc-home-card__image, .svc-gallery__item');
    if (!targets.length) return;

    var overlay = document.createElement('div');
    overlay.className = 'lightbox';
    overlay.setAttribute('aria-hidden', 'true');
    overlay.innerHTML =
      '<button class="lightbox__close" type="button" aria-label="閉じる">' +
      '<svg viewBox="0 0 24 24" fill="none" width="24" height="24"><path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>' +
      '</button>' +
      '<img class="lightbox__img" alt="">';
    document.body.appendChild(overlay);

    var img = overlay.querySelector('.lightbox__img');
    var closeBtn = overlay.querySelector('.lightbox__close');

    function open(url) {
      img.src = url;
      overlay.classList.add('is-open');
      overlay.setAttribute('aria-hidden', 'false');
      document.body.style.overflow = 'hidden';
    }
    function close() {
      overlay.classList.remove('is-open');
      overlay.setAttribute('aria-hidden', 'true');
      document.body.style.overflow = '';
      // delay clearing src so transition can finish
      setTimeout(function () { img.src = ''; }, 300);
    }

    targets.forEach(function (el) {
      el.addEventListener('click', function () {
        var bg = el.style.backgroundImage || getComputedStyle(el).backgroundImage;
        var match = bg.match(/url\((['"]?)([^'")]+)\1\)/);
        if (match && match[2]) open(match[2]);
      });
    });

    overlay.addEventListener('click', function (e) {
      if (e.target === overlay || closeBtn.contains(e.target) || e.target === closeBtn) close();
    });

    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && overlay.classList.contains('is-open')) close();
    });
  }
  initLightbox();

  // ========== Initialize ==========
  handleScroll();
})();
