<?php

namespace App\Policies;

use App\Models\Salle;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SallePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Salle $salle): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Salle $salle): bool
    {
        return true;
    }

    public function delete(User $user, Salle $salle): bool
    {
        return true;
    }

    public function restore(User $user, Salle $salle): bool
    {
        return true;
    }

    public function forceDelete(User $user, Salle $salle): bool
    {
        return true;
    }
}
