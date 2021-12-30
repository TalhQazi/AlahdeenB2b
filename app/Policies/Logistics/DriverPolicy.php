<?php

namespace App\Policies\Logistics;

use App\Models\Logistics\Driver;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Session;

class DriverPolicy
{
    use HandlesAuthorization;


    public function viewAny(User $user)
    {
        if($user->hasRole(['super-admin', 'admin'])) {
            return true;
        } else {
            return false;
        }
    }

    public function view(User $user, Driver $driver)
    {
        if($user->hasRole(['super-admin', 'admin'])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return Session::get('user_type') == "driver" ? true : false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Logistics\Driver  $driver
     * @return mixed
     */
    public function update(User $user, Driver $driver)
    {
        if($user->hasRole(['super-admin', 'admin'])) {
            return true;
        } else {
            return Session::get('user_type') == "driver" && $user->id == $driver->user_id ? true : false;
        }
    }

}
