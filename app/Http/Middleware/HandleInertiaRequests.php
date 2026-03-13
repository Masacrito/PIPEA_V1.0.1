<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $request->user() ? [
                    'id'              => $request->user()->id,
                    'nombre'          => $request->user()->nombre,
                    'primer_apellido' => $request->user()->primer_apellido,
                    'email'           => $request->user()->email,
                    'id_organismo'    => $request->user()->id_organismo,
                    'organismo'       => $request->user()->organismo?->only('id', 'nombre'),
                    'rol'             => $request->user()->rol?->only('id', 'rol'),
                ] : null,
            ],

            // Flash messages disponibles en todas las páginas
            'flash' => [
                'success' => session('success'),
                'error'   => session('error'),
                'warning' => session('warning'),
            ],
        ]);
    }
}
