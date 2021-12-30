<?php

namespace App\Policies;

use App\Models\Catalog;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Session;

class CatalogPolicy
{
    use HandlesAuthorization;

    // Allow all actions if user is admin
    public function before($user, $ability)
    {
        // always return null, false will stop passing validation to further specific actions
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
      return Session::get('user_type') == "seller" ? Response::allow() : Response::deny(__('You need to switch to seller in order to view catalogs'));
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Catalog  $catalog
     * @return mixed
     */
    public function view(User $user, Catalog $catalog)
    {
      if(Session::get('user_type') == "seller") {
        return $user->id == $catalog->user_id;
      } else {
        return Response::deny(__('You need to switch to seller in order to view catalog'));
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
      return Session::get('user_type') == "seller" ? Response::allow() : Response::deny(__('You need to switch to seller in order to add catalog'));
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Catalog  $catalog
     * @return mixed
     */
    public function update(User $user, Catalog $catalog)
    {
      if(Session::get('user_type') == "seller") {
        return $user->id == $catalog->user_id;
      } else {
        return Response::deny(__('You need to switch to seller in order to edit catalog'));
      }

    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Catalog  $catalog
     * @return mixed
     */
    public function delete(User $user, Catalog $catalog)
    {
      if(Session::get('user_type') == "seller") {
        return $user->id == $catalog->user_id;
      } else {
        return Response::deny(__('You need to switch to seller in order to delete catalog'));
      }
    }


}
