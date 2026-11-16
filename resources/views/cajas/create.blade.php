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
                                {{ $sucursal->nombre_traducido }}
                            </option>
                        @endforeach
                    </select>
                    @error('sucursal_id')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                @include('partials.campos-bilingues', ['item' => null, 'maxNombre' => 80, 'maxDescripcion' => 255])

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
