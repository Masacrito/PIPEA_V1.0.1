<?php

namespace App\Http\Controllers\Admin\Catalogos;

use App\Http\Controllers\Controller;
use App\Models\CatLineasAccionPrioridad;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CatPrioridadController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Catalogos/Prioridades/Index', [
            'prioridades' => CatLineasAccionPrioridad::orderBy('numero')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'numero'    => ['required', 'integer', 'min:1', 'max:99', 'unique:cat_lineas_accion_prioridades,numero'],
            'prioridad' => ['required', 'string', 'max:1000'],
            'activo'    => ['boolean'],
        ], [
            'numero.required'    => 'El número de prioridad es obligatorio.',
            'numero.unique'      => 'Ya existe una prioridad con ese número.',
            'numero.max'         => 'El número no puede ser mayor a 99.',
            'prioridad.required' => 'La descripción de la prioridad es obligatoria.',
        ]);

        CatLineasAccionPrioridad::create([
            'numero'    => $request->numero,
            'prioridad' => $request->prioridad,
            'activo'    => $request->activo ?? true,
        ]);

        return back()->with('success', 'Prioridad creada correctamente.');
    }

    public function update(Request $request, CatLineasAccionPrioridad $prioridad): RedirectResponse
    {
        $request->validate([
            'numero'    => ['required', 'integer', 'min:1', 'max:99', "unique:cat_lineas_accion_prioridades,numero,{$prioridad->id}"],
            'prioridad' => ['required', 'string', 'max:1000'],
            'activo'    => ['boolean'],
        ], [
            'numero.required'    => 'El número de prioridad es obligatorio.',
            'numero.unique'      => 'Ya existe una prioridad con ese número.',
            'prioridad.required' => 'La descripción de la prioridad es obligatoria.',
        ]);

        $prioridad->update($request->only('numero', 'prioridad', 'activo'));

        return back()->with('success', 'Prioridad actualizada correctamente.');
    }

    public function destroy(CatLineasAccionPrioridad $prioridad): RedirectResponse
    {
        // Solo el usuario id=1 (Super Admin) puede eliminar catálogos
        if (auth()->id() !== 1) {
            return back()->with("error", "Solo el Super Admin puede eliminar catálogos.");
        }

        if ($prioridad->lineasAccion()->exists()) {
            return back()->with('error', 'No se puede eliminar la prioridad porque tiene líneas de acción asociadas.');
        }

        $prioridad->delete();

        return back()->with('success', 'Prioridad eliminada correctamente.');
    }
}
