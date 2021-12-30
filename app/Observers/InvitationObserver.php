<?php

namespace App\Observers;

use App\Models\Invitation;
use App\Models\User;
use App\Notifications\UserInvited;
use Illuminate\Support\Facades\Notification;

class InvitationObserver
{
    /**
     * Handle events after all transactions are committed.
     *
     * @var bool
     */
    public $afterCommit = true;

    /**
     * Handle the Invitation "created" event.
     *
     * @param  \App\Models\Invitation  $invitation
     * @return void
     */
    public function created(Invitation $invitation)
    {
        Notification::send(User::find($invitation->user_id), new UserInvited($invitation));
    }

    /**
     * Handle the Invitation "updated" event.
     *
     * @param  \App\Models\Invitation  $invitation
     * @return void
     */
    public function updated(Invitation $invitation)
    {
        //
    }

    /**
     * Handle the Invitation "deleted" event.
     *
     * @param  \App\Models\Invitation  $invitation
     * @return void
     */
    public function deleted(Invitation $invitation)
    {
        //
    }
}
