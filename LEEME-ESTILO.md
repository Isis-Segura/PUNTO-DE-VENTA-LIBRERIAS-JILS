# Estilo JILS (claro + oscuro "Pizarra azulada") — para esta versión del proyecto

Paquete ACUMULATIVO (sustituye al anterior). Solo cambia CSS/estilos; no se tocó lógica, rutas ni textos.

## Correcciones de esta entrega (legibilidad)
- Autocompletado del navegador (correo/contraseña en login, crear usuario, etc.): ya no deja el campo azul pálido con
  texto del tema. Fondo y texto se fuerzan a los del tema (Chrome/Edge/Safari y Firefox).
- Controles nativos en oscuro (calendario, flecha del select, casillas, scrollbars): color-scheme claro/oscuro.
- Campos tel/url/hora/fecha-hora/archivo con el mismo estilo que el resto.
- Botones de la cabecera de la página de inicio ("Iniciar sesión"): texto blanco (antes verde sobre verde).
- Botón claro del banner de inicio, alertas del login, alertas neutras (estados vacíos), texto atenuado en inicio,
  avatar de sucursal inactiva: ahora con contraste suficiente en ambos modos.

## Dónde vive cada cosa
public/css/pos-theme.css (paleta/tokens) · public/css/jils-ui.css (componentes y formularios) ·
public/css/dark-mode-home.css (inicio en oscuro) · vendor/adminlte/page.blade.php (solo <style>) · master.blade.php.
Regla: en vistas nuevas usa var(--ui-title), var(--ui-body), var(--ui-muted), var(--ui-surface), var(--ui-inset), var(--ui-border).

Después de copiar: php artisan view:clear y npm run build. Si el navegador conserva el CSS viejo: Ctrl+F5.
