<?php

namespace App\Policies;

use App\Models\Hogar;
use App\Models\HogarMiembro;
use App\Models\User;

class HogarMiembroPolicy
{
    public function viewAny(User $user): bool
    {
        return Hogar::where('owner_id', $user->id)->exists();
    }

    public function view(User $user, HogarMiembro $miembro): bool
    {
        return $miembro->hogar->owner_id === $user->id;
    }

    public function create(User $user, Hogar $hogar): bool
    {
        return $user->persona?->hogar_id === $hogar->id
            || $hogar->owner_id === $user->id;
    }

    public function update(User $user, HogarMiembro $miembro): bool
    {
        return $miembro->hogar->owner_id === $user->id;
    }

    public function delete(User $user, HogarMiembro $miembro): bool
    {
        return $miembro->hogar->owner_id === $user->id;
    }
}
