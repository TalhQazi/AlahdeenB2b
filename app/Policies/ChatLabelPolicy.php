<?php

namespace App\Policies;

use App\Models\ChatLabel;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ChatLabelPolicy
{
    use HandlesAuthorization;

    public function saveLabels(User $user)
    {
        return true;
    }

    public function getLabels(User $user, ChatLabel $chatLabel)
    {
        return true;
    }

}
