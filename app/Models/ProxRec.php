<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProxRec extends Model
{
    protected $table = 'proxrec';
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id_recibo',
        'id_producto',
        'pxr_cantidad',
        'pxr_qty_recibida',
        'estado_pxr',
        'trial866',
    ];

    public function recepcion(): BelongsTo
    {
        return $this->belongsTo(Recepcion::class, 'id_recibo', 'id_recibo');
    }

    public function producto()
    {
        return $this->belongsTo(
            Producto::class,
            'id_producto',
            'id_producto'
        )->where('id_producto', '!=', '0');
    }

}
