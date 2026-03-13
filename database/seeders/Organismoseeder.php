<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Organismoseeder extends Seeder
{
    public function run(): void
    {
        DB::table('organismos_implementadores')->upsert(
            [
                [
                    'id'          => 1,
                    'nombre'      => 'Secretaría Anticorrupción y Buen Gobierno',
                    'siglas'      => 'SESAECH',
                    'activo'      => true,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'id'          => 2,
                    'nombre'      => 'Secretaría de Finanzas Públicas',
                    'siglas'      => 'SFP',
                    'activo'      => true,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
            ],
            uniqueBy: ['id'],
            update: ['nombre', 'siglas', 'activo']
        );
    }
}
