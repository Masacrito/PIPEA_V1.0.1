<?php

namespace App\Http\Controllers\Admin\LineasAccion;
use App\Http\Controllers\Controller;
use App\Models\CatLineasAccionEje;
use App\Models\CatLineasAccionObjetivo;
use App\Models\CatLineasAccionPrioridad;
use App\Models\CatLineasAccionEstrategia;
use App\Models\CatLineasAccionPlazo;
use App\Models\CatLineasAccionFrecMedicion;
use App\Models\LineaAccion;
use App\Models\Usuario;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Inertia\Inertia;
use Inertia\Response;

class LineaAccionController extends Controller
{
    public function index(): Response
    {
        $lineas = LineaAccion::with([
            'eje', 'objetivo', 'prioridad',
            'estrategia', 'plazo', 'frecuencia',
            'usuario.organismo',
        ])
            ->orderBy('id', 'desc')
            ->get()
            ->map(fn($l) => [
                'id'                  => $l->id,
                'id_eje'              => $l->id_eje,
                'id_objetivo'         => $l->id_objetivo,
                'id_prioridad'        => $l->id_prioridad,
                'id_estrategia'       => $l->id_estrategia,
                'id_plazo'            => $l->id_plazo,
                'id_frecuencia'       => $l->id_frecuencia,
                'id_usuario'          => $l->id_usuario,
                'numero_eje'          => $l->eje?->numero_eje,
                'eje'                 => $l->eje?->eje,
                'numero_objetivo'     => $l->objetivo?->numero_objetivo,
                'objetivo'            => $l->objetivo?->objetivo,
                'numero_prioridad'    => $l->prioridad?->numero,
                'prioridad'           => $l->prioridad?->prioridad,
                'estrategia'          => $l->estrategia?->estrategia,
                'plazo'               => $l->plazo?->plazo,
                'frecuencia'          => $l->frecuencia?->frecuencia,
                'usuario'             => $l->usuario?->nombre_completo,
                'organismo'           => $l->usuario?->organismo?->siglas
                    ?? $l->usuario?->organismo?->nombre,
                'meta'                => $l->meta,
                'unidad_medida'       => $l->unidad_medida,
                'avance_cuantitativo' => $l->avance_cuantitativo,
                'porcentaje_avance'   => $l->porcentaje_avance,
                'activo'              => $l->activo,
            ]);

        return Inertia::render('Admin/LineasAccion/Index', [
            'lineas'      => $lineas,
            'ejes'        => CatLineasAccionEje::where('activo', true)
                ->orderBy('numero_eje')
                ->get(['id','numero_eje','eje']),
            'estrategias' => CatLineasAccionEstrategia::where('activo', true)
                ->orderBy('estrategia')
                ->get(['id','estrategia']),
            'plazos'      => CatLineasAccionPlazo::where('activo', true)
                ->orderBy('id')
                ->get(['id','plazo']),
            'frecuencias' => CatLineasAccionFrecMedicion::where('activo', true)
                ->orderBy('id')
                ->get(['id','frecuencia']),
            'usuarios'    => Usuario::with('organismo')
                ->where('activo', true)
                ->whereNotNull('id_organismo')
                ->orderBy('nombre')
                ->get()
                ->map(fn($u) => [
                    'id'    => $u->id,
                    'label' => $u->nombre_completo . ' — '
                        . ($u->organismo?->siglas
                            ?? $u->organismo?->nombre
                            ?? 'Sin organismo'),
                ]),
        ]);
    }

    // ── Cascade: objetivos filtrados por eje (llamada fetch desde Vue) ─────────
    public function objetivosPorEje(int $ejeId): JsonResponse
    {
        return response()->json(
            CatLineasAccionObjetivo::where('id_eje', $ejeId)
                ->where('activo', true)
                ->orderBy('numero_objetivo')
                ->get(['id','numero_objetivo','objetivo'])
        );
    }

    // ── Cascade: prioridades filtradas por objetivo ────────────────────────────
    public function prioridadesPorObjetivo(int $objetivoId): JsonResponse
    {
        return response()->json(
            CatLineasAccionPrioridad::where('id_objetivo', $objetivoId)
                ->where('activo', true)
                ->orderBy('numero')
                ->get(['id','numero','prioridad'])
        );
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'id_eje'              => ['required', 'exists:cat_lineas_accion_ejes,id'],
            'id_objetivo'         => ['required', 'exists:cat_lineas_accion_objetivos,id'],
            'id_prioridad'        => ['required', 'exists:cat_lineas_accion_prioridades,id'],
            'id_estrategia'       => ['nullable', 'exists:cat_lineas_accion_estrategias,id'],
            'id_plazo'            => ['nullable', 'exists:cat_lineas_accion_plazos,id'],
            'id_frecuencia'       => ['nullable', 'exists:cat_lineas_accion_frec_medicion,id'],
            'id_usuario'          => ['required', 'exists:usuarios,id'],
            'meta'                => ['nullable', 'numeric', 'min:0'],
            'unidad_medida'       => ['nullable', 'string', 'max:100'],
            'avance_cuantitativo' => ['nullable', 'numeric', 'min:0'],
            'activo'              => ['boolean'],
        ], [
            'id_eje.required'       => 'Debes seleccionar un eje.',
            'id_objetivo.required'  => 'Debes seleccionar un objetivo.',
            'id_prioridad.required' => 'Debes seleccionar una prioridad.',
            'id_usuario.required'   => 'Debes asignar un responsable.',
        ]);

        LineaAccion::create([
            ...$request->only([
                'id_eje','id_objetivo','id_prioridad',
                'id_estrategia','id_plazo','id_frecuencia',
                'id_usuario','meta','unidad_medida','activo',
            ]),
            'avance_cuantitativo' => $request->avance_cuantitativo ?? 0,
        ]);

        return back()->with('success', 'Línea de acción creada correctamente.');
    }

    public function update(Request $request, LineaAccion $lineaAccion): RedirectResponse
    {
        $request->validate([
            'id_eje'              => ['required', 'exists:cat_lineas_accion_ejes,id'],
            'id_objetivo'         => ['required', 'exists:cat_lineas_accion_objetivos,id'],
            'id_prioridad'        => ['required', 'exists:cat_lineas_accion_prioridades,id'],
            'id_estrategia'       => ['nullable', 'exists:cat_lineas_accion_estrategias,id'],
            'id_plazo'            => ['nullable', 'exists:cat_lineas_accion_plazos,id'],
            'id_frecuencia'       => ['nullable', 'exists:cat_lineas_accion_frec_medicion,id'],
            'id_usuario'          => ['required', 'exists:usuarios,id'],
            'meta'                => ['nullable', 'numeric', 'min:0'],
            'unidad_medida'       => ['nullable', 'string', 'max:100'],
            'avance_cuantitativo' => ['nullable', 'numeric', 'min:0'],
            'activo'              => ['boolean'],
        ], [
            'id_eje.required'       => 'Debes seleccionar un eje.',
            'id_objetivo.required'  => 'Debes seleccionar un objetivo.',
            'id_prioridad.required' => 'Debes seleccionar una prioridad.',
            'id_usuario.required'   => 'Debes asignar un responsable.',
        ]);

        $lineaAccion->update([
            ...$request->only([
                'id_eje','id_objetivo','id_prioridad',
                'id_estrategia','id_plazo','id_frecuencia',
                'id_usuario','meta','unidad_medida',
                'avance_cuantitativo','activo',
            ]),
            'fecha_actualizacion' => now(),
        ]);

        return back()->with('success', 'Línea de acción actualizada correctamente.');
    }

    public function destroy(LineaAccion $lineaAccion): RedirectResponse
    {
        if ($lineaAccion->historialAvances()->exists()) {
            return back()->with('error', 'No se puede eliminar porque tiene historial de avances registrado.');
        }

        $lineaAccion->delete();

        return back()->with('success', 'Línea de acción eliminada correctamente.');
    }
}
