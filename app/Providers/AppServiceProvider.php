<?php

namespace App\Providers;

use App\Models\CuentaServicio;
use App\Models\Empresa;
use App\Models\Hogar;
use App\Models\HogarMiembro;
use App\Models\Persona;
use App\Models\PropiedadInmueble;
use App\Models\Recordatorio;
use App\Models\Sector;
use App\Policies\CuentaServicioPolicy;
use App\Policies\EmpresaPolicy;
use App\Policies\HogarMiembroPolicy;
use App\Policies\HogarPolicy;
use App\Policies\PersonaPolicy;
use App\Policies\PropiedadInmueblePolicy;
use App\Policies\SectorPolicy;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('partials.dashboard.sidebar', function ($view) {
            $badge = 0;
            if (Auth::check() && Auth::user()->persona?->hogar_id) {
                $badge = Recordatorio::where('hogar_id', Auth::user()->persona->hogar_id)
                    ->where('estado', 'pendiente')
                    ->where('fecha_vencimiento', '<=', now()->addDays(7))
                    ->count();
            }
            $view->with('recordatoriosBadge', $badge);
        });

        Gate::policy(Persona::class, PersonaPolicy::class);
        Gate::policy(Hogar::class, HogarPolicy::class);
        Gate::policy(HogarMiembro::class, HogarMiembroPolicy::class);
        Gate::policy(PropiedadInmueble::class, PropiedadInmueblePolicy::class);
        Gate::policy(CuentaServicio::class, CuentaServicioPolicy::class);
        Gate::policy(Empresa::class, EmpresaPolicy::class);
        Gate::policy(Sector::class, SectorPolicy::class);
    }
}
