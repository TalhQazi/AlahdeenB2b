<?php

namespace App\Policies;

use App\Models\PurchaseOrder;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Session;

class PurchaseOrderPolicy
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
   * @param  \App\Models\PurchaseOrder  $purchaseOrder
   * @return mixed
   */
  public function view(User $user, PurchaseOrder $purchaseOrder)
  {
    return $user->id == $purchaseOrder->created_by;
  }

  /**
   * Determine whether the user can create models.
   *
   * @param  \App\Models\User  $user
   * @return mixed
   */
  public function create(User $user)
  {
    return Session::get('user_type') == "buyer" ? Response::allow() : Response::deny(__('You need to switch to buyer in order to create invoice'));
  }

  /**
   * Determine whether the user can update the model.
   *
   * @param  \App\Models\User  $user
   * @param  \App\Models\PurchaseOrder  $purchaseOrder
   * @return mixed
   */
  public function update(User $user, PurchaseOrder $purchaseOrder)
  {
    return $user->id == $purchaseOrder->created_by;
  }

  /**
   * Determine whether the user can delete the model.
   *
   * @param  \App\Models\User  $user
   * @param  \App\Models\PurchaseOrder  $purchaseOrder
   * @return mixed
   */
  public function delete(User $user, PurchaseOrder $purchaseOrder)
  {
    return $user->id == $purchaseOrder->created_by;
  }

  /**
   * Determine whether the user can restore the model.
   *
   * @param  \App\Models\User  $user
   * @param  \App\Models\PurchaseOrder  $purchaseOrder
   * @return mixed
   */
  public function restore(User $user, PurchaseOrder $purchaseOrder)
  {
    return $user->id == $purchaseOrder->created_by;
  }

  /**
   * Determine whether the user can permanently delete the model.
   *
   * @param  \App\Models\User  $user
   * @param  \App\Models\PurchaseOrder  $purchaseOrder
   * @return mixed
   */
  public function forceDelete(User $user, PurchaseOrder $purchaseOrder)
  {
    return $user->id == $purchaseOrder->created_by;
  }

  public function download(User $user, PurchaseOrder $purchaseOrder)
  {
    return $user->id == $purchaseOrder->created_by;
  }
}
