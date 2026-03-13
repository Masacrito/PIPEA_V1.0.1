<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CatLineasAccionEstrategia extends Model
{
    protected $table = 'cat_lineas_accion_estrategias';

    protected $fillable = ['estrategia', 'activo'];

    protected $casts = ['activo' => 'boolean'];

    public function prioridades()
    {
        return $this->belongsToMany(
            CatLineasAccionPrioridad::class,
            'prioridad_estrategia',
            'id_estrategia',
            'id_prioridad'
        );
    }

    public function lineasAccion()
    {
        return $this->hasMany(LineaAccion::class, 'id_estrategia');
    }
}
