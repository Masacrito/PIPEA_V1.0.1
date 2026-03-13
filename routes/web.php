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

// ─── Rutas protegidas ────────────────────────────────────────────────────────
Route::middleware(['auth', 'activo'])->group(function () {

    // ── ADMIN — SUPER_ADMIN + ADMIN_DEPENDENCIA ───────────────────────────────
    Route::middleware('role:SUPER_ADMIN,ADMIN_DEPENDENCIA')
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {

            Route::get('dashboard', fn () => Inertia::render('Admin/Dashboard'))
                ->name('dashboard');

            // ── Catálogos ─────────────────────────────────────────────────────
            Route::prefix('catalogos')->name('catalogos.')->group(function () {

                Route::resource('ejes', \App\Http\Controllers\Admin\CatEjeController::class)
                    ->names('ejes')
                    ->only(['index', 'store', 'update', 'destroy']);

                // TODO: descomentar conforme se creen los controladores
                Route::resource('objetivos', \App\Http\Controllers\Admin\CatObjetivoController::class)->names('objetivos')->only(['index','store','update','destroy']);
                Route::resource('prioridades', \App\Http\Controllers\Admin\CatPrioridadController::class)->names('prioridades')->only(['index','store','update','destroy']);
                Route::resource('estrategias', \App\Http\Controllers\Admin\CatEstrategiaController::class)->names('estrategias')->only(['index','store','update','destroy']);
                Route::resource('plazos', \App\Http\Controllers\Admin\CatPlazoController::class)->names('plazos')->only(['index','store','update','destroy']);
                Route::resource('frecuencias', \App\Http\Controllers\Admin\CatFrecuenciaController::class)->names('frecuencias')->only(['index','store','update','destroy']);
            });

            // Route::resource('organismos', \App\Http\Controllers\Admin\OrganismoController::class)->names('organismos');
            // Route::resource('usuarios',   \App\Http\Controllers\Admin\UsuarioController::class)->names('usuarios');
            // Route::middleware('role:SUPER_ADMIN')->group(function () {
            //     Route::resource('roles', \App\Http\Controllers\Admin\RolController::class)->names('roles');
            //     Route::get('logs', fn () => Inertia::render('Admin/Logs/Index'))->name('logs.index');
            // });
        });

    // ── ORGANISMO — USUARIO_ORGANISMO ─────────────────────────────────────────
    Route::middleware('role:USUARIO_ORGANISMO')
        ->prefix('organismo')
        ->name('organismo.')
        ->group(function () {

            Route::get('dashboard', fn () => Inertia::render('Organismo/Dashboard'))
                ->name('dashboard');

            // Route::resource('lineas', \App\Http\Controllers\Organismo\LineaAccionController::class)->names('lineas');
            // Route::get('avances/registrar', [\App\Http\Controllers\Organismo\AvanceController::class, 'create'])->name('avances.create');
            // Route::post('avances',          [\App\Http\Controllers\Organismo\AvanceController::class, 'store'])->name('avances.store');
            // Route::get('evidencias',        fn () => Inertia::render('Organismo/Evidencias/Index'))->name('evidencias.index');
            // Route::get('reportes/avance',   fn () => Inertia::render('Organismo/Reportes/Avance'))->name('reportes.avance');
            // Route::get('perfil',            [\App\Http\Controllers\Organismo\PerfilController::class, 'edit'])->name('perfil');
            // Route::patch('perfil',          [\App\Http\Controllers\Organismo\PerfilController::class, 'update'])->name('perfil.update');
        });

}); 
