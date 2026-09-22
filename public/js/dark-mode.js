(function () {
  var KEY = 'pdv_jils_dark_mode';

  function isDark() {
    return localStorage.getItem(KEY) === '1';
  }

  function apply(on) {
    document.body.classList.toggle('dark-mode', !!on);
    // Home / landing sin AdminLTE
    document.documentElement.classList.toggle('theme-dark', !!on);
    document.body.classList.toggle('theme-dark', !!on);

    var icons = document.querySelectorAll('#icon-dark-mode, .js-dark-mode-icon');
    icons.forEach(function (icon) {
      icon.classList.remove('fa-moon', 'fa-sun');
      icon.classList.add(on ? 'fa-sun' : 'fa-moon');
    });

    var btns = document.querySelectorAll('#btn-dark-mode, .js-dark-mode-btn');
    btns.forEach(function (btn) {
      btn.setAttribute('title', on ? 'Modo claro' : 'Modo oscuro');
      btn.setAttribute('aria-label', on ? 'Modo claro' : 'Modo oscuro');
    });

    localStorage.setItem(KEY, on ? '1' : '0');
  }

  function toggle(e) {
    if (e) e.preventDefault();
    apply(!isDark());
  }

  // Aplicar cuanto antes (evita parpadeo)
  apply(isDark());

  document.addEventListener('click', function (e) {
    var t = e.target.closest && e.target.closest('#btn-dark-mode, .js-dark-mode-btn');
    if (t) toggle(e);
  });

  document.addEventListener('DOMContentLoaded', function () {
    apply(isDark());
  });
})();
