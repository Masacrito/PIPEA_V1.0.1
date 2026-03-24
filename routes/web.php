<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// ─── Página pública ───────────────────────────────────────────────────────────
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
    ]);
})->name('home');

// ─── Solo invitados ───────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('login',  [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

// ─── Logout ───────────────────────────────────────────────────────────────────
Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

// ─── Rutas protegidas ─────────────────────────────────────────────────────────
Route::middleware(['auth', 'activo'])->group(function () {

    // ── ADMIN ─────────────────────────────────────────────────────────────────
    Route::middleware('role:ADMIN')
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {

            Route::get('dashboard', [\App\Http\Controllers\Admin\Dashboard\AdminDashboardController::class, 'index'])
                ->name('dashboard');

            // ── Catálogos PI-PEA ──────────────────────────────────────────────
            Route::prefix('catalogos')->name('catalogos.')->group(function () {

                Route::resource('ejes', \App\Http\Controllers\Admin\Catalogos\CatEjeController::class)
                    ->names('ejes')->only(['index','store','update','destroy']);

                Route::resource('objetivos', \App\Http\Controllers\Admin\Catalogos\CatObjetivoController::class)
                    ->names('objetivos')->only(['index','store','update','destroy']);

                Route::resource('prioridades', \App\Http\Controllers\Admin\Catalogos\CatPrioridadController::class)
                    ->names('prioridades')->only(['index','store','update','destroy']);

                Route::resource('estrategias', \App\Http\Controllers\Admin\Catalogos\CatEstrategiaController::class)
                    ->names('estrategias')->only(['index','store','update','destroy']);

                Route::resource('plazos', \App\Http\Controllers\Admin\Catalogos\CatPlazoController::class)
                    ->names('plazos')->only(['index','store','update','destroy']);

                Route::resource('frecuencias', \App\Http\Controllers\Admin\Catalogos\CatFrecuenciaController::class)
                    ->names('frecuencias')->only(['index','store','update','destroy']);
            });

            // ── Líneas de acción ──────────────────────────────────────────────
            Route::prefix('lineas-accion')->name('lineas.')->group(function () {

                Route::get('/',             [\App\Http\Controllers\Admin\LineasAccion\LineaAccionController::class, 'index'])->name('index');
                Route::post('/',            [\App\Http\Controllers\Admin\LineasAccion\LineaAccionController::class, 'store'])->name('store');
                Route::put('/{lineaAccion}',[\App\Http\Controllers\Admin\LineasAccion\LineaAccionController::class, 'update'])->name('update');
                Route::delete('/{lineaAccion}',[\App\Http\Controllers\Admin\LineasAccion\LineaAccionController::class, 'destroy'])->name('destroy');

                Route::get('/cascade/objetivos/{eje}',
                    [\App\Http\Controllers\Admin\LineasAccion\LineaAccionController::class, 'objetivosPorEje'])
                    ->name('cascade.objetivos');

                Route::get('/cascade/prioridades/{objetivo}',
                    [\App\Http\Controllers\Admin\LineasAccion\LineaAccionController::class, 'prioridadesPorObjetivo'])
                    ->name('cascade.prioridades');
            });

            // ── Organismos ────────────────────────────────────────────────────
            Route::resource('organismos', \App\Http\Controllers\Admin\Organismos\OrganismoController::class)
                ->names('organismos')->only(['index','store','update','destroy']);

            // ── Usuarios ──────────────────────────────────────────────────────
            Route::resource('usuarios', \App\Http\Controllers\Admin\Usuarios\UsuarioController::class)
                ->names('usuarios')->only(['index','store','update','destroy']);
        });

    // ── ORGANISMO IMPLEMENTADOR ───────────────────────────────────────────────
    Route::middleware('role:ORGANISMO IMPLEMENTADOR')
        ->prefix('organismo')
        ->name('organismo.')
        ->group(function () {

            Route::get('dashboard', fn () => Inertia::render('Organismo/Dashboard'))
                ->name('dashboard');
        });

});
