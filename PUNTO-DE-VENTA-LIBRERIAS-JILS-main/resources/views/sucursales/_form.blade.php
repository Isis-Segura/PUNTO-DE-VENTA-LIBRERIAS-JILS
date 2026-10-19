@php
    $esEdicion = $sucursal !== null;
@endphp

<div class="form-group">
    <label>{{ __('Nombre') }}</label>
    <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror"
           value="{{ old('nombre', $sucursal->nombre ?? '') }}">
    @error('nombre')
        <span class="invalid-feedback">{{ $message }}</span>
    @enderror
</div>

<div class="form-group">
    <label>{{ __('Dirección') }}</label>
    <input type="text" name="direccion" class="form-control @error('direccion') is-invalid @enderror"
           value="{{ old('direccion', $sucursal->direccion ?? '') }}">
    @error('direccion')
        <span class="invalid-feedback">{{ $message }}</span>
    @enderror
</div>

<div class="form-group">
    <label>{{ __('Teléfono') }}</label>
    <input type="text" name="telefono" class="form-control @error('telefono') is-invalid @enderror"
           value="{{ old('telefono', $sucursal->telefono ?? '') }}">
    @error('telefono')
        <span class="invalid-feedback">{{ $message }}</span>
    @enderror
</div>

<div class="form-group">
    <label>{{ __('Información de contacto') }}</label>
    <input type="text" name="contacto" class="form-control @error('contacto') is-invalid @enderror"
           value="{{ old('contacto', $sucursal->contacto ?? '') }}"
           placeholder="{{ __('Correo, encargado, etc.') }}">
    @error('contacto')
        <span class="invalid-feedback">{{ $message }}</span>
    @enderror
</div>

<div class="form-group">
    <label>{{ __('Gerente responsable') }}</label>
    <select name="gerente_id" class="form-control @error('gerente_id') is-invalid @enderror">
        <option value="">{{ __('Sin asignar') }}</option>
        @foreach ($gerentes as $gerente)
            <option value="{{ $gerente->id }}" {{ old('gerente_id', $sucursal->gerente_id ?? '') == $gerente->id ? 'selected' : '' }}>
                {{ $gerente->name }}
            </option>
        @endforeach
    </select>
    @error('gerente_id')
        <span class="invalid-feedback">{{ $message }}</span>
    @enderror
    <small class="form-text text-muted">
        {{ __('Solo se listan los Gerentes que aún no tienen una sucursal asignada.') }}
    </small>
</div>

<div class="form-group">
    <label>{{ __('Estado') }}</label>
    <select name="activa" class="form-control">
        <option value="1" {{ old('activa', $sucursal->activa ?? true) == 1 ? 'selected' : '' }}>{{ __('Activa') }}</option>
        <option value="0" {{ old('activa', $sucursal->activa ?? true) == 0 ? 'selected' : '' }}>{{ __('Inactiva') }}</option>
    </select>
</div>
