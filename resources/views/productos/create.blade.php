@extends('adminlte::page')

@section('title', __('Nuevo producto'))

@section('content_header')
    <h1>{{ __('Nuevo producto') }}</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('productos.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label>{{ __('Sucursal') }}</label>
                    <select name="sucursal_id" class="form-control @error('sucursal_id') is-invalid @enderror">
                        @foreach ($sucursales as $s)
                            <option value="{{ $s->id }}" {{ old('sucursal_id') == $s->id ? 'selected' : '' }}>
                                {{ $s->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('sucursal_id')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label>{{ __('Categoría') }}</label>
                    <select name="categoria_id" class="form-control @error('categoria_id') is-invalid @enderror">
                        <option value="">{{ __('Sin categoría') }}</option>
                        @foreach ($categorias as $c)
                            <option value="{{ $c->id }}" {{ old('categoria_id') == $c->id ? 'selected' : '' }}>
                                {{ $c->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('categoria_id')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

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
                    <textarea name="descripcion" class="form-control @error('descripcion') is-invalid @enderror" rows="3">{{ old('descripcion') }}</textarea>
                    @error('descripcion')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label>{{ __('Código / SKU') }}</label>
                    <input type="text" name="codigo" class="form-control @error('codigo') is-invalid @enderror"
                           value="{{ old('codigo') }}">
                    @error('codigo')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label>{{ __('Precio') }}</label>
                        <input type="number" step="0.01" min="0" name="precio"
                               class="form-control @error('precio') is-invalid @enderror" value="{{ old('precio') }}">
                        @error('precio')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group col-md-4">
                        <label>{{ __('Cantidad inicial en inventario') }}</label>
                        <input type="number" min="0" name="cantidad_inicial"
                               class="form-control @error('cantidad_inicial') is-invalid @enderror"
                               value="{{ old('cantidad_inicial', 0) }}">
                        @error('cantidad_inicial')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group col-md-4">
                        <label>{{ __('Stock mínimo (alerta)') }}</label>
                        <input type="number" min="0" name="stock_minimo"
                               class="form-control @error('stock_minimo') is-invalid @enderror"
                               value="{{ old('stock_minimo', 5) }}">
                        @error('stock_minimo')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label>{{ __('Estado') }}</label>
                    <select name="activo" class="form-control">
                        <option value="1" selected>{{ __('Activo') }}</option>
                        <option value="0">{{ __('Inactivo') }}</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">{{ __('Guardar') }}</button>
                <a href="{{ route('productos.index') }}" class="btn btn-secondary">{{ __('Cancelar') }}</a>
            </form>
        </div>
    </div>
@stop
