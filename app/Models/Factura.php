<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Factura extends Model
{
    protected $table = 'facturas';
    protected $primaryKey = 'id_factura';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id_factura',
        'id_cliente',
        'fac_descripcion',
        'fac_fecha_hora',
        'fac_subtotal',
        'fac_iva',
        'estado_fac',
    ];

    /* =========================
       RELACIONES
    ========================== */

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class, 'id_cliente', 'id_cliente');
    }

    public function detalles(): HasMany
    {
        return $this->hasMany(ProxFac::class, 'id_factura', 'id_factura')
            ->whereIn('estado_pxf', ['ABI', 'APR']);
    }

    /* =========================
       MÉTODOS ESTÁTICOS (NUEVA LÓGICA)
    ========================== */

    public static function generarId(): string
    {
        $max = self::select(DB::raw("
                MAX(
                    CAST(
                        REGEXP_REPLACE(TRIM(id_factura), '\\D', '', 'g')
                        AS INTEGER
                    )
                ) AS max_id
            "))
            ->whereRaw("TRIM(id_factura) ~* '^FCT[0-9]+$'")
            ->value('max_id');

        $next = ($max ?? 0) + 1;

        return 'FCT' . str_pad($next, 4, '0', STR_PAD_LEFT);
    }

    public static function crearFactura(string $idCliente): self
    {
        return self::create([
            'id_factura'     => self::generarId(),
            'id_cliente'     => $idCliente,
            'fac_descripcion'=> null,
            'fac_fecha_hora' => Carbon::now(),
            'fac_subtotal'   => 0,
            'fac_iva'        => 0,
            'estado_fac'     => 'ABI',
        ]);
    }

    public static function actualizarTotales(string $idFactura, float $subtotal): void
    {
        $iva = round($subtotal * 0.15, 2);

        self::where('id_factura', $idFactura)->update([
            'fac_subtotal' => $subtotal,
            'fac_iva'      => $iva,
        ]);
    }
}
