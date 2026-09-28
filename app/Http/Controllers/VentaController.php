<?php

namespace App\Http\Controllers;

use App\Models\Inventario;
use App\Models\Caja;
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
        $query = Venta::with(['sucursal', 'cajero', 'caja', 'metodoPago']);

        $ids = auth()->user()->sucursalIdsPermitidas();
        if ($ids !== null) {
            $query->whereIn('sucursal_id', $ids);
        }

        if ($request->filled('sucursal_id')) {
            $query->where('sucursal_id', $request->sucursal_id);
        }

        // Búsqueda por folio, cajero o fecha (día)
        if ($request->filled('q') || $request->filled('adminlteSearch')) {
            $q = trim((string) ($request->get('q') ?: $request->get('adminlteSearch')));
            $query->where(function ($sub) use ($q) {
                $sub->where('folio', 'like', "%{$q}%")
                    ->orWhereHas('cajero', fn ($u) => $u->where('name', 'like', "%{$q}%"))
                    ->orWhereHas('sucursal', fn ($s) => $s->where('nombre', 'like', "%{$q}%"))
                    ->orWhereHas('metodoPago', fn ($m) => $m->where('nombre', 'like', "%{$q}%"));

                // Si parece una fecha (17/09/2026, 17-09-2026, 2026-09-17, 17/09, etc.)
                $norm = str_replace(['.', ' '], ['/', ''], $q);
                if (preg_match('/^(\d{1,2})[\/\-](\d{1,2})(?:[\/\-](\d{2,4}))?$/', $norm, $m)) {
                    $d = (int) $m[1];
                    $mo = (int) $m[2];
                    $y = isset($m[3]) ? (int) $m[3] : null;
                    if ($y !== null && $y < 100) {
                        $y += 2000;
                    }
                    $sub->orWhere(function ($f) use ($d, $mo, $y) {
                        $f->whereDay('created_at', $d)->whereMonth('created_at', $mo);
                        if ($y) {
                            $f->whereYear('created_at', $y);
                        }
                    });
                } elseif (preg_match('/^\d{4}-\d{2}-\d{2}$/', $q)) {
                    $sub->orWhereDate('created_at', $q);
                } elseif (preg_match('/^\d{1,2}$/', $q)) {
                    $sub->orWhereDay('created_at', (int) $q);
                }
            });
        }

        $ventas = $query->latest()->paginate(50)->withQueryString();

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
        $metodosPago = MetodoPago::whereIn('nombre', ['Efectivo', 'Tarjeta'])->orderBy('nombre')->get();

        // Gerente/Cajero sin sucursal: no pueden vender
        if (! $user->isAdmin() && ! $sucursal) {
            return redirect()
                ->route('ventas.index')
                ->with('error', __('messages.no_branch'));
        }

        // Cajas activas de la sucursal seleccionada
        $cajas = collect();
        if ($sucursal) {
            $cajas = Caja::where('sucursal_id', $sucursal->id)
                ->where('activa', true)
                ->orderBy('nombre')
                ->get();
        }

        // Admin puede entrar sin sucursal elegida (debe escogerla en pantalla)
        return view('ventas.pos', compact('sucursal', 'metodosPago', 'sucursales', 'cajas'));
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
                'imagen' => $p->imagen ? asset('portadas/'.$p->imagen) : null,
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
            'caja_id' => ['required', 'exists:cajas,id'],
            'metodo_pago_id' => ['required', 'exists:metodos_pago,id'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.producto_id' => ['required', 'exists:productos,id'],
            'items.*.cantidad' => ['required', 'integer', 'min:1', 'max:1000'],
            'monto_recibido' => ['nullable', 'numeric', 'min:0', 'max:999999.99'],
        ]);

        // Verificar que la caja pertenezca a la sucursal y esté activa
        $caja = Caja::where('id', $data['caja_id'])
            ->where('sucursal_id', $data['sucursal_id'])
            ->where('activa', true)
            ->first();
        if (! $caja) {
            return back()->withInput()->with('error', __('messages.invalid_cash_register'));
        }

        $ids = auth()->user()->sucursalIdsPermitidas();
        if ($ids !== null && ! in_array((int) $data['sucursal_id'], $ids, true)) {
            abort(403, __('messages.branch_permission'));
        }

        $esEfectivo = MetodoPago::whereKey($data['metodo_pago_id'])->value('nombre') === 'Efectivo';

        if ($esEfectivo && ! $request->filled('monto_recibido')) {
            return back()->withInput()->with('error', __('messages.cash_required'));
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
                        throw new \RuntimeException(__('messages.insufficient_inventory', [
                            'product' => $producto->nombre,
                            'available' => $inventario->cantidad,
                        ]));
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

                if ($total > 999999.99) {
                    throw new \RuntimeException(__('messages.sale_limit'));
                }

                $montoRecibido = null;
                $cambio = null;

                if ($esEfectivo) {
                    $montoRecibido = round((float) $data['monto_recibido'], 2);

                    if ($montoRecibido < $total) {
                        throw new \RuntimeException(__('messages.cash_below_total', [
                            'received' => number_format($montoRecibido, 2),
                            'total' => number_format($total, 2),
                        ]));
                    }

                    $cambio = round($montoRecibido - $total, 2);
                }

                // Folio correlativo según tickets existentes (si se borran, se reutiliza la secuencia)
                $maxFolio = (int) (Venta::query()
                    ->where('folio', 'like', 'V-%')
                    ->selectRaw('MAX(CAST(SUBSTRING(folio, 3) AS UNSIGNED)) as max_folio')
                    ->value('max_folio') ?? 0);
                $siguiente = $maxFolio + 1;
                $folio = 'V-'.str_pad((string) $siguiente, 6, '0', STR_PAD_LEFT);

                $venta = Venta::create([
                    'sucursal_id' => $data['sucursal_id'],
                    'user_id' => auth()->id(),
                    'caja_id' => $data['caja_id'],
                    'metodo_pago_id' => $data['metodo_pago_id'],
                    'folio' => $folio,
                    'subtotal' => $subtotal,
                    'iva' => $iva,
                    'tasa_iva' => $tasaIva,
                    'total' => $total,
                    'monto_recibido' => $montoRecibido,
                    'cambio' => $cambio,
                ]);

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

            return back()->withInput()->with('error', __('messages.sale_error'));
        }

        return redirect()
            ->route('ventas.show', $venta)
            ->with('success', __('messages.sale_created'));
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

        $venta->load(['detalles.producto', 'sucursal', 'cajero', 'caja', 'metodoPago']);

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

        $venta->load(['detalles.producto', 'sucursal', 'cajero', 'caja', 'metodoPago']);

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
            abort(403, __('messages.admin_delete_tickets'));
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
            ->with('success', __('messages.ticket_deleted'));
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
    #Johiel Puntos
    /**
     * Simula un cobro con tarjeta (API interna de prueba).
     * No mueve dinero real: valida formato y responde aprobado/rechazado.
     */
    public function simularPagoTarjeta(Request $request)
    {
        $data = $request->validate([
            'monto' => ['required', 'numeric', 'min:0.01', 'max:999999.99'],
            'numero' => ['required', 'string'],
            'titular' => ['required', 'string', 'max:80'],
            'vencimiento' => ['required', 'string', 'max:7'],
            'cvv' => ['required', 'string', 'min:3', 'max:4'],
        ], [
            'numero.required' => 'Ingresa el número de tarjeta.',
            'titular.required' => 'Ingresa el nombre del titular.',
            'vencimiento.required' => 'Ingresa el vencimiento (MM/AA).',
            'cvv.required' => 'Ingresa el CVV.',
        ]);

        // Simular latencia de red de una pasarela real
        usleep(900000); // ~0.9 s

        $numero = preg_replace('/\D+/', '', $data['numero']);

        if (strlen($numero) < 13 || strlen($numero) > 19) {
            return response()->json([
                'ok' => false,
                'mensaje' => 'Número de tarjeta inválido.',
            ], 422);
        }

        // Tarjetas de prueba conocidas (estilo Stripe de demo)
        // 4242... = aprobada | 4000 0000 0000 0002 = rechazada
        if (str_starts_with($numero, '4000000000000002') || $numero === '4000000000000002') {
            return response()->json([
                'ok' => false,
                'mensaje' => 'Pago rechazado por el banco emisor (fondos insuficientes o tarjeta bloqueada).',
                'codigo' => 'card_declined',
            ], 402);
        }

        // CVV trivialmente inválido
        if (! preg_match('/^\d{3,4}$/', $data['cvv'])) {
            return response()->json([
                'ok' => false,
                'mensaje' => 'CVV inválido.',
            ], 422);
        }

        // Vencimiento MM/AA o MM/AAAA
        $venc = preg_replace('/\s+/', '', $data['vencimiento']);
        if (! preg_match('/^(0[1-9]|1[0-2])\/(\d{2}|\d{4})$/', $venc)) {
            return response()->json([
                'ok' => false,
                'mensaje' => 'Vencimiento inválido. Usa el formato MM/AA.',
            ], 422);
        }

        $auth = 'AUTH-'.strtoupper(substr(md5($numero.microtime(true)), 0, 10));

        return response()->json([
            'ok' => true,
            'mensaje' => 'Pago aprobado',
            'autorizacion' => $auth,
            'ultimos4' => substr($numero, -4),
            'monto' => round((float) $data['monto'], 2),
        ]);
    }

}
