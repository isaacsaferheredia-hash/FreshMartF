<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Producto extends Model
{
    protected $table = 'productos';
    protected $primaryKey = 'id_producto';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id_producto',
        'pro_descripcion',
        'id_tipo',
        'pro_um_compra',
        'pro_um_venta',
        'pro_valor_compra',
        'pro_precio_venta',
        'pro_saldo_inicial',
        'pro_qty_ingresos',
        'pro_qty_egresos',
        'pro_qty_ajustes',
        'pro_saldo_final',
        'dest',
        'pro_img',
        'estado_prod',
        'user_id',
        'fecha_alta',
        'fecha_baja'
    ];

    public static function generarNuevoId(): string
    {
        $max = self::select(
            DB::raw("MAX(CAST(REGEXP_REPLACE(id_producto, '\\D', '', 'g') AS INTEGER)) as max_id")
        )->value('max_id');

        return 'P' . str_pad(($max ?? 0) + 1, 4, '0', STR_PAD_LEFT);
    }

    public static function generarImagen(): string
    {
        $max = self::select(
            DB::raw("MAX(CAST(REGEXP_REPLACE(pro_img, '\\D', '', 'g') AS INTEGER)) as max_img")
        )->value('max_img');

        return 'PRD' . str_pad(($max ?? 0) + 1, 3, '0', STR_PAD_LEFT);
    }

    public static function crearProducto(array $data): self
    {
        return self::create([
            'id_producto'       => self::generarNuevoId(),
            'pro_descripcion'   => $data['pro_descripcion'],
            'id_tipo'           => $data['id_tipo'],
            'pro_um_compra'     => $data['pro_um_compra'],
            'pro_um_venta'      => $data['pro_um_venta'],
            'pro_valor_compra'  => $data['pro_valor_compra'],
            'pro_precio_venta'  => $data['pro_precio_venta'],
            'pro_saldo_inicial' => $data['pro_saldo_inicial'] ?? 0,
            'pro_qty_ingresos'  => 0,
            'pro_qty_egresos'   => 0,
            'pro_qty_ajustes'   => 0,
            'pro_saldo_final'   => $data['pro_saldo_inicial'] ?? 0,
            'dest'              => $data['dest'],
            'pro_img'           => self::generarImagen(),
            'estado_prod'       => 'ACT',
            'user_id'           => 'admin',
            'fecha_alta'        => now(),
        ]);
    }

    public function desactivar(): void
    {
        $this->update([
            'estado_prod' => 'ANU',
            'fecha_baja'  => now(),
        ]);
    }


    /**
 * Actualiza un producto existente
 */
public function actualizarProducto(array $data): void
{
    $this->update([
        'pro_descripcion'  => $data['pro_descripcion'],
        'id_tipo'          => $data['id_tipo'],
        'pro_um_compra'    => $data['pro_um_compra'],
        'pro_um_venta'     => $data['pro_um_venta'],
        'pro_valor_compra' => $data['pro_valor_compra'],
        'pro_precio_venta' => $data['pro_precio_venta'],
        'dest'             => $data['dest'],
    ]);
}

}
