<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CatAmbito extends Model
{
    protected $table = 'cat_ambitos';

    protected $fillable = ['ambito', 'activo'];

    protected $casts = ['activo' => 'boolean'];

    public function organismos()
    {
        return $this->hasMany(OrganismoImplementador::class, 'id_ambito');
    }
}
