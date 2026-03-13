<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{



    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $usuario = Auth::user();

        if (! $usuario) {
            return redirect()->route('login');
        }

        $rolUsuario = $usuario->rol?->rol;

        if (! in_array($rolUsuario, $roles)) {
            // devolver 403 limpio
            if ($request->header('X-Inertia')) {
                abort(403, 'No tienes acceso a esta sección.');
            }

            // Si ya está autenticado pero en área incorrecta,
            // redirigir a su área correcta
            return redirect()->to($this->rutaPorRol($rolUsuario));
        }

        return $next($request);
    }

    protected function rutaPorRol(?string $rol): string
    {
        return match ($rol) {
            'SUPER_ADMIN', 'ADMIN_DEPENDENCIA' => route('admin.dashboard'),
            'USUARIO_ORGANISMO'            => route('organismo.dashboard'),
            default                        => route('login'),
        };
    }
}
