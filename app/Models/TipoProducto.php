<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoProducto extends Model
{
    protected $table = 'tipos_producto';
    protected $primaryKey = 'id_tipo';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id_tipo',
        'descripcion'
    ];
}
