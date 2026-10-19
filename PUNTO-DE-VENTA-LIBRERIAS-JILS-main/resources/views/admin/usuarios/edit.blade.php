@extends('adminlte::page')

@section('title', __('Editar usuario'))

@section('content_header')
    <h1>{{ __('Editar usuario') }}</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.usuarios.update', $usuario) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label>{{ __('Nombre') }}</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', $usuario->name) }}">
                    @error('name')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label>{{ __('Correo') }}</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email', $usuario->email) }}">
                    @error('email')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label>{{ __('Rol') }}</label>
                    <select name="role_id" id="role_id" class="form-control @error('role_id') is-invalid @enderror">
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}" data-slug="{{ $role->slug }}"
                                {{ old('role_id', $usuario->role_id) == $role->id ? 'selected' : '' }}>
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
                            <option value="{{ $s->id }}" {{ old('sucursal_id', $usuario->sucursal_id) == $s->id ? 'selected' : '' }}>
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
                    <label>{{ __('Estado') }}</label>
                    <select name="activo" class="form-control">
                        <option value="1" {{ old('activo', $usuario->activo) == 1 ? 'selected' : '' }}>{{ __('Activo') }}</option>
                        <option value="0" {{ old('activo', $usuario->activo) == 0 ? 'selected' : '' }}>{{ __('Inactivo') }}</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>{{ __('Nueva contraseña') }}</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                           placeholder="{{ __('Dejar en blanco para no cambiarla') }}">
                    @error('password')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label>{{ __('Confirmar nueva contraseña') }}</label>
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

        grupoSucursal.style.display = (slug === 'admin') ? 'none' : '';
    }

    roleSelect.addEventListener('change', actualizar);
    actualizar();
})();
</script>
@stop
