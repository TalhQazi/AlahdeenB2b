<?php

namespace App\Http\Controllers\Logistics;

use App\Http\Controllers\Controller;
use App\Models\Logistics\AcceptedRide;
use App\Models\Logistics\BookingRequest;
use App\Models\Logistics\Driver;
use App\Traits\PaginatorTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AvailableRidesController extends Controller
{
    use PaginatorTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, BookingRequest $bookingRequest)
    {
        $bookingRequests = BookingRequest::with(['requestor'])->where('pick_up_city_id', Auth::user()->city_id);

        if ($request->input('keywords')) {
            $searchParam = $request->input('keywords');
            $bookingRequest = $bookingRequest->where('pick_up_city_id', 'like', '%' . $searchParam . '%');
        }

        $bookingRequests = $bookingRequest->paginate(10);

            return view('pages.logistics.driver.city_bid')->with([
                'bookingReqs' => $bookingRequests,
                'paginator' => (string)$bookingRequests->links()
            ]);

    }


    /**
     * @param BookingRequest $bookingRequest
     * @return RedirectResponse
     */

    public function accept(BookingRequest $bookingRequest)
    {
        $accepted_ride = AcceptedRide::where('booking_request_id', $bookingRequest->id)->first();
        $bookingRequests = $bookingRequest->paginate(10);

        $message = __('Unable to accept booking request.');
        $alertClass = 'alert-error';

        //where driver is equal to the logged in user
        $driver = Driver::where('user_id', Auth::user()->id)->first();

        $accept_ride = new AcceptedRide();

        //check if booking_request_id exists in accepted_rides table
        $accepted_ride = AcceptedRide::where('booking_request_id', $bookingRequest->id)->first();

        if ($accepted_ride) {

            $message = __('Booking request already accepted.');
            $alertClass = 'alert-error';

            Session::flash('message', $message);
            Session::flash('alert-class', $alertClass);
            return view('pages.logistics.driver.city_bid')->with([
                'is_ride_accepted' => !empty($accepted_ride),
                'bookingReqs' => $bookingRequests,
                'paginator' => (string)$bookingRequests->links()
            ]);

        } else {

            $accept_ride->booking_request_id = $bookingRequest->id;
            $accept_ride->driver_id = $driver->id;
            $accept_ride->offer = $bookingRequest->bid_offer;

            if ($accept_ride->save()) {
                $message = __('Booking request assigned successfully start ride when reach destination.');
                $alertClass = 'alert-success';
            }

            Session::flash('message', $message);
            Session::flash('alert-class', $alertClass);
            return view('pages.logistics.driver.city_bid')->with([
                'is_ride_accepted' => !empty($accepted_ride),
                'bookingReqs' => $bookingRequests,
                'paginator' => (string)$bookingRequests->links()
            ]);
        }
    }

    //start ride when driver reaches destination
    public function startRide(BookingRequest $bookingRequest)
    {
        $accepted_ride = AcceptedRide::where('booking_request_id', $bookingRequest->id)->first();
        $bookingRequests = $bookingRequest->paginate(10);
        $driver = Driver::where('user_id', Auth::user()->id)->first();

        $message = __('Unable to start ride.');
        $alertClass = 'alert-error';

        $accepted_ride = AcceptedRide::where('booking_request_id', $bookingRequest->id)->where('driver_id', $driver->id)->first();

        if ($accepted_ride) {
            $accepted_ride->start_time = Carbon::now();
            $accepted_ride->save();

            $message = __('Ride started successfully.');
            $alertClass = 'alert-success';
        }

        Session::flash('message', $message);
        Session::flash('alert-class', $alertClass);
        return view('pages.logistics.driver.city_bid')->with([
            'is_ride_accepted' => !empty($accepted_ride),
            'bookingReqs' => $bookingRequests,
            'paginator' => (string)$bookingRequests->links()
        ]);
    }

    //end ride when driver reaches destination and update endtime in accepted_rides table
    public function endRide(BookingRequest $bookingRequest)
    {

        $accepted_ride = AcceptedRide::where('booking_request_id', $bookingRequest->id)->first();
        $bookingRequests = $bookingRequest->paginate(10);
        $driver = Driver::where('user_id', Auth::user()->id)->first();

        $message = __('Unable to end ride.');
        $alertClass = 'alert-error';

        $accepted_ride = AcceptedRide::where('booking_request_id', $bookingRequest->id)->where('driver_id', $driver->id)->first();

        if ($accepted_ride) {
            $accepted_ride->end_time = Carbon::now();
            $accepted_ride->save();

            $message = __('Ride ended successfully.');
            $alertClass = 'alert-success';
        }

        Session::flash('message', $message);
        Session::flash('alert-class', $alertClass);
        return view('pages.logistics.driver.city_bid')->with([
            'is_ride_accepted' => !empty($accepted_ride),
            'bookingReqs' => $bookingRequests,
            'paginator' => (string)$bookingRequests->links()
        ]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Logistics\BookingRequest  $bookingRequest
     * @return \Illuminate\Http\Response
     */
    public function show(BookingRequest $bookingRequest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Logistics\BookingRequest  $bookingRequest
     * @return \Illuminate\Http\Response
     */
    public function edit(BookingRequest $bookingRequest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Logistics\BookingRequest  $bookingRequest
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BookingRequest $bookingRequest)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Logistics\BookingRequest  $bookingRequest
     * @return \Illuminate\Http\Response
     */
    public function destroy(BookingRequest $bookingRequest)
    {
        //
    }
}
