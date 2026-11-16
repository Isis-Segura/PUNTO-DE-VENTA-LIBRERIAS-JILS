<?php

namespace App\Console\Commands;

use App\Models\Producto;
use App\Models\Sucursal;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AssignProductSkus extends Command
{
    protected $signature = 'productos:reasignar-sku {--aplicar : Guarda los SKU nuevos en la base de datos}';

    protected $description = 'Asigna SKU numéricos de 4 dígitos usando sucursal y consecutivo';

    public function handle(): int
    {
        if (! $this->option('aplicar')) {
            $plan = $this->crearPlan();
            $this->mostrarPlan($plan);

            if ($plan['errores']) {
                return self::FAILURE;
            }

            $this->warn('Vista previa únicamente. Para guardar los cambios, agrega --aplicar.');

            return self::SUCCESS;
        }

        $plan = DB::transaction(function () {
            $plan = $this->crearPlan(true);

            if ($plan['errores']) {
                return $plan;
            }

            foreach ($plan['asignaciones'] as $asignacion) {
                Producto::whereKey($asignacion['producto_id'])
                    ->update(['codigo' => $asignacion['codigo']]);
            }

            foreach ($plan['consecutivos'] as $sucursalId => $consecutivo) {
                Sucursal::whereKey($sucursalId)->update(['sku_sequence' => $consecutivo]);
            }

            return $plan;
        });

        $this->mostrarPlan($plan);

        if ($plan['errores']) {
            return self::FAILURE;
        }

        $this->info('SKU actualizados: '.$plan['total'].'.');

        return self::SUCCESS;
    }

    private function crearPlan(bool $bloquear = false): array
    {
        $sucursalesQuery = Sucursal::query()->orderBy('id');
        if ($bloquear) {
            $sucursalesQuery->lockForUpdate();
        }

        $asignaciones = [];
        $consecutivos = [];
        $errores = [];

        foreach ($sucursalesQuery->get() as $sucursal) {
            $productosQuery = $sucursal->productos()->orderBy('id');
            if ($bloquear) {
                $productosQuery->lockForUpdate();
            }

            $productos = $productosQuery->get();
            $cantidad = $productos->count();

            if ($cantidad > 0 && $sucursal->id > 99) {
                $errores[] = "La sucursal {$sucursal->id} tiene productos y su ID no cabe en un SKU de 4 dígitos.";
                continue;
            }

            if ($cantidad > 99) {
                $errores[] = "La sucursal {$sucursal->id} tiene {$cantidad} productos; el formato solo permite 99.";
                continue;
            }

            $consecutivos[$sucursal->id] = $cantidad;

            foreach ($productos as $indice => $producto) {
                $asignaciones[] = [
                    'producto_id' => $producto->id,
                    'sucursal_id' => $sucursal->id,
                    'nombre' => $producto->nombre,
                    'anterior' => $producto->codigo ?? '',
                    'codigo' => sprintf('%02d%02d', $sucursal->id, $indice + 1),
                ];
            }
        }

        return [
            'asignaciones' => $asignaciones,
            'consecutivos' => $consecutivos,
            'errores' => $errores,
            'total' => count($asignaciones),
        ];
    }

    private function mostrarPlan(array $plan): void
    {
        foreach ($plan['errores'] as $error) {
            $this->error($error);
        }

        $this->table(
            ['Producto', 'Sucursal', 'Nombre', 'SKU actual', 'SKU nuevo'],
            array_map(
                fn (array $asignacion) => [
                    $asignacion['producto_id'],
                    $asignacion['sucursal_id'],
                    $asignacion['nombre'],
                    $asignacion['anterior'],
                    $asignacion['codigo'],
                ],
                $plan['asignaciones'],
            ),
        );
    }
}
