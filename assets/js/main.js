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
    globalNav.querySelectorAll('.header__nav-link').forEach(function (link) {
      link.addEventListener('click', function () {
        hamburger.classList.remove('is-active');
        globalNav.classList.remove('is-active');
        document.body.style.overflow = '';
      });
    });
  }

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

  // ========== Initialize ==========
  handleScroll();
})();
