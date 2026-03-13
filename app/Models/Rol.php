<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    protected $table = 'roles';

    protected $fillable = ['rol', 'activo'];

    protected $casts = ['activo' => 'boolean'];

    public function usuarios()
    {
        return $this->hasMany(Usuario::class, 'id_rol');
    }

    // Relación N:M con acciones de menú
    public function accionesMenu()
    {
        return $this->belongsToMany(
            AccionMenu::class,
            'permisos_acciones',
            'id_rol',
            'id_accion'
        );
    }
}
