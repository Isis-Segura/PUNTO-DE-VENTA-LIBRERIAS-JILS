@extends('adminlte::page')

@section('title', __('Editar caja'))

@section('content_header')
    <h1>{{ __('Editar caja') }}</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('cajas.update', $caja) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="sucursal_id">{{ __('Sucursal') }} <span class="text-danger">*</span></label>
                    <select name="sucursal_id" id="sucursal_id"
                            class="form-control @error('sucursal_id') is-invalid @enderror" required>
                        @foreach ($sucursales as $sucursal)
                            <option value="{{ $sucursal->id }}"
                                {{ old('sucursal_id', $caja->sucursal_id) == $sucursal->id ? 'selected' : '' }}>
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
                    <input type="text" name="nombre" id="nombre"
                           value="{{ old('nombre', $caja->nombre) }}"
                           class="form-control @error('nombre') is-invalid @enderror"
                           maxlength="80" required>
                    @error('nombre')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="descripcion">{{ __('Descripción') }}</label>
                    <textarea name="descripcion" id="descripcion" rows="2"
                              class="form-control @error('descripcion') is-invalid @enderror"
                              maxlength="255">{{ old('descripcion', $caja->descripcion) }}</textarea>
                    @error('descripcion')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="activa">{{ __('Estado') }}</label>
                    <select name="activa" id="activa" class="form-control">
                        <option value="1" {{ old('activa', $caja->activa ? '1' : '0') == '1' ? 'selected' : '' }}>
                            {{ __('Activa') }}
                        </option>
                        <option value="0" {{ old('activa', $caja->activa ? '1' : '0') == '0' ? 'selected' : '' }}>
                            {{ __('Inactiva') }}
                        </option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> {{ __('Actualizar') }}
                </button>
                <a href="{{ route('cajas.index') }}" class="btn btn-secondary">{{ __('Cancelar') }}</a>
            </form>
        </div>
    </div>
@stop
