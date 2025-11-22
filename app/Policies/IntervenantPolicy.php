<?php

namespace App\Policies;

use App\Models\Intervenant;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class IntervenantPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Intervenant $intervenant): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Intervenant $intervenant): bool
    {
        return true;
    }

    public function delete(User $user, Intervenant $intervenant): bool
    {
        return true;
    }

    public function restore(User $user, Intervenant $intervenant): bool
    {
        return true;
    }

    public function forceDelete(User $user, Intervenant $intervenant): bool
    {
        return true;
    }
}
