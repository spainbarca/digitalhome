<?php

namespace App\Policies;

use App\Models\PropiedadInmueble;
use App\Models\User;

class PropiedadInmueblePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, PropiedadInmueble $propiedad): bool
    {
        return $user->persona?->hogar_id === $propiedad->persona?->hogar_id;
    }

    public function create(User $user): bool
    {
        return $user->persona !== null;
    }

    public function update(User $user, PropiedadInmueble $propiedad): bool
    {
        return $user->persona?->hogar_id === $propiedad->persona?->hogar_id;
    }

    public function delete(User $user, PropiedadInmueble $propiedad): bool
    {
        return $user->persona?->hogar_id === $propiedad->persona?->hogar_id;
    }
}
