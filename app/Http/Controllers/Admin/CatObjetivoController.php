<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
            'objetivos' => CatLineasAccionObjetivo::orderBy('numero_objetivo')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'numero_objetivo' => ['required', 'integer', 'min:1', 'max:99', 'unique:cat_lineas_accion_objetivos,numero_objetivo'],
            'objetivo'        => ['required', 'string', 'max:500'],
            'activo'          => ['boolean'],
        ], [
            'numero_objetivo.required' => 'El número de objetivo es obligatorio.',
            'numero_objetivo.unique'   => 'Ya existe un objetivo con ese número.',
            'objetivo.required'        => 'La descripción del objetivo es obligatoria.',
        ]);

        CatLineasAccionObjetivo::create([
            'numero_objetivo' => $request->numero_objetivo,
            'objetivo'        => $request->objetivo,
            'activo'          => $request->activo ?? true,
        ]);

        return back()->with('success', 'Objetivo creado correctamente.');
    }

    public function update(Request $request, CatLineasAccionObjetivo $objetivo): RedirectResponse
    {
        $request->validate([
            'numero_objetivo' => ['required', 'integer', 'min:1', 'max:99', "unique:cat_lineas_accion_objetivos,numero_objetivo,{$objetivo->id}"],
            'objetivo'        => ['required', 'string', 'max:500'],
            'activo'          => ['boolean'],
        ], [
            'numero_objetivo.required' => 'El número de objetivo es obligatorio.',
            'numero_objetivo.unique'   => 'Ya existe un objetivo con ese número.',
            'objetivo.required'        => 'La descripción del objetivo es obligatoria.',
        ]);

        $objetivo->update($request->only('numero_objetivo', 'objetivo', 'activo'));

        return back()->with('success', 'Objetivo actualizado correctamente.');
    }

    public function destroy(CatLineasAccionObjetivo $objetivo): RedirectResponse
    {
        if ($objetivo->lineasAccion()->exists()) {
            return back()->with('error', 'No se puede eliminar el objetivo porque tiene líneas de acción asociadas.');
        }

        $objetivo->delete();

        return back()->with('success', 'Objetivo eliminado correctamente.');
    }
}
