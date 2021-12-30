<?php

namespace App\Policies\Inventory;

use App\Models\Inventory\ProductDefinition;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductDefinitionPolicy
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
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\InventoryProductDefination  $inventoryProductDefination
     * @return mixed
     */
    public function update(User $user, ProductDefinition $productDefinition)
    {
        return $user->id == $productDefinition->product->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\InventoryProductDefination  $inventoryProductDefination
     * @return mixed
     */
    public function delete(User $user, ProductDefinition $productDefinition)
    {
        return $user->id == $productDefinition->product->user_id;
    }

}
