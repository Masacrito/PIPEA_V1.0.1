<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CatEjesSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('cat_lineas_accion_ejes')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $ejes = [
            ['numero_eje' => 1, 'eje' => 'Combatir la corrupción y la impunidad'],
            ['numero_eje' => 2, 'eje' => 'Combatir la arbitrariedad y el abuso de poder'],
            ['numero_eje' => 3, 'eje' => 'Promover la mejora de la gestión pública y de los puntos de contacto gobierno-sociedad'],
            ['numero_eje' => 4, 'eje' => 'Involucrar a la sociedad y el sector privado'],
        ];

        foreach ($ejes as $eje) {
            DB::table('cat_lineas_accion_ejes')->insert([
                ...$eje,
                'activo'     => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
