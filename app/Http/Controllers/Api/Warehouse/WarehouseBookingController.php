<?php

namespace App\Http\Controllers\Api\Warehouse;

use App\Events\RecordStatsEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWarehouseBooking;
use App\Models\User;
use App\Models\Warehouse\WarehouseBooking;
use Illuminate\Http\Request;
use App\Models\Warehouse\Warehouse;
use App\Notifications\Warehouse\BookingUser;
use App\Notifications\Warehouse\BookingWarehouseOwner;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;


class WarehouseBookingController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Warehouse $warehouse, StoreWarehouseBooking $request)
    {

        if(!$warehouse->is_active) {
            return $this->error(
                __('Unable to book warehouse'),
                200
            );
        } else {

            $validatedData = $request->validated();

            if(!empty($validatedData['area']) && $validatedData['area'] > $warehouse->area) {
                return $this->error(
                    __('Area required for booking can not be greater then warehouse area'),
                    200
                );
            }

            // $validatedData['start_time'] = $validatedData['start_date'] . ' ' . $validatedData['start_time'];
            // $validatedData['end_time'] = $validatedData['end_date'] . ' ' . $validatedData['end_time'];


            $validatedData['start_time'] = $validatedData['start'];
            $validatedData['end_time'] = $validatedData['end'];

            $created = tap(WarehouseBooking::create([
                'warehouse_id' => $warehouse->id,
                'booked_by' => Auth::user()->id,
                'item' => $validatedData['item'],
                'description' => $validatedData['description'],
                'start_time' => $validatedData['start_time'],
                'end_time' => $validatedData['end_time'],
                'booking_status' => $warehouse->user_id == Auth::user()->id ? 'confirmed' : 'pending',
                'type' => $validatedData['type'] ?? 'fully',
                'quantity' => $validatedData['quantity'] ?? NULL,
                'unit' => $validatedData['unit'] ?? '',
                'area' => $validatedData['area'] ?? NULL,
                'goods_value' => $validatedData['goods_value'] ?? NULL,
            ]),
            function(WarehouseBooking $warehouseBooking) use ($warehouse) {
                if($warehouse->user_id != Auth::user()->id) {
                    $warehouseOwner = User::find($warehouse->user_id);
                    Notification::send(Auth::user(), new BookingUser($warehouseBooking));
                    Notification::send($warehouseOwner, new BookingWarehouseOwner($warehouseBooking));

                    RecordStatsEvent::dispatch([$warehouse], 'contacted_owner', 'warehouse');
                  }
            });

            if($created) {
                return $this->success(
                    [],
                    __('Request for the booking warehouse has been forwarded to concerned department, you will be contacted accordingly'),
                );
            } else {
                return $this->error(
                    __('Unable to forward your request for booking concerned warehouse, please try again later'),
                    200
                );
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Warehouse\WarehouseBooking  $warehouseBooking
     * @return \Illuminate\Http\Response
     */
    public function show(WarehouseBooking $warehouseBooking)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Warehouse\WarehouseBooking  $warehouseBooking
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WarehouseBooking $warehouseBooking)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Warehouse\WarehouseBooking  $warehouseBooking
     * @return \Illuminate\Http\Response
     */
    public function destroy(WarehouseBooking $warehouseBooking)
    {
        //
    }
}
