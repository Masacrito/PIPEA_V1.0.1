<?php

namespace App\Http\Controllers\Admin\Catalogos;

use App\Http\Controllers\Controller;
use App\Models\CatLineasAccionEje;
use App\Models\CatLineasAccionObjetivo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CatObjetivoController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Catalogos/Objetivos/Index', [
            'objetivos' => CatLineasAccionObjetivo::with('eje')
                ->orderBy('numero_objetivo')
                ->get(),
            'ejes' => CatLineasAccionEje::where('activo', true)
                ->orderBy('numero_eje')
                ->get(['id', 'numero_eje', 'eje']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'id_eje'          => ['required', 'exists:cat_lineas_accion_ejes,id'],
            'numero_objetivo' => ['required', 'integer', 'min:1', 'max:99', 'unique:cat_lineas_accion_objetivos,numero_objetivo'],
            'objetivo'        => ['required', 'string', 'max:500'],
            'activo'          => ['boolean'],
        ], [
            'id_eje.required'          => 'Debes seleccionar un eje.',
            'id_eje.exists'            => 'El eje seleccionado no existe.',
            'numero_objetivo.required' => 'El número de objetivo es obligatorio.',
            'numero_objetivo.unique'   => 'Ya existe un objetivo con ese número.',
            'objetivo.required'        => 'La descripción del objetivo es obligatoria.',
        ]);

        CatLineasAccionObjetivo::create($request->only('id_eje', 'numero_objetivo', 'objetivo', 'activo'));

        return back()->with('success', 'Objetivo creado correctamente.');
    }

    public function update(Request $request, CatLineasAccionObjetivo $objetivo): RedirectResponse
    {
        $request->validate([
            'id_eje'          => ['required', 'exists:cat_lineas_accion_ejes,id'],
            'numero_objetivo' => ['required', 'integer', 'min:1', 'max:99', "unique:cat_lineas_accion_objetivos,numero_objetivo,{$objetivo->id}"],
            'objetivo'        => ['required', 'string', 'max:500'],
            'activo'          => ['boolean'],
        ], [
            'id_eje.required'          => 'Debes seleccionar un eje.',
            'numero_objetivo.required' => 'El número de objetivo es obligatorio.',
            'numero_objetivo.unique'   => 'Ya existe un objetivo con ese número.',
            'objetivo.required'        => 'La descripción del objetivo es obligatoria.',
        ]);

        $objetivo->update($request->only('id_eje', 'numero_objetivo', 'objetivo', 'activo'));

        return back()->with('success', 'Objetivo actualizado correctamente.');
    }

    public function destroy(CatLineasAccionObjetivo $objetivo): RedirectResponse
    {
        // Solo el usuario id=1 (Super Admin) puede eliminar catálogos
        if (auth()->id() !== 1) {
            return back()->with("error", "Solo el Super Admin puede eliminar catálogos.");
        }

        if ($objetivo->lineasAccion()->exists()) {
            return back()->with('error', 'No se puede eliminar el objetivo porque tiene líneas de acción asociadas.');
        }

        if ($objetivo->prioridades()->exists()) {
            return back()->with('error', 'No se puede eliminar el objetivo porque tiene prioridades asociadas.');
        }

        $objetivo->delete();

        return back()->with('success', 'Objetivo eliminado correctamente.');
    }
}
