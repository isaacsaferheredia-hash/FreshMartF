<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnidadMedida extends Model
{
    protected $table = 'unidades_medidas';
    protected $primaryKey = 'id_unidad_medida';

    public $incrementing = false;   // 🔴 CLAVE (CHAR, no int)
    protected $keyType = 'string';  // 🔴 CLAVE
    public $timestamps = false;

    protected $fillable = [
        'id_unidad_medida',
        'um_descripcion'
    ];
}
