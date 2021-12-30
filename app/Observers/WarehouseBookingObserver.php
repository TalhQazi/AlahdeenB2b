<?php

namespace App\Observers;

use App\Models\User;
use App\Models\Warehouse\WarehouseBooking;
use App\Models\Warehouse\WarehouseRelatedLogging;
use App\Notifications\Warehouse\RejectBooking;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Notification;

class WarehouseBookingObserver
{

    protected $request;

    public function __construct()
    {
        $this->request = app('request');
    }
    /**
     * Handle the WarehouseBooking "created" event.
     *
     * @param  \App\Models\Warehouse\WarehouseBooking  $warehouseBooking
     * @return void
     */
    public function created(WarehouseBooking $warehouseBooking)
    {
        //
    }

    /**
     * Handle the WarehouseBooking "updated" event.
     *
     * @param  \App\Models\Warehouse\WarehouseBooking  $warehouseBooking
     * @return void
     */
    public function updated(WarehouseBooking $warehouseBooking)
    {
        $bookingAttributes = $warehouseBooking->toArray();
        if(!empty($bookingAttributes['warehouse'])) {
            unset($bookingAttributes['warehouse']);
        }


        $fieldsChanged = array_diff($warehouseBooking->getOriginal(),$bookingAttributes);
        unset($fieldsChanged['created_at']);
        unset($fieldsChanged['updated_at']);
        WarehouseRelatedLogging::create([
            'model_id' => $warehouseBooking->id,
            'model_type' => get_class($warehouseBooking),
            'data' => json_encode($fieldsChanged),
            'user_id' => Auth::user()->id,
            'ip' => Request::ip(),
            'description' => $this->request->reason,
            'action' => "Updated Booking",
        ]);

        if($warehouseBooking->booking_status == "rejected") {
            $user = User::find($warehouseBooking->booked_by);
            Notification::send($user, new RejectBooking($warehouseBooking, $this->request->reason));
        }
    }

    /**
     * Handle the WarehouseBooking "deleted" event.
     *
     * @param  \App\Models\Warehouse\WarehouseBooking  $warehouseBooking
     * @return void
     */
    public function deleted(WarehouseBooking $warehouseBooking)
    {
        //
    }

    /**
     * Handle the WarehouseBooking "restored" event.
     *
     * @param  \App\Models\Warehouse\WarehouseBooking  $warehouseBooking
     * @return void
     */
    public function restored(WarehouseBooking $warehouseBooking)
    {
        //
    }

    /**
     * Handle the WarehouseBooking "force deleted" event.
     *
     * @param  \App\Models\Warehouse\WarehouseBooking  $warehouseBooking
     * @return void
     */
    public function forceDeleted(WarehouseBooking $warehouseBooking)
    {
        //
    }
}
