<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrganismoImplementador extends Model
{
    protected $table = 'organismos_implementadores';

    protected $fillable = [
        'id_ambito',
        'id_nivel',
        'id_poder',
        'nombre',
        'activo',
    ];

    protected $casts = ['activo' => 'boolean'];

    public function ambito()
    {
        return $this->belongsTo(CatAmbito::class, 'id_ambito');
    }

    public function nivel()
    {
        return $this->belongsTo(CatNivel::class, 'id_nivel');
    }

    public function poder()
    {
        return $this->belongsTo(CatPoder::class, 'id_poder');
    }

    public function usuarios()
    {
        return $this->hasMany(Usuario::class, 'id_organismo');
    }

    public function lineasAccion()
    {
        return $this->hasMany(LineaAccion::class, 'id_organismo');
    }
}
