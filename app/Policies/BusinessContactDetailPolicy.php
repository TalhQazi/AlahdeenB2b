<?php

namespace App\Policies;

use App\Models\BusinessContactDetail;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BusinessContactDetailPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function createBusinessContact(User $user, BusinessContactDetail $businessContactDetail, $businessId)
    {
        $businessDetails = $user->business()->select('id')->first();
        if(!empty($businessDetails)) {
            return $businessDetails->id == $businessId;
        } else {
            return false;
        }
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\BusinessContactDetail  $businessContactDetail
     * @return mixed
     */
    public function update(User $user, BusinessContactDetail $businessContactDetail)
    {
        $businessContacts = $user->businessContacts()->select('business_contact_details.id')->get();
        if(!empty($businessContacts)) {
            $businessContactIds = $businessContacts->pluck('id')->all();
            return in_array($businessContactDetail->id, $businessContactIds);
        } else {
            return false;
        }
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\BusinessContactDetail  $businessContactDetail
     * @return mixed
     */
    public function delete(User $user, BusinessContactDetail $businessContactDetail)
    {

        $businessContacts = $user->businessContacts()->select('business_contact_details.id')->get();
        if(!empty($businessContacts)) {
            $businessContactIds = $businessContacts->pluck('id')->all();
            return in_array($businessContactDetail->id, $businessContactIds);
        } else {
            return false;
        }
    }

}
