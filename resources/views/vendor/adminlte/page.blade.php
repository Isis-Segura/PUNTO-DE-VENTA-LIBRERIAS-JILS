@extends('adminlte::master')

@inject('layoutHelper', 'JeroenNoten\LaravelAdminLte\Helpers\LayoutHelper')
@inject('preloaderHelper', 'JeroenNoten\LaravelAdminLte\Helpers\PreloaderHelper')

@section('adminlte_css')
    @stack('css')
    @yield('css')
    <style>
    /* =====================================================================
       PALETA COFFEE / VINO TINTO UNIFICADA CON LOGIN
       ===================================================================== */

    ::selection {
        background-color: var(--pos-selection) !important;
        color: var(--pos-white) !important;
    }

    /* ---------------------------------------------------------------------
       1. MODO OSCURO CÁLIDO (IGUAL AL LOGIN Y AL INICIO)
       --------------------------------------------------------------------- */
    body.dark-mode {
        background-color: var(--pos-dark-page) !important;
        color: var(--pos-cream) !important;
    }

    body.dark-mode .content-wrapper,
    body.dark-mode .main-footer {
        background-color: var(--pos-dark-content) !important;
    }

    /* Fondo y bordes de tarjetas en Modo Oscuro */
    body.dark-mode .card,
    body.dark-mode .bg-white,
    body.dark-mode [class*="card"] {
        background-color: var(--pos-dark-card) !important;
        border: 1px solid var(--pos-dark-border) !important;
        color: var(--pos-cream) !important;
    }

    /* Solución definitiva para títulos y descripciones de libros */
    body.dark-mode .card *,
    body.dark-mode .card h1, body.dark-mode .card h2, body.dark-mode .card h3,
    body.dark-mode .card h4, body.dark-mode .card h5, body.dark-mode .card h6,
    body.dark-mode .card-title, body.dark-mode .card-header,
    body.dark-mode .card-body, body.dark-mode .card-footer {
        color: var(--pos-cream) !important;
    }

    body.dark-mode .text-muted, 
    body.dark-mode small, 
    body.dark-mode .card small,
    body.dark-mode .card .text-secondary {
        color: var(--pos-dark-muted) !important;
    }

    /* Navbar superior en Modo Oscuro */
    body.dark-mode .main-header {
        background-color: var(--pos-dark-navbar) !important;
        border-bottom: 1px solid var(--pos-sidebar-border) !important;
    }

    body.dark-mode .main-header .nav-link,
    body.dark-mode .main-header i,
    body.dark-mode .main-header span,
    body.dark-mode .main-header a {
        color: var(--pos-cream) !important;
        background-color: transparent !important;
    }

    body.dark-mode .main-header .nav-link:hover,
    body.dark-mode .main-header .nav-link:hover i {
        color: var(--pos-white) !important;
    }

    /* ---------------------------------------------------------------------
       2. MODO CLARO CÁLIDO
       --------------------------------------------------------------------- */
    body:not(.dark-mode) .content-wrapper {
        background-color: var(--pos-content-light) !important;
    }

    body:not(.dark-mode) .main-header {
        background-color: var(--pos-surface) !important;
        border-bottom: 1px solid var(--pos-cream) !important;
    }

    body:not(.dark-mode) .main-header .nav-link,
    body:not(.dark-mode) .main-header i,
    body:not(.dark-mode) .main-header span {
        color: var(--pos-navbar-text) !important;
    }

    body:not(.dark-mode) .card {
        background-color: var(--pos-surface) !important;
        border: 1px solid var(--pos-cream) !important;
        box-shadow: 0 4px 12px rgba(42, 12, 16, 0.04) !important;
    }

    body:not(.dark-mode) h1, body:not(.dark-mode) h2, body:not(.dark-mode) h3, 
    body:not(.dark-mode) h4, body:not(.dark-mode) h5, body:not(.dark-mode) h6,
    body:not(.dark-mode) .card-title {
        color: var(--pos-title) !important;
    }

    /* ---------------------------------------------------------------------
       3. SIDEBAR LATERAL (MENÚ VINO TINTO)
       --------------------------------------------------------------------- */
    .main-sidebar,
    .main-sidebar *,
    .main-sidebar .sidebar,
    .main-sidebar .nav-sidebar,
    .main-sidebar .nav-item,
    .main-sidebar .nav-link {
        background-color: transparent !important;
    }

    .main-sidebar {
        background-color: var(--pos-sidebar) !important;
        border-right: 1px solid var(--pos-sidebar-border) !important;
    }

    .brand-link {
        background-color: var(--pos-sidebar-brand) !important;
        color: var(--pos-white) !important;
        border-bottom: 1px solid var(--pos-sidebar-border) !important;
    }

    .nav-header {
        color: var(--pos-dashboard-neutral) !important;
        background: transparent !important;
        font-size: 0.75rem !important;
        font-weight: 700 !important;
    }

    .nav-sidebar .nav-link {
        color: var(--pos-cream) !important;
        font-weight: 600 !important;
        border-radius: 8px !important;
        margin: 2px 8px !important;
        padding: 0.6rem 0.8rem !important;
    }

    .nav-sidebar .nav-link i,
    .nav-sidebar .nav-link p {
        color: var(--pos-cream) !important;
    }

    .nav-sidebar .nav-link:hover {
        background-color: var(--pos-sidebar-hover) !important;
        color: var(--pos-white) !important;
    }

    .nav-sidebar .nav-link.active,
    .nav-sidebar .nav-item.menu-open > .nav-link {
        background-color: var(--pos-sidebar-active) !important;
        color: var(--pos-white) !important;
        font-weight: 700 !important;
        box-shadow: none !important;
    }

    /* ---------------------------------------------------------------------
       4. TARJETAS DE ESTADÍSTICAS SUPERIORES
       --------------------------------------------------------------------- */
    .small-box.bg-primary, .card.bg-primary, .bg-primary,
    [class*="bg-purple"], [class*="bg-indigo"] {
        background-color: var(--pos-dashboard-primary) !important;
        color: var(--pos-white) !important;
        border-radius: 12px !important;
    }

    .small-box.bg-success, .card.bg-success, .bg-success,
    [class*="bg-teal"] {
        background-color: var(--pos-dashboard-success) !important;
        color: var(--pos-white) !important;
        border-radius: 12px !important;
    }

    .small-box.bg-secondary, .card.bg-secondary, .bg-secondary,
    .small-box.bg-gray, .card.bg-gray {
        background-color: var(--pos-dashboard-neutral) !important;
        color: var(--pos-white) !important;
        border-radius: 12px !important;
    }

    .small-box *, .card.bg-primary *, .card.bg-success *, .card.bg-secondary * {
        color: var(--pos-white) !important;
    }

    /* ---------------------------------------------------------------------
       5. BOTONES Y ACCIONES
       --------------------------------------------------------------------- */
    .btn-primary, button.btn-primary, a.btn-primary,
    .btn-dark, a.btn-dark {
        background-color: var(--pos-primary-action) !important;
        border-color: var(--pos-primary-border) !important;
        color: var(--pos-white) !important;
    }

    .btn-primary:hover, button.btn-primary:hover, a.btn-primary:hover {
        background-color: var(--pos-primary-action-hover) !important;
        border-color: var(--pos-sidebar-brand) !important;
        color: var(--pos-white) !important;
    }

    .btn-warning {
        background-color: var(--pos-warning) !important;
        border-color: var(--pos-warning-border) !important;
        color: var(--pos-white) !important;
    }

    .btn-danger {
        background-color: var(--pos-danger) !important;
        border-color: var(--pos-danger-border) !important;
        color: var(--pos-white) !important;
    }

    /* ---------------------------------------------------------------------
       6. PAGINACIÓN DE ABAJO (PESTAÑAS DE PÁGINAS 1, 2, 3...)
       --------------------------------------------------------------------- */
    .pagination {
        margin-top: 20px !important;
        justify-content: center !important;
    }

    .pagination .page-item .page-link {
        border-radius: 8px !important;
        margin: 0 4px !important;
        font-weight: 600 !important;
        padding: 8px 16px !important;
    }

    /* Paginación Modo Claro */
    body:not(.dark-mode) .pagination .page-link {
        background-color: var(--pos-surface) !important;
        border: 1px solid var(--pos-cream) !important;
        color: var(--pos-old-wine) !important;
    }

    body:not(.dark-mode) .pagination .page-item.active .page-link {
        background-color: var(--pos-old-wine) !important;
        border-color: var(--pos-old-wine) !important;
        color: var(--pos-white) !important;
    }

    body:not(.dark-mode) .pagination .page-link:hover {
        background-color: var(--pos-light-pagination-hover) !important;
        color: var(--pos-old-wine-hover) !important;
    }

    /* Paginación Modo Oscuro */
    body.dark-mode .pagination .page-link {
        background-color: var(--pos-dark-pagination) !important;
        border: 1px solid var(--pos-dark-input-border) !important;
        color: var(--pos-cream) !important;
    }

    body.dark-mode .pagination .page-item.active .page-link {
        background-color: var(--pos-old-wine) !important;
        border-color: var(--pos-old-wine-border) !important;
        color: var(--pos-white) !important;
    }

    body.dark-mode .pagination .page-link:hover {
        background-color: var(--pos-dark-pagination-hover) !important;
        color: var(--pos-white) !important;
    }

    /* ---------------------------------------------------------------------
       7. INPUTS Y SELECTS
       --------------------------------------------------------------------- */
    body.dark-mode .form-control,
    body.dark-mode select,
    body.dark-mode .form-select {
        background-color: var(--pos-dark-input) !important;
        color: var(--pos-cream) !important;
        border: 1px solid var(--pos-dark-input-border) !important;
    }

    body.dark-mode .form-control::placeholder {
        color: var(--pos-dashboard-neutral) !important;
    }
    </style>
@stop

@section('classes_body', $layoutHelper->makeBodyClasses())

@section('body_data', $layoutHelper->makeBodyData())

@section('body')
    <div class="wrapper">

        {{-- Preloader Animation (fullscreen mode) --}}
        @if($preloaderHelper->isPreloaderEnabled())
            @include('adminlte::partials.common.preloader')
        @endif

        {{-- Top Navbar --}}
        @if($layoutHelper->isLayoutTopnavEnabled())
            @include('adminlte::partials.navbar.navbar-layout-topnav')
        @else
            @include('adminlte::partials.navbar.navbar')
        @endif

        {{-- Left Main Sidebar --}}
        @if(!$layoutHelper->isLayoutTopnavEnabled())
            @include('adminlte::partials.sidebar.left-sidebar')
        @endif

        {{-- Content Wrapper --}}
        @empty($iFrameEnabled)
            @include('adminlte::partials.cwrapper.cwrapper-default')
        @else
            @include('adminlte::partials.cwrapper.cwrapper-iframe')
        @endempty

        {{-- Footer --}}
        @hasSection('footer')
            @include('adminlte::partials.footer.footer')
        @endif

        {{-- Right Control Sidebar --}}
        @if($layoutHelper->isRightSidebarEnabled())
            @include('adminlte::partials.sidebar.right-sidebar')
        @endif

    </div>
@stop

@section('adminlte_js')
    @stack('js')
    @yield('js')
@stop