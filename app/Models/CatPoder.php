<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CatPoder extends Model
{
    protected $table = 'cat_poderes';

    protected $fillable = ['poder', 'activo'];

    protected $casts = ['activo' => 'boolean'];

    public function organismos()
    {
        return $this->hasMany(OrganismoImplementador::class, 'id_poder');
    }
}
