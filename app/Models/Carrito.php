<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\CarritoDetalle;

class Carrito extends Model
{
    protected $table = 'carritos';
    protected $primaryKey = 'car_id';
    public $incrementing = false;
    protected $keyType = 'string';

    // 🔥 Tabla diseñada manualmente
    public $timestamps = false;

    protected $fillable = [
        'car_id',
        'cli_id',
        'car_estado',
        'car_subtotal',
        'car_iva',
        'car_total',
        'car_ip',
        'car_user_agent'
    ];

    /* ======================================================
     | RELACIONES
     ======================================================*/

    public function detalles()
    {
        return $this->hasMany(CarritoDetalle::class, 'car_id', 'car_id');
    }

    /* ======================================================
     | MÉTODOS DE NEGOCIO
     ======================================================*/

    /**
     * Retorna el carrito activo del cliente
     */
    public static function carritoActivo(string $cliId): ?self
    {
        return self::whereRaw('TRIM(cli_id) = ?', [trim($cliId)])
            ->where('car_estado', 'ABI')
            ->first();
    }



    /**
     * Obtiene o crea un carrito activo
     */
    public static function obtenerActivo(string $cliId, $request): self
    {
        return self::firstOrCreate(
            [
                'cli_id'     => trim($cliId),
                'car_estado' => 'ABI'
            ],
            [
                'car_id'         => self::generarId(),
                'car_subtotal'   => 0,
                'car_iva'        => 0,
                'car_total'      => 0,
                'car_ip'         => $request->ip(),
                'car_user_agent' => substr($request->header('User-Agent'), 0, 250)
            ]
        );
    }


    /**
     * Agrega un producto al carrito
     */
    public function agregarProducto(Producto $producto, int $cantidad): void
    {
        $detalle = $this->detalles()
            ->where('pro_id', trim($producto->id_producto))
            ->first();

        if ($detalle) {
            // Producto ya existe → sumar cantidad
            $detalle->cantidad += $cantidad;
            $detalle->subtotal = $detalle->cantidad * $detalle->precio_unit;
            $detalle->save();
        } else {
            // Nuevo producto en el carrito
            $this->detalles()->create([
                'car_det_id'  => CarritoDetalle::generarId(),
                'car_id'      => $this->car_id,
                'pro_id'      => trim($producto->id_producto),
                'cantidad'    => $cantidad,
                'precio_unit' => $producto->pro_precio_venta,
                'subtotal'    => $cantidad * $producto->pro_precio_venta
            ]);
        }

        // 🔥 Siempre recalcular totales
        $this->recalcularTotales();
    }


    /**
     * Actualiza la cantidad de un producto
     */
    public function actualizarCantidad($carDetId, int $cantidad)
    {
        $detalle = $this->detalles()
            ->where('car_det_id', $carDetId)
            ->firstOrFail();

        $detalle->cantidad = $cantidad;
        $detalle->subtotal = $detalle->cantidad * $detalle->precio_unit;
        $detalle->save();

        // Recalcular totales del carrito
        $this->car_subtotal = $this->detalles()->sum('subtotal');
        $this->car_iva = $this->car_subtotal * 0.12;
        $this->car_total = $this->car_subtotal + $this->car_iva;
        $this->save();

        return $detalle; // 👈 CLAVE
    }

    /**
     * Elimina un detalle del carrito
     */
    public function eliminarDetalle(string $carDetId): void
    {
        $this->detalles()
            ->where('car_det_id', $carDetId)
            ->delete();

        $this->recalcularTotales();
    }

    /* ======================================================
     | MÉTODOS PRIVADOS
     ======================================================*/

    /**
     * Recalcula subtotal, IVA y total
     */
    private function recalcularTotales(): void
    {
        $subtotal = $this->detalles()->sum('subtotal');
        $iva = round($subtotal * 0.12, 2);

        $this->update([
            'car_subtotal' => $subtotal,
            'car_iva'      => $iva,
            'car_total'    => $subtotal + $iva
        ]);
    }

    public static function generarId(): string
    {
        $ultimo = self::selectRaw("
        MAX(CAST(SUBSTRING(car_id FROM 5) AS INTEGER)) AS max_id
    ")->value('max_id');

        $siguiente = ($ultimo ?? 0) + 1;

        return 'CAR-' . str_pad($siguiente, 6, '0', STR_PAD_LEFT);
    }



}
