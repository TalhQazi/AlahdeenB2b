<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Warehouse\Warehouse;
use App\Models\Warehouse\WarehouseBooking;
use Illuminate\Auth\Access\HandlesAuthorization;

class WarehouseBookingPolicy
{
    use HandlesAuthorization;

    // Allow all actions if user is admin
    public function before($user, $ability)
    {
        // return null false will stop passing validation to further specific actions
        return $user->isAdmin() ? true : null;
    }

    public function viewAny(User $user)
    {
        return true;
    }

    public function updateOrReject(User $user)
    {
        return false;
    }


    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Warehouse  $warehouse
     * @return mixed
     */
    public function view(User $user, Warehouse $warehouse)
    {
        return $user->id == $warehouse->user_id;
    }


    /**
     * Determine whether the user can store the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Warehouse  $warehouse
     * @return mixed
     */
    public function store(User $user, Warehouse $warehouse)
    {
        return $user->id == $warehouse->user_id;
    }


    /* Determine whether the user can edit the model.
    *
    * @param  \App\Models\User  $user
    * @param  \App\Models\WarehouseBooking  $warehouseBooking
    * @param  \App\Models\Warehouse  $warehouse
    * @return mixed
    */
    public function edit(User $user, WarehouseBooking $warehouseBooking)
    {
        return $this->actionAllowed($user, $warehouseBooking, $warehouseBooking->warehouse);
    }



    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\WarehouseBooking  $warehouseBooking
     * @param  \App\Models\Warehouse  $warehouse
     * @return mixed
     */
    public function update(User $user, WarehouseBooking $warehouseBooking, Warehouse $warehouse)
    {
        return $this->actionAllowed($user, $warehouseBooking, $warehouse);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\WarehouseBooking  $warehouseBooking
     * @param  \App\Models\Warehouse  $warehouse
     * @return mixed
     */
    public function delete(User $user, WarehouseBooking $warehouseBooking, Warehouse $warehouse)
    {
        return $this->actionAllowed($user, $warehouseBooking, $warehouse);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\WarehouseBooking  $warehouseBooking
     * @param  \App\Models\Warehouse  $warehouse
     * @return mixed
     */
    public function getBookings(User $user, WarehouseBooking $warehouseBooking, Warehouse $warehouse)
    {
       return $this->actionAllowed($user, $warehouseBooking, $warehouse);
    }

     /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\WarehouseBooking  $warehouseBooking
     * @param  \App\Models\Warehouse  $warehouse
     * @return mixed
     */
    public function actionAllowed(User $user, WarehouseBooking $warehouseBooking, Warehouse $warehouse) {
        //delete/update/get warehouse schedule in case its active, and its owned by the authenticated user
        if($warehouse->is_active) {
            return $user->id == $warehouseBooking->booked_by;
        } else { //allow warehouse owner to delete/update/get warehouse schedule even if its inactive
            return ($user->id == $warehouseBooking->booked_by && $user->id == $warehouse->user_id);
        }
    }

}
