<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CatAmbitosNivelesPoderesSeeder extends Seeder
{
    public function run(): void
    {
        // ── Ámbitos ───────────────────────────────────────────────────────────
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('cat_ambitos')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $ambitos = [
            'Estatal',
            'Municipal',
            'Federal',
        ];

        foreach ($ambitos as $a) {
            DB::table('cat_ambitos')->insert([
                'ambito'     => $a,
                'activo'     => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // ── Niveles ───────────────────────────────────────────────────────────
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('cat_niveles')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $niveles = [
            'Secretaría / Dependencia',
            'Organismo autónomo',
            'Entidad paraestatal',
            'Ayuntamiento',
            'Organismo descentralizado',
            'Tribunal / Poder Judicial',
            'Órgano constitucional autónomo',
        ];

        foreach ($niveles as $n) {
            DB::table('cat_niveles')->insert([
                'nivel'      => $n,
                'activo'     => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // ── Poderes ───────────────────────────────────────────────────────────
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('cat_poderes')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $poderes = [
            'Ejecutivo',
            'Legislativo',
            'Judicial',
            'Autónomo',
        ];

        foreach ($poderes as $p) {
            DB::table('cat_poderes')->insert([
                'poder'      => $p,
                'activo'     => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
