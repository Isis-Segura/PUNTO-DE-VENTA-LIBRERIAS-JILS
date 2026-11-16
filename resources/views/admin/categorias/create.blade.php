@extends('adminlte::page')

@section('title', __('Nueva categoría'))

@section('content_header')
    <h1>{{ __('Nueva categoría') }}</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('categorias.store') }}" method="POST">
                @csrf

                @include('partials.campos-bilingues', ['item' => null, 'maxNombre' => 80, 'maxDescripcion' => 500])

                <button type="submit" class="btn btn-primary">{{ __('Guardar') }}</button>
                <a href="{{ route('categorias.index') }}" class="btn btn-secondary">{{ __('Cancelar') }}</a>
            </form>
        </div>
    </div>
@stop
