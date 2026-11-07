@include('partials.app-toasts')
{{-- Modal de confirmación propio de PDV JILS (no usa el confirm() del navegador) --}}
<div class="modal fade" id="appConfirmModal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 420px;">
        <div class="modal-content" style="border: 1px solid var(--ui-border); border-radius: 14px; overflow: hidden; box-shadow: 0 12px 40px rgba(29, 45, 44,.2);">
            <div class="modal-body text-center px-4 pt-4 pb-3">
                <div id="appConfirmIcon" class="mb-3" style="font-size: 2.4rem; color: var(--ui-danger-icon, #9b3b3b);">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h5 id="appConfirmTitle" class="font-weight-bold mb-2" style="color: var(--ui-title);">{{ __('Confirmar acción') }}</h5>
                <p id="appConfirmMessage" class="text-muted mb-0" style="font-size: .95rem; line-height: 1.5;"></p>
            </div>
            <div class="modal-footer justify-content-center border-0 pt-0 pb-4 px-4" style="gap: .5rem;">
                <button type="button" class="btn btn-outline-secondary px-4" data-dismiss="modal" id="appConfirmCancel">
                    {{ __('Cancelar') }}
                </button>
                <button type="button" class="btn px-4" id="appConfirmOk" style="min-width: 120px;">
                    {{ __('Confirmar') }}
                </button>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    if (window.__appConfirmReady) return;
    window.__appConfirmReady = true;

    var pending = null; // { type: 'form'|'link', el: Element }

    function openConfirm(opts) {
        var title = opts.title || @json(__('Confirmar acción'));
        var message = opts.message || @json(__('¿Seguro que deseas continuar?'));
        var type = opts.type || 'danger'; // danger | warning | primary
        var okText = opts.okText || @json(__('Confirmar'));

        document.getElementById('appConfirmTitle').textContent = title;
        document.getElementById('appConfirmMessage').textContent = message;
        document.getElementById('appConfirmOk').textContent = okText;

        var icon = document.getElementById('appConfirmIcon');
        var okBtn = document.getElementById('appConfirmOk');
        if (type === 'warning') {
            icon.innerHTML = '<i class="fas fa-edit"></i>';
            icon.style.color = 'var(--ui-warn-icon, #8a5a12)';
            okBtn.className = 'btn btn-warning px-4';
        } else if (type === 'primary') {
            icon.innerHTML = '<i class="fas fa-question-circle"></i>';
            icon.style.color = 'var(--ui-body)';
            okBtn.className = 'btn btn-primary px-4';
        } else {
            icon.innerHTML = '<i class="fas fa-exclamation-triangle"></i>';
            icon.style.color = 'var(--ui-danger-icon, #9b3b3b)';
            okBtn.className = 'btn btn-danger px-4';
        }

        $('#appConfirmModal').modal('show');
    }

    document.getElementById('appConfirmOk').addEventListener('click', function () {
        $('#appConfirmModal').modal('hide');
        if (!pending) return;
        var p = pending;
        pending = null;
        if (p.type === 'form') {
            p.el.dataset.appConfirmBypass = '1';
            if (typeof p.el.requestSubmit === 'function') p.el.requestSubmit();
            else p.el.submit();
        } else if (p.type === 'link') {
            window.location.href = p.href;
        }
    });

    document.addEventListener('submit', function (e) {
        var form = e.target;
        if (!form || form.tagName !== 'FORM') return;
        if (form.dataset.appConfirmBypass === '1') {
            delete form.dataset.appConfirmBypass;
            return;
        }
        var msg = form.getAttribute('data-confirm');
        if (!msg) return;
        e.preventDefault();
        e.stopPropagation();
        pending = { type: 'form', el: form };
        openConfirm({
            title: form.getAttribute('data-confirm-title') || @json(__('¿Eliminar?')),
            message: msg,
            type: form.getAttribute('data-confirm-type') || 'danger',
            okText: form.getAttribute('data-confirm-ok') || @json(__('Sí, eliminar'))
        });
    }, true);

    document.addEventListener('click', function (e) {
        var a = e.target.closest && e.target.closest('a[data-confirm]');
        if (!a) return;
        var msg = a.getAttribute('data-confirm');
        if (!msg) return;
        e.preventDefault();
        e.stopPropagation();
        pending = { type: 'link', href: a.getAttribute('href') };
        openConfirm({
            title: a.getAttribute('data-confirm-title') || @json(__('¿Continuar?')),
            message: msg,
            type: a.getAttribute('data-confirm-type') || 'warning',
            okText: a.getAttribute('data-confirm-ok') || @json(__('Sí, continuar'))
        });
    }, true);
})();
</script>
