<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Compra extends Model
{
    protected $table = 'compras';
    protected $primaryKey = 'id_compra';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id_compra',
        'id_proveedor',
        'oc_fecha_hora',
        'oc_subtotal',
        'oc_iva',
        'estado_oc',
        'trial859',
    ];

    /* =========================
       RELACIÓN
    ========================== */
    public function detalles(): HasMany
    {
        return $this->hasMany(Proxoc::class, 'id_compra', 'id_compra')
            ->whereIn('estado_pxoc', ['ABI', 'ANU']);
    }

    /* =========================
       OBTENER TODAS LAS COMPRAS
    ========================== */
    public static function getCompras()
    {
        return self::whereIn('estado_oc', ['APR', 'ANU'])
            ->orderByDesc('oc_fecha_hora')
            ->get();
    }

    /* =========================
       OBTENER COMPRA POR ID
    ========================== */
    public static function getCompraById(string $id): self
    {
        return self::with('detalles')->findOrFail($id);
    }

    /* =========================
       GENERAR ID (ROBUSTO)
       TOLERANTE A ESPACIOS
    ========================== */
    public static function generarNuevoId(): string
    {
        $max = self::select(DB::raw("
                MAX(
                    CAST(
                        SUBSTRING(TRIM(id_compra), 4)
                        AS INTEGER
                    )
                ) AS max_id
            "))
            ->whereRaw("TRIM(id_compra) ~ '^CPR[0-9]+$'")
            ->value('max_id');

        $next = ($max ?? 0) + 1;

        return 'CPR' . str_pad($next, 3, '0', STR_PAD_LEFT);
    }

    /* =========================
       CREAR COMPRA + DETALLES
    ========================== */
    public static function createCompraConDetalle(array $data): self
    {
        return DB::transaction(function () use ($data) {

            // 🔒 Lock transaccional (evita duplicados)
            DB::select('SELECT pg_advisory_xact_lock(778899)');

            $subtotal = 0;
            foreach ($data['items'] as $it) {
                $subtotal += ((float)$it['costo']) * ((int)$it['cantidad']);
            }

            $iva = round($subtotal * 0.12, 3);
            $idCompra = self::generarNuevoId();

            $compra = self::create([
                'id_compra'     => $idCompra,
                'id_proveedor'  => $data['id_proveedor'],
                'oc_fecha_hora' => $data['oc_fecha_hora'],
                'oc_subtotal'   => round($subtotal, 3),
                'oc_iva'        => $iva,
                'estado_oc'     => 'APR',
                'trial859'      => 'T',
            ]);

            foreach ($data['items'] as $it) {
                Proxoc::create([
                    'id_compra'    => $idCompra,
                    'id_producto'  => $it['id_producto'],
                    'pxo_cantidad' => (int)$it['cantidad'],
                    'pxo_valor'    => (float)$it['costo'],
                    'pxo_subtotal' => round(
                        ((float)$it['costo']) * ((int)$it['cantidad']),
                        3
                    ),
                    'estado_pxoc'  => 'ABI',
                    'trial863'     => 'T',
                ]);
            }

            return $compra;
        });
    }

    /* =========================
       ACTUALIZAR COMPRA + DETALLES
    ========================== */
    public static function updateCompraConDetalle(self $compra, array $data): void
    {
        DB::transaction(function () use ($compra, $data) {

            $subtotal = 0;
            foreach ($data['items'] as $it) {
                $subtotal += ((float)$it['costo']) * ((int)$it['cantidad']);
            }

            $iva = round($subtotal * 0.12, 3);

            $compra->update([
                'id_proveedor'  => $data['id_proveedor'],
                'oc_fecha_hora' => $data['oc_fecha_hora'],
                'oc_subtotal'   => round($subtotal, 3),
                'oc_iva'        => $iva,
                'estado_oc'     => 'APR',
            ]);

            Proxoc::where('id_compra', $compra->id_compra)->delete();

            foreach ($data['items'] as $it) {
                Proxoc::create([
                    'id_compra'    => $compra->id_compra,
                    'id_producto'  => $it['id_producto'],
                    'pxo_cantidad' => (int)$it['cantidad'],
                    'pxo_valor'    => (float)$it['costo'],
                    'pxo_subtotal' => round(
                        ((float)$it['costo']) * ((int)$it['cantidad']),
                        3
                    ),
                    'estado_pxoc'  => 'ABI',
                    'trial863'     => 'T',
                ]);
            }
        });
    }
}
