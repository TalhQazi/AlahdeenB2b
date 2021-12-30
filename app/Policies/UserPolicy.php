<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    // Allow all actions if user is registered as corporate
    public function before($user, $ability)
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can view list of model.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function updateRole(User $user)
    {
        return true;
    }

}
