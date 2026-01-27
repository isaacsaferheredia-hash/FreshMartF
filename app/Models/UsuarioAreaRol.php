<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsuarioAreaRol extends Model
{
    protected $table = 'usuario_area_rol';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'id_area',
        'id_rol',
    ];
}
