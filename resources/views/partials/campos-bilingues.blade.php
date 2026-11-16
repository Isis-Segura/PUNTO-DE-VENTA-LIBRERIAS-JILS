{{-- Nombre y descripción en español e inglés.
     Parámetros: $item (modelo o null), $inglesObligatorio (bool, por defecto true), $maxDescripcion (por defecto 50) --}}
@php
    // El nombre en español siempre es obligatorio; el de inglés lo es salvo que $inglesObligatorio sea false (productos).
    $inglesObligatorio = $inglesObligatorio ?? true;
    $maxDescripcion = $maxDescripcion ?? 50;
    $maxNombre = $maxNombre ?? 150;
    $reqEs = 'required';
    $reqEn = $inglesObligatorio ? 'required' : '';
    $estrellaEs = ' <span class="text-danger">*</span>';
    $estrellaEn = $inglesObligatorio ? ' <span class="text-danger">*</span>' : ' <small class="text-muted">('.__('opcional').')</small>';
@endphp

<div class="form-row">
    <div class="form-group col-md-6">
        <label for="nombre">{{ __('Nombre (Español)') }}{!! $estrellaEs !!}</label>
        <input type="text" name="nombre" id="nombre" maxlength="{{ $maxNombre }}" {{ $reqEs }}
               class="form-control @error('nombre') is-invalid @enderror"
               value="{{ old('nombre', $item->nombre ?? '') }}">
        @error('nombre')<span class="invalid-feedback">{{ $message }}</span>@enderror
    </div>
    <div class="form-group col-md-6">
        <label for="nombre_en">{{ __('Nombre (Inglés)') }}{!! $estrellaEn !!}</label>
        <input type="text" name="nombre_en" id="nombre_en" maxlength="{{ $maxNombre }}" {{ $reqEn }}
               class="form-control @error('nombre_en') is-invalid @enderror"
               value="{{ old('nombre_en', $item->nombre_en ?? '') }}">
        @error('nombre_en')<span class="invalid-feedback">{{ $message }}</span>@enderror
    </div>
</div>

<div class="form-row">
    <div class="form-group col-md-6">
        <label for="descripcion">{{ __('Descripción (Español)') }} <small class="text-muted">({{ __('opcional') }})</small></label>
        <textarea name="descripcion" id="descripcion" rows="2" maxlength="{{ $maxDescripcion }}"
                  class="form-control @error('descripcion') is-invalid @enderror">{{ old('descripcion', $item->descripcion ?? '') }}</textarea>
        @error('descripcion')<span class="invalid-feedback">{{ $message }}</span>@enderror
    </div>
    <div class="form-group col-md-6">
        <label for="descripcion_en">{{ __('Descripción (Inglés)') }} <small class="text-muted">({{ __('opcional') }})</small></label>
        <textarea name="descripcion_en" id="descripcion_en" rows="2" maxlength="{{ $maxDescripcion }}"
                  class="form-control @error('descripcion_en') is-invalid @enderror">{{ old('descripcion_en', $item->descripcion_en ?? '') }}</textarea>
        @error('descripcion_en')<span class="invalid-feedback">{{ $message }}</span>@enderror
    </div>
</div>
