<?php

namespace App\Providers;

use App\Models\HistorialAvance;
use App\Models\LineaAccion;
use App\Models\OrganismoImplementador;
use App\Models\Usuario;
use App\Observers\AuditObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Registro del observer de auditoría en todos los modelos requeridos
        LineaAccion::observe(AuditObserver::class);
        HistorialAvance::observe(AuditObserver::class);
        Usuario::observe(AuditObserver::class);
        OrganismoImplementador::observe(AuditObserver::class);
    }
}
