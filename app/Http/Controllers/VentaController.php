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
     * Historial de ventas.
     */
    public function index(Request $request)
    {
        $datos = $request->validate([
            'sucursal_id' => ['nullable', 'integer', 'exists:sucursales,id'],
            'desde' => ['nullable', 'date'],
            'hasta' => ['nullable', 'date', 'after_or_equal:desde'],
        ], [
            'desde.date' => 'La fecha "Desde" no es válida.',
            'hasta.date' => 'La fecha "Hasta" no es válida.',
            'hasta.after_or_equal' => 'La fecha "Hasta" debe ser igual o posterior a "Desde".',
            'sucursal_id.exists' => 'La sucursal seleccionada no es válida.',
        ]);

        $query = Venta::with(['sucursal', 'cajero', 'metodoPago']);

        $ids = auth()->user()->sucursalIdsPermitidas();
        if ($ids !== null) {
            $query->whereIn('sucursal_id', $ids);
        }

        if (! empty($datos['sucursal_id'])) {
            $query->where('sucursal_id', $datos['sucursal_id']);
        }

        if (! empty($datos['desde'])) {
            $query->where('created_at', '>=', $datos['desde'].' 00:00:00');
        }

        if (! empty($datos['hasta'])) {
            $query->where('created_at', '<=', $datos['hasta'].' 23:59:59');
        }

        if ($request->filled('q') || $request->filled('adminlteSearch')) {
            $q = trim((string) ($request->get('q') ?: $request->get('adminlteSearch')));
            $query->where(function ($sub) use ($q) {
                $sub->where('folio', 'like', "%{$q}%")
                    ->orWhereHas('cajero', fn ($u) => $u->where('name', 'like', "%{$q}%"));
            });
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
        $user = auth()->user();
        $sucursal = $this->resolverSucursalActiva($request);
        $sucursales = $this->sucursalesVisibles();
        $metodosPago = MetodoPago::orderBy('nombre')->get();

        // Gerente/Cajero sin sucursal: no pueden vender
        if (! $user->isAdmin() && ! $sucursal) {
            return redirect()
                ->route('ventas.index')
                ->with('error', 'No tienes una sucursal asignada. Pide al Administrador General que te asigne una.');
        }

        // Admin puede entrar sin sucursal elegida (debe escogerla en pantalla)
        return view('ventas.pos', compact('sucursal', 'metodosPago', 'sucursales'));
    }

    /**
     * Endpoint JSON usado por el buscador del POS.
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
            ->limit(100)
            ->get()
            ->map(fn ($p) => [
                'id' => $p->id,
                'nombre' => $p->nombre,
                'precio' => (float) $p->precio,
                'existencia' => $p->inventario?->cantidad ?? 0,
                'imagen' => $p->imagen ? asset('storage/'.$p->imagen) : null,
            ]);

        return response()->json($productos);
    }

    /**
     * Confirma la venta: valida stock, crea el ticket y descuenta inventario.
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

        $esEfectivo = MetodoPago::whereKey($data['metodo_pago_id'])->value('nombre') === 'Efectivo';

        if ($esEfectivo && ! $request->filled('monto_recibido')) {
            return back()->withInput()->with('error', 'Indica el monto en efectivo que entregó el cliente.');
        }

        try {
            $venta = DB::transaction(function () use ($data, $esEfectivo) {
                $subtotal = 0;
                $lineas = [];

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

                // Precios sin IVA; se aplica tasa del 16% (México)
                $tasaIva = 16.0;
                $iva = round($subtotal * ($tasaIva / 100), 2);
                $total = round($subtotal + $iva, 2);

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
                    'folio' => 'TEMP',
                    'subtotal' => $subtotal,
                    'iva' => $iva,
                    'tasa_iva' => $tasaIva,
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

                    $linea['inventario']->decrement('cantidad', $linea['cantidad']);
                }

                return $venta;
            });
        } catch (\RuntimeException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        } catch (\Throwable $e) {
            report($e);

            return back()->withInput()->with('error', __('Ocurrió un error al registrar la venta. Intenta de nuevo.'));
        }

        return redirect()
            ->route('ventas.show', $venta)
            ->with('success', 'Venta registrada correctamente.');
    }

    /**
     * Muestra el ticket de una venta.
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
     * Descarga el recibo (PDF si Dompdf está instalado, si no HTML).
     */
    public function reciboDigital(Venta $venta)
    {
        $ids = auth()->user()->sucursalIdsPermitidas();
        if ($ids !== null && ! in_array($venta->sucursal_id, $ids, true)) {
            abort(403);
        }

        $venta->load(['detalles.producto', 'sucursal', 'cajero', 'metodoPago']);

        $html = view('ventas.recibo-digital', compact('venta'))->render();
        $nombreArchivo = 'recibo-'.$venta->folio.'.pdf';

        if (class_exists(\Dompdf\Dompdf::class)) {
            $options = new \Dompdf\Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isRemoteEnabled', false);
            $options->set('defaultFont', 'DejaVu Sans');

            $dompdf = new \Dompdf\Dompdf($options);
            $dompdf->loadHtml($html, 'UTF-8');
            $dompdf->setPaper('A5', 'portrait');
            $dompdf->render();

            return response($dompdf->output(), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="'.$nombreArchivo.'"',
            ]);
        }

        return response($html, 200, [
            'Content-Type' => 'text/html; charset=UTF-8',
            'Content-Disposition' => 'inline; filename="recibo-'.$venta->folio.'.html"',
        ]);
    }

    /**
     * Elimina un ticket del historial (solo Administrador).
     * Devuelve el stock al inventario.
     */
    public function destroy(Venta $venta)
    {
        if (! auth()->user()->isAdmin()) {
            abort(403, __('Solo un administrador puede eliminar tickets del historial.'));
        }

        $venta->load('detalles');

        DB::transaction(function () use ($venta) {
            foreach ($venta->detalles as $detalle) {
                $inventario = Inventario::where('producto_id', $detalle->producto_id)
                    ->lockForUpdate()
                    ->first();

                if ($inventario) {
                    $inventario->increment('cantidad', $detalle->cantidad);
                }
            }

            $venta->detalles()->delete();
            $venta->delete();
        });

        return redirect()
            ->route('ventas.index')
            ->with('success', __('Ticket eliminado. El inventario fue actualizado.'));
    }

    private function resolverSucursalActiva(Request $request): ?Sucursal
    {
        $user = auth()->user();

        if (! $user->isAdmin()) {
            return $user->sucursal;
        }

        // Admin: solo si eligió sucursal explícitamente (sin default)
        if ($request->filled('sucursal_id')) {
            return Sucursal::where('activa', true)->find($request->sucursal_id);
        }

        return null;
    }

    private function sucursalesVisibles()
    {
        return Sucursal::where('activa', true)->orderBy('nombre')->get();
    }
}
