<?php

namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWarehouseBooking;
use App\Http\Resources\WarehouseBookingCollection;
use App\Models\User;
use App\Models\Warehouse\WarehouseBooking;
use App\Models\Warehouse\Warehouse;
use App\Notifications\Warehouse\BookingUser;
use App\Notifications\Warehouse\BookingWarehouseOwner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Traits\PaginatorTrait;

class WarehouseBookingController extends Controller
{

    private $noOfItems;

    public function __construct()
    {
        $this->noOfItems = config('pagination.warehouse_booking', config('pagination.default'));
    }

    public function index(WarehouseBooking $warehouseBooking, Request $request)
    {

        $warehouseBookings = $warehouseBooking->with('warehouse', 'invoice', 'bookedBy', 'warehouse.images')->where('booked_by', Auth::user()->id)->orderBy('created_at','desc');
        // ->where('booked_by', '!=', $warehouseBooking->warehouse->owner_id)

        // if($request->input('keywords')) {
        //     $searchParam = $request->input('keywords');
        //     $warehouseBookings = $bookingAgreementTerm::withTrashed()->with(config('relation_configuration.warehouse_booking.index'))
        //     ->whereHas('booked_by', function($query) use ($searchParam) {
        //         $query->where('name', 'like', '%'.$searchParam.'%');
        //     })
        //     ->orWhereHas('locality', function($query) use ($searchParam) {
        //         $query->where('name', 'like', '%'.$searchParam.'%');
        //     });
        // }


        $warehouseBookings = $warehouseBookings->whereHas(
            'warehouse',
            function($query) {
                $query->where('user_id', '!=', Auth::user()->id);
            }
        );
        $warehouseBookings = $warehouseBookings->paginate($this->noOfItems);
        $warehouseBookings = (new WarehouseBookingCollection($warehouseBookings))->response()->getData();

        if ($request->ajax()) {
            return response()->json(['warehouse_bookings' => $warehouseBookings, 'paginator' => (string) PaginatorTrait::getPaginator($request, $warehouseBookings)->links()]);
        } else {
            return view('pages.warehouse-booking.index')->with([
                'warehouse_bookings' => $warehouseBookings->data,
                // 'payment_methods' => PaymentMethod::getAllActive(),
                // 'payment_statuses' => $bookingAgreementTerm->payment_status,
                'quantity_units' =>  config('quantity_unit'),
                'table_header' => 'components.warehouse-booking.index.theader',
                'table_body' => 'components.warehouse-booking.index.tbody',
                'paginator' => PaginatorTrait::getPaginator($request, $warehouseBookings)
            ]);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Warehouse $warehouse)
    {
        $this->authorize('view', [WarehouseBooking::class, $warehouse]);
        return view('pages.warehouse-booking.view')->with(['warehouse_id' => $warehouse->id]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Warehouse $warehouse, StoreWarehouseBooking $request)
    {
        $this->authorize('store', [WarehouseBooking::class, $warehouse]);

        $message = __('Unable to save warehouse booking details');
        $alertClass = 'alert-error';

        $validatedData = $request->validated();
        // $validatedData['start_time'] = $validatedData['start_date'] . ' ' . $validatedData['start_time'];
        // $validatedData['end_time'] = $validatedData['end_date'] . ' ' . $validatedData['end_time'];

        $validatedData['start_time'] = $validatedData['start'];
        $validatedData['end_time'] = $validatedData['end'];

        $created = $this->saveWarehouseBooking($warehouse, $validatedData);

        if($created) {
            $message = __('Warehouse booking details have been saved successfully');
            $alertClass = 'alert-success';
        }

        Session::flash('message', $message);
        Session::flash('alert-class', $alertClass);
        return redirect()->back();

    }

    public function edit(WarehouseBooking $warehouseBooking, Request $request)
    {
        $this->authorize('edit', [$warehouseBooking]);
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
        $warehouse = $warehouseBooking->warehouse;

        $this->authorize('update', [$warehouseBooking, $warehouse]);
        $message = __('Unable to update warehouse booking details');
        $alertClass = 'alert-error';

        if($warehouseBooking->status == "approved") {
            $message = __('Unable to update booking details as details have been agreed upon, visit booking invoices page to see details');
            Session::flash('message', $message);
            Session::flash('alert-class', $alertClass);
            return redirect()->back();
        }

        $validatedData = $request->validated();

        $bookingDetails = [
            'item' => $validatedData['item'],
            'description' => $validatedData['description'],
            'start_time' => $validatedData['start_date'] . ' ' .$validatedData['start_time'],
            'end_time' => $validatedData['end_date'] . ' ' . $validatedData['end_time'],
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
        return redirect()->back();

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\WarehouseBooking  $warehouseBooking
     * @return \Illuminate\Http\Response
     */
    public function destroy(Warehouse $warehouse, WarehouseBooking $warehouseBooking)
    {
        $this->authorize('delete', [$warehouseBooking, $warehouse]);

        if($warehouseBooking->delete()) {
            Session::flash('message', __('Warehouse booking has been deleted successfully'));
            Session::flash('alert-class', 'alert-success');
        } else {
            Session::flash('message', __('Unable to delete warehouse booking'));
            Session::flash('alert-class', 'alert-error');
        }

        return redirect()->back();
    }

    public function getWarehouseBookings(Warehouse $warehouse, WarehouseBooking $warehouseBooking, Request $request)
    {
        $startDate = $request->input('start');
        $endDate = $request->input('end');
        $startDate = date_format(date_create($startDate), 'Y-m-d H:i:s');
        $endDate = date_format(date_create($endDate), 'Y-m-d H:i:s');

        $warehouseBookings = $warehouseBooking->where('start_time', '>=', $startDate)->where('end_time', '<=', $endDate)->where('warehouse_id',$warehouse->id)->get();

        $data = [];
        if(!empty($warehouseBookings)) {
            foreach($warehouseBookings as $key => $bookingDetails) {
                $data[$key]['id'] = $bookingDetails['id'];
                $data[$key]['item'] = $bookingDetails['item'];
                $data[$key]['description'] = $bookingDetails['description'];
                $data[$key]['start'] = $bookingDetails['start_time'];
                $data[$key]['end'] = $bookingDetails['end_time'];
                $data[$key]['overlap'] = false;
                if($bookingDetails['booking_status'] == 'pending') {
                    $data[$key]['backgroundColor'] = 'yellow';
                    $data[$key]['borderColor'] = 'yellow';
                    $data[$key]['textColor'] = 'black';
                } else if($bookingDetails['booking_status'] == 'confirmed') {
                    $data[$key]['backgroundColor'] = 'red';
                    $data[$key]['borderColor'] = 'red';
                } else {
                    $data[$key]['backgroundColor'] = 'blue';
                    $data[$key]['borderColor'] = 'blue';
                }
                $data[$key]['can_be_edited'] = $bookingDetails->booked_by == Auth::user()->id ? true : false;
                $data[$key]['can_be_deleted'] = $bookingDetails->booked_by == Auth::user()->id ? true : false;
            }
        }

        if($request->ajax()) {
            return response()->json($data);
        } else {
            return $data;
        }

    }


    public function saveWarehouseBooking(Warehouse $warehouse, $validatedData)
    {
        return tap(WarehouseBooking::create([
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
            }
        });
    }
}

