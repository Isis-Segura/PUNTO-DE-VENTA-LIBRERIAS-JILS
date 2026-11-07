{{-- Notificaciones propias de PDV JILS (reemplazan alert() del navegador) --}}
<div id="appToastWrap" aria-live="polite" aria-atomic="true"
     style="position:fixed;top:16px;right:16px;z-index:99999;display:flex;flex-direction:column;gap:10px;max-width:380px;width:calc(100% - 32px);pointer-events:none;">
</div>

<style>
.app-toast {
    pointer-events: auto;
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 14px 16px;
    border-radius: 12px;
    background: var(--ui-inset);
    box-shadow: 0 10px 30px rgba(29, 45, 44,.18);
    border-left: 4px solid #5b6b6a;
    animation: appToastIn .25s ease;
    font-family: system-ui, -apple-system, Segoe UI, Roboto, sans-serif;
}
.app-toast.is-out { animation: appToastOut .2s ease forwards; }
.app-toast-success { border-left-color: #2f6b4f; }
.app-toast-error { border-left-color: #9b3b3b; }
.app-toast-warning { border-left-color: #8a5a12; }
.app-toast-info { border-left-color: #3a4c5d; }
.app-toast-icon { font-size: 1.25rem; line-height: 1; margin-top: 2px; }
.app-toast-success .app-toast-icon { color: #2f6b4f; }
.app-toast-error .app-toast-icon { color: #9b3b3b; }
.app-toast-warning .app-toast-icon { color: #8a5a12; }
.app-toast-info .app-toast-icon { color: var(--ui-body); }
.app-toast-body { flex: 1; min-width: 0; }
.app-toast-title { font-weight: 700; font-size: .9rem; color: var(--ui-title); margin: 0 0 2px; }
.app-toast-msg { font-size: .88rem; color: var(--ui-body); margin: 0; line-height: 1.4; }
.app-toast-close {
    border: 0; background: transparent; color: var(--ui-muted); cursor: pointer;
    font-size: 1.1rem; line-height: 1; padding: 0 0 0 6px;
}
@keyframes appToastIn {
    from { opacity: 0; transform: translateX(16px); }
    to { opacity: 1; transform: translateX(0); }
}
@keyframes appToastOut {
    from { opacity: 1; transform: translateX(0); }
    to { opacity: 0; transform: translateX(16px); }
}
</style>

<script>
(function () {
    if (window.appToast) return;

    var ICONS = {
        success: 'fas fa-check-circle',
        error: 'fas fa-times-circle',
        warning: 'fas fa-exclamation-triangle',
        info: 'fas fa-info-circle'
    };
    var TITLES = {
        success: @json(__('Listo')),
        error: @json(__('Error')),
        warning: @json(__('Atención')),
        info: @json(__('Información'))
    };

    function ensureWrap() {
        var w = document.getElementById('appToastWrap');
        if (!w) {
            w = document.createElement('div');
            w.id = 'appToastWrap';
            w.style.cssText = 'position:fixed;top:16px;right:16px;z-index:99999;display:flex;flex-direction:column;gap:10px;max-width:380px;width:calc(100% - 32px);pointer-events:none;';
            document.body.appendChild(w);
        }
        return w;
    }

    window.appToast = function (message, type, title) {
        type = type || 'info';
        if (['success', 'error', 'warning', 'info'].indexOf(type) === -1) type = 'info';
        var wrap = ensureWrap();
        var el = document.createElement('div');
        el.className = 'app-toast app-toast-' + type;
        el.innerHTML =
            '<div class="app-toast-icon"><i class="' + (ICONS[type] || ICONS.info) + '"></i></div>' +
            '<div class="app-toast-body">' +
                '<p class="app-toast-title"></p>' +
                '<p class="app-toast-msg"></p>' +
            '</div>' +
            '<button type="button" class="app-toast-close" aria-label="Cerrar">&times;</button>';
        el.querySelector('.app-toast-title').textContent = title || TITLES[type] || '';
        el.querySelector('.app-toast-msg').textContent = message || '';
        el.querySelector('.app-toast-close').addEventListener('click', function () { dismiss(el); });
        wrap.appendChild(el);
        var t = setTimeout(function () { dismiss(el); }, type === 'error' ? 6000 : 4200);
        el._timer = t;
    };

    function dismiss(el) {
        if (!el || el._gone) return;
        el._gone = true;
        if (el._timer) clearTimeout(el._timer);
        el.classList.add('is-out');
        setTimeout(function () { if (el.parentNode) el.parentNode.removeChild(el); }, 200);
    }

    // Compatibilidad: alert del navegador → toast de error
    window.alert = function (msg) {
        window.appToast(String(msg == null ? '' : msg), 'error');
    };
})();
</script>
