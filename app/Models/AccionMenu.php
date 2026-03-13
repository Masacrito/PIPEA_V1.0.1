<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccionMenu extends Model
{
    protected $table = 'acciones_menu';

    protected $fillable = [
        'controlador',
        'accion',
        'menu',
        'orden',
        'icono',
    ];

    // Relación inversa N:M con roles
    public function roles()
    {
        return $this->belongsToMany(
            Rol::class,
            'permisos_acciones',
            'id_accion',
            'id_rol'
        );
    }
}
