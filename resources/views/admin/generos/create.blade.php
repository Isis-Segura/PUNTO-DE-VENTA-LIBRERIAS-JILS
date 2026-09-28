@extends('adminlte::page')

@section('title', __('Nueva género'))

@section('content_header')
    <h1>{{ __('Nueva género') }}</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('generos.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label>{{ __('Nombre') }}</label>
                    <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror"
                           value="{{ old('nombre') }}">
                    @error('nombre')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label>{{ __('Descripción') }}</label>
                    <textarea name="descripcion" rows="3" class="form-control @error('descripcion') is-invalid @enderror">{{ old('descripcion') }}</textarea>
                    @error('descripcion')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label>{{ __('Nombre') }} (English)</label>
                    <input type="text" name="nombre_en" class="form-control @error('nombre_en') is-invalid @enderror" value="{{ old('nombre_en') }}">
                    @error('nombre_en')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label>{{ __('Descripción') }} (English)</label>
                    <textarea name="descripcion_en" rows="3" class="form-control @error('descripcion_en') is-invalid @enderror">{{ old('descripcion_en') }}</textarea>
                    @error('descripcion_en')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>

                <button type="submit" class="btn btn-primary">{{ __('Guardar') }}</button>
                <a href="{{ route('generos.index') }}" class="btn btn-secondary">{{ __('Cancelar') }}</a>
            </form>
        </div>
    </div>
@stop
