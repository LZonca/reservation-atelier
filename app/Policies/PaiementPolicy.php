<?php

namespace App\Policies;

use App\Models\Paiement;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PaiementPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Paiement $paiement): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Paiement $paiement): bool
    {
        return true;
    }

    public function delete(User $user, Paiement $paiement): bool
    {
        return true;
    }

    public function restore(User $user, Paiement $paiement): bool
    {
        return true;
    }

    public function forceDelete(User $user, Paiement $paiement): bool
    {
        return true;
    }
}
