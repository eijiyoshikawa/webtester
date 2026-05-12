(function () {
  var tabs = document.querySelectorAll('.recruitTab');
  if (!tabs.length) return;

  tabs.forEach(function (tab) {
    tab.addEventListener('click', function () {
      var target = tab.getAttribute('data-tab-target');
      if (!target) return;

      document.querySelectorAll('.recruitTab').forEach(function (t) {
        var active = t === tab;
        t.classList.toggle('is-active', active);
        t.setAttribute('aria-selected', active ? 'true' : 'false');
      });

      document.querySelectorAll('.recruitPanel').forEach(function (p) {
        p.classList.toggle('is-active', p.id === target);
      });
    });
  });
})();
