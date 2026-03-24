<?php

namespace App\Http\Controllers\Admin\Catalogos;

use App\Http\Controllers\Controller;
use App\Models\CatLineasAccionEje;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CatEjeController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Catalogos/Ejes/Index', [
            'ejes' => CatLineasAccionEje::orderBy('numero_eje')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'numero_eje' => ['required', 'integer', 'min:1', 'max:99', 'unique:cat_lineas_accion_ejes,numero_eje'],
            'eje'        => ['required', 'string', 'max:255', 'unique:cat_lineas_accion_ejes,eje'],
            'activo'     => ['boolean'],
        ], [
            'numero_eje.required' => 'El número de eje es obligatorio.',
            'numero_eje.unique'   => 'Ya existe un eje con ese número.',
            'eje.required'        => 'El nombre del eje es obligatorio.',
            'eje.unique'          => 'Ya existe un eje con ese nombre.',
        ]);

        CatLineasAccionEje::create([
            'numero_eje' => $request->numero_eje,
            'eje'        => $request->eje,
            'activo'     => $request->activo ?? true,
        ]);

        return back()->with('success', 'Eje creado correctamente.');
    }

    public function update(Request $request, CatLineasAccionEje $eje): RedirectResponse
    {
        $request->validate([
            'numero_eje' => ['required', 'integer', 'min:1', 'max:99', "unique:cat_lineas_accion_ejes,numero_eje,{$eje->id}"],
            'eje'        => ['required', 'string', 'max:255', "unique:cat_lineas_accion_ejes,eje,{$eje->id}"],
            'activo'     => ['boolean'],
        ], [
            'numero_eje.required' => 'El número de eje es obligatorio.',
            'numero_eje.unique'   => 'Ya existe un eje con ese número.',
            'eje.required'        => 'El nombre del eje es obligatorio.',
            'eje.unique'          => 'Ya existe un eje con ese nombre.',
        ]);

        $eje->update($request->only('numero_eje', 'eje', 'activo'));

        return back()->with('success', 'Eje actualizado correctamente.');
    }

    public function destroy(CatLineasAccionEje $eje): RedirectResponse
    {
        // Solo el usuario id=1 (Super Admin) puede eliminar catálogos
        if (auth()->id() !== 1) {
            return back()->with("error", "Solo el Super Admin puede eliminar catálogos.");
        }

        if ($eje->lineasAccion()->exists()) {
            return back()->with('error', 'No se puede eliminar el eje porque tiene líneas de acción asociadas.');
        }

        $eje->delete();

        return back()->with('success', 'Eje eliminado correctamente.');
    }
}
