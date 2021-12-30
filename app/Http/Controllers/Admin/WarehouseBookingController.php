<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWarehouseBooking;
use App\Http\Resources\WarehouseBookingCollection;
use App\Models\Warehouse\WarehouseBooking;
use App\Traits\PaginatorTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;


class WarehouseBookingController extends Controller
{
    private $noOfItems;

    public function __construct()
    {
        $this->noOfItems = config('pagination.warehouse', config('pagination.default'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(WarehouseBooking $warehouseBooking, Request $request)
    {
        $this->authorize('viewAny', WarehouseBooking::class);
        $warehouseBookings = $warehouseBooking::withTrashed()
                            ->with(config('relation_configuration.warehouse_booking.index'))
                            ->withTrashed()
                            ->orderBy('created_at','desc');

        if($request->input('keywords')) {
            $searchParam = $request->input('keywords');
            $warehouseBookings = $warehouseBooking::withTrashed()->with(config('relation_configuration.warehouse_booking.index'))
            ->whereHas('bookedBy', function($query) use ($searchParam) {
                $query->where('name', 'like', '%'.$searchParam.'%');
            });
        }

        $warehouseBookings = $warehouseBookings->paginate($this->noOfItems);
        $warehouseBookings = (new WarehouseBookingCollection($warehouseBookings))->response()->getData();

        if ($request->ajax()) {
            return response()->json(['warehouse_bookings' => $warehouseBookings, 'paginator' => (string) PaginatorTrait::getPaginator($request, $warehouseBookings)->links()]);
        } else {
            return view('pages.warehouse-booking.admin.index')->with([
                'warehouse_bookings' => $warehouseBookings->data,
                'quantity_units' =>  config('quantity_unit'),
                'table_header' => 'components.warehouse-booking.index.admin.theader',
                'table_body' => 'components.warehouse-booking.index.admin.tbody',
                'paginator' => PaginatorTrait::getPaginator($request, $warehouseBookings)
            ]);
        }
    }

    public function view(WarehouseBooking $warehouseBooking, Request $request)
    {
        $this->authorize('viewAny', WarehouseBooking::class);
        if($request->ajax()){
            return response()->json($warehouseBooking);
        } else {
            return $warehouseBooking;
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\WarehouseBooking  $warehouseBooking
     * @return \Illuminate\Http\Response
     */
    public function update(StoreWarehouseBooking $request, WarehouseBooking $warehouseBooking)
    {
        $this->authorize('updateOrReject', WarehouseBooking::class);

        $message = __('Unable to update warehouse booking details');
        $alertClass = 'alert-error';
        $validatedData = $request->validated();

        $validatedData['start_time'] = $validatedData['start'];
        $validatedData['end_time'] = $validatedData['end'];

        $bookingDetails = [
            'item' => $validatedData['item'],
            'description' => $validatedData['description'],
            'start_time' => $validatedData['start'],
            'end_time' => $validatedData['end'],
            'booking_status' => $validatedData['booking_status'] ?? 'pending',
            'quantity' => $validatedData['quantity'] ?? NULL,
            'unit' => $validatedData['unit'] ?? NULL,
            'type' => $validatedData['type'] ?? 'fully',
            'area' => $validatedData['type'] == 'partial' ? $validatedData['area']:  NULL,
            'goods_value' => $validatedData['goods_value'] ?? NULL
        ];

        if($warehouseBooking->update($bookingDetails)) {
            $message = __('Warehouse booking details have been updated successfully');
            $alertClass = 'alert-success';
        }

        Session::flash('message', $message);
        Session::flash('alert-class', $alertClass);

        if($validatedData['booking_status'] && $validatedData['booking_status'] == "approved") {
            return redirect()->route('admin.warehousebookings.create-agreement', ['warehouse_booking' => $warehouseBooking->id]);
        } else {
            return redirect()->back();
        }


    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\WarehouseBooking  $warehouseBooking
     * @return \Illuminate\Http\Response
     */
    public function reject(WarehouseBooking $warehouseBooking, Request $request)
    {
        $this->authorize('updateOrReject', WarehouseBooking::class);

        $message = __('Unable to reject warehouse booking details');
        $alertClass = 'alert-error';

        $warehouseBooking->booking_status = 'rejected';
        $request->action = "Rejected Booking";

        if($warehouseBooking->save()) {
            $message = __('Warehouse booking details have been rejected successfully');
            $alertClass = 'alert-success';
        }

        Session::flash('message', $message);
        Session::flash('alert-class', $alertClass);
        return redirect()->back();

    }
}
