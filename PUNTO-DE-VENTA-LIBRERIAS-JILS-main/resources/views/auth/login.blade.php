<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Librería JILS') }} - {{ __('Acceso a Terminal POS') }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/jpeg" href="{{ asset('vendor/adminlte/dist/img/J_logo.jpeg') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">

    <!-- Vite Styles & Scripts (Bootstrap 5) -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        :root {
            --pos-blue-dark: #1A0608;
            --pos-blue-primary: #561C24;
            --pos-blue-hover: #6D2932;
            --pos-blue-light: #F5EFE6;
            --pos-green-success: #3E5C46;
            --pos-bg: #FAF7F2;
            --pos-card-bg: #ffffff;
            --pos-border: #C7B7A3;
            --pos-border-focus: #561C24;
            --pos-text-main: #2A0C10;
            --pos-text-muted: #6D2932;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: var(--pos-bg);
            background-image: 
                radial-gradient(#C7B7A3 1px, transparent 1px),
                linear-gradient(to bottom, #F5EFE6 0%, #FAF7F2 180px);
            background-size: 24px 24px, 100% 100%;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            color: var(--pos-text-main);
        }

        /* Contenedor Principal / Tarjeta POS */
        .pos-login-wrapper {
            width: 100%;
            max-width: 440px;
        }

        .pos-login-card {
            background: var(--pos-card-bg);
            border-radius: 16px;
            border: 1px solid var(--pos-border);
            border-top: 5px solid var(--pos-blue-primary);
            box-shadow: 0 10px 25px -5px rgba(86, 28, 36, 0.1), 0 8px 10px -6px rgba(86, 28, 36, 0.05);
            padding: 2.25rem 2rem;
            position: relative;
        }

        /* Barra Superior */
        .card-topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.75rem;
            padding-bottom: 0.85rem;
            border-bottom: 1px solid var(--pos-blue-light);
        }

        .back-link {
            color: var(--pos-text-muted);
            font-size: 0.82rem;
            font-weight: 700;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            transition: color 0.2s ease;
        }

        .back-link:hover {
            color: var(--pos-blue-primary);
        }

        .lang-pill {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.55rem;
            border-radius: 6px;
            font-size: 0.72rem;
            font-weight: 800;
            text-decoration: none;
            color: var(--pos-text-muted);
            background: #ffffff;
            border: 1px solid var(--pos-border);
            transition: all 0.2s ease;
        }

        .lang-pill.active {
            background: var(--pos-blue-primary);
            color: #ffffff;
            border-color: var(--pos-blue-primary);
        }

        .lang-pill:hover:not(.active) {
            background: var(--pos-blue-light);
            color: var(--pos-text-main);
        }

        /* Encabezado */
        .login-header {
            text-align: center;
            margin-bottom: 1.85rem;
        }

        .login-logo {
            width: 64px;
            height: 64px;
            border-radius: 14px;
            object-fit: cover;
            border: 2px solid var(--pos-border);
            box-shadow: 0 4px 10px rgba(86, 28, 36, 0.08);
            margin-bottom: 0.85rem;
        }

        .login-title {
            font-size: 1.35rem;
            font-weight: 800;
            color: var(--pos-text-main);
            letter-spacing: -0.02em;
            margin-bottom: 0.35rem;
        }

        .pos-terminal-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            background: var(--pos-blue-light);
            color: var(--pos-blue-primary);
            border: 1px solid var(--pos-border);
            font-size: 0.75rem;
            font-weight: 800;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            padding: 0.3rem 0.75rem;
            border-radius: 8px;
        }

        /* Formulario e Inputs Clásicos POS */
        .form-label {
            font-size: 0.82rem;
            font-weight: 700;
            color: var(--pos-text-main);
            margin-bottom: 0.35rem;
            display: flex;
            align-items: center;
            gap: 0.45rem;
        }

        .input-group-custom {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon-left {
            position: absolute;
            left: 1rem;
            color: var(--pos-text-muted);
            font-size: 0.95rem;
            z-index: 5;
            pointer-events: none;
        }

        .input-toggle-right {
            position: absolute;
            right: 0.75rem;
            background: none;
            border: none;
            color: var(--pos-text-muted);
            font-size: 0.95rem;
            cursor: pointer;
            z-index: 5;
            padding: 0.4rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.2s ease;
        }

        .input-toggle-right:hover {
            color: var(--pos-blue-primary);
        }

        .form-control-pos {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.75rem;
            font-size: 0.95rem;
            font-family: inherit;
            color: var(--pos-text-main);
            background-color: #ffffff;
            border: 1.5px solid var(--pos-border);
            border-radius: 10px;
            transition: all 0.2s ease;
        }

        .form-control-pos.has-toggle {
            padding-right: 2.75rem;
        }

        .form-control-pos:focus {
            background-color: #ffffff;
            border-color: var(--pos-border-focus);
            outline: none;
            box-shadow: 0 0 0 3px rgba(86, 28, 36, 0.18);
        }

        .form-control-pos.is-invalid {
            border-color: #ef4444;
            background-color: #fffaf0;
        }

        .form-control-pos.is-invalid:focus {
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.18);
        }

        /* Checkbox */
        .form-check-label {
            font-size: 0.84rem;
            font-weight: 600;
            color: var(--pos-text-muted);
            cursor: pointer;
            user-select: none;
        }

        .form-check-input {
            cursor: pointer;
            border-color: var(--pos-border);
        }

        .form-check-input:checked {
            background-color: var(--pos-blue-primary);
            border-color: var(--pos-blue-primary);
        }

        /* Botón de Entrada POS */
        .btn-pos-submit {
            width: 100%;
            background-color: var(--pos-blue-primary);
            color: #ffffff;
            font-weight: 800;
            font-size: 0.98rem;
            padding: 0.85rem;
            border-radius: 10px;
            border: none;
            box-shadow: 0 4px 12px rgba(86, 28, 36, 0.25);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.6rem;
            transition: all 0.2s ease;
            cursor: pointer;
            margin-top: 1.5rem;
        }

        .btn-pos-submit:hover {
            background-color: var(--pos-blue-hover);
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(86, 28, 36, 0.35);
            color: #ffffff;
        }

        .btn-pos-submit:active {
            transform: translateY(0);
        }

        /* Footer de la Terminal */
        .terminal-status-footer {
            margin-top: 1.75rem;
            padding-top: 1.25rem;
            border-top: 1px solid var(--pos-blue-light);
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--pos-text-muted);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .status-dot-green {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: var(--pos-green-success);
        }

        /* ---------- Modo oscuro ---------- */
        .dark-mode-toggle-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 26px;
            height: 26px;
            border-radius: 6px;
            border: 1px solid var(--pos-border);
            color: var(--pos-text-muted);
            background: #ffffff;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .dark-mode-toggle-btn:hover {
            background: var(--pos-blue-light);
            color: var(--pos-text-main);
        }

        .dark-mode {
            --pos-bg: #120507;
            --pos-card-bg: #1F0B0E;
            --pos-border: #4A2026;
            --pos-border-focus: #8C3A46;
            --pos-blue-light: #3D161C;
            --pos-text-main: #F5EFE6;
            --pos-text-muted: #C7B7A3;
        }

        .dark-mode body {
            background-image:
                radial-gradient(#3D161C 1px, transparent 1px),
                linear-gradient(to bottom, #120507 0%, #120507 180px);
        }

        .dark-mode .pos-login-card {
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.6), 0 8px 10px -6px rgba(0, 0, 0, 0.4);
        }

        .dark-mode .card-topbar {
            border-bottom-color: var(--pos-border);
        }

        .dark-mode .lang-pill,
        .dark-mode .dark-mode-toggle-btn {
            background: #19080B;
            border-color: var(--pos-border);
            color: var(--pos-text-muted);
        }

        .dark-mode .lang-pill.active {
            background: var(--pos-blue-light);
            color: #E8D8C4;
            border-color: #6D2932;
        }

        .dark-mode .lang-pill:hover:not(.active),
        .dark-mode .dark-mode-toggle-btn:hover {
            background: #2A0C10;
            color: var(--pos-text-main);
        }

        .dark-mode .login-logo {
            border-color: var(--pos-border);
        }

        .dark-mode .pos-terminal-badge {
            background: var(--pos-blue-light);
            color: #E8D8C4;
            border-color: #6D2932;
        }

        .dark-mode .form-label {
            color: #E8D8C4;
        }

        .dark-mode .form-control-pos {
            background-color: #260E12;
            color: var(--pos-text-main);
        }

        .dark-mode .form-control-pos:focus {
            background-color: #260E12;
        }

        .dark-mode .form-control-pos.is-invalid {
            background-color: #3B1414;
        }

        .dark-mode .form-check-label {
            color: var(--pos-text-muted);
        }

        .dark-mode .terminal-status-footer {
            border-top-color: var(--pos-border);
            color: var(--pos-text-muted);
        }
</style>
</head>
<body>

    <script src="{{ asset('js/dark-mode.js') }}?v={{ @filemtime(public_path('js/dark-mode.js')) }}"></script>

    <div class="pos-login-wrapper">
        <div class="pos-login-card">
            <!-- Barra superior: Enlace de regreso y selector de idioma -->
            <div class="card-topbar">
                <a href="{{ url('/') }}" class="back-link">
                    <i class="fas fa-arrow-left"></i>
                    <span>{{ __('Volver al Inicio') }}</span>
                </a>

                <div class="d-flex align-items-center gap-1">
                    @foreach (config('idiomas.disponibles', ['es' => 'Español', 'en' => 'English']) as $codigo => $nombre)
                        <a href="{{ url('/lang/'.$codigo) }}"
                           class="lang-pill {{ app()->getLocale() === $codigo ? 'active' : '' }}"
                           title="{{ $nombre }}">
                            {{ strtoupper($codigo) }}
                        </a>
                    @endforeach

                    <a href="#" id="btn-dark-mode" class="js-dark-mode-btn dark-mode-toggle-btn"
                       title="Modo oscuro" aria-label="Modo oscuro">
                        <i class="fas fa-moon js-dark-mode-icon" id="icon-dark-mode"></i>
                    </a>
                </div>
            </div>

            <!-- Encabezado con Logo Oficial de Mostrador -->
            <div class="login-header">
                <img src="{{ asset('vendor/adminlte/dist/img/J_logo.jpeg') }}" alt="Logo Librería JILS" class="login-logo">
                <h1 class="login-title">{{ __('Librería JILS') }}</h1>
                <p class="text-muted small mb-0">{{ __('Punto de Venta') }}</p>
            </div>

            <!-- Alertas de Error si fallan credenciales -->
            @if (isset($errors) && $errors->any())
                <div class="alert alert-danger py-2 px-3 rounded-3 mb-4 d-flex align-items-center gap-2" role="alert" style="font-size: 0.85rem;">
                    <i class="fas fa-exclamation-circle text-danger fs-5"></i>
                    <div>
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Formulario de Autenticación -->
            <form action="{{ route('login') }}" method="POST" autocomplete="on">
                @csrf

                <!-- Campo Correo -->
                <div class="mb-3">
                    <label for="email" class="form-label">{{ __('Correo') }}</label>
                    <div class="input-group-custom">
                        <i class="fas fa-envelope input-icon-left"></i>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            class="form-control-pos @error('email') is-invalid @enderror"
                            placeholder="{{ __('ejemplo@correo.com') }}"
                            required
                            autofocus
                        >
                    </div>
                </div>

                <!-- Campo Contraseña -->
                <div class="mb-3">
                    <label for="password" class="form-label">{{ __('Contraseña') }}</label>
                    <div class="input-group-custom">
                        <i class="fas fa-lock input-icon-left"></i>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-control-pos has-toggle @error('password') is-invalid @enderror"
                            placeholder="••••••••"
                            required
                        >
                        <button type="button" class="input-toggle-right" id="togglePasswordBtn" title="{{ __('Mostrar/ocultar contraseña') }}" tabindex="-1">
                            <i class="fas fa-eye" id="togglePasswordIcon"></i>
                        </button>
                    </div>
                </div>

                <!-- Botón de Envío -->
                <button type="submit" class="btn-pos-submit">
                    <i class="fas fa-sign-in-alt"></i>
                    <span>{{ __('Iniciar Sesión') }}</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Script para Mostrar/Ocultar Contraseña -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggleBtn = document.getElementById('togglePasswordBtn');
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('togglePasswordIcon');

            if (toggleBtn && passwordInput && toggleIcon) {
                toggleBtn.addEventListener('click', function () {
                    const isPassword = passwordInput.type === 'password';
                    passwordInput.type = isPassword ? 'text' : 'password';
                    toggleIcon.classList.toggle('fa-eye', !isPassword);
                    toggleIcon.classList.toggle('fa-eye-slash', isPassword);
                });
            }
        });
    </script>
</body>
</html>