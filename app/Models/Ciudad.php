<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ciudad extends Model
{
    protected $table = 'ciudades';

    protected $primaryKey = 'id_ciudad';

    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'id_ciudad',
        'ciu_descripcion'
    ];

    public function edit(Cliente $cliente)
    {
        $ciudades = Ciudad::orderBy('ciu_descripcion')->get();

        return view('clientes.edit', compact('cliente', 'ciudades'));
    }
}


