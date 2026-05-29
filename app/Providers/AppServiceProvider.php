<?php

namespace App\Providers;

use App\Models\CuentaServicio;
use App\Models\Hogar;
use App\Models\HogarMiembro;
use App\Models\Persona;
use App\Models\PropiedadInmueble;
use App\Policies\CuentaServicioPolicy;
use App\Policies\HogarMiembroPolicy;
use App\Policies\HogarPolicy;
use App\Policies\PersonaPolicy;
use App\Policies\PropiedadInmueblePolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::policy(Persona::class, PersonaPolicy::class);
        Gate::policy(Hogar::class, HogarPolicy::class);
        Gate::policy(HogarMiembro::class, HogarMiembroPolicy::class);
        Gate::policy(PropiedadInmueble::class, PropiedadInmueblePolicy::class);
        Gate::policy(CuentaServicio::class, CuentaServicioPolicy::class);
    }
}
