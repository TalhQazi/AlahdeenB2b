<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\WarehouseBooking as ResourcesWarehouseBooking;
use App\Models\Warehouse\BookingAgreementTerm;
use App\Models\Warehouse\Warehouse;
use App\Models\Warehouse\WarehouseBooking;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests\StoreWarehouseBookingAgreement;
use App\Http\Resources\BookingAgreementTerm as ResourcesBookingAgreementTerm;
use App\Http\Resources\BookingAgreementTermCollection;
use App\Models\Payment;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use App\Traits\PaginatorTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\RequiredIf;

class BookingAgreementTermsController extends Controller
{
    private $noOfItems;
    private $settings;

    public function __construct()
    {
        $this->authorizeResource(BookingAgreementTerm::class);
        $this->noOfItems = config('pagination.warehouse_booking_agreements', config('pagination.default'));
        $this->settings =  config('images_configuration.warehouse_booking_invoices', config('images_configuration.default'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(BookingAgreementTerm $bookingAgreementTerm, Request $request)
    {
        $invoices = $bookingAgreementTerm->with('booking', 'booking.bookedBy', 'booking.warehouse', 'booking.warehouse.owner')->orderBy('created_at','desc');

        if($request->input('keywords')) {
            $searchParam = $request->input('keywords');
            $warehouseBookings = $bookingAgreementTerm::withTrashed()->with(config('relation_configuration.warehouse_booking.index'))
            ->whereHas('booked_by', function($query) use ($searchParam) {
                $query->where('name', 'like', '%'.$searchParam.'%');
            })
            ->orWhereHas('locality', function($query) use ($searchParam) {
                $query->where('name', 'like', '%'.$searchParam.'%');
            });
        }

        $invoices = $invoices->paginate($this->noOfItems);
        $invoices = (new BookingAgreementTermCollection($invoices))->response()->getData();

        if ($request->ajax()) {
            return response()->json(['warehouse_bookings' => $warehouseBookings, 'paginator' => (string) PaginatorTrait::getPaginator($request, $warehouseBookings)->links()]);
        } else {
            return view('pages.warehouse-agreement.admin.index')->with([
                'invoices' => $invoices->data,
                'payment_methods' => PaymentMethod::getAllActive(),
                'payment_statuses' => $bookingAgreementTerm->payment_status,
                'quantity_units' =>  config('quantity_unit'),
                'table_header' => 'components.booking-invoices.admin.theader',
                'table_body' => 'components.booking-invoices.admin.tbody',
                'paginator' => PaginatorTrait::getPaginator($request, $invoices)
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(WarehouseBooking $warehouseBooking)
    {
        $warehouse = Warehouse::find($warehouseBooking->warehouse_id);
        $warehouseBooking = (new ResourcesWarehouseBooking($warehouseBooking))->response()->getData();
        return view('pages.warehouse-agreement.admin.create')->with([
            'warehouse_booking' => $warehouseBooking->data,
            'quantity_units' => config('quantity_unit'),
            'price' => round(($warehouse->price / 30) * Carbon::parse($warehouseBooking->data->start_time)->diffInDays($warehouseBooking->data->end_time)),
        ]);
    }

     /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function view(BookingAgreementTerm $bookingAgreementTerm)
    {
        $bookingAgreementTerm = (new ResourcesBookingAgreementTerm($bookingAgreementTerm))->response()->getData();
        return view('pages.warehouse-agreement.admin.view')->with([
            'invoice_details' => $bookingAgreementTerm->data,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(WarehouseBooking $warehouseBooking, StoreWarehouseBookingAgreement $request)
    {
        $alertClass = "alert-error";
        $message = __('Unable to create invoice');

        $validatedData = $request->validated();

        $validatedData['start_time'] = $validatedData['start_date'] . ' ' . $validatedData['start_time'];
        $validatedData['end_time'] = $validatedData['end_date'] . ' ' . $validatedData['end_time'];

        $created = BookingAgreementTerm::create([
            'booking_id' => $warehouseBooking->id,
            'item' => $validatedData['item'],
            'description' => $validatedData['description'],
            'start_time' => $validatedData['start_time'],
            'end_time' => $validatedData['end_time'],
            'type' => $validatedData['type'] ?? 'fully',
            'quantity' => $validatedData['quantity'] ?? NULL,
            'unit' => $validatedData['unit'] ?? '',
            'area' => $validatedData['area'] ?? NULL,
            'goods_value' => $validatedData['goods_value'] ?? NULL,
            'price' => $validatedData['price'],
            'user_terms' => $validatedData['user_terms'],
            'owner_terms' => $validatedData['owner_terms'],
            'created_by' => Auth::user()->id,
            'creator_role' => Role::where('name',Auth::user()->getRoleNames())->get()->pluck('id')[0],

        ]);

        if($created) {
            $alertClass = "alert-success";
            $message = __('Invoice has been created successfully');
        }

        Session::flash('message', $message);
        Session::flash('alert-class', $alertClass);
        return redirect()->route('admin.warehousebookings.invoices');

    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\WarehouseBookingAgreementTerms  $warehouseBookingAgreementTerms
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request, BookingAgreementTerm $bookingAgreementTerm, Payment $payment)
    {
        $alertClass = "alert-error";
        $message = __('Unable to update invoice status');

        Validator::make($request->all(), [
            'ref_image' => ['image', 'nullable', 'mimes:'.$this->settings['mimes'], 'max:'.$this->settings['max']],
            'ref_text' => ['required', 'string'],
            'transaction_date' => ['required', 'date', 'date_format:Y-m-d'],
            'payment_method_id' => ['required', Rule::in(PaymentMethod::getAllActive()->pluck('id')->toArray())],
            'status' => ['required', 'string', Rule::in($bookingAgreementTerm->payment_status)],
            'amount' => [Rule::requiredIf($request->status == 'refunded') ,'integer', 'nullable'],
        ])->validate();

        DB::beginTransaction();

        $commissionCollected = 0;
        $taxCollected = 0;

        if($request->status == "received") {
            $bookingAgreementTerm->requestor_payment_status = 1;
            $request->amount = $bookingAgreementTerm->price - $commissionCollected - $taxCollected;
        } else if($request->status == "paid") {
            $bookingAgreementTerm->owner_paid_status = 1;
            $commissionCollected = $bookingAgreementTerm->price * ($bookingAgreementTerm->commission_percentage / 100);
            $bookingAgreementTerm->commission_paid = $commissionCollected;
            $taxCollected = $bookingAgreementTerm->price * ($bookingAgreementTerm->tax_percentage / 100);
            $bookingAgreementTerm->tax_amount = $taxCollected;
            $bookingAgreementTerm->total_paid_to_owner = $bookingAgreementTerm->price - $commissionCollected - $taxCollected;
            if($bookingAgreementTerm->requestor_payment_status && $bookingAgreementTerm->owner_paid_status) {
                $bookingAgreementTerm->status = "confirmed";
            }
            $request->amount = $bookingAgreementTerm->total_paid_to_owner;
        } else {
            $bookingAgreementTerm->status = $request->status;
        }

        if($bookingAgreementTerm->save()) {

            if (!empty($request->ref_image)) {
                $payment->ref_image_document = $request->ref_image->store('public/payment/images');
            }

            $payment->ref_text = $request->ref_text;
            $payment->transaction_date = $request->transaction_date;
            $payment->payment_method_id = $request->payment_method_id;
            $payment->payment_status = $request->status;
            $payment->amount = $request->status == "refunded" ? $bookingAgreementTerm->price : $request->amount;
            $payment->updated_by = Auth::user()->id;
            $payment->is_closed = !empty($request->close_subscription) && ($request->status == "cancelled" || $request ->status == "refunded") ? 1 : 0;
            $payment->model_id = $bookingAgreementTerm->id;
            $payment->model_type = get_class($bookingAgreementTerm);
            // $payment->payment()->associate($bookingAgreementTerm);
            // dd($payment->payment()->associate($bookingAgreementTerm));
            if($payment->save()) {
                $alertClass = "alert-success";
                $message = __('Invoice status updated successfully');
                DB::commit();
            } else {
                DB::rollBack();
            }


        } else {
            DB::rollBack();
        }

        Session::flash('message', $message);
        Session::flash('alert-class', $alertClass);

        return redirect()->route('admin.warehousebookings.invoices');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\WarehouseBookingAgreementTerms  $warehouseBookingAgreementTerms
     * @return \Illuminate\Http\Response
     */
    public function destroy(BookingAgreementTerm $bookingAgreementTerm)
    {
        //
    }
}
