<?php

namespace App\Policies;

use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TestimonialPolicy
{
    use HandlesAuthorization;

    // Allow all actions if user is admin
    public function before($user, $ability)
    {
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

     /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function view(User $user, Testimonial $testimonial)
    {
        return true;
    }


    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Testimonial  $testimonial
     * @return mixed
     */
    public function update(User $user, Testimonial $testimonial)
    {
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Testimonial  $testimonial
     * @return mixed
     */
    public function delete(User $user, Testimonial $testimonial)
    {
        return true;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Testimonial  $testimonial
     * @return mixed
     */
    public function restore(User $user, Testimonial $testimonial)
    {
        return true;
    }
}

