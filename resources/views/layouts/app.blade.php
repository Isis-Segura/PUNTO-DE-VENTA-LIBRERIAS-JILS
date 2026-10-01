<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'PDV JILS') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        /* =====================================================================
           LAYOUT PRINCIPAL & SIDEBAR — MODO COFFEE VINO TINTO
           ===================================================================== */
        :root {
            --pos-dark: #1A0608;
            --pos-sidebar-bg: #2A0C10;
            --pos-primary: #561C24;
            --pos-primary-hover: #6D2932;
            --pos-light-bg: #FAF7F2;
            --pos-accent-light: #F5EFE6;
            --pos-border: #C7B7A3;
            --pos-text-main: #2A0C10;
            --pos-text-muted: #6D2932;
            --pos-text-light: #E8D8C4;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: var(--pos-light-bg);
            color: var(--pos-text-main);
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }

        #app {
            display: flex;
            min-height: 100vh;
            width: 100%;
        }

        /* Sidebar Principal */
        .app-sidebar {
            width: 250px;
            background-color: var(--pos-sidebar-bg);
            color: var(--pos-text-light);
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
            border-right: 1px solid #3D1217;
        }

        .sidebar-brand {
            padding: 1.25rem 1.5rem;
            background-color: var(--pos-dark);
            border-bottom: 1px solid #3D1217;
            font-size: 1.15rem;
            font-weight: 800;
            color: #ffffff;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        .sidebar-menu {
            list-style: none;
            padding: 1rem 0.75rem;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
        }

        .sidebar-menu .nav-link,
        .sidebar-menu a,
        .sidebar-menu button {
            display: flex !important;
            align-items: center !important;
            gap: 0.75rem !important;
            padding: 0.7rem 1rem !important;
            color: var(--pos-text-light) !important;
            background-color: transparent !important;
            border-radius: 8px !important;
            border: none !important;
            font-weight: 600 !important;
            font-size: 0.9rem !important;
            text-decoration: none !important;
            transition: all 0.2s ease !important;
            width: 100% !important;
            text-align: left !important;
        }

        .sidebar-menu .nav-link:hover,
        .sidebar-menu a:hover {
            background-color: var(--pos-primary) !important;
            color: #ffffff !important;
        }

        .sidebar-menu .nav-link.active,
        .sidebar-menu a.active {
            background-color: var(--pos-primary) !important;
            color: #ffffff !important;
            font-weight: 700 !important;
        }

        /* Área de Contenido Principal */
        .app-main {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        /* Header / Barra Superior */
        .app-header {
            background-color: #ffffff;
            border-bottom: 1px solid var(--pos-border);
            padding: 0.85rem 1.75rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .app-content {
            padding: 1.75rem;
            flex-grow: 1;
        }

        /* Estilos globales para tarjetas e inputs */
        .card {
            background-color: #ffffff !important;
            border: 1px solid var(--pos-border) !important;
            border-radius: 12px !important;
            box-shadow: 0 4px 12px rgba(42, 12, 16, 0.04) !important;
        }

        .alert-info, .bg-info, [class*="bg-info"] {
            background-color: var(--pos-accent-light) !important;
            color: var(--pos-primary) !important;
            border: 1px solid var(--pos-border) !important;
            border-radius: 10px !important;
            font-weight: 600 !important;
        }

        .form-control, .form-select, select, input {
            border: 1.5px solid var(--pos-border) !important;
            border-radius: 8px !important;
            color: var(--pos-text-main) !important;
        }

        .form-control:focus, .form-select:focus, select:focus, input:focus {
            border-color: var(--pos-primary) !important;
            box-shadow: 0 0 0 3px rgba(86, 28, 36, 0.15) !important;
        }

        .btn-primary {
            background-color: var(--pos-primary) !important;
            border-color: var(--pos-primary) !important;
            color: var(--pos-text-light) !important;
            font-weight: 700 !important;
            border-radius: 8px !important;
        }

        .btn-primary:hover {
            background-color: var(--pos-primary-hover) !important;
            border-color: var(--pos-primary-hover) !important;
            color: #ffffff !important;
        }
    </style>
</head>
<body>
    <div id="app">
        <!-- Sidebar Flotante Lateral -->
        <aside class="app-sidebar">
            <a class="sidebar-brand" href="{{ url('/') }}">
                📖 {{ config('app.name', 'PDV JILS') }}
            </a>
            <ul class="sidebar-menu">
                <li><a href="{{ route('home') }}" class="nav-link active">🎰 Punto de venta</a></li>
                <li><a href="#" class="nav-link">🏢 Sucursales</a></li>
                <li><a href="#" class="nav-link">💵 Cajas</a></li>
                <li><a href="#" class="nav-link">📦 Productos</a></li>
                <li><a href="#" class="nav-link">📋 Historial de ventas</a></li>
                <li><a href="#" class="nav-link">👥 Usuarios</a></li>
                <li><a href="#" class="nav-link">🏷️ Categorías</a></li>
                <li><a href="#" class="nav-link">📚 Géneros</a></li>
            </ul>
        </aside>

        <!-- Panel de Contenido Principal -->
        <div class="app-main">
            <header class="app-header">
                <span class="fw-bold text-muted">Panel de Administración</span>
                <ul class="navbar-nav ms-auto flex-row align-items-center gap-3">
                    @guest
                        @if (Route::has('login'))
                            <li class="nav-item">
                                <a class="nav-link fw-bold" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                        @endif
                    @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle fw-bold" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                👤 {{ Auth::user()->name }}
                            </a>

                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>
            </header>

            <main class="app-content">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>