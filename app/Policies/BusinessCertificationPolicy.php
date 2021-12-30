<?php

namespace App\Policies;

use App\Models\BusinessCertification;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BusinessCertificationPolicy
{
    use HandlesAuthorization;

    // Allow all actions if user is registered as corporate
    public function before($user, $ability)
    {
       // return null false will stop passing validation to further specific actions
        return $user->hasRole('corporate') ? null: false;

    }

    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function createCertificate(User $user, BusinessCertification $businessCertification, $businessId)
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
     * @param  \App\Models\BusinessCertification  $businessCertification
     * @return mixed
     */
    public function update(User $user, BusinessCertification $businessCertification)
    {
        $businessDetails = $user->businessCertificates()->select('business_certifications.id')->get();
        if(!empty($businessDetails)) {
            $businessIds = $businessDetails->pluck('id')->all();
            return in_array($businessCertification->id, $businessIds);
        } else {
            return false;
        }
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\BusinessCertification  $businessCertification
     * @return mixed
     */
    public function delete(User $user, BusinessCertification $businessCertification)
    {
        $businessDetails = $user->businessCertificates()->select('business_certifications.id')->get();
        if(!empty($businessDetails)) {
            $businessIds = $businessDetails->pluck('id')->all();
            return in_array($businessCertification->id, $businessIds);
        } else {
            return false;
        }
    }

}
