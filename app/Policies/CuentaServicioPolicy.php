<?php

namespace App\Policies;

use App\Models\CuentaServicio;
use App\Models\PropiedadInmueble;
use App\Models\User;

class CuentaServicioPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->persona !== null;
    }

    public function view(User $user, CuentaServicio $cuenta): bool
    {
        return $user->persona?->hogar_id === $cuenta->propiedad?->persona?->hogar_id;
    }

    public function create(User $user, PropiedadInmueble $propiedad): bool
    {
        return $user->persona?->hogar_id === $propiedad->persona?->hogar_id;
    }

    public function update(User $user, CuentaServicio $cuenta): bool
    {
        return $user->persona?->hogar_id === $cuenta->propiedad?->persona?->hogar_id;
    }

    public function delete(User $user, CuentaServicio $cuenta): bool
    {
        return $user->persona?->hogar_id === $cuenta->propiedad?->persona?->hogar_id;
    }
}
