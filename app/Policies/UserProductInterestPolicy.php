<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserProductInterest;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserProductInterestPolicy
{
    use HandlesAuthorization;


    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }


    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UserProductInterest  $userProductInterest
     * @return mixed
     */
    public function delete(User $user, UserProductInterest $userProductInterest)
    {
        return $user->id == $userProductInterest->user_id;
    }

}
