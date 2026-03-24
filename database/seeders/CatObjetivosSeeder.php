<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CatObjetivosSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('cat_lineas_accion_objetivos')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // id_eje: 1=Eje01, 2=Eje02, 3=Eje03, 4=Eje04
        $objetivos = [
            // ── Eje 01 ────────────────────────────────────────────────────────
            [
                'id_eje'          => 1,
                'numero_objetivo' => 1,
                'objetivo'        => 'Promover los mecanismos de coordinación de las autoridades competentes para la mejora de los procesos de prevención, denuncia, detección, investigación, substanciación y sanción de faltas administrativas y hechos de corrupción.',
            ],
            [
                'id_eje'          => 1,
                'numero_objetivo' => 2,
                'objetivo'        => 'Fortalecer las capacidades institucionales para el desahogo de carpetas de investigación y causas penales en materia de delitos por hechos de corrupción.',
            ],
            // ── Eje 02 ────────────────────────────────────────────────────────
            [
                'id_eje'          => 2,
                'numero_objetivo' => 3,
                'objetivo'        => 'Fortalecer el servicio público mediante servicios profesionales de carrera y mecanismos de integridad a escala nacional, bajo principios de mérito, eficiencia, consistencia estructural, capacidad funcional, ética e integridad.',
            ],
            [
                'id_eje'          => 2,
                'numero_objetivo' => 4,
                'objetivo'        => 'Fomentar el desarrollo y aplicación de procesos estandarizados de planeación, presupuestación y ejercicio del gasto con un enfoque de máxima publicidad y participación de la sociedad en la gestión de riesgos y el fomento de la integridad empresarial.',
            ],
            [
                'id_eje'          => 2,
                'numero_objetivo' => 5,
                'objetivo'        => 'Fortalecer los mecanismos de homologación de sistemas, principios, prácticas y capacidades de auditoría, fiscalización, control interno y rendición de cuentas a escala nacional.',
            ],
            // ── Eje 03 ────────────────────────────────────────────────────────
            [
                'id_eje'          => 3,
                'numero_objetivo' => 6,
                'objetivo'        => 'Promover la implementación de esquemas que erradiquen áreas de riesgo que propician la corrupción en las interacciones que establecen ciudadanos y empresas con el gobierno al realizar trámites, y acceder a programas y servicios públicos.',
            ],
            [
                'id_eje'          => 3,
                'numero_objetivo' => 7,
                'objetivo'        => 'Impulsar la adopción y homologación de reglas en materia de contrataciones públicas, asociaciones público-privadas y cabildeo, que garanticen interacciones íntegras e imparciales entre gobierno y sector privado.',
            ],
            // ── Eje 04 ────────────────────────────────────────────────────────
            [
                'id_eje'          => 4,
                'numero_objetivo' => 8,
                'objetivo'        => 'Impulsar el desarrollo de mecanismos efectivos de participación que favorezcan el involucramiento social en el control de la corrupción, así como en la vigilancia y rendición de cuentas de las decisiones de gobierno.',
            ],
            [
                'id_eje'          => 4,
                'numero_objetivo' => 9,
                'objetivo'        => 'Promover la adopción y aplicación de principios, políticas y programas de integridad y anticorrupción en el sector privado.',
            ],
            [
                'id_eje'          => 4,
                'numero_objetivo' => 10,
                'objetivo'        => 'Fomentar la socialización y adopción de valores prácticos relevantes en la sociedad para el control de la corrupción.',
            ],
        ];

        foreach ($objetivos as $obj) {
            DB::table('cat_lineas_accion_objetivos')->insert([
                ...$obj,
                'activo'     => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
