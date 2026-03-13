<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CatLineasAccionPlazo extends Model
{
    protected $table = 'cat_lineas_accion_plazos';

    protected $fillable = ['plazo', 'activo'];

    protected $casts = ['activo' => 'boolean'];

    public function lineasAccion()
    {
        return $this->hasMany(LineaAccion::class, 'id_plazo');
    }
}
