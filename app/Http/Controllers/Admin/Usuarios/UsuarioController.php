<?php

namespace App\Http\Controllers\Admin\Usuarios;

use App\Http\Controllers\Controller;
use App\Models\OrganismoImplementador;
use App\Models\Rol;
use App\Models\Usuario;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class UsuarioController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Usuarios/Index', [
            'usuarios' => Usuario::with(['rol', 'organismo'])
                ->orderBy('nombre')
                ->get()
                ->map(fn($u) => [
                    'id'               => $u->id,
                    'nombre'           => $u->nombre,
                    'primer_apellido'  => $u->primer_apellido,
                    'segundo_apellido' => $u->segundo_apellido,
                    'nombre_completo'  => $u->nombre_completo,
                    'email'            => $u->email,
                    'curp'             => $u->curp,
                    'rfc'              => $u->rfc,
                    'activo'           => $u->activo,
                    'fecha_registro'   => $u->fecha_registro?->format('d/m/Y'),
                    'id_rol'           => $u->id_rol,
                    'id_organismo'     => $u->id_organismo,
                    'rol'              => $u->rol?->rol,
                    'organismo'        => $u->organismo?->siglas ?? $u->organismo?->nombre,
                    'es_superadmin'    => $u->id === 1, // ← indica al front que es protegido
                ]),
            'roles'      => Rol::where('activo', true)->orderBy('id')->get(['id','rol']),
            'organismos' => OrganismoImplementador::where('activo', true)
                ->orderBy('nombre')
                ->get()
                ->map(fn($o) => [
                    'id'    => $o->id,
                    'label' => ($o->siglas ? "[{$o->siglas}] " : '') . $o->nombre,
                ]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'id_rol'          => ['required', 'exists:roles,id'],
            'id_organismo'    => ['required', 'exists:organismos_implementadores,id'],
            'nombre'          => ['required', 'string', 'max:100'],
            'primer_apellido' => ['required', 'string', 'max:100'],
            'segundo_apellido'=> ['nullable', 'string', 'max:100'],
            'curp'            => ['nullable', 'string', 'size:18', 'unique:usuarios,curp'],
            'rfc'             => ['nullable', 'string', 'min:12', 'max:13', 'unique:usuarios,rfc'],
            'email'           => ['required', 'email', 'unique:usuarios,email'],
            'password'        => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'id_rol.required'          => 'Debes asignar un rol.',
            'id_organismo.required'    => 'Debes asignar un organismo.',
            'nombre.required'          => 'El nombre es obligatorio.',
            'primer_apellido.required' => 'El primer apellido es obligatorio.',
            'curp.size'                => 'La CURP debe tener exactamente 18 caracteres.',
            'curp.unique'              => 'Esta CURP ya está registrada.',
            'rfc.unique'               => 'Este RFC ya está registrado.',
            'email.required'           => 'El correo electrónico es obligatorio.',
            'email.unique'             => 'Este correo ya está registrado.',
            'password.required'        => 'La contraseña es obligatoria.',
            'password.min'             => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed'       => 'Las contraseñas no coinciden.',
        ]);

        Usuario::create([
            'id_rol'          => $request->id_rol,
            'id_organismo'    => $request->id_organismo,
            'nombre'          => $request->nombre,
            'primer_apellido' => $request->primer_apellido,
            'segundo_apellido'=> $request->segundo_apellido,
            'curp'            => $request->curp ? strtoupper($request->curp) : null,
            'rfc'             => $request->rfc  ? strtoupper($request->rfc)  : null,
            'email'           => strtolower($request->email),
            'password'        => $request->password,
            'activo'          => $request->activo ?? true,
        ]);

        return back()->with('success', 'Usuario creado correctamente.');
    }

    public function update(Request $request, Usuario $usuario): RedirectResponse
    {
        // El usuario id=1 (Super Admin) no se puede modificar
        if ($usuario->id === 1) {
            return back()->with('error', 'El usuario Super Admin no puede ser modificado.');
        }

        $request->validate([
            'id_rol'          => ['required', 'exists:roles,id'],
            'id_organismo'    => ['required', 'exists:organismos_implementadores,id'],
            'nombre'          => ['required', 'string', 'max:100'],
            'primer_apellido' => ['required', 'string', 'max:100'],
            'segundo_apellido'=> ['nullable', 'string', 'max:100'],
            'curp'            => ['nullable', 'string', 'size:18', "unique:usuarios,curp,{$usuario->id}"],
            'rfc'             => ['nullable', 'string', 'min:12', 'max:13', "unique:usuarios,rfc,{$usuario->id}"],
            'email'           => ['required', 'email', "unique:usuarios,email,{$usuario->id}"],
            'password'        => ['nullable', 'string', 'min:8', 'confirmed'],
            'activo'          => ['boolean'],
        ], [
            'id_rol.required'          => 'Debes asignar un rol.',
            'id_organismo.required'    => 'Debes asignar un organismo.',
            'nombre.required'          => 'El nombre es obligatorio.',
            'primer_apellido.required' => 'El primer apellido es obligatorio.',
            'curp.size'                => 'La CURP debe tener exactamente 18 caracteres.',
            'curp.unique'              => 'Esta CURP ya está registrada.',
            'rfc.unique'               => 'Este RFC ya está registrado.',
            'email.unique'             => 'Este correo ya está registrado.',
            'password.min'             => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed'       => 'Las contraseñas no coinciden.',
        ]);

        $data = $request->only([
            'id_rol', 'id_organismo', 'nombre', 'primer_apellido',
            'segundo_apellido', 'email', 'activo',
        ]);

        if ($request->curp)            $data['curp']     = strtoupper($request->curp);
        if ($request->rfc)             $data['rfc']      = strtoupper($request->rfc);
        if ($request->filled('password')) $data['password'] = $request->password;

        $usuario->update($data);

        return back()->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy(Usuario $usuario): RedirectResponse
    {
        // El usuario id=1 (Super Admin) no se puede eliminar
        if ($usuario->id === 1) {
            return back()->with('error', 'El usuario Super Admin no puede ser eliminado.');
        }

        if ($usuario->lineasAccion()->exists()) {
            return back()->with('error', 'No se puede eliminar el usuario porque tiene líneas de acción asignadas.');
        }

        $usuario->delete();

        return back()->with('success', 'Usuario eliminado correctamente.');
    }
}
