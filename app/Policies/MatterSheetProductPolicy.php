<?php

namespace App\Policies;

use App\Models\MatterSheetProduct;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;

class MatterSheetProductPolicy
{
    use HandlesAuthorization;
    public function before($user, $ability)
    {
        // return null false will stop passing validation to further specific actions
        return $user->isAdmin() ?: null;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MatterSheetProduct  $matter_sheet_product
     * @return mixed
     */
    public function update(User $user, MatterSheetProduct $matter_sheet_product)
    {
        if (Session::get('user_type') == "seller") {
            return $user->id == $matter_sheet_product->user_id;
        }
        return Response::deny(__('You need to switch to seller in order to edit matter sheet products'));
    }
}
