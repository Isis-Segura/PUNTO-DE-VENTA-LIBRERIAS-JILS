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

                @include('partials.campos-bilingues', ['item' => null, 'maxNombre' => 80, 'maxDescripcion' => 50])

                <button type="submit" class="btn btn-primary">{{ __('Guardar') }}</button>
                <a href="{{ route('generos.index') }}" class="btn btn-secondary">{{ __('Cancelar') }}</a>
            </form>
        </div>
    </div>
@stop
