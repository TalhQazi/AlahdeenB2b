<?php

namespace App\Http\Controllers\Logistics;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookingRequest;
use App\Models\City;
use App\Models\Logistics\BookingConsignment;
use App\Models\Logistics\BookingRequest;
use App\Models\Logistics\Vehicle;
use App\Traits\PaginatorTrait;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\Product;

class BookingRequestController extends Controller
{

    private $noOfItems;

    /**
     * @throws AuthorizationException
     */
    public function __construct()
    {
        // $this->authorize(BookingRequest::class);
        $this->noOfItems = config('pagination.booking_request', config('pagination.default'));
    }

    use PaginatorTrait;

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View|JsonResponse
     */
    public function index(BookingRequest $booking_req, Request $request)
    {


        if ($request->input('keywords')) {
            $searchParam = $request->input('keywords');

            $booking_req = $booking_req->where('title', 'like', '%' . $searchParam . '%');

            if (!(Auth::user()->hasRole(['admin', 'super-admin']))) {
                $booking_req = $booking_req->where('shipment_requestor', Auth::user()->id);
            }
        }

        $booking_req = $booking_req->paginate($this->noOfItems);

        if ($request->ajax()) {
            return response()->json(['bookingConsignments' => $booking_req, 'paginator' => (string)PaginatorTrait::getPaginator($request, $booking_req)->links()]);
        } else {

            return view('pages.logistics.booking_request.index')->with([
                'bookingReqs' => $booking_req,
                'paginator' => (string)$booking_req->links()
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View|Response
     */
    public function create()
    {
        $product_list = Product::all();
        return view('pages.logistics.booking_request.form')->with([
            'cities' => City::all(),
            'delivery_types' => config('logistic_booking_request.delivery_types'),
            'vehicles' => Vehicle::all(),
            'vehicle_types' => config('logistic_booking_request.vehicle_types'),
            'weight_units' => config('logistic_booking_request.weight_units'),
            'volume_units' => config('logistic_booking_request.volume_units'),
            'package_types' => config('logistic_booking_request.package_types'),
            'product_list' => $product_list,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreBookingRequest $request
     * @return RedirectResponse
     */
    public function store(StoreBookingRequest $request)
    {

        $message = __('Unable to request booking');
        $alertClass = 'alert-error';

        $validatedData = $request->validated();


        DB::beginTransaction();

        // dd($validatedData['terms_agreed'] == 'on' ? 1 : 0);

        $created = BookingRequest::create([
            'vehicle_id' => !empty($validatedData['child_vehicle_id']) ? $validatedData['child_vehicle_id'] : $validatedData['vehicle_id'],
            'delivery_type' => $validatedData['delivery_type'],
            'pick_up_city_id' => $validatedData['pick_up_city_id'],
            'shipper_name' => $validatedData['shipper_name'],
            'shipper_contact_number' => $validatedData['shipper_contact_number'],
            'shipper_address' => $validatedData['shipper_address'],
            // 'shipper_lat' => $validatedData['shipper_lat'],
            // 'shipper_lng' => $validatedData['shipper_lng'],
            'drop_off_city_id' => $validatedData['drop_off_city_id'],
            'receiver_name' => $validatedData['receiver_name'],
            'receiver_contact_number' => $validatedData['receiver_contact_number'],
            'receiver_address' => $validatedData['receiver_address'],
            // 'receiver_lat' => $validatedData['receiver_lat'],
            // 'receiver_lng' => $validatedData['receiver_lng'],
            'weight' => $validatedData['weight'],
            'weight_unit' => $validatedData['weight_unit'],
            'volume' => $validatedData['volume'],
            'volume_unit' => $validatedData['volume_unit'],
            'departure_date' => $validatedData['departure_date'],
            'departure_time' => $validatedData['departure_time'],
            'bid_offer' => $validatedData['bid_offer'],
            'comments_and_wishes' => $validatedData['comments_and_wishes'],
            'terms_agreed' => $validatedData['terms_agreed'] == 'on' ? 1 : 0,
            'shipment_requestor' => Auth::user()->id,
            'pick_up_country' => $validatedData['pick_up_country'] ?? NULL,
            'drop_off_country' => $validatedData['drop_off_country'] ?? NULL,
            'shipper_lat' => 0.22154,
            'shipper_lng' => 0.22154,
            'receiver_lat' => 0.22154,
            'receiver_lng' => 0.22154,
        ]);

        if ($created) {

            $data = [];
            foreach ($validatedData['item'] as $key => $value) {
                $data[] = new BookingConsignment([
                    'booking_request_id' => $created->id,
                    'product_id' => $value['product_id'],
                    'type_of_packing' => $value['type_of_packing'],
                    'no_of_packs' => $value['no_of_packs'],
                    'description' => $value['description'],
                ]);
            }
            $created->consignment()->saveMany($data);

            DB::commit();

            Session::flash('message', __('Booking request has been sent successfully'));
            Session::flash('alert-class', 'alert-success');
            return redirect()->route('logistics.booking_request.index');

        } else {

            DB::rollBack();
            Session::flash('message', $message);
            Session::flash('alert-class', $alertClass);
            return redirect()->back();

        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param BookingRequest $bookingRequest
     * @return Application|Factory|View
     */
    public function edit(BookingRequest $bookingRequest)
    {

        $b_consg = BookingConsignment::with(['products'])->where('booking_request_id', $bookingRequest->id)->get();

        $pick_up_city_id = City::find($bookingRequest->pick_up_city_id);
        $drop_off_city_id = City::find($bookingRequest->drop_off_city_id);

        $selected_vehicle = Vehicle::find($bookingRequest->vehicle_id);

        return view('pages.logistics.booking_request.form')->with([
            'booking_request' => $bookingRequest,
            'booking_consignments' => $b_consg,
            'cities' => City::all(),
            'delivery_types' => config('logistic_booking_request.delivery_types'),
            'vehicles' => Vehicle::all(),
            'vehicle_types' => config('logistic_booking_request.vehicle_types'),
            'weight_units' => config('logistic_booking_request.weight_units'),
            'volume_units' => config('logistic_booking_request.volume_units'),
            'package_types' => config('logistic_booking_request.package_types'),
            'pick_up_city' => $pick_up_city_id->city,
            'drop_off_city' => $drop_off_city_id->city,
            'selected_vehicle' => $selected_vehicle
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param StoreBookingRequest $request
     * @param BookingRequest $bookingRequest
     * @return RedirectResponse
     */
    public function update(StoreBookingRequest $request, BookingRequest $bookingRequest): RedirectResponse
    {
        $message = __('Unable to update request booking');
        $alertClass = 'alert-error';

        $validatedData = $request->validated();

        DB::beginTransaction();

        BookingConsignment::where('booking_request_id', $bookingRequest->id)->delete();

        $bookingRequest->vehicle_id = !empty($validatedData['child_vehicle_id']) ? $validatedData['child_vehicle_id'] : $validatedData['vehicle_id'];
        $bookingRequest->delivery_type = $validatedData['delivery_type'];
        $bookingRequest->pick_up_city_id = $validatedData['pick_up_city_id'];
        $bookingRequest->shipper_name = $validatedData['shipper_name'];
        $bookingRequest->shipper_contact_number = $validatedData['shipper_contact_number'];
        $bookingRequest->shipper_address = $validatedData['shipper_address'];
        //        $bookingRequest->shipper_lat = $validatedData['shipper_lat'];
        //        $bookingRequest->shipper_lng = $validatedData['shipper_lng'];
        $bookingRequest->drop_off_city_id = $validatedData['drop_off_city_id'];
        $bookingRequest->receiver_name = $validatedData['receiver_name'];
        $bookingRequest->receiver_contact_number = $validatedData['receiver_contact_number'];
        $bookingRequest->receiver_address = $validatedData['receiver_address'];
        //        $bookingRequest->receiver_lat = $validatedData['receiver_lat'];
        //        $bookingRequest->receiver_lng = $validatedData['receiver_lng'];
        $bookingRequest->weight = $validatedData['weight'];
        $bookingRequest->weight_unit = $validatedData['weight_unit'];
        $bookingRequest->volume = $validatedData['volume'];
        $bookingRequest->volume_unit = $validatedData['volume_unit'];
        $bookingRequest->departure_date = $validatedData['departure_date'];
        $bookingRequest->departure_time = $validatedData['departure_time'];
        $bookingRequest->bid_offer = $validatedData['bid_offer'];
        $bookingRequest->comments_and_wishes = $validatedData['comments_and_wishes'];
        $bookingRequest->terms_agreed = $validatedData['terms_agreed'] == 'on' ? 1 : 0;
        $bookingRequest->pick_up_country = $validatedData['pick_up_country'];
        $bookingRequest->drop_off_country = $validatedData['drop_off_country'];
        $bookingRequest->shipper_lat = 0.22154;
        $bookingRequest->shipper_lng = 0.22154;
        $bookingRequest->receiver_lat = 0.22154;
        $bookingRequest->receiver_lng = 0.22154;

        if ($bookingRequest->save()) {

            $data = [];
            foreach ($validatedData['item'] as $key => $value) {
                $data[] = new BookingConsignment([
                    'booking_request_id' => $bookingRequest->id,
                    'product_id' => $value['product_id'],
                    'type_of_packing' => $value['type_of_packing'],
                    'no_of_packs' => $value['no_of_packs'],
                    'description' => $value['description'],
                ]);
            }
            $bookingRequest->consignment()->saveMany($data);

            DB::commit();
            Session::flash('message', __('Purchase return request has been updated successfully'));
            Session::flash('alert-class', 'alert-success');
            return redirect()->route('logistics.booking_request.index');
        } else {
            DB::rollBack();
            Session::flash('message', $message);
            Session::flash('alert-class', $alertClass);
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param BookingRequest $bookingRequest
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy(BookingRequest $bookingRequest): RedirectResponse
    {
        $message = __('Unable to delete booking request.');
        $alertClass = 'alert-error';

        // $this->authorize('delete', $bookingRequest);
        if ($bookingRequest->delete()) {
            $message = __('Booking request deleted successfully');
            $alertClass = 'alert-success';
        }

        Session::flash('message', $message);
        Session::flash('alert-class', $alertClass);
        return redirect()->route('logistics.booking_request.index');
    }
}
