@php( $logout_url = View::getSection('logout_url') ?? config('adminlte.logout_url', 'logout') )
@php( $profile_url = Auth::check() ? route('profile.password.edit') : '' )

@if (config('adminlte.use_route_url', false))
    @php( $logout_url = $logout_url ? route($logout_url) : '' )
@else
    @php( $logout_url = $logout_url ? url($logout_url) : '' )
@endif

<li class="nav-item dropdown user-menu">
    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
        <span>{{ Auth::user()->name }}</span>
    </a>

    <ul class="dropdown-menu dropdown-menu-right" style="min-width: 12rem;">
        <li class="user-footer d-flex flex-column p-2">
            <a class="btn btn-default btn-flat btn-block text-left mb-1" href="{{ $profile_url }}">
                <i class="fas fa-fw fa-key text-primary"></i>
                {{ __('Cambiar contraseña') }}
            </a>
            <a class="btn btn-default btn-flat btn-block text-left"
               href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fa fa-fw fa-power-off text-red"></i>
                {{ __('Salir') }}
            </a>
            <form id="logout-form" action="{{ $logout_url }}" method="POST" style="display: none;">
                @if(config('adminlte.logout_method'))
                    {{ method_field(config('adminlte.logout_method')) }}
                @endif
                {{ csrf_field() }}
            </form>
        </li>
    </ul>
</li>
