<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Recepcion extends Model
{
    protected $table = 'recepciones';
    protected $primaryKey = 'id_recibo';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id_recibo',
        'rec_descripcion',
        'rec_fechahora',
        'rec_num_produc',
        'estado_rec',
        'user_id',
        'trial863',
    ];

    public function detalles(): HasMany
    {
        return $this->hasMany(ProxRec::class, 'id_recibo', 'id_recibo');
    }

    public static function generarId(): string
    {
        $max = self::select(DB::raw("
            MAX(
                CAST(
                    REGEXP_REPLACE(TRIM(id_recibo), '\\D', '', 'g')
                    AS INTEGER
                )
            ) AS max_id
        "))
            ->whereRaw("TRIM(id_recibo) ~* '^REC[0-9]+$'")
            ->value('max_id');

        $next = ($max ?? 0) + 1;

        return 'REC' . str_pad($next, 4, '0', STR_PAD_LEFT);
    }

    public static function crearRecepcion(string $descripcion, int $numProductos): self
    {
        return self::create([
            'id_recibo'      => self::generarId(),
            'rec_descripcion'=> $descripcion,
            'rec_fechahora'  => Carbon::now(),
            'rec_num_produc' => $numProductos,
            'estado_rec'     => 'ABI',
            'user_id'        => 'admin',
        ]);
    }

    public function getRouteKeyName()
    {
        return 'id_recibo';
    }

}
