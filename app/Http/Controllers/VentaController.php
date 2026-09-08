<?php

namespace App\Http\Controllers;

use App\Models\Inventario;
use App\Models\MetodoPago;
use App\Models\Producto;
use App\Models\Sucursal;
use App\Models\Venta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VentaController extends Controller
{
    /**
     * Historial de ventas (lo que el protocolo llama "Consultar el historial
     * de ventas" / "Supervisar las ventas realizadas en su sucursal").
     */
    public function index(Request $request)
    {
        $query = Venta::with(['sucursal', 'cajero', 'metodoPago']);

        $ids = auth()->user()->sucursalIdsPermitidas();
        if ($ids !== null) {
            $query->whereIn('sucursal_id', $ids);
        }

        if ($request->filled('sucursal_id')) {
            $query->where('sucursal_id', $request->sucursal_id);
        }

        if ($request->filled('desde')) {
            $query->whereDate('created_at', '>=', $request->desde);
        }

        if ($request->filled('hasta')) {
            $query->whereDate('created_at', '<=', $request->hasta);
        }

        $ventas = $query->latest()->paginate(15)->withQueryString();

        $sucursalesQuery = Sucursal::orderBy('nombre');
        if ($ids !== null) {
            $sucursalesQuery->whereIn('id', $ids);
        }
        $sucursales = $sucursalesQuery->get();

        return view('ventas.index', compact('ventas', 'sucursales'));
    }

    /**
     * Pantalla del Punto de Venta (POS).
     */
    public function create(Request $request)
    {
        $sucursal = $this->resolverSucursalActiva($request);

        if (! $sucursal) {
            return redirect()
                ->route('ventas.index')
                ->with('error', 'No tienes una sucursal asignada. Pide al Administrador General que te asigne una.');
        }

        $metodosPago = MetodoPago::orderBy('nombre')->get();
        $sucursales = $this->sucursalesVisibles(); // solo se usa si es Admin, para el selector

        return view('ventas.pos', compact('sucursal', 'metodosPago', 'sucursales'));
    }

    /**
     * Endpoint JSON usado por el buscador del POS (fetch/JS).
     */
    public function buscarProductos(Request $request)
    {
        $sucursal = $this->resolverSucursalActiva($request);

        if (! $sucursal) {
            return response()->json([]);
        }

        $texto = trim((string) $request->get('q', ''));

        $productos = Producto::with('inventario')
            ->where('sucursal_id', $sucursal->id)
            ->where('activo', true)
            ->when($texto !== '', function ($q) use ($texto) {
                $q->where(function ($sub) use ($texto) {
                    $sub->where('nombre', 'like', "%{$texto}%")
                        ->orWhere('codigo', 'like', "%{$texto}%");
                });
            })
            ->orderBy('nombre')
            ->limit(30)
            ->get()
            ->map(fn ($p) => [
                'id' => $p->id,
                'nombre' => $p->nombre,
                'precio' => (float) $p->precio,
                'existencia' => $p->inventario?->cantidad ?? 0,
            ]);

        return response()->json($productos);
    }

    /**
     * Confirma la venta: valida stock, crea el ticket (venta + detalle) y
     * descuenta el inventario automáticamente. Todo en una transacción para
     * que, si algo falla, no se quede el inventario a medio actualizar.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'sucursal_id' => ['required', 'exists:sucursales,id'],
            'metodo_pago_id' => ['required', 'exists:metodos_pago,id'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.producto_id' => ['required', 'exists:productos,id'],
            'items.*.cantidad' => ['required', 'integer', 'min:1'],
            'monto_recibido' => ['nullable', 'numeric', 'min:0'],
        ]);

        $ids = auth()->user()->sucursalIdsPermitidas();
        if ($ids !== null && ! in_array((int) $data['sucursal_id'], $ids, true)) {
            abort(403, 'No tienes permiso sobre esa sucursal.');
        }

        // El pago en efectivo requiere el monto que entregó el cliente para
        // poder calcular el vuelto.
        $esEfectivo = MetodoPago::whereKey($data['metodo_pago_id'])->value('nombre') === 'Efectivo';

        if ($esEfectivo && ! $request->filled('monto_recibido')) {
            return back()->withInput()->with('error', 'Indica el monto en efectivo que entregó el cliente.');
        }

        try {
            $venta = DB::transaction(function () use ($data, $esEfectivo) {
                $subtotal = 0;
                $lineas = [];

                // Se bloquean las filas de inventario (lockForUpdate) para
                // evitar que dos ventas al mismo tiempo vendan el mismo
                // producto por encima del stock disponible.
                foreach ($data['items'] as $item) {
                    $producto = Producto::where('id', $item['producto_id'])
                        ->where('sucursal_id', $data['sucursal_id'])
                        ->firstOrFail();

                    $inventario = Inventario::where('producto_id', $producto->id)
                        ->lockForUpdate()
                        ->firstOrFail();

                    if ($inventario->cantidad < $item['cantidad']) {
                        throw new \RuntimeException("No hay suficiente inventario de \"{$producto->nombre}\". Disponible: {$inventario->cantidad}.");
                    }

                    $precioUnitario = (float) $producto->precio;
                    $importe = round($precioUnitario * $item['cantidad'], 2);
                    $subtotal += $importe;

                    $lineas[] = [
                        'producto_id' => $producto->id,
                        'cantidad' => $item['cantidad'],
                        'precio_unitario' => $precioUnitario,
                        'subtotal' => $importe,
                        'inventario' => $inventario,
                    ];
                }

                $total = $subtotal; // aquí se podrían sumar impuestos/descuentos si el proyecto lo requiere

                $montoRecibido = null;
                $cambio = null;

                if ($esEfectivo) {
                    $montoRecibido = round((float) $data['monto_recibido'], 2);

                    if ($montoRecibido < $total) {
                        throw new \RuntimeException('El monto en efectivo entregado ($'.number_format($montoRecibido, 2).') es menor al total de la venta ($'.number_format($total, 2).').');
                    }

                    $cambio = round($montoRecibido - $total, 2);
                }

                $venta = Venta::create([
                    'sucursal_id' => $data['sucursal_id'],
                    'user_id' => auth()->id(),
                    'metodo_pago_id' => $data['metodo_pago_id'],
                    'folio' => 'TEMP', // se reemplaza abajo una vez que ya existe el id
                    'subtotal' => $subtotal,
                    'total' => $total,
                    'monto_recibido' => $montoRecibido,
                    'cambio' => $cambio,
                ]);

                $venta->update(['folio' => 'V-'.str_pad($venta->id, 6, '0', STR_PAD_LEFT)]);

                foreach ($lineas as $linea) {
                    $venta->detalles()->create([
                        'producto_id' => $linea['producto_id'],
                        'cantidad' => $linea['cantidad'],
                        'precio_unitario' => $linea['precio_unitario'],
                        'subtotal' => $linea['subtotal'],
                    ]);

                    // Actualiza el inventario automáticamente (ej. 20 - 3 = 17)
                    $linea['inventario']->decrement('cantidad', $linea['cantidad']);
                }

                return $venta;
            });
        } catch (\RuntimeException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        } catch (\Throwable $e) {
            report($e); // se registra en el log para poder revisarlo después

            return back()->withInput()->with('error', __('Ocurrió un error al registrar la venta. Intenta de nuevo.'));
        }

        return redirect()
            ->route('ventas.show', $venta)
            ->with('success', 'Venta registrada correctamente.');
    }

    /**
     * Muestra el ticket/comprobante de una venta ya confirmada.
     */
    public function show(Venta $venta)
    {
        $ids = auth()->user()->sucursalIdsPermitidas();
        if ($ids !== null && ! in_array($venta->sucursal_id, $ids, true)) {
            abort(403);
        }

        $venta->load(['detalles.producto', 'sucursal', 'cajero', 'metodoPago']);

        return view('ventas.ticket', compact('venta'));
    }

    /**
     * Genera y descarga el recibo digital de una venta ya confirmada
     * (archivo .html con formato de recibo, listo para guardar, enviar o
     * imprimir/guardar como PDF desde el navegador).
     */
    public function reciboDigital(Venta $venta)
    {
        $ids = auth()->user()->sucursalIdsPermitidas();
        if ($ids !== null && ! in_array($venta->sucursal_id, $ids, true)) {
            abort(403);
        }

        $venta->load(['detalles.producto', 'sucursal', 'cajero', 'metodoPago']);

        $html = view('ventas.recibo-digital', compact('venta'))->render();

        $nombreArchivo = 'recibo-'.$venta->folio.'.html';

        return response($html, 200, [
            'Content-Type' => 'text/html; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="'.$nombreArchivo.'"',
        ]);
    }

    /**
     * Determina la sucursal activa para el POS: fija (la del usuario) si es
     * Gerente/Cajero, o elegida por query string si es Admin.
     */
    private function resolverSucursalActiva(Request $request): ?Sucursal
    {
        $user = auth()->user();

        if (! $user->isAdmin()) {
            return $user->sucursal;
        }

        if ($request->filled('sucursal_id')) {
            return Sucursal::find($request->sucursal_id);
        }

        return Sucursal::where('activa', true)->orderBy('nombre')->first();
    }

    private function sucursalesVisibles()
    {
        return Sucursal::where('activa', true)->orderBy('nombre')->get();
    }
}
