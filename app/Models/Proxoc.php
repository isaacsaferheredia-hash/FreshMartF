<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proxoc extends Model
{
    protected $table = 'proxoc';

    protected $primaryKey = null;
    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'id_compra',
        'id_producto',
        'pxo_cantidad',
        'pxo_valor',
        'pxo_subtotal',
        'estado_pxoc',
        'trial863',
    ];
}
