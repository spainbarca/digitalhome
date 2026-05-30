<?php

namespace App\Policies;

use App\Models\Empresa;
use App\Models\User;

class EmpresaPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Empresa $empresa): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Empresa $empresa): bool
    {
        return true;
    }

    public function delete(User $user, Empresa $empresa): bool
    {
        return true;
    }

    public function restore(User $user, Empresa $empresa): bool
    {
        return true;
    }

    public function forceDelete(User $user, Empresa $empresa): bool
    {
        return false;
    }
}
