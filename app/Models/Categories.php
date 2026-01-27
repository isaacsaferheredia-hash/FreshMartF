<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\Collection;
class Categories extends Model
{
    use HasFactory;

    protected $table = 'tipos_producto';
    protected $primaryKey = 'id_Tipo';
    protected $fillable = [
        'tipo_Descripcion'
    ];
    static  function getCategories():Collection
    {
        return Categories::all();
    }

}
