<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Proveedor extends Model
{
    protected $table = 'proveedores';
    protected $primaryKey = 'id_proveedor';

    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id_proveedor',
        'prv_nombre',
        'prv_ruc_ced',
        'prv_telefono',
        'prv_celular',
        'prv_mail',
        'id_ciudad',
        'prv_direccion',
        'estado_prv'
    ];

    /* =========================
       RELACIONES
    ========================== */
    public function ciudad()
    {
        return $this->belongsTo(Ciudad::class, 'id_ciudad', 'id_ciudad');
    }

    public function getRouteKeyName()
    {
        return 'id_proveedor';
    }

    /* =========================
       MÉTODOS ESTÁTICOS (NUEVA LÓGICA)
    ========================== */

    public static function listar($buscar = null)
    {
        return self::where(function ($q) use ($buscar) {
            if ($buscar) {
                $q->where('prv_nombre', 'ILIKE', "%$buscar%")
                    ->orWhere('prv_ruc_ced', 'ILIKE', "%$buscar%")
                    ->orWhere('id_proveedor', 'ILIKE', "%$buscar%");
            }
        })
            ->orderBy('id_proveedor')
            ->paginate(10);
    }

    public static function crear(array $data): self
    {
        $data['id_proveedor'] = self::generarId();
        $data['estado_prv']   = 'ACT';

        return self::create($data);
    }

    public static function actualizar(self $proveedor, array $data): bool
    {
        return $proveedor->update($data);
    }

    public static function desactivar(self $proveedor): bool
    {
        return $proveedor->update(['estado_prv' => 'INA']);
    }

    /* =========================
       GENERADOR DE ID ROBUSTO
    ========================== */
    public static function generarId(): string
    {
        $max = self::select(DB::raw("
                MAX(
                    CAST(
                        REGEXP_REPLACE(TRIM(id_proveedor), '\\D', '', 'g')
                        AS INTEGER
                    )
                ) AS max_id
            "))
            ->whereRaw("TRIM(id_proveedor) ~* '^PRV[0-9]+$'")
            ->value('max_id');

        $next = ($max ?? 0) + 1;

        return 'PRV' . str_pad($next, 3, '0', STR_PAD_LEFT);
    }
}
