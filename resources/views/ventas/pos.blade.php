@extends('adminlte::page')

@section('title', __('Punto de venta'))

@section('content_header')
    <h1>{{ __('Punto de venta') }}@if($sucursal) — {{ $sucursal->nombre }}@endif</h1>
@stop

@section('content')
    @once
        @include('partials.app-toasts')
    @endonce

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if (auth()->user()->isAdmin())
        <form method="GET" action="{{ route('ventas.create') }}" class="form-inline mb-3">
            <label class="mr-2">{{ __('Vendiendo en la sucursal') }}:</label>
            <select name="sucursal_id" class="form-control" onchange="this.form.submit()">
                <option value="">{{ __('— Elige una sucursal —') }}</option>
                @foreach ($sucursales as $s)
                    <option value="{{ $s->id }}" {{ $sucursal && $s->id === $sucursal->id ? 'selected' : '' }}>{{ $s->nombre }}</option>
                @endforeach
            </select>
        </form>
        @unless ($sucursal)
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i>
                {{ __('Selecciona una sucursal para ver sus productos y registrar la venta.') }}
            </div>
        @endunless
    @elseif ($sucursal)
        <p class="text-muted mb-3">{{ __('Vendiendo en') }}: <strong>{{ $sucursal->nombre }}</strong></p>
    @endif

    <div class="row">
        {{-- Buscador y resultados --}}
        <div class="col-md-7">
            <div class="card">
                <div class="card-header">
                    <input type="text" id="buscador" class="form-control" autofocus
                           placeholder="{{ __('Buscar producto por nombre o código...') }}">
                </div>
                <div class="card-body" style="max-height: 520px; overflow-y: auto;">
                    <div id="resultados" class="row pos-prod-grid">
                        <div class="col-12 text-center text-muted py-4">{{ $sucursal ? __('Cargando productos...') : __('Selecciona una sucursal para ver productos') }}</div>
                    </div>
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
                                <th style="width: 130px;">{{ __('Cant.') }}</th>
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
                        <span>{{ __('IVA') }} (16%):</span>
                        <span id="iva-total">$0.00</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <strong>{{ __('Total') }}:</strong>
                        <span id="gran-total"><strong>$0.00</strong></span>
                    </div>

                    <form id="form-venta" action="{{ route('ventas.store') }}" method="POST" class="mt-3">
                        @csrf
                        @if($sucursal)<input type="hidden" name="sucursal_id" value="{{ $sucursal->id }}">@endif
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
                                   class="form-control" placeholder="0.00" min="0" max="999999.99" step="0.01">
                            <small class="form-text" id="vuelto-info"></small>
                        </div>

                        <div id="grupo-tarjeta" class="alert alert-info py-2" style="display: none; font-size: 0.9rem;">
                            <i class="fas fa-credit-card"></i>
                            {{ __('Al confirmar se abrirá el cobro simulado con tarjeta.') }}
                        </div>

                        <input type="hidden" name="tarjeta_autorizacion" id="tarjeta-autorizacion" value="">

                        <button type="submit" id="btn-confirmar" class="btn btn-success btn-block" disabled>
                            <i class="fas fa-check"></i> {{ __('Confirmar venta') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal pago con tarjeta (simulado) — estilo alineado al resto de la app --}}
    <div class="modal fade" id="modalTarjeta" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 420px;">
            <div class="modal-content card-pago-tarjeta">
                <div class="modal-body p-0">
                    <div class="pago-tarjeta-head">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="pago-tarjeta-kicker">{{ __('Cobro simulado') }}</div>
                                <h5 class="mb-0 font-weight-bold">{{ __('Pago con tarjeta') }}</h5>
                            </div>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="pago-tarjeta-monto mt-3">
                            <span>{{ __('Total a cobrar') }}</span>
                            <strong id="tarjeta-monto-label">$0.00</strong>
                        </div>
                    </div>

                    <div class="pago-tarjeta-body">
                        <div class="form-group">
                            <label class="pago-label">{{ __('Número de tarjeta') }}</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-credit-card"></i></span>
                                </div>
                                <input type="text" id="tarjeta-numero" class="form-control" placeholder="4242 4242 4242 4242" maxlength="19" autocomplete="off">
                            </div>
                            <small class="form-text text-muted">{{ __('Prueba: 4242… aprueba · 4000…0002 rechaza') }}</small>
                        </div>

                        <div class="form-group">
                            <label class="pago-label">{{ __('Nombre del titular') }}</label>
                            <input type="text" id="tarjeta-titular" class="form-control" placeholder="{{ __('Como aparece en la tarjeta') }}">
                        </div>

                        <div class="form-row">
                            <div class="form-group col-7">
                                <label class="pago-label">{{ __('Vencimiento') }}</label>
                                <input type="text" id="tarjeta-venc" class="form-control" placeholder="MM/AA" maxlength="5">
                            </div>
                            <div class="form-group col-5">
                                <label class="pago-label">CVV</label>
                                <input type="password" id="tarjeta-cvv" class="form-control" placeholder="•••" maxlength="4">
                            </div>
                        </div>

                        <div id="tarjeta-msg" class="pago-msg" style="display:none;"></div>

                        <div class="d-flex mt-3" style="gap: 0.5rem;">
                            <button type="button" class="btn btn-outline-secondary flex-fill" data-dismiss="modal">{{ __('Cancelar') }}</button>
                            <button type="button" class="btn btn-primary flex-fill" id="btn-procesar-tarjeta">
                                <i class="fas fa-lock mr-1"></i> {{ __('Procesar pago') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop


@section('css')
<style>


/* Modal pago tarjeta — mismo lenguaje visual que formularios del sistema */
.card-pago-tarjeta {
    border: none;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 12px 40px rgba(15, 23, 42, 0.18);
}
.pago-tarjeta-head {
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    padding: 1.15rem 1.35rem 1rem;
}
.pago-tarjeta-kicker {
    font-size: 0.72rem;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    color: #64748b;
    font-weight: 600;
    margin-bottom: 0.15rem;
}
.pago-tarjeta-monto {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 0.65rem 0.9rem;
    font-size: 0.9rem;
    color: #475569;
}
.pago-tarjeta-monto strong {
    font-size: 1.25rem;
    color: #0f172a;
}
.pago-tarjeta-body {
    padding: 1.25rem 1.35rem 1.35rem;
    background: #fff;
}
.pago-label {
    font-size: 0.8rem;
    font-weight: 600;
    color: #475569;
    margin-bottom: 0.3rem;
}
.pago-tarjeta-body .form-control,
.pago-tarjeta-body .input-group-text {
    border-radius: 8px;
    border-color: #e2e8f0;
}
.pago-tarjeta-body .input-group-text {
    background: #f8fafc;
    color: #64748b;
}
.pago-tarjeta-body .input-group .form-control {
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
}
.pago-tarjeta-body .input-group-prepend .input-group-text {
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
}
.pago-msg {
    margin-top: 0.5rem;
    padding: 0.55rem 0.75rem;
    border-radius: 8px;
    font-size: 0.85rem;
}
.pago-msg.text-success {
    background: #ecfdf5;
    color: #047857 !important;
}
.pago-msg.text-danger {
    background: #fef2f2;
    color: #b91c1c !important;
}
.pago-msg.text-muted {
    background: #f1f5f9;
}

.pos-prod-card {
    background: #fff;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(15,23,42,.08);
    height: 100%;
    display: flex;
    flex-direction: column;
}
.pos-prod-card.is-sin-stock { opacity: .55; }
.pos-prod-img {
    aspect-ratio: 2/3;
    background: linear-gradient(145deg,#1e293b,#475569);
    overflow: hidden;
}
.pos-prod-img img { width: 100%; height: 100%; object-fit: cover; object-position: center top; display: block; }
.pos-prod-ph {
    width: 100%; height: 100%;
    display: flex; align-items: center; justify-content: center;
    color: rgba(255,255,255,.4); font-size: 2rem;
}
.pos-prod-info { padding: 8px 10px 10px; flex: 1; display: flex; flex-direction: column; }
.pos-prod-name {
    font-weight: 700; font-size: .82rem; line-height: 1.25;
    display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
    min-height: 2.1em; margin-bottom: 4px;
}
.pos-prod-price { font-weight: 800; font-size: 1.05rem; }
.pos-prod-stock { font-size: .72rem; color: #64748b; margin-bottom: 4px; }
.pos-btn-add {
    border-radius: 8px; font-weight: 600; margin-top: auto;
}
</style>
@stop

@section('js')
<script>
(function () {
    const urlBuscar = @json($sucursal ? route('ventas.buscar', ['sucursal_id' => $sucursal->id]) : null);
    const buscador = document.getElementById('buscador');
    const resultados = document.getElementById('resultados');
    const carritoBody = document.getElementById('carrito-body');
    const itemsContainer = document.getElementById('items-container');
    const subtotalEl = document.getElementById('subtotal-total');
    const ivaEl = document.getElementById('iva-total');
    const totalEl = document.getElementById('gran-total');
    const TASA_IVA = 0.16;
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
        if (!urlBuscar) return;
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
            resultados.innerHTML = '<div class="col-12 text-center text-muted py-4">{{ __('Sin resultados') }}</div>';
            return;
        }

        resultados.innerHTML = '';

        productos.forEach(function (p) {
            const sinStock = p.existencia <= 0;
            const col = document.createElement('div');
            col.className = 'col-6 col-md-4 mb-3';
            const imgHtml = p.imagen
                ? '<img src="' + p.imagen + '" alt="">'
                : '<div class="pos-prod-ph"><i class="fas fa-book"></i></div>';
            col.innerHTML = `
                <div class="pos-prod-card ${sinStock ? 'is-sin-stock' : ''}">
                    <div class="pos-prod-img">${imgHtml}</div>
                    <div class="pos-prod-info">
                        <div class="pos-prod-name">${escaparHtml(p.nombre)}</div>
                        <div class="pos-prod-price">$${p.precio.toFixed(2)}</div>
                        <div class="pos-prod-stock">{{ __('Stock') }}: ${p.existencia}</div>
                        <button type="button" class="btn btn-sm btn-primary btn-block mt-1" ${sinStock ? 'disabled' : ''}>
                            <i class="fas fa-plus"></i> {{ __('Agregar') }}
                        </button>
                    </div>
                </div>
            `;
            col.querySelector('button').addEventListener('click', function () {
                agregarAlCarrito(p);
            });
            resultados.appendChild(col);
        });
    }

    function agregarAlCarrito(producto) {
        const existente = carrito[producto.id];
        const cantidadActual = existente ? existente.cantidad : 0;

        if (cantidadActual + 1 > producto.existencia) {
            appToast('{{ __('No hay suficiente existencia de este producto.') }}', 'warning');
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
            appToast('{{ __('No hay suficiente existencia de este producto.') }}', 'warning');
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
            if (ivaEl) ivaEl.textContent = '$0.00';
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
                    <div class="d-flex align-items-center" style="gap: 4px;">
                        <button type="button" class="btn btn-sm btn-outline-secondary btn-menos" title="{{ __('Quitar uno') }}">
                            <i class="fas fa-minus"></i>
                        </button>
                        <input type="number" min="1" max="${Math.min(item.existencia, 1000)}" value="${item.cantidad}"
                               class="form-control form-control-sm cantidad-input" style="width: 58px; text-align: center;">
                        <button type="button" class="btn btn-sm btn-outline-secondary btn-mas" title="{{ __('Agregar uno') }}">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </td>
                <td>$${importe.toFixed(2)}</td>
                <td>
                    <button type="button" class="btn btn-sm btn-danger btn-quitar" title="{{ __('Quitar del carrito') }}">
                        <i class="fas fa-times"></i>
                    </button>
                </td>
            `;

            tr.querySelector('.cantidad-input').addEventListener('change', function () {
                cambiarCantidad(id, this.value);
            });
            tr.querySelector('.btn-menos').addEventListener('click', function () {
                cambiarCantidad(id, item.cantidad - 1);
            });
            tr.querySelector('.btn-mas').addEventListener('click', function () {
                cambiarCantidad(id, item.cantidad + 1);
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

        const iva = Math.round(subtotal * TASA_IVA * 100) / 100;
        const total = Math.round((subtotal + iva) * 100) / 100;

        subtotalEl.textContent = '$' + subtotal.toFixed(2);
        if (ivaEl) ivaEl.textContent = '$' + iva.toFixed(2);
        totalEl.innerHTML = '<strong>$' + total.toFixed(2) + '</strong>';
        btnConfirmar.disabled = false;

        totalActual = total;
        actualizarVuelto();
    }

    function escaparHtml(texto) {
        const div = document.createElement('div');
        div.textContent = texto;
        return div.innerHTML;
    }

    
    // Cargar listado de la sucursal al abrir (sin escribir en el buscador)
    function cargarProductosInicial() {
        if (!urlBuscar) return;
        fetch(urlBuscar + '&q=')
            .then(r => r.json())
            .then(mostrarResultados)
            .catch(() => {
                resultados.innerHTML = '<tr><td colspan="4" class="text-center text-danger py-3">{{ __('Error al cargar productos') }}</td></tr>';
            });
    }
    cargarProductosInicial();


    const grupoTarjeta = document.getElementById('grupo-tarjeta');
    const inputAuth = document.getElementById('tarjeta-autorizacion');
    const urlSimularTarjeta = @json(route('ventas.simular-pago-tarjeta'));
    let pagoTarjetaAprobado = false;

    function esTarjetaSeleccionada() {
        const op = selectMetodoPago.options[selectMetodoPago.selectedIndex];
        return op && op.getAttribute('data-nombre') === 'Tarjeta';
    }

    const _actualizarVueltoOrig = typeof actualizarVuelto === 'function' ? actualizarVuelto : null;

    function actualizarPagoUI() {
        if (typeof actualizarVuelto === 'function') actualizarVuelto();
        if (grupoTarjeta) {
            grupoTarjeta.style.display = esTarjetaSeleccionada() ? '' : 'none';
        }
        if (!esTarjetaSeleccionada() && inputAuth) {
            inputAuth.value = '';
            pagoTarjetaAprobado = false;
        }
    }
    selectMetodoPago.addEventListener('change', actualizarPagoUI);
    actualizarPagoUI();

    formVenta.addEventListener('submit', function (e) {
        const TOTAL_MAX = 999999.99;
        if (totalActual > TOTAL_MAX) {
            e.preventDefault();
            appToast('{{ __('El total de la venta supera el máximo permitido ($999,999.99).') }}', 'error');
            return;
        }

        if (!Object.keys(carrito).length) {
            e.preventDefault();
            appToast('{{ __('Agrega al menos un producto al carrito.') }}', 'warning');
            return;
        }

        if (esEfectivoSeleccionado()) {
            const recibido = parseFloat(inputMontoRecibido.value);
            if (isNaN(recibido) || recibido < totalActual) {
                e.preventDefault();
                appToast('{{ __('Ingresa un monto en efectivo suficiente para cubrir el total de la venta.') }}', 'warning');
                return;
            }
        }

        if (esTarjetaSeleccionada() && !pagoTarjetaAprobado) {
            e.preventDefault();
            document.getElementById('tarjeta-monto-label').textContent = '$' + totalActual.toFixed(2);
            document.getElementById('tarjeta-msg').style.display = 'none';
            $('#modalTarjeta').modal('show');
            return;
        }
    });

    document.getElementById('btn-procesar-tarjeta').addEventListener('click', function () {
        const btn = this;
        const msg = document.getElementById('tarjeta-msg');
        const numero = document.getElementById('tarjeta-numero').value;
        const titular = document.getElementById('tarjeta-titular').value;
        const vencimiento = document.getElementById('tarjeta-venc').value;
        const cvv = document.getElementById('tarjeta-cvv').value;

        msg.style.display = 'block';
        msg.className = 'pago-msg text-muted';
        msg.textContent = '{{ __('Procesando con la pasarela…') }}';
        btn.disabled = true;

        fetch(urlSimularTarjeta, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            },
            body: JSON.stringify({
                monto: totalActual,
                numero: numero,
                titular: titular,
                vencimiento: vencimiento,
                cvv: cvv
            })
        })
        .then(async function (r) {
            const data = await r.json().catch(function () { return {}; });
            if (!r.ok || !data.ok) {
                throw new Error(data.mensaje || '{{ __('Pago rechazado') }}');
            }
            return data;
        })
        .then(function (data) {
            msg.className = 'pago-msg text-success';
            msg.textContent = '{{ __('Aprobado') }}: ' + (data.autorizacion || '') + ' (****' + (data.ultimos4 || '') + ')';
            if (inputAuth) inputAuth.value = data.autorizacion || 'OK';
            pagoTarjetaAprobado = true;
            setTimeout(function () {
                $('#modalTarjeta').modal('hide');
                btn.disabled = false;
                formVenta.submit();
            }, 700);
        })
        .catch(function (err) {
            msg.className = 'pago-msg text-danger';
            msg.textContent = err.message || '{{ __('Error al procesar el pago') }}';
            btn.disabled = false;
            pagoTarjetaAprobado = false;
        });
    });
})();
</script>
@stop
