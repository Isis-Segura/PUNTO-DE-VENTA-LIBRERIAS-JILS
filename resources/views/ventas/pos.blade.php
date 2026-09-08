@extends('adminlte::page')

@section('title', __('Punto de venta'))

@section('content_header')
    <h1>{{ __('Punto de venta') }} — {{ $sucursal->nombre }}</h1>
@stop

@section('content')

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if ($sucursales->count() > 1)
        <form method="GET" class="form-inline mb-3">
            <label class="mr-2">{{ __('Vendiendo en la sucursal') }}:</label>
            <select name="sucursal_id" class="form-control" onchange="this.form.submit()">
                @foreach ($sucursales as $s)
                    <option value="{{ $s->id }}" {{ $s->id === $sucursal->id ? 'selected' : '' }}>{{ $s->nombre }}</option>
                @endforeach
            </select>
        </form>
    @endif

    <div class="row">
        {{-- Buscador y resultados --}}
        <div class="col-md-7">
            <div class="card">
                <div class="card-header">
                    <input type="text" id="buscador" class="form-control" autofocus
                           placeholder="{{ __('Buscar producto por nombre o código...') }}">
                </div>
                <div class="card-body p-0" style="max-height: 480px; overflow-y: auto;">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>{{ __('Producto') }}</th>
                                <th>{{ __('Precio') }}</th>
                                <th>{{ __('Existencia') }}</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="resultados">
                            <tr><td colspan="4" class="text-center text-muted py-3">{{ __('Escribe para buscar productos...') }}</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Carrito --}}
        <div class="col-md-5">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-shopping-cart"></i> {{ __('Carrito') }}
                </div>
                <div class="card-body p-0" style="max-height: 320px; overflow-y: auto;">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>{{ __('Producto') }}</th>
                                <th style="width: 90px;">{{ __('Cant.') }}</th>
                                <th>{{ __('Subtotal') }}</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="carrito-body">
                            <tr id="carrito-vacio"><td colspan="4" class="text-center text-muted py-3">{{ __('El carrito está vacío') }}</td></tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        <strong>{{ __('Subtotal') }}:</strong>
                        <span id="subtotal-total">$0.00</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <strong>{{ __('Total') }}:</strong>
                        <span id="gran-total"><strong>$0.00</strong></span>
                    </div>

                    <form id="form-venta" action="{{ route('ventas.store') }}" method="POST" class="mt-3">
                        @csrf
                        <input type="hidden" name="sucursal_id" value="{{ $sucursal->id }}">
                        <div id="items-container"></div>

                        <div class="form-group">
                            <label>{{ __('Método de pago') }}</label>
                            <select name="metodo_pago_id" id="metodo-pago" class="form-control" required>
                                @foreach ($metodosPago as $mp)
                                    <option value="{{ $mp->id }}" data-nombre="{{ $mp->nombre }}">{{ $mp->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group" id="grupo-efectivo" style="display: none;">
                            <label>{{ __('Monto recibido en efectivo') }}</label>
                            <input type="number" step="0.01" min="0" name="monto_recibido" id="monto-recibido"
                                   class="form-control" placeholder="0.00">
                            <small class="form-text" id="vuelto-info"></small>
                        </div>

                        <button type="submit" id="btn-confirmar" class="btn btn-success btn-block" disabled>
                            <i class="fas fa-check"></i> {{ __('Confirmar venta') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
<script>
(function () {
    const urlBuscar = @json(route('ventas.buscar', ['sucursal_id' => $sucursal->id]));
    const buscador = document.getElementById('buscador');
    const resultados = document.getElementById('resultados');
    const carritoBody = document.getElementById('carrito-body');
    const itemsContainer = document.getElementById('items-container');
    const subtotalEl = document.getElementById('subtotal-total');
    const totalEl = document.getElementById('gran-total');
    const btnConfirmar = document.getElementById('btn-confirmar');
    const formVenta = document.getElementById('form-venta');
    const selectMetodoPago = document.getElementById('metodo-pago');
    const grupoEfectivo = document.getElementById('grupo-efectivo');
    const inputMontoRecibido = document.getElementById('monto-recibido');
    const vueltoInfo = document.getElementById('vuelto-info');

    // Carrito en memoria: { productoId: { nombre, precio, cantidad, existencia } }
    let carrito = {};
    let timeoutBusqueda = null;
    let totalActual = 0;

    function esEfectivoSeleccionado() {
        const opcion = selectMetodoPago.options[selectMetodoPago.selectedIndex];
        return !!opcion && opcion.dataset.nombre === 'Efectivo';
    }

    function actualizarVuelto() {
        if (!esEfectivoSeleccionado()) {
            grupoEfectivo.style.display = 'none';
            vueltoInfo.textContent = '';
            return;
        }

        grupoEfectivo.style.display = '';

        const recibido = parseFloat(inputMontoRecibido.value);

        if (isNaN(recibido)) {
            vueltoInfo.textContent = '';
            return;
        }

        const vuelto = recibido - totalActual;

        if (vuelto < 0) {
            vueltoInfo.innerHTML = '<span class="text-danger">{{ __('Falta') }}: $' + Math.abs(vuelto).toFixed(2) + '</span>';
        } else {
            vueltoInfo.innerHTML = '<span class="text-success">{{ __('Vuelto') }}: $' + vuelto.toFixed(2) + '</span>';
        }
    }

    selectMetodoPago.addEventListener('change', actualizarVuelto);
    inputMontoRecibido.addEventListener('input', actualizarVuelto);

    buscador.addEventListener('input', function () {
        clearTimeout(timeoutBusqueda);
        const texto = this.value.trim();

        timeoutBusqueda = setTimeout(function () {
            fetch(urlBuscar + '&q=' + encodeURIComponent(texto))
                .then(r => r.json())
                .then(mostrarResultados)
                .catch(() => {
                    resultados.innerHTML = '<tr><td colspan="4" class="text-center text-danger py-3">{{ __('Error al buscar productos') }}</td></tr>';
                });
        }, 300);
    });

    function mostrarResultados(productos) {
        if (!productos.length) {
            resultados.innerHTML = '<tr><td colspan="4" class="text-center text-muted py-3">{{ __('Sin resultados') }}</td></tr>';
            return;
        }

        resultados.innerHTML = '';

        productos.forEach(function (p) {
            const sinStock = p.existencia <= 0;
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${escaparHtml(p.nombre)}</td>
                <td>$${p.precio.toFixed(2)}</td>
                <td>${p.existencia}</td>
                <td>
                    <button type="button" class="btn btn-sm btn-primary" ${sinStock ? 'disabled' : ''}>
                        <i class="fas fa-plus"></i>
                    </button>
                </td>
            `;
            tr.querySelector('button').addEventListener('click', function () {
                agregarAlCarrito(p);
            });
            resultados.appendChild(tr);
        });
    }

    function agregarAlCarrito(producto) {
        const existente = carrito[producto.id];
        const cantidadActual = existente ? existente.cantidad : 0;

        if (cantidadActual + 1 > producto.existencia) {
            alert('{{ __('No hay suficiente existencia de este producto.') }}');
            return;
        }

        if (existente) {
            existente.cantidad += 1;
        } else {
            carrito[producto.id] = {
                nombre: producto.nombre,
                precio: producto.precio,
                cantidad: 1,
                existencia: producto.existencia,
            };
        }

        renderCarrito();
    }

    function cambiarCantidad(id, nuevaCantidad) {
        const item = carrito[id];
        if (!item) return;

        nuevaCantidad = parseInt(nuevaCantidad, 10);

        if (isNaN(nuevaCantidad) || nuevaCantidad < 1) {
            delete carrito[id];
        } else if (nuevaCantidad > item.existencia) {
            alert('{{ __('No hay suficiente existencia de este producto.') }}');
            item.cantidad = item.existencia;
        } else {
            item.cantidad = nuevaCantidad;
        }

        renderCarrito();
    }

    function quitarDelCarrito(id) {
        delete carrito[id];
        renderCarrito();
    }

    function renderCarrito() {
        const ids = Object.keys(carrito);
        carritoBody.innerHTML = '';
        itemsContainer.innerHTML = '';

        if (!ids.length) {
            carritoBody.innerHTML = '<tr><td colspan="4" class="text-center text-muted py-3">{{ __('El carrito está vacío') }}</td></tr>';
            subtotalEl.textContent = '$0.00';
            totalEl.innerHTML = '<strong>$0.00</strong>';
            btnConfirmar.disabled = true;
            totalActual = 0;
            actualizarVuelto();
            return;
        }

        let subtotal = 0;

        ids.forEach(function (id) {
            const item = carrito[id];
            const importe = item.precio * item.cantidad;
            subtotal += importe;

            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${escaparHtml(item.nombre)}</td>
                <td>
                    <input type="number" min="1" max="${item.existencia}" value="${item.cantidad}"
                           class="form-control form-control-sm cantidad-input" style="width: 70px;">
                </td>
                <td>$${importe.toFixed(2)}</td>
                <td>
                    <button type="button" class="btn btn-sm btn-danger btn-quitar">
                        <i class="fas fa-times"></i>
                    </button>
                </td>
            `;

            tr.querySelector('.cantidad-input').addEventListener('change', function () {
                cambiarCantidad(id, this.value);
            });
            tr.querySelector('.btn-quitar').addEventListener('click', function () {
                quitarDelCarrito(id);
            });

            carritoBody.appendChild(tr);

            // Inputs ocultos que sí viajan al servidor al confirmar la venta
            itemsContainer.insertAdjacentHTML('beforeend', `
                <input type="hidden" name="items[${id}][producto_id]" value="${id}">
                <input type="hidden" name="items[${id}][cantidad]" value="${item.cantidad}">
            `);
        });

        subtotalEl.textContent = '$' + subtotal.toFixed(2);
        totalEl.innerHTML = '<strong>$' + subtotal.toFixed(2) + '</strong>';
        btnConfirmar.disabled = false;

        totalActual = subtotal;
        actualizarVuelto();
    }

    function escaparHtml(texto) {
        const div = document.createElement('div');
        div.textContent = texto;
        return div.innerHTML;
    }

    formVenta.addEventListener('submit', function (e) {
        if (!Object.keys(carrito).length) {
            e.preventDefault();
            alert('{{ __('Agrega al menos un producto al carrito.') }}');
            return;
        }

        if (esEfectivoSeleccionado()) {
            const recibido = parseFloat(inputMontoRecibido.value);

            if (isNaN(recibido) || recibido < totalActual) {
                e.preventDefault();
                alert('{{ __('Ingresa un monto en efectivo suficiente para cubrir el total de la venta.') }}');
            }
        }
    });
})();
</script>
@stop
