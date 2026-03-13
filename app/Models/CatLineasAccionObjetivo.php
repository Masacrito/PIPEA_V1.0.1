<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CatLineasAccionObjetivo extends Model
{
    protected $table = 'cat_lineas_accion_objetivos';

    protected $fillable = ['numero_objetivo', 'objetivo', 'activo'];

    protected $casts = ['activo' => 'boolean'];

    public function prioridades()
    {
        return $this->hasMany(CatLineasAccionPrioridad::class, 'id_objetivo');
    }

    public function lineasAccion()
    {
        return $this->hasMany(LineaAccion::class, 'id_objetivo');
    }
}
