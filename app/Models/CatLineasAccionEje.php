<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CatLineasAccionEje extends Model
{
    protected $table = 'cat_lineas_accion_ejes';

    protected $fillable = ['numero_eje', 'eje', 'activo'];

    protected $casts = ['activo' => 'boolean'];

    public function prioridades()
    {
        return $this->hasMany(CatLineasAccionPrioridad::class, 'id_eje');
    }

    public function lineasAccion()
    {
        return $this->hasMany(LineaAccion::class, 'id_eje');
    }
}
