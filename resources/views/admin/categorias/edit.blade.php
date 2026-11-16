@extends('adminlte::page')

@section('title', __('Editar categoría'))

@section('content_header')
    <h1>{{ __('Editar categoría') }}</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('categorias.update', $categoria) }}" method="POST">
                @csrf
                @method('PUT')

                @include('partials.campos-bilingues', ['item' => $categoria, 'maxNombre' => 80, 'maxDescripcion' => 500])

                <button type="submit" class="btn btn-primary">{{ __('Guardar') }}</button>
                <a href="{{ route('categorias.index') }}" class="btn btn-secondary">{{ __('Cancelar') }}</a>
            </form>
        </div>
    </div>
@stop
