<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex, nofollow">
  <meta name="referrer" content="no-referrer">
  <title>Términos y condiciones — Librería JILS</title>
  <style>
    * {
      box-sizing: border-box;
    }

    body {
      min-height: 100vh;
      margin: 0;
      padding: 24px 12px;
      display: grid;
      place-items: center;
      background: #f1f5f9;
      color: #0f172a;
      font-family: "Segoe UI", Arial, sans-serif;
    }

    .wrap {
      width: min(100%, 640px);
    }

    .card {
      overflow: hidden;
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(15, 23, 42, .08);
    }

    .card-head {
      padding: 20px 28px;
      background: linear-gradient(135deg, #1e3a8a, #3b82f6);
      color: #fff;
    }

    .card-head small {
      display: block;
      font-size: 13px;
      letter-spacing: .06em;
      opacity: .9;
      text-transform: uppercase;
    }

    .card-head h1 {
      margin: 4px 0 0;
      font-size: 22px;
    }

    .card-body {
      padding: 28px;
      color: #334155;
      font-size: 15px;
      line-height: 1.6;
    }

    .card-body + .card-body {
      border-top: 1px solid #e2e8f0;
      background: #f8fafc;
    }

    .terminos h2 {
      margin: 0 0 12px;
      color: #0f172a;
      font-size: 18px;
    }

    .terminos p {
      margin: 0 0 12px;
    }

    .terminos ul {
      margin: 0 0 20px;
      padding-left: 22px;
    }

    .terminos li + li {
      margin-top: 6px;
    }

    .decision {
      display: flex;
      flex-wrap: wrap;
      gap: 12px;
    }

    .decision form {
      display: contents;
    }

    .decision button {
      flex: 1 1 180px;
      min-height: 48px;
      padding: 12px 18px;
      border: 2px solid transparent;
      border-radius: 8px;
      color: #fff;
      cursor: pointer;
      font: inherit;
      font-weight: 700;
      transition: background-color .2s ease, transform .2s ease;
    }

    .decision button:hover {
      transform: translateY(-1px);
    }

    .decision button:focus-visible {
      outline: 3px solid #0f172a;
      outline-offset: 3px;
    }

    .decision button:disabled {
      cursor: wait;
      opacity: .7;
      transform: none;
    }

    #accept {
      background: #2563eb;
    }

    #accept:hover {
      background: #1d4ed8;
    }

    #reject {
      background: #64748b;
    }

    #reject:hover,
    #reject[aria-pressed="true"] {
      background: #475569;
    }

    #status,
    .status {
      min-height: 48px;
      margin: 18px 0 0;
      padding: 12px 14px;
      border: 1px solid #bfdbfe;
      border-radius: 8px;
      background: #eff6ff;
      color: #1e3a8a;
    }

    #status[data-choice="rejected"] {
      border-color: #fecaca;
      background: #fef2f2;
      color: #991b1b;
    }

    .status.ok {
      margin: 0 0 20px;
      border-color: #a7f3d0;
      background: #ecfdf5;
      color: #065f46;
    }

    .section-title {
      margin: 0 0 12px;
      color: #0f172a;
      font-size: 18px;
    }

    .creds {
      margin: 0 0 16px;
      border: 1px solid #e2e8f0;
      border-radius: 10px;
      background: #f8fafc;
    }

    .creds .row {
      padding: 14px 16px;
    }

    .creds .row + .row {
      border-top: 1px solid #e2e8f0;
    }

    .creds .lbl {
      display: block;
      color: #64748b;
      font-size: 12px;
      text-transform: uppercase;
    }

    .creds .val {
      display: block;
      color: #0f172a;
      font-size: 16px;
      font-weight: 600;
      overflow-wrap: anywhere;
    }

    .creds .mono {
      font-family: Consolas, monospace;
    }

    .creds .muted {
      color: #475569;
      font-size: 14px;
      font-weight: 400;
    }

    .hint {
      margin: 0 0 16px;
      color: #475569;
      font-size: 14px;
    }

    .btn-login {
      display: block;
      padding: 12px 18px;
      border-radius: 8px;
      background: #2563eb;
      color: #fff;
      font-weight: 700;
      text-align: center;
      text-decoration: none;
    }

    .btn-login:hover {
      background: #1d4ed8;
    }

    [hidden] {
      display: none !important;
    }

    .closing {
      text-align: center;
    }

    .closing h2 {
      margin: 0 0 8px;
      color: #0f172a;
      font-size: 18px;
    }

    .closing p {
      margin: 0;
    }

    .spinner {
      width: 40px;
      height: 40px;
      margin: 8px auto 18px;
      border: 4px solid #bfdbfe;
      border-top-color: #2563eb;
      border-radius: 50%;
      animation: spin .9s linear infinite;
    }

    @keyframes spin {
      to {
        transform: rotate(360deg);
      }
    }

    @media (prefers-reduced-motion: reduce) {
      .spinner {
        animation: none;
      }
    }

    .foot {
      margin: 14px auto 0;
      color: #94a3b8;
      font-size: 12px;
      text-align: center;
    }

    @media (max-width: 480px) {
      .card-head,
      .card-body {
        padding: 22px 20px;
      }
    }
  </style>
</head>
<body>
  <div class="wrap">
    <main class="card">
      <header class="card-head">
        <small>Librería JILS</small>
        <h1>Términos y condiciones</h1>
      </header>

      @if ($aceptado)
        {{-- Ya aceptó: no hay botones (no se puede deshacer). Ve sus datos y puede releer los términos. --}}
        <section class="card-body">
          <p style="margin:0 0 12px;">Hola <strong>{{ $usuario->name }}</strong>,</p>

          <p class="status ok" role="status">
            @if ($recienAceptado)<strong>¡Gracias!</strong>@endif
            Aceptaste los términos y condiciones el
            {{ $usuario->terminos_aceptados_at->timezone('America/Mexico_City')->format('d/m/Y') }}.
            Tu decisión ya quedó registrada.
          </p>

          <h2 class="section-title">Tus datos de acceso</h2>
          <div class="creds">
            <div class="row">
              <span class="lbl">Usuario / correo</span>
              <span class="val">{{ $usuario->email }}</span>
            </div>
            <div class="row">
              <span class="lbl">Rol</span>
              <span class="val">{{ $rolNombre }}</span>
            </div>
            <div class="row">
              <span class="lbl">Contraseña</span>
              @if (filled($password))
                <span class="val mono">{{ $password }}</span>
              @else
                <span class="val muted">Tu contraseña ya fue cambiada, por eso ya no se muestra aquí. Usa la contraseña que definiste.</span>
              @endif
            </div>
          </div>

          @if (filled($password))
            <p class="hint">
              Te recomendamos iniciar sesión y cambiar la contraseña desde tu menú de usuario
              (<em>Cambiar contraseña</em>). Cuando la cambies, esta página dejará de mostrarla.
            </p>
          @endif

          <a class="btn-login" href="{{ route('login') }}">Ir a iniciar sesión</a>
        </section>

        <section class="card-body terminos" aria-labelledby="terms-title">
          <p style="color:#64748b;font-size:14px;">Puedes volver a leer los términos que aceptaste cuando quieras.</p>
          @include('partials.terminos')
        </section>
      @else
        {{-- Todavía no acepta: no se muestra ningún dato de acceso. --}}
        <section id="terms-view" class="card-body terminos" aria-labelledby="terms-title">
          @include('partials.terminos')

          <p><strong>Seleccione una opción para indicar su decisión:</strong></p>
          <div class="decision">
            <form id="accept-form" method="POST" action="{{ $accionUrl }}">
              @csrf
              <button id="accept" type="submit">Aceptar términos</button>
            </form>
            <button id="reject" type="button" aria-pressed="false">Rechazar términos</button>
          </div>
          <p id="status" role="status" aria-live="polite">
            Hola {{ $usuario->name }}. Al aceptar, tu decisión queda registrada y verás tus datos de acceso.
            Si rechazas, no se guarda nada y esta página se cierra.
          </p>
        </section>

        {{-- Pantalla que se muestra al rechazar (la activa el script de abajo). --}}
        <section id="closing-view" class="card-body closing" role="status" aria-live="polite" hidden>
          <div id="closing-spinner" class="spinner" aria-hidden="true"></div>
          <h2>Cerrando términos y condiciones</h2>
          <p id="closing-text">No se guardó nada. Esta página se cerrará en un momento…</p>
        </section>
      @endif
    </main>

    <footer class="foot">Librería JILS · PDV JILS</footer>
  </div>

  @unless ($aceptado)
    <script>
      const acceptForm = document.getElementById("accept-form");
      const acceptButton = document.getElementById("accept");
      const rejectButton = document.getElementById("reject");
      const termsView = document.getElementById("terms-view");
      const closingView = document.getElementById("closing-view");
      const closingSpinner = document.getElementById("closing-spinner");
      const closingText = document.getElementById("closing-text");

      // Rechazar NO envía nada al servidor (no se guarda nada). Muestra la pantalla
      // "Cerrando términos y condiciones" y cierra la página; al volver a entrar con
      // el enlace del correo se vuelve a preguntar.
      rejectButton.addEventListener("click", () => {
        termsView.hidden = true;
        closingView.hidden = false;
        setTimeout(cerrarPagina, 1800);
      });

      function cerrarPagina() {
        // 1) Cerrar la pestaña. Los navegadores solo lo permiten si se abrió desde un
        //    enlace (por ejemplo, desde el correo) y no se ha navegado dentro de ella.
        window.close();

        // 2) Si no se pudo cerrar y hay una página anterior, regresar a ella.
        setTimeout(() => {
          if (window.history.length > 1) {
            window.history.back();
          }

          // 3) Si tampoco hubo a dónde volver, avisar que ya se puede cerrar a mano.
          setTimeout(() => {
            closingSpinner.hidden = true;
            closingText.textContent =
              "No se guardó nada. Ya puedes cerrar esta pestaña. Si quieres decidir más tarde, " +
              "vuelve a abrir el enlace del correo de bienvenida.";
          }, 900);
        }, 400);
      }

      // Evita enviar dos veces si se hace doble clic en "Aceptar términos".
      acceptForm.addEventListener("submit", () => {
        acceptButton.disabled = true;
        acceptButton.textContent = "Guardando…";
      });
    </script>
  @endunless
</body>
</html>
