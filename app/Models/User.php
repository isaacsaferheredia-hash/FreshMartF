<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;


use App\Models\Cliente;
use App\Models\UsuarioAreaRol;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;


    /**
     * =========================
     * ATRIBUTOS ASIGNABLES
     * =========================
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'rol',      // ✅ FIX CRÍTICO
        'cli_id',   // NULL para administrativos
    ];

    /**
     * =========================
     * ATRIBUTOS OCULTOS
     * =========================
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * =========================
     * CASTS
     * =========================
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];

    /**
     * =========================
     * RELACIONES
     * =========================
     */

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cli_id', 'id_cliente');
    }

    /**
     * =========================
     * AUTORIZACIÓN POR ÁREA
     * =========================
     */

    /**
     * Retorna el rol del usuario en un área
     */
    public function rolEnArea(string $area): ?string
    {
        return UsuarioAreaRol::where('user_id', $this->id)
            ->where('id_area', strtoupper($area))
            ->value('id_rol');
    }

    /**
     * Verifica si el usuario tiene acceso a un área
     */
    public function tieneArea(string $area): bool
    {
        // SOLO ADMIN tiene acceso total
        if ($this->rol === 'ADMIN') {
            return true;
        }

        return UsuarioAreaRol::where('user_id', $this->id)
            ->where('id_area', strtoupper($area))
            ->exists();
    }

    /**
     * Verifica si el usuario puede realizar una acción en un área
     */
    public function puede(string $area, string $accion): bool
    {
        // 🔓 ADMIN y JEFE pueden todo
        if (in_array($this->rol, ['ADMIN', 'JEFE'])) {
            return true;
        }

        $rol = $this->rolEnArea($area);

        if (! $rol) {
            return false;
        }

        $accion = strtoupper($accion);

        $matriz = [
            'JEFE' => [
                'VISUALIZAR' => true,
                'CREAR'      => false,
                'APROBAR'    => true,
                'ANULAR'     => true,
            ],
            'AUXILIAR' => [
                'VISUALIZAR' => true,
                'CREAR'      => true,
                'APROBAR'    => false,
                'ANULAR'     => false,
            ],
            'OPERATIVO' => [
                'VISUALIZAR' => true,
                'CREAR'      => false,
                'APROBAR'    => false,
                'ANULAR'     => false,
            ],
        ];

        return $matriz[$rol][$accion] ?? false;
    }

    /**
     * =========================
     * HELPERS DE CONVENIENCIA
     * =========================
     */

    /**
     * ¿Es cliente ecommerce?
     */
    public function esCliente(): bool
    {
        return $this->rol === 'CLIENTE';
    }

    /**
     * ¿Es usuario administrativo?
     * (lógica original preservada)
     */
    public function esAdmin(): bool
    {
        return UsuarioAreaRol::where('user_id', $this->id)->exists()
            || in_array($this->rol, ['ADMIN', 'JEFE']);
    }
}
