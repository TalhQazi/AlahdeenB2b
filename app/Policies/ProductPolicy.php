<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ProductPolicy
{
    use HandlesAuthorization;

    // // Allow all actions if user is admin
    public function before($user, $ability)
    {
        // return null false will stop passing validation to further specific actions
        return $user->isAdmin() ?: null;

    }

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return Session::get('user_type') == "seller" ? Response::allow() : Response::deny(__('You need to switch to seller in order to view your products'));
    }

    public function viewAnyMatterSheetProduct(User $user)
    {
        return Session::get('user_type') == "seller" ? Response::allow() : Response::deny(__('You need to switch to seller in order to view your products'));
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Product  $product
     * @return mixed
     */
    public function view(User $user, Product $product)
    {
      if(Session::get('user_type') == "seller") {
        return $user->id == $product->user_id;
      } else {
        return Response::deny(__('You need to switch to seller in order to view your product'));
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
        return Session::get('user_type') == "seller" ? Response::allow() : Response::deny(__('You need to switch to seller in order to add product'));
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Product  $product
     * @return mixed
     */
    public function update(User $user, Product $product)
    {
      if(Session::get('user_type') == "seller") {
        return $user->id == $product->user_id;
      } else {
        return Response::deny(__('You need to switch to seller in order to edit product'));
      }
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Product  $product
     * @return mixed
     */
    public function delete(User $user, Product $product)
    {
      if(Session::get('user_type') == "seller") {
        return $user->id == $product->user_id;
      } else {
        return Response::deny(__('You need to switch to seller in order to delete product'));
      }
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Product  $product
     * @return mixed
     */
    public function restore(User $user, Product $product)
    {
      if(Session::get('user_type') == "seller") {
        return $user->id == $product->user_id;
      } else {
        return Response::deny(__('You need to switch to seller in order to restore product'));
      }
    }

}
