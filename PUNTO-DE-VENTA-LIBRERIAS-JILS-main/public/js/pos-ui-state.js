/**
 * Persistencia de UI para PDV JILS
 * - Sidebar colapsado: lo maneja AdminLTE con sidebar_collapse_remember
 * - Pantalla completa: el navegador la pierde al navegar; aquí la recordamos
 *   y la reaplicamos cuando es posible (puede requerir un clic por política del navegador)
 */
(function () {
    'use strict';

    var FS_KEY = 'pdv_jils_fullscreen';

    function isFullscreen() {
        return !!(
            document.fullscreenElement ||
            document.webkitFullscreenElement ||
            document.mozFullScreenElement ||
            document.msFullscreenElement
        );
    }

    function requestFs() {
        var el = document.documentElement;
        if (el.requestFullscreen) return el.requestFullscreen();
        if (el.webkitRequestFullscreen) return el.webkitRequestFullscreen();
        if (el.msRequestFullscreen) return el.msRequestFullscreen();
        return Promise.reject();
    }

    function exitFs() {
        if (document.exitFullscreen) return document.exitFullscreen();
        if (document.webkitExitFullscreen) return document.webkitExitFullscreen();
        if (document.msExitFullscreen) return document.msExitFullscreen();
        return Promise.resolve();
    }

    function saveFullscreenState() {
        try {
            localStorage.setItem(FS_KEY, isFullscreen() ? '1' : '0');
        } catch (e) { /* private mode */ }
    }

    function updateFullscreenIcon() {
        // AdminLTE ya escucha fullscreenchange; forzamos un refresh del icono
        var $btn = window.jQuery ? window.jQuery('[data-widget="fullscreen"]') : null;
        if ($btn && $btn.length && typeof $btn.Fullscreen === 'function') {
            try {
                $btn.Fullscreen('toggleIcon');
            } catch (e) { /* ignore */ }
        }
        // Fallback manual del icono
        var icon = document.querySelector('[data-widget="fullscreen"] i');
        if (!icon) return;
        if (isFullscreen()) {
            icon.classList.remove('fa-expand-arrows-alt');
            icon.classList.add('fa-compress-arrows-alt');
        } else {
            icon.classList.remove('fa-compress-arrows-alt');
            icon.classList.add('fa-expand-arrows-alt');
        }
    }

    // Guardar cada vez que cambia el estado de fullscreen (botón o Esc)
    document.addEventListener('fullscreenchange', function () {
        saveFullscreenState();
        updateFullscreenIcon();
    });
    document.addEventListener('webkitfullscreenchange', function () {
        saveFullscreenState();
        updateFullscreenIcon();
    });

    // Al hacer clic en el widget de fullscreen de AdminLTE, también persistimos
    document.addEventListener('click', function (e) {
        var btn = e.target.closest('[data-widget="fullscreen"]');
        if (!btn) return;
        // El toggle de AdminLTE es async; guardamos un instante después
        setTimeout(saveFullscreenState, 150);
    }, true);

    /**
     * Intentar restaurar fullscreen al cargar la página.
     * Nota: muchos navegadores bloquean requestFullscreen sin gesto del usuario.
     * Si falla, dejamos el flag en localStorage para que el usuario solo pulse
     * el botón una vez y vuelva a quedar activo.
     */
    function tryRestoreFullscreen() {
        var want = false;
        try {
            want = localStorage.getItem(FS_KEY) === '1';
        } catch (e) {
            return;
        }
        if (!want || isFullscreen()) return;

        // Intento silencioso (suele fallar sin gesto de usuario)
        var p = requestFs();
        if (p && typeof p.then === 'function') {
            p.then(function () {
                updateFullscreenIcon();
            }).catch(function () {
                // Bloqueado por el navegador: no molestamos al usuario.
                // La preferencia sigue en localStorage; al hacer clic en el
                // botón de fullscreen funcionará y se mantendrá el flag.
            });
        }
    }

    // Ejecutar cuando el DOM esté listo (después de AdminLTE)
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () {
            setTimeout(tryRestoreFullscreen, 100);
        });
    } else {
        setTimeout(tryRestoreFullscreen, 100);
    }
})();
