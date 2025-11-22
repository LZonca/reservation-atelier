<?php

namespace App\Policies;

use App\Models\Atelier;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AtelierPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Atelier $atelier): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Atelier $atelier): bool
    {
        return true;
    }

    public function delete(User $user, Atelier $atelier): bool
    {
        return true;
    }

    public function restore(User $user, Atelier $atelier): bool
    {
        return true;
    }

    public function forceDelete(User $user, Atelier $atelier): bool
    {
        return true;
    }
}
