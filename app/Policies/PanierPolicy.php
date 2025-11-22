<?php

namespace App\Policies;

use App\Models\Panier;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PanierPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Panier $panier): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Panier $panier): bool
    {
        return true;
    }

    public function delete(User $user, Panier $panier): bool
    {
        return true;
    }

    public function restore(User $user, Panier $panier): bool
    {
        return true;
    }

    public function forceDelete(User $user, Panier $panier): bool
    {
        return true;
    }
}
