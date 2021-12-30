<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Warehouse\Warehouse;
use Illuminate\Auth\Access\HandlesAuthorization;

class WarehousePolicy
{
    use HandlesAuthorization;


    // Allow all actions if user is admin
    public function before($user, $ability)
    {
        // return null false will stop passing validation to further specific actions
        return $user->isAdmin() ? true : null;
    }


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
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Warehouse  $warehouse
     * @return mixed
     */
    public function update(User $user, Warehouse $warehouse)
    {
        return $user->id == $warehouse->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Warehouse  $warehouse
     * @return mixed
     */
    public function delete(User $user, Warehouse $warehouse)
    {
        /*
            sending true in before method if user is admin/super-admin
            sending false so that normal users can't access this function
        */
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Warehouse  $warehouse
     * @return mixed
     */
    public function restore(User $user, Warehouse $warehouse)
    {
        /*
            sending true in before method if user is admin/super-admin
            sending false so that normal users can't access this function
        */
        return false;
    }

    public function saveWarehouseFeatures(User $user, Warehouse $warehouse)
    {
        return $user->id == $warehouse->user_id;
    }

    public function saveWarehouseImages(User $user, Warehouse $warehouse)
    {
        return $user->id == $warehouse->user_id;
    }

    public function setMainImage(User $user, Warehouse $warehouse)
    {
        return $user->id == $warehouse->user_id;
    }

    public function deleteImages(User $user, Warehouse $warehouse)
    {
        return $user->id == $warehouse->user_id;
    }

    public function unsetMainImage(User $user, Warehouse $warehouse)
    {
        return $user->id == $warehouse->user_id;
    }

    public function activate(User $user, Warehouse $warehouse)
    {
        return $user->id == $warehouse->user_id;
    }

    public function deactivate(User $user, Warehouse $warehouse)
    {
        return $user->id == $warehouse->user_id;
    }


    /**
     * Determine whether the user can approve the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Warehouse  $warehouse
     * @return mixed
     */
    public function approve(User $user, Warehouse $warehouse)
    {
        /*
            sending true in before method if user is admin/super-admin
            sending false so that normal users can't access this function
        */
        return false;
    }

    /**
     * Determine whether the user can disapprove the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Warehouse  $warehouse
     * @return mixed
     */
    public function disapprove(User $user, Warehouse $warehouse)
    {
        /*
            sending true in before method if user is admin/super-admin
            sending false so that normal users can't access this function
        */
        return false;
    }

}
