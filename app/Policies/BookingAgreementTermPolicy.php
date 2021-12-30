<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Warehouse\BookingAgreementTerm;
use Illuminate\Auth\Access\HandlesAuthorization;

class BookingAgreementTermPolicy
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
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Warehouse\BookingAgreement  $bookingAgreement
     * @return mixed
     */
    public function view(User $user, BookingAgreementTerm $bookingAgreementTerm)
    {
        $isOwner = $bookingAgreementTerm->booking->bookedBy->id == $user->id ? false : true;

        //Warehouse owner can view the invoice once the owner has been paid the amount
        if($isOwner && ($bookingAgreementTerm->status == "paid" || $bookingAgreementTerm->status == "confirmed")) {
            return $user->id == $bookingAgreementTerm->booking->warehouse->user_id;
        } else {
            return $user->id == $bookingAgreementTerm->booking->booked_by;
        }
    }

    public function makePayment(User $user, BookingAgreementTerm $bookingAgreementTerm)
    {
        $isOwner = $bookingAgreementTerm->booking->bookedBy->id == $user->id ? false : true;

        //Warehouse owner can view the invoice once the owner has been paid the amount
        if($isOwner) {
            return false;
        } else {
            return $user->id == $bookingAgreementTerm->booking->booked_by && $bookingAgreementTerm->status == "pending";
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
        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Warehouse\BookingAgreement  $bookingAgreement
     * @return mixed
     */
    public function update(User $user, BookingAgreementTerm $bookingAgreementTerm)
    {
        return false;
    }

}
