<?php

namespace App\Policies;

use App\Models\SubscriptionOrder;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SubscriptionOrderPolicy
{
    use HandlesAuthorization;

    // Allow all actions if user is admin
    public function before($user, $ability)
    {
        // return null false will stop passing validation to further specific actions
        return $user->isAdmin();

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

    public function updatePaymentStatus(User $user, SubscriptionOrder $subscriptionOrder)
    {
        return true;
    }

}
