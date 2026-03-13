<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CatNivel extends Model
{
    protected $table = 'cat_niveles';

    protected $fillable = ['nivel', 'activo'];

    protected $casts = ['activo' => 'boolean'];

    public function organismos()
    {
        return $this->hasMany(OrganismoImplementador::class, 'id_nivel');
    }
}
