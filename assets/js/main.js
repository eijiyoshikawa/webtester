/**
 * Chillna - Main JavaScript
 * 訪問看護ステーション ちるな
 */

(function () {
  'use strict';

  // ========== Header Scroll Effect ==========
  const header = document.getElementById('header');

  function handleHeaderScroll() {
    if (window.scrollY > 50) {
      header.classList.add('is-scrolled');
    } else {
      header.classList.remove('is-scrolled');
    }
  }

  window.addEventListener('scroll', handleHeaderScroll, { passive: true });

  // ========== Hamburger Menu ==========
  const hamburger = document.getElementById('hamburger');
  const globalNav = document.getElementById('globalNav');

  hamburger.addEventListener('click', function () {
    this.classList.toggle('is-active');
    globalNav.classList.toggle('is-active');
    document.body.style.overflow = globalNav.classList.contains('is-active') ? 'hidden' : '';
  });

  // Close nav when clicking a link
  globalNav.querySelectorAll('.header__nav-link').forEach(function (link) {
    link.addEventListener('click', function () {
      hamburger.classList.remove('is-active');
      globalNav.classList.remove('is-active');
      document.body.style.overflow = '';
    });
  });

  // ========== Smooth Scroll ==========
  document.querySelectorAll('a[href^="#"]').forEach(function (anchor) {
    anchor.addEventListener('click', function (e) {
      e.preventDefault();
      var targetId = this.getAttribute('href');
      if (targetId === '#') return;

      var target = document.querySelector(targetId);
      if (!target) return;

      var headerHeight = header.offsetHeight;
      var targetPosition = target.getBoundingClientRect().top + window.pageYOffset - headerHeight;

      window.scrollTo({
        top: targetPosition,
        behavior: 'smooth'
      });
    });
  });

  // ========== Fade-in on Scroll (Intersection Observer) ==========
  var fadeElements = document.querySelectorAll('.fade-in');

  if ('IntersectionObserver' in window) {
    var observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');
          observer.unobserve(entry.target);
        }
      });
    }, {
      threshold: 0.15,
      rootMargin: '0px 0px -50px 0px'
    });

    fadeElements.forEach(function (el) {
      observer.observe(el);
    });
  } else {
    // Fallback: show all elements
    fadeElements.forEach(function (el) {
      el.classList.add('is-visible');
    });
  }

  // ========== Back to Top Button ==========
  var backToTop = document.getElementById('backToTop');

  function handleBackToTop() {
    if (window.scrollY > 500) {
      backToTop.classList.add('is-visible');
    } else {
      backToTop.classList.remove('is-visible');
    }
  }

  window.addEventListener('scroll', handleBackToTop, { passive: true });

  // ========== Active Navigation Highlight ==========
  var sections = document.querySelectorAll('section[id]');
  var navLinks = document.querySelectorAll('.header__nav-link');

  function highlightNav() {
    var scrollPos = window.scrollY + header.offsetHeight + 100;

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

  // ========== Staff Number Counter Animation ==========
  var staffNumbers = document.querySelectorAll('.staff__number');

  if ('IntersectionObserver' in window && staffNumbers.length > 0) {
    var counterObserver = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          animateCounter(entry.target);
          counterObserver.unobserve(entry.target);
        }
      });
    }, { threshold: 0.5 });

    staffNumbers.forEach(function (el) {
      counterObserver.observe(el);
    });
  }

  function animateCounter(el) {
    var target = parseInt(el.textContent, 10);
    var current = 0;
    var duration = 1500;
    var step = Math.max(1, Math.floor(duration / target / 16));
    var increment = 1;

    el.textContent = '0';

    var timer = setInterval(function () {
      current += increment;
      if (current >= target) {
        current = target;
        clearInterval(timer);
      }
      el.textContent = current;
    }, step * 16);
  }

  // ========== Initialize ==========
  handleHeaderScroll();
  handleBackToTop();

})();
