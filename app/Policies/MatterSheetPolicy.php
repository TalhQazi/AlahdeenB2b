<?php

namespace App\Policies;

use App\Models\MatterSheet;
use App\Models\MatterSheetProduct;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Session;
use Illuminate\Auth\Access\Response;

class MatterSheetPolicy
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
        return Session::get('user_type') == "seller" ? Response::allow() : Response::deny(__('You need to switch to seller in order to view your matter sheets'));
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MatterSheetProduct  $matter_sheet_product
     * @return mixed
     */
    public function view(User $user, MatterSheet $matter_sheet)
    {
        if (Session::get('user_type') == "seller") {
            return $user->id == $matter_sheet->user_id;
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
        return Session::get('user_type') == "seller" ? Response::allow() : Response::deny(__('You need to switch to seller in order to add matter sheet'));
    }


    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MatterSheet  $matter_sheet
     * @return mixed
     */
    public function deleteMatterSheet(User $user, MatterSheet $matter_sheet)
    {
        if (Session::get('user_type') == "seller") {
            return $user->id == $matter_sheet->user_id;
        } else {
            return Response::deny(__('You need to switch to seller in order to delete matter sheet.'));
        }
    }

    public function deleteMatterProduct(User $user, MatterSheet $matter_sheet)
    {
        if (Session::get('user_type') == "seller") {
            return $user->id == $matter_sheet->user_id;
        } else {
            return Response::deny(__('You need to switch to seller in order to delete matter sheet product.'));
        }
    }


    public function approve(User $user)
    {
        return $user->isAdmin() ? true : Response::deny(__('You need to be an admin to approve matter sheets.'));
    }
}
