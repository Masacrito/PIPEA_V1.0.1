<?php

namespace App\Http\Controllers\Admin\Catalogos;

use App\Http\Controllers\Controller;
use App\Models\CatLineasAccionPlazo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CatPlazoController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Catalogos/Plazos/Index', [
            'plazos' => CatLineasAccionPlazo::orderBy('id')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'plazo'  => ['required', 'string', 'max:100', 'unique:cat_lineas_accion_plazos,plazo'],
            'activo' => ['boolean'],
        ], [
            'plazo.required' => 'El nombre del plazo es obligatorio.',
            'plazo.unique'   => 'Ya existe un plazo con ese nombre.',
        ]);

        CatLineasAccionPlazo::create([
            'plazo'  => $request->plazo,
            'activo' => $request->activo ?? true,
        ]);

        return back()->with('success', 'Plazo creado correctamente.');
    }

    public function update(Request $request, CatLineasAccionPlazo $plazo): RedirectResponse
    {
        $request->validate([
            'plazo'  => ['required', 'string', 'max:100', "unique:cat_lineas_accion_plazos,plazo,{$plazo->id}"],
            'activo' => ['boolean'],
        ], [
            'plazo.required' => 'El nombre del plazo es obligatorio.',
            'plazo.unique'   => 'Ya existe un plazo con ese nombre.',
        ]);

        $plazo->update($request->only('plazo', 'activo'));

        return back()->with('success', 'Plazo actualizado correctamente.');
    }

    public function destroy(CatLineasAccionPlazo $plazo): RedirectResponse
    {
//        // Solo el usuario id=1 (Super Admin) puede eliminar catálogos
//        if (auth()->id() !== 1) {
//            return back()->with("error", "Solo el Super Admin puede eliminar catálogos.");
//        }

        if ($plazo->lineasAccion()->exists()) {
            return back()->with('error', 'No se puede eliminar el plazo porque tiene líneas de acción asociadas.');
        }

        $plazo->delete();

        return back()->with('success', 'Plazo eliminado correctamente.');
    }
}
