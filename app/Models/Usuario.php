<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use Notifiable;

    protected $table = 'usuarios';

    protected $fillable = [
        'id_rol',
        'id_organismo',
        'nombre',
        'primer_apellido',
        'segundo_apellido',
        'curp',
        'rfc',
        'email',
        'password',
        'activo',
        'fecha_registro',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'activo'          => 'boolean',
        'fecha_registro'  => 'datetime',
        'password'        => 'hashed',
    ];

    // Nombre completo helper
    public function getNombreCompletoAttribute(): string
    {
        return trim("{$this->nombre} {$this->primer_apellido} {$this->segundo_apellido}");
    }

    public function rol()
    {
        return $this->belongsTo(Rol::class, 'id_rol');
    }

    public function organismo()
    {
        return $this->belongsTo(OrganismoImplementador::class, 'id_organismo');
    }

    public function lineasAccion()
    {
        return $this->hasMany(LineaAccion::class, 'id_usuario');
    }

    public function historialAvances()
    {
        return $this->hasMany(HistorialAvance::class, 'id_usuario');
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class, 'id_usuario');
    }

    // Helper: verificar si el usuario tiene permiso sobre una acción
    public function tienePermiso(string $controlador, string $accion): bool
    {
        return $this->rol
            ->accionesMenu()
            ->where('controlador', $controlador)
            ->where('accion', $accion)
            ->exists();
    }
}
