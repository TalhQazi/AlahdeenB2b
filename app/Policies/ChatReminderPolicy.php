<?php

namespace App\Policies;

use App\Models\ChatReminder;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ChatReminderPolicy
{
    use HandlesAuthorization;

    public function setReminder(User $user)
    {
        return true;
    }

    public function getReminders(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ChatReminder  $chatReminder
     * @return mixed
     */
    public function markReminderAsDone(User $user, ChatReminder $chatReminder)
    {
        return $user->id == $chatReminder->user_id;
    }


}
