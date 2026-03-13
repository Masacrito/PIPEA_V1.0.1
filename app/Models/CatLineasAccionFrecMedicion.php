<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CatLineasAccionFrecMedicion extends Model
{
    protected $table = 'cat_lineas_accion_frec_medicion';

    protected $fillable = ['frecuencia', 'activo'];

    protected $casts = ['activo' => 'boolean'];

    public function lineasAccion()
    {
        return $this->hasMany(LineaAccion::class, 'id_frecuencia');
    }
}
