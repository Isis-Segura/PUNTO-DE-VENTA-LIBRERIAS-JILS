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
        background-color: #561C24 !important;
        color: #ffffff !important;
    }

    /* ---------------------------------------------------------------------
       1. MODO OSCURO CÁLIDO (IGUAL AL LOGIN Y AL INICIO)
       --------------------------------------------------------------------- */
    body.dark-mode {
        background-color: #1F0E11 !important;
        color: #F5EFE6 !important;
    }

    body.dark-mode .content-wrapper,
    body.dark-mode .main-footer {
        background-color: #261317 !important;
    }

    /* Fondo y bordes de tarjetas en Modo Oscuro */
    body.dark-mode .card,
    body.dark-mode .bg-white,
    body.dark-mode [class*="card"] {
        background-color: #321A1E !important;
        border: 1px solid #4D282E !important;
        color: #F5EFE6 !important;
    }

    /* Solución definitiva para títulos y descripciones de libros */
    body.dark-mode .card *,
    body.dark-mode .card h1, body.dark-mode .card h2, body.dark-mode .card h3,
    body.dark-mode .card h4, body.dark-mode .card h5, body.dark-mode .card h6,
    body.dark-mode .card-title, body.dark-mode .card-header,
    body.dark-mode .card-body, body.dark-mode .card-footer {
        color: #F5EFE6 !important;
    }

    body.dark-mode .text-muted, 
    body.dark-mode small, 
    body.dark-mode .card small,
    body.dark-mode .card .text-secondary {
        color: #D6C2B4 !important;
    }

    /* Navbar superior en Modo Oscuro */
    body.dark-mode .main-header {
        background-color: #180B0D !important;
        border-bottom: 1px solid #3D1C21 !important;
    }

    body.dark-mode .main-header .nav-link,
    body.dark-mode .main-header i,
    body.dark-mode .main-header span,
    body.dark-mode .main-header a {
        color: #E8D8C4 !important;
        background-color: transparent !important;
    }

    body.dark-mode .main-header .nav-link:hover,
    body.dark-mode .main-header .nav-link:hover i {
        color: #FFFFFF !important;
    }

    /* ---------------------------------------------------------------------
       2. MODO CLARO CÁLIDO
       --------------------------------------------------------------------- */
    body:not(.dark-mode) .content-wrapper {
        background-color: #F9F6F0 !important;
    }

    body:not(.dark-mode) .main-header {
        background-color: #FFFFFF !important;
        border-bottom: 1px solid #E8D8C4 !important;
    }

    body:not(.dark-mode) .main-header .nav-link,
    body:not(.dark-mode) .main-header i,
    body:not(.dark-mode) .main-header span {
        color: #3D1217 !important;
    }

    body:not(.dark-mode) .card {
        background-color: #FFFFFF !important;
        border: 1px solid #E8D8C4 !important;
        box-shadow: 0 4px 12px rgba(42, 12, 16, 0.04) !important;
    }

    body:not(.dark-mode) h1, body:not(.dark-mode) h2, body:not(.dark-mode) h3, 
    body:not(.dark-mode) h4, body:not(.dark-mode) h5, body:not(.dark-mode) h6,
    body:not(.dark-mode) .card-title {
        color: #2A0C10 !important;
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
        background-color: #180B0D !important;
        border-right: 1px solid #3D1C21 !important;
    }

    .brand-link {
        background-color: #120507 !important;
        color: #ffffff !important;
        border-bottom: 1px solid #3D1C21 !important;
    }

    .nav-header {
        color: #A08276 !important;
        background: transparent !important;
        font-size: 0.75rem !important;
        font-weight: 700 !important;
    }

    .nav-sidebar .nav-link {
        color: #E8D8C4 !important;
        font-weight: 600 !important;
        border-radius: 8px !important;
        margin: 2px 8px !important;
        padding: 0.6rem 0.8rem !important;
    }

    .nav-sidebar .nav-link i,
    .nav-sidebar .nav-link p {
        color: #E8D8C4 !important;
    }

    .nav-sidebar .nav-link:hover {
        background-color: #561C24 !important;
        color: #ffffff !important;
    }

    .nav-sidebar .nav-link.active,
    .nav-sidebar .nav-item.menu-open > .nav-link {
        background-color: #561C24 !important;
        color: #ffffff !important;
        font-weight: 700 !important;
        box-shadow: none !important;
    }

    /* ---------------------------------------------------------------------
       4. TARJETAS DE ESTADÍSTICAS SUPERIORES
       --------------------------------------------------------------------- */
    .small-box.bg-primary, .card.bg-primary, .bg-primary,
    [class*="bg-purple"], [class*="bg-indigo"] {
        background-color: #561C24 !important;
        color: #FFFFFF !important;
        border-radius: 12px !important;
    }

    .small-box.bg-success, .card.bg-success, .bg-success,
    [class*="bg-teal"] {
        background-color: #7B4B3A !important;
        color: #FFFFFF !important;
        border-radius: 12px !important;
    }

    .small-box.bg-secondary, .card.bg-secondary, .bg-secondary,
    .small-box.bg-gray, .card.bg-gray {
        background-color: #A08276 !important;
        color: #FFFFFF !important;
        border-radius: 12px !important;
    }

    .small-box *, .card.bg-primary *, .card.bg-success *, .card.bg-secondary * {
        color: #FFFFFF !important;
    }

    /* ---------------------------------------------------------------------
       5. BOTONES Y ACCIONES
       --------------------------------------------------------------------- */
    .btn-primary, button.btn-primary, a.btn-primary,
    .btn-dark, a.btn-dark {
        background-color: #561C24 !important;
        border-color: #561C24 !important;
        color: #FFFFFF !important;
    }

    .btn-primary:hover, button.btn-primary:hover, a.btn-primary:hover {
        background-color: #3D1217 !important;
        border-color: #3D1217 !important;
        color: #FFFFFF !important;
    }

    .btn-warning {
        background-color: #D97706 !important;
        border-color: #D97706 !important;
        color: #FFFFFF !important;
    }

    .btn-danger {
        background-color: #991B1B !important;
        border-color: #991B1B !important;
        color: #FFFFFF !important;
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
        background-color: #FFFFFF !important;
        border: 1px solid #E8D8C4 !important;
        color: #561C24 !important;
    }

    body:not(.dark-mode) .pagination .page-item.active .page-link {
        background-color: #561C24 !important;
        border-color: #561C24 !important;
        color: #FFFFFF !important;
    }

    body:not(.dark-mode) .pagination .page-link:hover {
        background-color: #F5EFE6 !important;
        color: #3D1217 !important;
    }

    /* Paginación Modo Oscuro */
    body.dark-mode .pagination .page-link {
        background-color: #321A1E !important;
        border: 1px solid #4D282E !important;
        color: #E8D8C4 !important;
    }

    body.dark-mode .pagination .page-item.active .page-link {
        background-color: #561C24 !important;
        border-color: #8C2D3A !important;
        color: #FFFFFF !important;
    }

    body.dark-mode .pagination .page-link:hover {
        background-color: #4D282E !important;
        color: #FFFFFF !important;
    }

    /* ---------------------------------------------------------------------
       7. INPUTS Y SELECTS
       --------------------------------------------------------------------- */
    body.dark-mode .form-control,
    body.dark-mode select,
    body.dark-mode .form-select {
        background-color: #261317 !important;
        color: #F5EFE6 !important;
        border: 1px solid #4D282E !important;
    }

    body.dark-mode .form-control::placeholder {
        color: #A08276 !important;
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