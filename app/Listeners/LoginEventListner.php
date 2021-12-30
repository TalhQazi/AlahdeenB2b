<?php

namespace App\Listeners;

use App\Traits\PackageUsageTrait;
use Illuminate\Auth\Events\Login as LoginEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Session;

class LoginEventListner
{
    use PackageUsageTrait;

    /**
     * Handle the event.
     *
     * @param  IlluminateAuthEventsLogin  $event
     * @return void
     */
    public function handle(LoginEvent $event)
    {
        // dd($this->canUseFeature('can_view_buying_selling_analytics'));
        Session::put('can_view_buying_selling_analytics', $this->canUseFeature('can_view_buying_selling_analytics'));
    }
}
