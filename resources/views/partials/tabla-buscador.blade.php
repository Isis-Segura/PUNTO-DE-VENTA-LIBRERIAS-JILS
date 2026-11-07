{{-- Buscador de tablas (barra del navbar): filtra las filas "tr.js-search-item" sin recargar.
     - Ignora mayúsculas y acentos.
     - Cada fila trae su texto en data-search (nombre, correo, rol, sucursal, fechas...).
     - Si la fila trae data-estado (activo | inactivo | pendiente), también se encuentra
       escribiendo el estado (en español o inglés), con al menos 3 letras. --}}
<script>
(function(){
  function norm(s){
    s=(s||'').toString().toLowerCase().trim();
    return s.normalize ? s.normalize('NFD').replace(/[\u0300-\u036f]/g,'') : s;
  }
  var ESTADOS={activo:['activo','active'],inactivo:['inactivo','inactive'],pendiente:['pendiente','pending']};
  function coincideEstado(q,estado){
    if(q.length<3||!estado) return false;
    return (ESTADOS[estado]||[]).some(function(w){ return w.indexOf(q)===0; });
  }
  function filtrar(texto){
    var q=norm(texto);
    var items=document.querySelectorAll('tr.js-search-item');
    var n=0;
    items.forEach(function(el){
      var ok=!q||norm(el.getAttribute('data-search')).indexOf(q)!==-1||coincideEstado(q,el.getAttribute('data-estado'));
      el.style.display=ok?'':'none';
      if(ok)n++;
    });
    var empty=document.querySelector('tr.js-search-empty');
    if(empty) empty.style.display=(items.length&&n===0)?'':'none';
  }
  document.addEventListener('input',function(e){
    if(e.target&&(e.target.name==='q'||e.target.name==='adminlteSearch')) filtrar(e.target.value);
  });
  document.addEventListener('submit',function(e){
    var f=e.target, inp=f&&f.querySelector&&f.querySelector('input[name="adminlteSearch"],input[name="q"]');
    if(inp&&document.querySelector('tr.js-search-item')){ e.preventDefault(); filtrar(inp.value); }
  });
  var p=new URLSearchParams(location.search);
  var ini=p.get('q')||p.get('adminlteSearch')||'';
  if(ini) filtrar(ini);
})();
</script>
