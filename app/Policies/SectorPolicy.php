<?php

namespace App\Policies;

use App\Models\Sector;
use App\Models\User;

class SectorPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Sector $sector): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Sector $sector): bool
    {
        return true;
    }

    public function delete(User $user, Sector $sector): bool
    {
        return true;
    }

    public function restore(User $user, Sector $sector): bool
    {
        return true;
    }

    public function forceDelete(User $user, Sector $sector): bool
    {
        return false;
    }
}
