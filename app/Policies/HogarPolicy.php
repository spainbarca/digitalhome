<?php

namespace App\Policies;

use App\Models\Hogar;
use App\Models\User;

class HogarPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Hogar $hogar): bool
    {
        return $hogar->owner_id === $user->id;
    }

    public function create(User $user): bool
    {
        return !Hogar::where('owner_id', $user->id)->exists();
    }

    public function update(User $user, Hogar $hogar): bool
    {
        return $hogar->owner_id === $user->id;
    }

    public function delete(User $user, Hogar $hogar): bool
    {
        return $hogar->owner_id === $user->id;
    }
}
