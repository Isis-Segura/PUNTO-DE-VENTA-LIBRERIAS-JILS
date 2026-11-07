@extends('adminlte::master')

@inject('layoutHelper', 'JeroenNoten\LaravelAdminLte\Helpers\LayoutHelper')
@inject('preloaderHelper', 'JeroenNoten\LaravelAdminLte\Helpers\PreloaderHelper')

@section('adminlte_css')
    @stack('css')
    @yield('css')
    <style>
    /* =====================================================================
       TEMA JILS — claro y oscuro (Pizarra azulada). Todo sale de --ui-* y
       --pos-* definidos en pos-theme.css; aquí no hay colores sueltos.
       ===================================================================== */

    ::selection { background-color: var(--pos-selection) !important; color: var(--pos-white) !important; }

    /* ---------- Fondo general y contenido ---------- */
    body, body.dark-mode { background-color: var(--ui-page) !important; color: var(--ui-text) !important; }
    .content-wrapper, .main-footer { background-color: var(--ui-page) !important; color: var(--ui-text) !important; }
    body.dark-mode .content-wrapper, body.dark-mode .main-footer { background-color: var(--pos-dark-content) !important; }

    /* ---------- Barra superior ---------- */
    .main-header {
        background-color: var(--ui-nav) !important;
        border-bottom: 1px solid var(--ui-border) !important;
    }
    .main-header .nav-link, .main-header .nav-link i, .main-header span, .main-header a { color: var(--ui-text) !important; background-color: transparent !important; }
    .main-header .nav-link:hover, .main-header .nav-link:hover i { color: var(--ui-link) !important; }

    /* ---------- Títulos y textos ---------- */
    h1, h2, h3, h4, h5, h6, .card-title { color: var(--ui-title) !important; }
    .text-muted, small, .small { color: var(--ui-muted) !important; }
    .text-dark { color: var(--ui-title) !important; }
    .content-header h1 { font-weight: 700; }
    a:not(.btn):not(.nav-link):not(.page-link):not(.dropdown-item) { color: var(--ui-link); }
    label, .col-form-label, .form-check-label { color: var(--ui-body); }

    /* ---------- Tarjetas ---------- */
    .card {
        background-color: var(--ui-surface) !important;
        border: 1px solid var(--ui-border) !important;
        color: var(--ui-text) !important;
        box-shadow: var(--pos-shadow) !important;
    }
    .card-header, .card-footer { background-color: transparent !important; border-color: var(--ui-divider) !important; color: var(--ui-title); }
    .card-body, .card-header, .card-footer, .card-title { color: var(--ui-text); }
    .bg-white, .bg-light { background-color: var(--ui-surface) !important; color: var(--ui-text) !important; }

    /* ---------- Menú lateral ---------- */
    .main-sidebar, .main-sidebar *, .main-sidebar .sidebar, .main-sidebar .nav-sidebar, .main-sidebar .nav-item, .main-sidebar .nav-link { background-color: transparent !important; }
    .main-sidebar { background-color: var(--ui-sidebar) !important; border-right: 1px solid var(--ui-sidebar-border) !important; }
    .brand-link { background-color: var(--ui-sidebar-brand) !important; color: var(--pos-white) !important; border-bottom: 1px solid var(--ui-sidebar-border) !important; }
    .brand-link .brand-text { color: var(--pos-white) !important; }
    .nav-header { color: var(--ui-sidebar-head) !important; background: transparent !important; font-size: .75rem !important; font-weight: 700 !important; }
    .nav-sidebar .nav-link { color: var(--ui-sidebar-text) !important; font-weight: 600 !important; border-radius: 8px !important; margin: 2px 8px !important; padding: .6rem .8rem !important; }
    .nav-sidebar .nav-link i, .nav-sidebar .nav-link p { color: var(--ui-sidebar-text) !important; }
    .nav-sidebar .nav-link:hover { background-color: var(--ui-sidebar-hover) !important; color: var(--pos-white) !important; }
    .nav-sidebar .nav-link:hover i, .nav-sidebar .nav-link:hover p { color: var(--pos-white) !important; }
    .nav-sidebar .nav-link.active, .nav-sidebar .nav-item.menu-open > .nav-link { background-color: var(--pos-sidebar-active) !important; color: var(--pos-white) !important; font-weight: 700 !important; box-shadow: none !important; }
    .nav-sidebar .nav-link.active i, .nav-sidebar .nav-link.active p { color: var(--pos-white) !important; }

    /* ---------- Tarjetas de color de AdminLTE ---------- */
    .small-box.bg-primary, .card.bg-primary, .bg-primary, [class*="bg-purple"], [class*="bg-indigo"] { background-color: var(--pos-dashboard-primary) !important; color: var(--pos-white) !important; border-radius: 12px !important; }
    .small-box.bg-success, .card.bg-success, .bg-success, [class*="bg-teal"] { background-color: var(--pos-dashboard-success) !important; color: var(--pos-white) !important; border-radius: 12px !important; }
    .small-box.bg-secondary, .card.bg-secondary, .bg-secondary, .small-box.bg-gray, .card.bg-gray { background-color: var(--pos-dashboard-neutral) !important; color: var(--pos-title) !important; border-radius: 12px !important; }
    .small-box *, .card.bg-primary *, .card.bg-success * { color: var(--pos-white) !important; }
    .small-box.bg-secondary *, .card.bg-secondary * { color: var(--pos-title) !important; }

    /* ---------- Botones ---------- */
    .btn-primary, button.btn-primary, a.btn-primary, .btn-dark, a.btn-dark { background-color: var(--pos-primary-action) !important; border-color: var(--pos-primary-border) !important; color: var(--pos-white) !important; }
    .btn-primary:hover, button.btn-primary:hover, a.btn-primary:hover { background-color: var(--pos-primary-action-hover) !important; border-color: var(--pos-sidebar-brand) !important; color: var(--pos-white) !important; }
    .btn-warning { background-color: var(--pos-warning) !important; border-color: var(--pos-warning-border) !important; color: var(--pos-white) !important; }
    .btn-danger  { background-color: var(--pos-danger)  !important; border-color: var(--pos-danger-border)  !important; color: var(--pos-white) !important; }
    .btn-default, .btn-light, .btn-outline-secondary, .btn-secondary {
        background-color: var(--ui-inset) !important; border: 1px solid var(--ui-border) !important; color: var(--ui-body) !important;
    }
    .btn-default:hover, .btn-light:hover, .btn-outline-secondary:hover, .btn-secondary:hover { background-color: var(--ui-surface-2) !important; color: var(--ui-title) !important; }
    .btn-outline-primary { color: var(--ui-link) !important; border-color: var(--ui-link) !important; background: transparent !important; }
    .btn-outline-primary:hover { background-color: var(--pos-primary-action) !important; color: var(--pos-white) !important; }
    .btn-outline-danger { color: var(--ui-danger-text, #e58a8a) !important; border-color: currentColor !important; background: transparent !important; }
    .btn:disabled, .btn.disabled { opacity: .55; }

    /* ---------- Paginación ---------- */
    .pagination { margin-top: 20px !important; justify-content: center !important; }
    .pagination .page-item .page-link { border-radius: 8px !important; margin: 0 4px !important; font-weight: 600 !important; padding: 8px 16px !important; background-color: var(--ui-inset) !important; border: 1px solid var(--ui-border) !important; color: var(--ui-body) !important; }
    .pagination .page-item.active .page-link { background-color: var(--pos-accent) !important; border-color: var(--pos-accent-border) !important; color: var(--pos-white) !important; }
    .pagination .page-item.disabled .page-link { opacity: .5; }
    .pagination .page-link:hover { background-color: var(--ui-surface-2) !important; color: var(--ui-title) !important; }
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