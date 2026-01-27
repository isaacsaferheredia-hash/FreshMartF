<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductModel extends Model
{
    protected $table = 'productos';
    protected $primaryKey = 'id_producto';

    // PK tipo P001, P002...
    public $incrementing = false;
    protected $keyType = 'string';

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
        'estado_prod',
        'user_id',
        'fecha_alta',
        'fecha_baja'
    ];

    /**
     * Relación con tipo/categoría de producto
     */
    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categories::class, 'id_tipo', 'id_tipo');
    }

    /**
     * Productos destacados para el HOME
     */


    public function facturas()
    {
        return $this->hasMany(ProxFac::class, 'id_producto', 'id_producto');
    }

    public static function getDestacados()
    {
        return DB::table('productos')
            ->where('estado_prod', 'ACT')
            ->where('dest', 'S')
            ->limit(8)
            ->get();
    }
}
