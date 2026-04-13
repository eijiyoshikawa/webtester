/* ============================================
   SUISEI STUDIO — main.js
   Vanilla JS: cursor, scroll animations, text split,
   hamburger menu, FAQ accordion
   ============================================ */

(function () {
  'use strict';

  // --- Custom Cursor ---
  var cursor = document.querySelector('.cursor');
  var follower = document.querySelector('.cursor-follower');
  var isTouch = window.matchMedia('(hover: none)').matches;

  if (!isTouch && cursor && follower) {
    var mx = 0, my = 0;
    var fx = 0, fy = 0;

    document.addEventListener('mousemove', function (e) {
      mx = e.clientX;
      my = e.clientY;
      cursor.style.left = mx + 'px';
      cursor.style.top = my + 'px';
    });

    function followCursor() {
      fx += (mx - fx) * 0.12;
      fy += (my - fy) * 0.12;
      follower.style.left = fx + 'px';
      follower.style.top = fy + 'px';
      requestAnimationFrame(followCursor);
    }
    requestAnimationFrame(followCursor);

    // Hover effect on interactive elements
    function bindCursorHover() {
      var hoverTargets = document.querySelectorAll('a, button, .cta__button, .guide__cta-button');
      hoverTargets.forEach(function (el) {
        el.addEventListener('mouseenter', function () {
          cursor.classList.add('is-hover');
          follower.classList.add('is-hover');
        });
        el.addEventListener('mouseleave', function () {
          cursor.classList.remove('is-hover');
          follower.classList.remove('is-hover');
        });
      });
    }
    bindCursorHover();
  }

  // --- Navigation Scroll Effect ---
  var nav = document.getElementById('nav');
  if (nav) {
    window.addEventListener('scroll', function () {
      if (window.scrollY > 50) {
        nav.classList.add('is-scrolled');
      } else {
        nav.classList.remove('is-scrolled');
      }
    });
  }

  // --- Hamburger Menu ---
  var hamburger = document.getElementById('hamburger');
  var mobileMenu = document.getElementById('mobile-menu');

  if (hamburger && mobileMenu) {
    hamburger.addEventListener('click', function () {
      hamburger.classList.toggle('is-active');
      mobileMenu.classList.toggle('is-open');
      document.body.style.overflow = mobileMenu.classList.contains('is-open') ? 'hidden' : '';
    });

    // Close menu on link click
    var menuLinks = mobileMenu.querySelectorAll('.mobile-menu__link');
    menuLinks.forEach(function (link) {
      link.addEventListener('click', function () {
        hamburger.classList.remove('is-active');
        mobileMenu.classList.remove('is-open');
        document.body.style.overflow = '';
      });
    });
  }

  // --- Hero Load Animation ---
  document.addEventListener('DOMContentLoaded', function () {
    var heroLines = document.querySelectorAll('.hero__line');
    var heroSub = document.querySelector('.hero__sub');

    heroLines.forEach(function (line, i) {
      setTimeout(function () {
        line.classList.add('is-visible');
      }, 300 + i * 250);
    });

    if (heroSub) {
      setTimeout(function () {
        heroSub.classList.add('is-visible');
      }, 900);
    }
  });

  // --- Statement Text Splitting ---
  var statementText = document.getElementById('statement-text');
  if (statementText) {
    var text = statementText.textContent;
    statementText.textContent = '';
    for (var i = 0; i < text.length; i++) {
      var span = document.createElement('span');
      span.className = 'char';
      span.textContent = text[i];
      span.style.transitionDelay = (i * 40) + 'ms';
      statementText.appendChild(span);
    }
  }

  // --- FAQ Accordion ---
  var faqQuestions = document.querySelectorAll('.faq__question');
  faqQuestions.forEach(function (btn) {
    btn.addEventListener('click', function () {
      var answer = btn.nextElementSibling;
      var isOpen = btn.getAttribute('aria-expanded') === 'true';

      // Close all others in same category
      var category = btn.closest('.faq__category');
      if (category) {
        category.querySelectorAll('.faq__question').forEach(function (otherBtn) {
          if (otherBtn !== btn) {
            otherBtn.setAttribute('aria-expanded', 'false');
            var otherAnswer = otherBtn.nextElementSibling;
            if (otherAnswer) otherAnswer.classList.remove('is-open');
          }
        });
      }

      // Toggle clicked
      btn.setAttribute('aria-expanded', isOpen ? 'false' : 'true');
      if (answer) answer.classList.toggle('is-open', !isOpen);
    });
  });

  // --- Scroll-Triggered Animations (IntersectionObserver) ---
  var animElements = document.querySelectorAll('[data-animate]');
  if ('IntersectionObserver' in window) {
    var observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');

          // If this is the statement text, reveal chars
          if (entry.target.querySelector('.char')) {
            var chars = entry.target.querySelectorAll('.char');
            chars.forEach(function (c) {
              c.classList.add('is-visible');
            });
          }

          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.15 });

    animElements.forEach(function (el) {
      observer.observe(el);
    });
  } else {
    // Fallback: make everything visible
    animElements.forEach(function (el) {
      el.classList.add('is-visible');
      var chars = el.querySelectorAll('.char');
      chars.forEach(function (c) { c.classList.add('is-visible'); });
    });
  }

  // --- Smooth scroll for anchor links ---
  document.querySelectorAll('a[href^="#"]').forEach(function (anchor) {
    anchor.addEventListener('click', function (e) {
      var target = document.querySelector(this.getAttribute('href'));
      if (target) {
        e.preventDefault();
        target.scrollIntoView({ behavior: 'smooth' });
      }
    });
  });

})();
