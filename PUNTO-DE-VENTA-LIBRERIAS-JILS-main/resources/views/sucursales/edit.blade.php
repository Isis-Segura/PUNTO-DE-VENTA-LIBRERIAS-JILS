@extends('adminlte::page')

@section('title', __('Editar sucursal'))

@section('content_header')
    <h1>{{ __('Editar sucursal') }}</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('sucursales.update', $sucursal) }}" method="POST">
                @csrf
                @method('PUT')
                @include('sucursales._form', ['sucursal' => $sucursal])

                <button type="submit" class="btn btn-primary">{{ __('Guardar') }}</button>
                <a href="{{ route('sucursales.index') }}" class="btn btn-secondary">{{ __('Cancelar') }}</a>
            </form>
        </div>
    </div>
@stop
