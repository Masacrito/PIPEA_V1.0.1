<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Roleseeder extends Seeder
{
    public function run(): void
    {
        DB::table('roles')->upsert(
            [
                ['id' => 1, 'rol' => 'SUPER_ADMIN',       'activo' => true],
                ['id' => 2, 'rol' => 'ADMIN_DEPENDENCIA', 'activo' => true],
                ['id' => 3, 'rol' => 'USUARIO_ORGANISMO', 'activo' => true],
            ],
            uniqueBy: ['id'],
            update: ['rol', 'activo']
        );
    }
}
