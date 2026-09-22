@extends('adminlte::page')

@section('title', __('Nueva caja'))

@section('content_header')
    <h1>{{ __('Nueva caja') }}</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('cajas.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="sucursal_id">{{ __('Sucursal') }} <span class="text-danger">*</span></label>
                    <select name="sucursal_id" id="sucursal_id"
                            class="form-control @error('sucursal_id') is-invalid @enderror" required>
                        <option value="">{{ __('Selecciona una sucursal') }}</option>
                        @foreach ($sucursales as $sucursal)
                            <option value="{{ $sucursal->id }}"
                                {{ old('sucursal_id', $sucursales->count() === 1 ? $sucursales->first()->id : '') == $sucursal->id ? 'selected' : '' }}>
                                {{ $sucursal->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('sucursal_id')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="nombre">{{ __('Nombre') }} <span class="text-danger">*</span></label>
                    <input type="text" name="nombre" id="nombre" value="{{ old('nombre') }}"
                           class="form-control @error('nombre') is-invalid @enderror"
                           maxlength="80" required placeholder="Ej. Caja 1, Caja Principal">
                    @error('nombre')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="descripcion">{{ __('Descripción') }}</label>
                    <textarea name="descripcion" id="descripcion" rows="2"
                              class="form-control @error('descripcion') is-invalid @enderror"
                              maxlength="255">{{ old('descripcion') }}</textarea>
                    @error('descripcion')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="activa">{{ __('Estado') }}</label>
                    <select name="activa" id="activa" class="form-control">
                        <option value="1" {{ old('activa', '1') == '1' ? 'selected' : '' }}>{{ __('Activa') }}</option>
                        <option value="0" {{ old('activa') == '0' ? 'selected' : '' }}>{{ __('Inactiva') }}</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> {{ __('Guardar') }}
                </button>
                <a href="{{ route('cajas.index') }}" class="btn btn-secondary">{{ __('Cancelar') }}</a>
            </form>
        </div>
    </div>
@stop
