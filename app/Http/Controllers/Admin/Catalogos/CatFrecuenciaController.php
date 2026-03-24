<?php

namespace App\Http\Controllers\Admin\Catalogos;

use App\Http\Controllers\Controller;
use App\Models\CatLineasAccionFrecMedicion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CatFrecuenciaController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Catalogos/Frecuencias/Index', [
            'frecuencias' => CatLineasAccionFrecMedicion::orderBy('id')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'frecuencia' => ['required', 'string', 'max:100', 'unique:cat_lineas_accion_frec_medicion,frecuencia'],
            'activo'     => ['boolean'],
        ], [
            'frecuencia.required' => 'El nombre de la frecuencia es obligatorio.',
            'frecuencia.unique'   => 'Ya existe una frecuencia con ese nombre.',
        ]);

        CatLineasAccionFrecMedicion::create([
            'frecuencia' => $request->frecuencia,
            'activo'     => $request->activo ?? true,
        ]);

        return back()->with('success', 'Frecuencia creada correctamente.');
    }

    public function update(Request $request, CatLineasAccionFrecMedicion $frecuencia): RedirectResponse
    {
        $request->validate([
            'frecuencia' => ['required', 'string', 'max:100', "unique:cat_lineas_accion_frec_medicion,frecuencia,{$frecuencia->id}"],
            'activo'     => ['boolean'],
        ], [
            'frecuencia.required' => 'El nombre de la frecuencia es obligatorio.',
            'frecuencia.unique'   => 'Ya existe una frecuencia con ese nombre.',
        ]);

        $frecuencia->update($request->only('frecuencia', 'activo'));

        return back()->with('success', 'Frecuencia actualizada correctamente.');
    }

    public function destroy(CatLineasAccionFrecMedicion $frecuencia): RedirectResponse
    {
        if ($frecuencia->lineasAccion()->exists()) {
            return back()->with('error', 'No se puede eliminar la frecuencia porque tiene líneas de acción asociadas.');
        }

        $frecuencia->delete();

        return back()->with('success', 'Frecuencia eliminada correctamente.');
    }
}
