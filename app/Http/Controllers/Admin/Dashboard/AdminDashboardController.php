<?php

namespace App\Http\Controllers\Admin\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\LineaAccion;
use App\Models\OrganismoImplementador;
use App\Models\Usuario;
use Inertia\Inertia;
use Inertia\Response;

class AdminDashboardController extends Controller
{
    public function index(): Response
    {
        $totalLineas = LineaAccion::where('activo', true)->count();

        // Avance general: promedio de porcentajes de todas las líneas con meta > 0
        $avanceGeneral = 0;
        if ($totalLineas > 0) {
            $lineasConMeta = LineaAccion::where('activo', true)
                ->where('meta', '>', 0)
                ->get(['meta', 'avance_cuantitativo']);

            if ($lineasConMeta->count() > 0) {
                $sumaPorc = $lineasConMeta->sum(fn($l) =>
                round(($l->avance_cuantitativo / $l->meta) * 100, 2)
                );
                $avanceGeneral = round($sumaPorc / $lineasConMeta->count(), 1);
            }
        }

        // Últimos avances registrados
        $ultimosAvances = LineaAccion::with(['usuario.organismo', 'prioridad'])
            ->where('activo', true)
            ->where('avance_cuantitativo', '>', 0)
            ->where('meta', '>', 0)
            ->orderByDesc('fecha_actualizacion')
            ->limit(5)
            ->get()
            ->map(fn($l) => [
                'id'          => $l->id,
                'organismo'   => $l->usuario?->organismo?->siglas
                    ?? $l->usuario?->organismo?->nombre
                        ?? 'Sin organismo',
                'linea'       => $l->prioridad
                    ? "Prioridad {$l->prioridad->numero}"
                    : "Línea #{$l->id}",
                'porcentaje'  => round(($l->avance_cuantitativo / $l->meta) * 100, 1),
            ]);

        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'organismos' => OrganismoImplementador::where('activo', true)->count(),
                'usuarios'   => Usuario::where('activo', true)->count(),
                'lineas'     => $totalLineas,
                'avance'     => $avanceGeneral,
            ],
            'ultimosAvances' => $ultimosAvances,
        ]);
    }
}
