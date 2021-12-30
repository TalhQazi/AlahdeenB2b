<?php

namespace App\Observers;

use App\Models\User;
use App\Models\Warehouse\BookingAgreementTerm;
use App\Models\Warehouse\WarehouseRelatedLogging;
use App\Notifications\Warehouse\BookingAgreementOwner;
use App\Notifications\Warehouse\BookingAgreementUser;
use App\Notifications\Warehouse\BookingConfirmedOwner;
use App\Notifications\Warehouse\BookingConfirmedUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Request;

class BookingAgreementObserver
{
    /**
     * Handle the WarehouseBookingAgreementTerm "created" event.
     *
     * @param  \App\Models\WarehouseBookingAgreementTerm  $warehouseBookingAgreementTerm
     * @return void
     */
    public function created(BookingAgreementTerm $bookingAgreementTerm)
    {
        Notification::send(User::find($bookingAgreementTerm->booking->booked_by), new BookingAgreementUser($bookingAgreementTerm));
        Notification::send(User::find($bookingAgreementTerm->booking->warehouse->owner->id), new BookingAgreementOwner($bookingAgreementTerm));
    }

    /**
     * Handle the WarehouseBookingAgreementTerm "updated" event.
     *
     * @param  \App\Models\WarehouseBookingAgreementTerm  $warehouseBookingAgreementTerm
     * @return void
     */
    public function updated(BookingAgreementTerm $bookingAgreementTerm)
    {
        $fieldsChanged = array_diff($bookingAgreementTerm->getOriginal(),$bookingAgreementTerm->toArray());
        unset($fieldsChanged['created_at']);
        unset($fieldsChanged['updated_at']);
        WarehouseRelatedLogging::create([
            'model_id' => $bookingAgreementTerm->id,
            'model_type' => get_class($bookingAgreementTerm),
            'data' => json_encode($fieldsChanged),
            'user_id' => Auth::user()->id,
            'ip' => Request::ip(),
            'description' => $this->request->reason ?? NULL,
            'action' => "Updated Booking Agreement",
        ]);

        if($bookingAgreementTerm->status == "confirmed") {
            Notification::send(User::find($bookingAgreementTerm->booking->booked_by), new BookingConfirmedUser($bookingAgreementTerm));
            Notification::send(User::find($bookingAgreementTerm->booking->warehouse->owner->id), new BookingConfirmedOwner($bookingAgreementTerm));
        }

    }

}
