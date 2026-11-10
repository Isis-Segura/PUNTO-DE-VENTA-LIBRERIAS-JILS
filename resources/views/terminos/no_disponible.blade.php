<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex, nofollow">
  <meta name="referrer" content="no-referrer">
  <title>Página no disponible — Librería JILS</title>
  @include('terminos._estilos')
</head>
<body>
  <div class="wrap">
    <main class="card">
      <header class="card-head">
        <small>Librería JILS</small>
        <h1>Página no disponible</h1>
      </header>

      <section class="card-body unavailable">
        <h2>Este enlace ya no está disponible</h2>
        <p>
          Hola <strong>{{ $usuario->name }}</strong>. Por seguridad, el enlace de los términos y condiciones
          solo se puede abrir una vez, y ya fue utilizado (o fue reemplazado por uno más reciente).
        </p>

        @if ($pendiente)
          <p class="status ok" role="status">
            @if ($enviada)<strong>Solicitud enviada.</strong>@else<strong>Solicitud pendiente.</strong>@endif
            Los administradores ya recibieron tu solicitud. Cuando la aprueben, recibirás un nuevo correo
            con un enlace temporal para volver a ver los términos y condiciones.
          </p>
        @else
          <p>Si quieres volver a verlos, envía una solicitud a los administradores. Cuando la aprueben,
             te llegará un nuevo correo con un enlace temporal.</p>
          <form method="POST" action="{{ $solicitarUrl }}" id="solicitar-form">
            @csrf
            <button type="submit" class="btn-solicitar" id="solicitar-btn">Solicitar ver los términos de nuevo</button>
          </form>
        @endif
      </section>
    </main>

    <footer class="foot">Librería JILS · PDV JILS</footer>
  </div>
  @unless ($pendiente)
    <script>
      document.getElementById("solicitar-form").addEventListener("submit", () => {
        const b = document.getElementById("solicitar-btn");
        b.disabled = true;
        b.textContent = "Enviando…";
      });
    </script>
  @endunless
</body>
</html>
