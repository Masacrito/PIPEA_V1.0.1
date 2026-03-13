<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * Crea 3 usuarios de prueba, uno por rol.
 *
 * ┌─────────────────────────────────────────────────────────────────┐
 * │  ROL                │  EMAIL                    │  PASSWORD     │
 * ├─────────────────────┼───────────────────────────┼───────────────┤
 * │  SUPER_ADMIN        │  superadmin@sesaech.gob   │  Admin@1234   │
 * │  ADMIN_DEPENDENCIA  │  admindep@sesaech.gob     │  Admin@1234   │
 * │  USUARIO_ORGANISMO  │  usuario@sfp.gob          │  Admin@1234   │
 * └─────────────────────┴───────────────────────────┴───────────────┘
 *
 * IMPORTANTE: Cambiar contraseñas en producción.
 */
class Usuariospruebaseeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('Admin@1234');
        $ahora    = now();

        DB::table('usuarios')->upsert(
            [
                // ── SUPER_ADMIN ───────────────────────────────────────────────
                [
                    'id'              => 1,
                    'nombre'          => 'Administrador',
                    'primer_apellido' => 'Sistema',
                    'segundo_apellido'=> null,
                    'email'           => 'admin@test.gob',
                    'password'        => Hash::make('password'),
                    'id_rol'          => 1, // SUPER_ADMIN
                    'id_organismo'    => 1, // SESAECH
                    'activo'          => true,
                    'created_at'      => $ahora,
                    'updated_at'      => $ahora,
                ],

                // ── ADMIN_DEPENDENCIA ─────────────────────────────────────────
                [
                    'id'              => 2,
                    'nombre'          => 'Administrador',
                    'primer_apellido' => 'Dependencia',
                    'segundo_apellido'=> null,
                    'email'           => 'admindep@test.gob',
                    'password'        => Hash::make('password'),
                    'id_rol'          => 2, // ADMIN_DEPENDENCIA
                    'id_organismo'    => 1, // SESAECH
                    'activo'          => true,
                    'created_at'      => $ahora,
                    'updated_at'      => $ahora,
                ],

                // ── USUARIO_ORGANISMO ─────────────────────────────────────────
                [
                    'id'              => 3,
                    'nombre'          => 'Usuario',
                    'primer_apellido' => 'Organismo',
                    'segundo_apellido'=> null,
                    'email'           => 'usuario@test.gob',
                    'password'        => Hash::make('password'),
                    'id_rol'          => 3, // USUARIO_ORGANISMO
                    'id_organismo'    => 2, // Secretaría de Finanzas
                    'activo'          => true,
                    'created_at'      => $ahora,
                    'updated_at'      => $ahora,
                ],
            ],
            uniqueBy: ['id'],
            update: ['nombre', 'primer_apellido', 'email', 'password', 'id_rol', 'id_organismo', 'activo']
        );
    }
}
