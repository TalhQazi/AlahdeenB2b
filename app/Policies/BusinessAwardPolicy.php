<?php

namespace App\Policies;

use App\Models\BusinessAward;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BusinessAwardPolicy
{
    use HandlesAuthorization;

    // Allow all actions if user is registered as corporate
    public function before($user, $ability)
    {
        // return null, false will stop passing validation to further specific actions
        return $user->hasRole('corporate') ? null: false;

    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function createAward(User $user, BusinessAward $businessAward, $businessId)
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
     * @param  \App\Models\BusinessAward  $businessAward
     * @return mixed
     */
    public function update(User $user, BusinessAward $businessAward)
    {
        $businessDetails = $user->businessAwards()->select('business_awards.id')->get();
        if(!empty($businessDetails)) {
            $businessIds = $businessDetails->pluck('id')->all();
            return in_array($businessAward->id, $businessIds);
        } else {
            return false;
        }
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\BusinessAward  $businessAward
     * @return mixed
     */
    public function delete(User $user, BusinessAward $businessAward)
    {
        $businessDetails = $user->businessAwards()->select('business_awards.id')->get();
        if(!empty($businessDetails)) {
            $businessIds = $businessDetails->pluck('id')->all();
            return in_array($businessAward->id, $businessIds);
        } else {
            return false;
        }
    }

}
