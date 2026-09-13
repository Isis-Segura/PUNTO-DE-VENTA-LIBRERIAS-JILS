<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Librería JILS') }} - Punto de Venta e Inventario</title>

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
            --pos-dark: #0f172a;
            --pos-blue: #1e40af;
            --pos-blue-hover: #1d4ed8;
            --pos-blue-light: #eff6ff;
            --pos-green: #059669;
            --pos-green-light: #ecfdf5;
            --pos-bg: #f8fafc;
            --pos-card-border: #cbd5e1;
            --pos-text-main: #0f172a;
            --pos-text-muted: #475569;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: var(--pos-bg);
            color: var(--pos-text-main);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
        }

        /* Navbar */
        .pos-navbar {
            background: #ffffff;
            border-bottom: 1px solid var(--pos-card-border);
            padding: 0.85rem 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
        }

        .brand-logo {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            object-fit: cover;
            border: 2px solid #e2e8f0;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        .brand-title {
            font-size: 1.22rem;
            font-weight: 800;
            color: var(--pos-dark);
            line-height: 1.15;
            letter-spacing: -0.02em;
        }

        .brand-subtitle {
            font-size: 0.72rem;
            font-weight: 700;
            color: var(--pos-blue);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        /* Selector de Idioma */
        .lang-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.35rem 0.65rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 800;
            text-decoration: none;
            transition: all 0.2s ease;
            border: 1px solid var(--pos-card-border);
            color: var(--pos-text-muted);
            background-color: #ffffff;
        }

        .lang-badge.active {
            background-color: var(--pos-blue-light);
            color: var(--pos-blue);
            border-color: #bfdbfe;
        }

        .lang-badge:hover:not(.active) {
            background-color: #f1f5f9;
            color: var(--pos-dark);
        }

        /* Hero Section */
        .pos-hero {
            padding: 4.5rem 0 3.75rem;
            background: linear-gradient(180deg, #f1f5f9 0%, #f8fafc 100%);
            border-bottom: 1px solid var(--pos-card-border);
        }

        .hero-title {
            font-size: 2.75rem;
            font-weight: 800;
            color: var(--pos-dark);
            line-height: 1.18;
            letter-spacing: -0.03em;
            margin-bottom: 1.25rem;
        }

        .hero-title span {
            color: var(--pos-blue);
        }

        .hero-description {
            font-size: 1.12rem;
            color: var(--pos-text-muted);
            line-height: 1.65;
            max-width: 650px;
            margin: 0 auto 2.25rem;
            font-weight: 500;
        }

        .btn-pos-primary {
            background-color: var(--pos-blue);
            color: #ffffff;
            font-weight: 800;
            font-size: 1.02rem;
            padding: 0.85rem 1.85rem;
            border-radius: 10px;
            border: none;
            box-shadow: 0 4px 14px rgba(30, 64, 175, 0.25);
            display: inline-flex;
            align-items: center;
            gap: 0.65rem;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .btn-pos-primary:hover {
            background-color: var(--pos-blue-hover);
            color: #ffffff;
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(30, 64, 175, 0.35);
        }

        .btn-pos-outline {
            background-color: #ffffff;
            color: var(--pos-dark);
            font-weight: 700;
            font-size: 1rem;
            padding: 0.85rem 1.6rem;
            border-radius: 10px;
            border: 1.5px solid var(--pos-card-border);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .btn-pos-outline:hover {
            background-color: #f1f5f9;
            color: var(--pos-dark);
            border-color: #94a3b8;
        }

        /* Feature Cards */
        .feature-card {
            background: #ffffff;
            border: 1px solid var(--pos-card-border);
            border-top: 4px solid var(--pos-blue);
            border-radius: 14px;
            padding: 1.85rem;
            height: 100%;
            transition: all 0.25s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
        }

        .feature-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 24px -8px rgba(15, 23, 42, 0.1);
            border-color: #94a3b8;
        }

        .feature-card.card-green { border-top-color: var(--pos-green); }
        .feature-card.card-amber { border-top-color: #d97706; }
        .feature-card.card-purple { border-top-color: #7c3aed; }

        .feature-icon-wrapper {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            margin-bottom: 1.25rem;
        }

        .icon-blue { background: var(--pos-blue-light); color: var(--pos-blue); }
        .icon-green { background: var(--pos-green-light); color: var(--pos-green); }
        .icon-amber { background: #fffbeb; color: #d97706; }
        .icon-purple { background: #faf5ff; color: #7c3aed; }

        .feature-title {
            font-size: 1.18rem;
            font-weight: 800;
            color: var(--pos-dark);
            margin-bottom: 0.5rem;
        }

        .feature-desc {
            font-size: 0.92rem;
            color: var(--pos-text-muted);
            line-height: 1.55;
            margin: 0;
            font-weight: 500;
        }

        /* Banner de Acceso */
        .pos-info-banner {
            background: #0f172a;
            border-radius: 16px;
            padding: 2.25rem;
            color: #ffffff;
            border: 1px solid #1e293b;
            box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.18);
        }

        /* Footer */
        .pos-footer {
            margin-top: auto;
            background: #ffffff;
            border-top: 1px solid var(--pos-card-border);
            padding: 1.75rem 0;
            font-size: 0.86rem;
            color: var(--pos-text-muted);
        }
    </style>
</head>
<body>

    <!-- Navegación Superior -->
    <header class="pos-navbar">
        <div class="container d-flex justify-content-between align-items-center">
            <a href="{{ url('/') }}" class="d-flex align-items-center gap-2 text-decoration-none">
                <img src="{{ asset('vendor/adminlte/dist/img/J_logo.jpeg') }}" alt="Logo JILS" class="brand-logo">
                <div>
                    <div class="brand-title">Librería JILS</div>
                    <div class="brand-subtitle">Punto de Venta &bull; Inventario</div>
                </div>
            </a>

            <div class="d-flex align-items-center gap-3">
                <!-- Selector de Idioma -->
                <div class="d-flex gap-1">
                    @foreach (config('idiomas.disponibles', ['es' => 'Español', 'en' => 'English']) as $codigo => $nombre)
                        <a href="{{ url('/lang/'.$codigo) }}"
                           class="lang-badge {{ app()->getLocale() === $codigo ? 'active' : '' }}"
                           title="{{ $nombre }}">
                            {{ strtoupper($codigo) }}
                        </a>
                    @endforeach
                </div>

                <!-- Botón de Acceso -->
                @auth
                    <a href="{{ auth()->user()->isAdmin() ? url('/admin') : url('/home') }}" class="btn-pos-primary py-2 px-3 fs-6">
                        <i class="fas fa-chart-line"></i>
                        <span>Ir al Panel</span>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn-pos-primary py-2 px-3 fs-6">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>Iniciar Sesión</span>
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Sección Principal (Hero) -->
    <main>
        <section class="pos-hero text-center">
            <div class="container">
                <h1 class="hero-title">
                    Control Integral de <span>Ventas e Inventario</span><br class="d-none d-md-block">
                    para Librerías Multi-Sede
                </h1>

                <p class="hero-description">
                    Plataforma para la administración de cajas, cobro de tickets,
                    control de existencias por sucursales y gestión de usuarios.
                </p>

                <div class="d-flex justify-content-center gap-3 flex-wrap">
                    @auth
                        <a href="{{ auth()->user()->isAdmin() ? url('/admin') : url('/home') }}" class="btn-pos-primary">
                            <i class="fas fa-desktop"></i>
                            <span>Ir al Panel de Trabajo</span>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn-pos-primary">
                            <i class="fas fa-cash-register"></i>
                            <span>Acceder al Punto de Venta</span>
                        </a>
                    @endauth

                    <a href="#modulos" class="btn-pos-outline">
                        <i class="fas fa-cubes"></i>
                        <span>Ver Módulos</span>
                    </a>
                </div>
            </div>
        </section>

        <!-- Módulos del Sistema -->
        <section id="modulos" class="py-5">
            <div class="container">
                <div class="text-center mb-5">
                    <h2 class="fw-bold text-dark fs-3 mb-2">Módulos del Sistema</h2>
                    <p class="text-muted">Operaciones centralizadas para agilizar ventas y catálogo.</p>
                </div>

                <div class="row g-4">
                    <!-- Tarjeta 1: POS -->
                    <div class="col-md-6 col-lg-3">
                        <div class="feature-card">
                            <div class="feature-icon-wrapper icon-blue">
                                <i class="fas fa-cash-register"></i>
                            </div>
                            <h3 class="feature-title">Punto de Venta</h3>
                            <p class="feature-desc">
                                Registro rápido de ventas, cobros en efectivo o tarjeta y emisión de tickets.
                            </p>
                        </div>
                    </div>

                    <!-- Tarjeta 2: Inventario -->
                    <div class="col-md-6 col-lg-3">
                        <div class="feature-card card-green">
                            <div class="feature-icon-wrapper icon-green">
                                <i class="fas fa-boxes"></i>
                            </div>
                            <h3 class="feature-title">Stock y Catálogo</h3>
                            <p class="feature-desc">
                                Catálogo centralizado de libros y artículos con alertas de bajo inventario.
                            </p>
                        </div>
                    </div>

                    <!-- Tarjeta 3: Multi-Sucursal -->
                    <div class="col-md-6 col-lg-3">
                        <div class="feature-card card-amber">
                            <div class="feature-icon-wrapper icon-amber">
                                <i class="fas fa-store-alt"></i>
                            </div>
                            <h3 class="feature-title">Multi-Sucursal</h3>
                            <p class="feature-desc">
                                Administración de sedes con inventarios independientes y asignación de cajas.
                            </p>
                        </div>
                    </div>

                    <!-- Tarjeta 4: Seguridad y Roles -->
                    <div class="col-md-6 col-lg-3">
                        <div class="feature-card card-purple">
                            <div class="feature-icon-wrapper icon-purple">
                                <i class="fas fa-user-shield"></i>
                            </div>
                            <h3 class="feature-title">Control de Acceso</h3>
                            <p class="feature-desc">
                                Permisos para Administrador General, Gerentes de Sede y Cajeros.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Banner de Acceso -->
                <div class="pos-info-banner mt-5">
                    <div class="row align-items-center">
                        <div class="col-lg-8 mb-3 mb-lg-0">
                            <h3 class="fw-bold fs-4 mb-2">Operaciones en mostrador</h3>
                            <p class="text-light opacity-75 mb-0">
                                Inicia sesión con tus credenciales asignadas para acceder a tu módulo.
                            </p>
                        </div>
                        <div class="col-lg-4 text-lg-end">
                            <a href="{{ route('login') }}" class="btn btn-light fw-bold px-4 py-2 rounded-3 text-dark">
                                <i class="fas fa-key me-1"></i> Iniciar Sesión
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Pie de Página -->
    <footer class="pos-footer">
        <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
            <div>
                <strong>Librería JILS</strong> &bull; Proyecto Integrador &bull; Universidad de Colima
            </div>
            <div class="text-muted">
                Facultad de Ingeniería Electromecánica &bull; Grupo 3ºE &bull; {{ date('Y') }}
            </div>
        </div>
    </footer>

</body>
</html>
