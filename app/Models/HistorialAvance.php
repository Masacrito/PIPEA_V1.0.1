<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistorialAvance extends Model
{
    protected $table = 'historial_avance';

    protected $fillable = [
        'id_linea_accion',
        'id_usuario',
        'usuario',
        'medio_verificacion',
        'documento',
        'url',
        'comentario',
        'fecha_registro',
    ];

    protected $casts = [
        'fecha_registro' => 'datetime',
    ];

    public function lineaAccion()
    {
        return $this->belongsTo(LineaAccion::class, 'id_linea_accion');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }
}
