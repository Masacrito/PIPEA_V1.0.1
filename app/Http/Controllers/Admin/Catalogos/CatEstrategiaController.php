<?php

namespace App\Http\Controllers\Admin\Catalogos;
use App\Http\Controllers\Controller;
use App\Models\CatLineasAccionEstrategia;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CatEstrategiaController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Catalogos/Estrategias/Index', [
            'estrategias' => CatLineasAccionEstrategia::orderBy('id')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'estrategia' => ['required', 'string', 'max:255', 'unique:cat_lineas_accion_estrategias,estrategia'],
            'activo'     => ['boolean'],
        ], [
            'estrategia.required' => 'El nombre de la estrategia es obligatorio.',
            'estrategia.unique'   => 'Ya existe una estrategia con ese nombre.',
        ]);

        CatLineasAccionEstrategia::create([
            'estrategia' => $request->estrategia,
            'activo'     => $request->activo ?? true,
        ]);

        return back()->with('success', 'Estrategia creada correctamente.');
    }

    public function update(Request $request, CatLineasAccionEstrategia $estrategia): RedirectResponse
    {
        $request->validate([
            'estrategia' => ['required', 'string', 'max:255', "unique:cat_lineas_accion_estrategias,estrategia,{$estrategia->id}"],
            'activo'     => ['boolean'],
        ], [
            'estrategia.required' => 'El nombre de la estrategia es obligatorio.',
            'estrategia.unique'   => 'Ya existe una estrategia con ese nombre.',
        ]);

        $estrategia->update($request->only('estrategia', 'activo'));

        return back()->with('success', 'Estrategia actualizada correctamente.');
    }

    public function destroy(CatLineasAccionEstrategia $estrategia): RedirectResponse
    {
        // Solo el usuario id=1 (Super Admin) puede eliminar catálogos
        if (auth()->id() !== 1) {
            return back()->with("error", "Solo el Super Admin puede eliminar catálogos.");
        }

        if ($estrategia->lineasAccion()->exists()) {
            return back()->with('error', 'No se puede eliminar la estrategia porque tiene líneas de acción asociadas.');
        }

        $estrategia->delete();

        return back()->with('success', 'Estrategia eliminada correctamente.');
    }
}
