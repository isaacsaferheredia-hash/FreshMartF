<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Cliente extends Model
{
    protected $table = 'clientes';
    protected $primaryKey = 'id_cliente';

    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id_cliente',
        'cli_nombre',
        'cli_ruc_ced',
        'cli_telefono',
        'cli_celular',
        'cli_mail',
        'id_ciudad',
        'cli_direccion',
        'estado_cli'
    ];


    public function ciudad()
    {
        return $this->belongsTo(Ciudad::class, 'id_ciudad', 'id_ciudad');
    }

    public function facturas()
    {
        return $this->hasMany(Factura::class, 'id_cliente', 'id_cliente');
    }


    public static function getClientes()
    {
        return self::whereIn('estado_cli', ['ACT', 'INA'])
            ->orderBy('cli_nombre')
            ->paginate(10);
    }

    public static function getClienteById(string $id): self
    {
        return self::findOrFail($id);
    }


    public static function generarNuevoId(): string
    {
        $max = self::select(DB::raw("
                MAX(
                    CAST(
                        SUBSTRING(TRIM(id_cliente), 4)
                        AS INTEGER
                    )
                ) AS max_id
            "))
            ->whereRaw("TRIM(id_cliente) ~ '^CLT[0-9]+$'")
            ->value('max_id');

        $next = ($max ?? 0) + 1;

        return 'CLT' . str_pad($next, 4, '0', STR_PAD_LEFT);
    }


    /**
     * ALGORITMO
     * PRERREQUISITOS
     * 1. la data del cliente
     * logica del negocio
     * 1. Buscar registro que tenga la cedula enviada
     * 1.1. Si ya existe devolver error
     * 1.2. Si no, continua
     * 2. Insertar en la BD
     * 3. devolver respuesta
     * @param array $data
     * @return self
     * @throws \Throwable
     */
    public static function createCliente(array $data): self
    {
        return DB::transaction(function () use ($data) {

            // 1. Buscar select * from clientes where cli_cedula='hhhh'
             $cliente = self::where('cli_ruc_ced', $data['cli_ruc_ced'])->first();
            //
            //1.1  Si existe devolver error
            if($cliente != null){
                return null;
            }
            // 2. Registro y 3. retorna

            return self::create([
                'id_cliente'    => self::generarNuevoId(),
                'cli_nombre'    => $data['cli_nombre'],
                'cli_ruc_ced'   => $data['cli_ruc_ced'],
                'cli_telefono'  => $data['cli_telefono'] ?? null,
                'cli_celular'   => $data['cli_celular'] ?? null,
                'cli_mail'      => $data['cli_mail'],
                'id_ciudad'     => $data['id_ciudad'],
                'cli_direccion' => $data['cli_direccion'],
                'estado_cli'    => 'ACT',
            ]);
        });
    }


    public static function updateCliente(self $cliente, array $data): void
    {
        $cliente->update([
            'cli_nombre'    => $data['cli_nombre'],
            'cli_ruc_ced'   => $data['cli_ruc_ced'],
            'cli_telefono'  => $data['cli_telefono'] ?? null,
            'cli_celular'   => $data['cli_celular'] ?? null,
            'cli_mail'      => $data['cli_mail'],
            'id_ciudad'     => $data['id_ciudad'],
            'cli_direccion' => $data['cli_direccion'],
        ]);
    }

    public static function desactivarCliente(self $cliente): void
    {
        $cliente->update([
            'estado_cli' => 'INA'
        ]);
    }
}
