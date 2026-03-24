<?php

namespace App\Http\Controllers\Admin\Organismos;
use App\Http\Controllers\Controller;
use App\Models\CatAmbito;
use App\Models\CatNivel;
use App\Models\CatPoder;
use App\Models\OrganismoImplementador;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class OrganismoController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Organismos/Index', [
            'organismos' => OrganismoImplementador::with(['ambito','nivel','poder'])
                ->orderBy('nombre')
                ->get()
                ->map(fn($o) => [
                    'id'      => $o->id,
                    'nombre'  => $o->nombre,
                    'siglas'  => $o->siglas,
                    'activo'  => $o->activo,
                    'ambito'  => $o->ambito?->ambito,
                    'nivel'   => $o->nivel?->nivel,
                    'poder'   => $o->poder?->poder,
                    'id_ambito' => $o->id_ambito,
                    'id_nivel'  => $o->id_nivel,
                    'id_poder'  => $o->id_poder,
                ]),
            'ambitos'   => CatAmbito::where('activo', true)->orderBy('ambito')->get(['id','ambito']),
            'niveles'   => CatNivel::where('activo', true)->orderBy('nivel')->get(['id','nivel']),
            'poderes'   => CatPoder::where('activo', true)->orderBy('poder')->get(['id','poder']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nombre'     => ['required', 'string', 'max:255', 'unique:organismos_implementadores,nombre'],
            'siglas'     => ['nullable', 'string', 'max:20'],
            'id_ambito'  => ['nullable', 'exists:cat_ambitos,id'],
            'id_nivel'   => ['nullable', 'exists:cat_niveles,id'],
            'id_poder'   => ['nullable', 'exists:cat_poderes,id'],
            'activo'     => ['boolean'],
        ], [
            'nombre.required' => 'El nombre del organismo es obligatorio.',
            'nombre.unique'   => 'Ya existe un organismo con ese nombre.',
        ]);

        OrganismoImplementador::create($request->only(
            'nombre', 'siglas', 'id_ambito', 'id_nivel', 'id_poder', 'activo'
        ));

        return back()->with('success', 'Organismo creado correctamente.');
    }

    public function update(Request $request, OrganismoImplementador $organismo): RedirectResponse
    {
        $request->validate([
            'nombre'    => ['required', 'string', 'max:255', "unique:organismos_implementadores,nombre,{$organismo->id}"],
            'siglas'    => ['nullable', 'string', 'max:20'],
            'id_ambito' => ['nullable', 'exists:cat_ambitos,id'],
            'id_nivel'  => ['nullable', 'exists:cat_niveles,id'],
            'id_poder'  => ['nullable', 'exists:cat_poderes,id'],
            'activo'    => ['boolean'],
        ], [
            'nombre.required' => 'El nombre del organismo es obligatorio.',
            'nombre.unique'   => 'Ya existe un organismo con ese nombre.',
        ]);

        $organismo->update($request->only(
            'nombre', 'siglas', 'id_ambito', 'id_nivel', 'id_poder', 'activo'
        ));

        return back()->with('success', 'Organismo actualizado correctamente.');
    }

    public function destroy(OrganismoImplementador $organismo): RedirectResponse
    {
        if ($organismo->usuarios()->exists()) {
            return back()->with('error', 'No se puede eliminar el organismo porque tiene usuarios asignados.');
        }

        if ($organismo->lineasAccion()->exists()) {
            return back()->with('error', 'No se puede eliminar el organismo porque tiene líneas de acción asociadas.');
        }

        $organismo->delete();

        return back()->with('success', 'Organismo eliminado correctamente.');
    }
}
