<?php

namespace App\Policies;

use App\Models\Challan;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Session;

class ChallanPolicy
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
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Challan  $challan
     * @return mixed
     */
    public function view(User $user, Challan $challan)
    {
       return $user->id == $challan->from || $user->id == $challan->to;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return Session::get('user_type') == "seller" ? Response::allow() : Response::deny(__('You need to switch to seller in order to create challan'));
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Challan  $challan
     * @return mixed
     */
    public function update(User $user, Challan $challan)
    {
      return $user->id == $challan->from;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Challan  $challan
     * @return mixed
     */
    public function delete(User $user, Challan $challan)
    {
      return $user->id == $challan->from;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Challan  $challan
     * @return mixed
     */
    public function restore(User $user, Challan $challan)
    {
      return $user->id == $challan->from;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Challan  $challan
     * @return mixed
     */
    public function forceDelete(User $user, Challan $challan)
    {
      return $user->id == $challan->from;
    }

    public function download(User $user, Challan $challan)
    {
      return $user->id == $challan->from || $user->id == $challan->to;
    }
}
