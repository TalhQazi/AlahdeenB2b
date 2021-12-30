<?php

namespace App\Policies;

use App\Models\AdditionalBusinessDetail;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdditionalBusinessDetailPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasRole('corporate');
    }

}
