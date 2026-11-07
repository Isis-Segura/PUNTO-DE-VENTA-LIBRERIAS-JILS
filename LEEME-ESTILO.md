# Estilo JILS (claro + oscuro "Pizarra azulada") — para esta versión del proyecto

Solo se cambió CSS / estilos. No se tocaron rutas, controladores, modelos, JS de lógica ni textos.
Los dos correos conservan sus textos y variables Blade; solo cambió su apariencia.

- public/css/pos-theme.css      paleta y tokens (--pos-*, --ui-*) claro y oscuro
- public/css/jils-ui.css        NUEVO: componentes y cobertura de Bootstrap/AdminLTE en ambos modos
- public/css/dark-mode-home.css modo oscuro de la página de inicio
- vendor/adminlte/page.blade.php (solo el bloque <style>) y master.blade.php (carga jils-ui.css y la fuente)
- terminos/aceptar.blade.php    página pública de términos: misma paleta, y oscuro si el sistema lo pide
- emails/*                      ambos correos con la paleta (colores en línea, los correos no soportan variables CSS)

Regla para vistas nuevas: no usar colores fijos; usar var(--ui-title), var(--ui-body), var(--ui-muted), var(--ui-surface), var(--ui-inset), var(--ui-border).
Después de copiar: php artisan view:clear y npm run build.
