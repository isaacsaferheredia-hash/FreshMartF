<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Producto;

class CarritoDetalle extends Model
{
    protected $table = 'carrito_detalle';
    protected $primaryKey = 'car_det_id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'car_det_id',
        'car_id',
        'pro_id',
        'cantidad',
        'precio_unit',
        'subtotal'
    ];

    // 🔥 CLAVE: pro_id es STRING (P0001, P0002, etc.)
    protected $casts = [
        'pro_id' => 'string',
    ];

    public function carrito()
    {
        return $this->belongsTo(Carrito::class, 'car_id', 'car_id');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'pro_id', 'id_producto');
    }

    public static function generarId(): string
    {
        $ultimo = self::selectRaw("
        MAX(CAST(SUBSTRING(car_det_id FROM 5) AS INTEGER)) AS max_id
    ")->value('max_id');

        $siguiente = ($ultimo ?? 0) + 1;

        return 'CAD-' . str_pad($siguiente, 6, '0', STR_PAD_LEFT);
    }


}
