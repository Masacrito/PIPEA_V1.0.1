<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CatCatalogosSimpleSeeder extends Seeder
{
    public function run(): void
    {
        // ── Estrategias ───────────────────────────────────────────────────────
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('cat_lineas_accion_estrategias')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $estrategias = [
            'Fortalecimiento institucional',
            'Coordinación interinstitucional',
            'Transparencia y rendición de cuentas',
            'Capacitación y profesionalización',
            'Participación ciudadana',
            'Innovación tecnológica',
            'Marco normativo',
            'Fiscalización y auditoría',
            'Difusión y comunicación',
            'Perspectiva de género e interculturalidad',
        ];

        foreach ($estrategias as $e) {
            DB::table('cat_lineas_accion_estrategias')->insert([
                'estrategia' => $e,
                'activo'     => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // ── Plazos ────────────────────────────────────────────────────────────
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('cat_lineas_accion_plazos')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $plazos = [
            'Corto plazo (1 año)',
            'Mediano plazo (2-3 años)',
            'Largo plazo (4-6 años)',
            'Permanente',
        ];

        foreach ($plazos as $p) {
            DB::table('cat_lineas_accion_plazos')->insert([
                'plazo'      => $p,
                'activo'     => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // ── Frecuencias de medición ───────────────────────────────────────────
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('cat_lineas_accion_frec_medicion')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $frecuencias = [
            'Mensual',
            'Bimestral',
            'Trimestral',
            'Semestral',
            'Anual',
        ];

        foreach ($frecuencias as $f) {
            DB::table('cat_lineas_accion_frec_medicion')->insert([
                'frecuencia' => $f,
                'activo'     => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
