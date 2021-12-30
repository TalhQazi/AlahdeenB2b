<?php

namespace App\Policies;

use App\Models\PurchaseReturn;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;

class PurchaseReturnPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */


    public function before($user, $ability)
    {
        // return null false will stop passing validation to further specific actions
        return $user->isAdmin() ?: null;
    }

    public function update(User $user, PurchaseReturn $purchaseReturn)
    {
        if (Session::get('user_type') == "seller") {
            return $user->id == $purchaseReturn->user_id;
        } else {
            return Response::deny(__('You need to switch to seller in order to edit purchase return'));
        }
    }

    public function change_status(User $user, PurchaseReturn $purchaseReturn)
    {
        if ($user->id == $purchaseReturn->user_id) {
            return true;
        } else {
            return Response::deny(__('You are not authorize to change the status'));
        }
    }
}
