@extends('adminlte::page')

@section('title', __('Nuevo usuario'))

@section('content_header')
    <h1>{{ __('Nuevo usuario') }}</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.usuarios.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label>{{ __('Nombre') }}</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name') }}">
                    @error('name')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label>{{ __('Correo') }}</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email') }}">
                    @error('email')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label>{{ __('Rol') }}</label>
                    <select name="role_id" id="role_id" class="form-control @error('role_id') is-invalid @enderror">
                        <option value="">{{ __('Selecciona un rol') }}</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}" data-slug="{{ $role->slug }}"
                                {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                {{ $role->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('role_id')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group" id="grupo-sucursal">
                    <label>{{ __('Sucursal') }}</label>
                    <select name="sucursal_id" id="sucursal_id" class="form-control @error('sucursal_id') is-invalid @enderror">
                        <option value="">{{ __('Selecciona una sucursal') }}</option>
                        @foreach ($sucursales as $s)
                            <option value="{{ $s->id }}" {{ old('sucursal_id') == $s->id ? 'selected' : '' }}>
                                {{ $s->nombre }}
                            </option>
                        @endforeach
                    </select>
                    <small class="form-text text-muted">{{ __('Requerida para Gerente y Cajero; el Administrador General normalmente no necesita una.') }}</small>
                    @error('sucursal_id')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label>{{ __('Contraseña') }}</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                    @error('password')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label>{{ __('Confirmar contraseña') }}</label>
                    <input type="password" name="password_confirmation" class="form-control">
                </div>

                <button type="submit" class="btn btn-primary">{{ __('Guardar') }}</button>
                <a href="{{ route('admin.usuarios.index') }}" class="btn btn-secondary">{{ __('Cancelar') }}</a>
            </form>
        </div>
    </div>
@stop

@section('js')
<script>
(function () {
    var roleSelect = document.getElementById('role_id');
    var grupoSucursal = document.getElementById('grupo-sucursal');

    function actualizar() {
        var opcion = roleSelect.options[roleSelect.selectedIndex];
        var slug = opcion ? opcion.dataset.slug : null;

        // El Administrador General normalmente no necesita sucursal; se
        // oculta el campo (queda vacío) solo cuando el rol elegido es admin.
        grupoSucursal.style.display = (slug === 'admin') ? 'none' : '';
    }

    roleSelect.addEventListener('change', actualizar);
    actualizar();
})();
</script>
@stop
