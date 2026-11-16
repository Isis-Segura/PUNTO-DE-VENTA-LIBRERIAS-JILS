{{-- Buscador de listados paginados: busca en TODAS las páginas (en el servidor), sin recargar.
     Funciona con el campo del navbar/sidebar (adminlteSearch) o un campo "q".
     La vista debe envolver su listado y paginación en <div id="js-resultados">. --}}
<script>
(function () {
    var box = document.getElementById('js-resultados');
    if (!box) return;
    var timer = null, ctl = null;

    function buscar(texto) {
        var url = new URL(window.location.href);
        var q = (texto || '').trim();
        if (q) { url.searchParams.set('q', q); } else { url.searchParams.delete('q'); }
        url.searchParams.delete('adminlteSearch');
        url.searchParams.delete('page');

        if (ctl && window.AbortController) ctl.abort();
        ctl = window.AbortController ? new AbortController() : null;

        fetch(url.toString(), {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'text/html' },
            credentials: 'same-origin',
            signal: ctl ? ctl.signal : undefined
        })
        .then(function (r) {
            if (!r.ok) throw new Error('HTTP ' + r.status);
            return r.text();
        })
        .then(function (html) {
            var doc = new DOMParser().parseFromString(html, 'text/html');
            var nuevo = doc.getElementById('js-resultados');
            if (!nuevo) throw new Error('La respuesta no contiene el listado de resultados.');
            box.innerHTML = nuevo.innerHTML;
            window.history.replaceState(null, '', url.toString());
        })
        .catch(function (error) {
            if (error.name === 'AbortError') return;
            console.error('Falló la búsqueda en vivo; se intentará la búsqueda normal.', error);
            window.location.assign(url.toString());
        });
    }

    function esBuscador(el) {
        return el && el.name && (el.name === 'q' || el.name === 'adminlteSearch');
    }

    document.addEventListener('input', function (e) {
        if (!esBuscador(e.target)) return;
        var valor = e.target.value;
        clearTimeout(timer);
        timer = setTimeout(function () { buscar(valor); }, 250);
    });

    document.addEventListener('submit', function (e) {
        var form = e.target;
        var input = form && form.querySelector && form.querySelector('input[name="adminlteSearch"], input[name="q"]');
        if (!input) return;
        e.preventDefault();
        clearTimeout(timer);
        buscar(input.value);
    });
})();
</script>
