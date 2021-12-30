<?php

namespace App\Providers;

use App\Events\RecordStatsEvent;
use App\Events\SaveProductsCategoryEvent;
use App\Listeners\LoginEventListner;
use App\Listeners\RecordStats;
use App\Listeners\SaveProductsCategoryListner;
use App\Models\Invitation;
use App\Models\Warehouse\BookingAgreementTerm;
use App\Models\Warehouse\WarehouseBooking;
use App\Observers\BookingAgreementObserver;
use App\Observers\InvitationObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Observers\WarehouseBookingObserver;
use Illuminate\Auth\Events\Login as LoginEvent;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        SaveProductsCategoryEvent::class => [SaveProductsCategoryListner::class],
        RecordStatsEvent::class => [RecordStats::class],
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        LoginEvent::class => [LoginEventListner::class]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        WarehouseBooking::observe(WarehouseBookingObserver::class);
        BookingAgreementTerm::observe(BookingAgreementObserver::class);
        Invitation::observe(InvitationObserver::class);
    }
}
