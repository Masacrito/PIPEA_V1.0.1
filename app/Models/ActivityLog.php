<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $table = 'activity_logs';

    protected $fillable = [
        'id_usuario',
        'usuario_nombre',
        'evento',
        'modelo',
        'tabla',
        'registro_id',
        'valores_anteriores',
        'valores_nuevos',
        'ip',
        'user_agent',
        'fecha_registro',
    ];

    protected $casts = [
        'valores_anteriores' => 'array',
        'valores_nuevos'     => 'array',
        'fecha_registro'     => 'datetime',
    ];

    // Relación opcional con usuario
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }
}
