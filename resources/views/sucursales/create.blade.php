@extends('adminlte::page')

@section('title', __('Nueva sucursal'))

@section('content_header')
    <h1>{{ __('Nueva sucursal') }}</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('sucursales.store') }}" method="POST">
                @csrf
                @include('sucursales._form', ['sucursal' => null])

                <button type="submit" class="btn btn-primary">{{ __('Guardar') }}</button>
                <a href="{{ route('sucursales.index') }}" class="btn btn-secondary">{{ __('Cancelar') }}</a>
            </form>
        </div>
    </div>
@stop
