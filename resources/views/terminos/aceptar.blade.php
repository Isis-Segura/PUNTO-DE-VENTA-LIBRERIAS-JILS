<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex, nofollow">
  <meta name="referrer" content="no-referrer">
  <title>Términos y condiciones — Librería JILS</title>
  @include('terminos._estilos')
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
          <p style="color:var(--t-muted);font-size:14px;">Puedes volver a leer los términos que aceptaste cuando quieras.</p>
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
              <input type="hidden" name="decision" value="accept">
              <button id="accept" type="submit">Aceptar términos</button>
            </form>
            <button id="reject" type="button" aria-pressed="false">Rechazar términos</button>
          </div>
          <p id="status" role="status" aria-live="polite">
            Hola {{ $usuario->name }}. Al aceptar, tu decisión queda registrada y verás tus datos de acceso.
            Este enlace solo se puede abrir una vez: al aceptar o rechazar dejará de estar disponible. Si rechazas, no se guarda la aceptación y esta página se cierra.
          </p>
        </section>

        {{-- Pantalla que se muestra al rechazar (la activa el script de abajo). --}}
        <section id="closing-view" class="card-body closing" role="status" aria-live="polite" hidden>
          <div id="closing-spinner" class="spinner" aria-hidden="true"></div>
          <h2>Cerrando términos y condiciones</h2>
          <p id="closing-text">No se guardó la aceptación. Esta página se cerrará en un momento…</p>
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

      // Rechazar avisa al servidor para gastar el enlace (no se guarda la aceptación).
      // Muestra la pantalla "Cerrando términos y condiciones" y cierra la página; si
      // vuelve a abrir el enlace verá "página no disponible" y podrá pedir verlo otra vez.
      rejectButton.addEventListener("click", () => {
        const datos = new FormData(acceptForm);
        datos.set("decision", "reject");
        fetch(acceptForm.action, {
          method: "POST",
          body: datos,
          headers: { "Accept": "application/json" },
          keepalive: true,
        }).catch(() => {});
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
              "No se guardó la aceptación. Ya puedes cerrar esta pestaña. Este enlace ya no estará " +
              "disponible; si quieres verlo otra vez, podrás solicitarlo a los administradores desde el mismo enlace.";
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
