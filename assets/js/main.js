/* ============================================
   SUISEI STUDIO — main.js
   Vanilla JS: cursor, scroll animations, text split
   ============================================ */

(function () {
  'use strict';

  // --- Custom Cursor ---
  const cursor = document.querySelector('.cursor');
  const follower = document.querySelector('.cursor-follower');
  const isTouch = window.matchMedia('(hover: none)').matches;

  if (!isTouch && cursor && follower) {
    let mx = 0, my = 0;
    let fx = 0, fy = 0;

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

    var hoverTargets = document.querySelectorAll('a, button, .cta__button');
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

  // --- Hero Load Animation ---
  window.addEventListener('DOMContentLoaded', function () {
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

})();
