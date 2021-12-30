<?php

namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookingAgreementTerm as ResourcesBookingAgreementTerm;
use App\Http\Resources\BookingAgreementTermCollection;
use App\Models\PaymentMethod;
use App\Models\Warehouse\BookingAgreementTerm;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\PaginatorTrait;


class BookingAgreementTermsController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(BookingAgreementTerm::class);
        $this->noOfItems = config('pagination.warehouse_booking_agreements', config('pagination.default'));
        $this->cart = Cart::instance('booking_agreement_terms');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(BookingAgreementTerm $bookingAgreementTerm, Request $request)
    {
        $invoices = $bookingAgreementTerm->with('booking.bookedBy', 'booking.warehouse', 'booking.warehouse.owner')
                    ->whereHas(
                        'booking.warehouse',
                        function($query) {
                            $query->where('user_id', '=', Auth::user()->id);
                        }
                    )->orWhereHas(
                        'booking.bookedBy',
                        function($query) {
                            $query->where('booked_by', '=', Auth::user()->id);
                        }
                    )->orderBy('created_at', 'desc');

        if($request->input('keywords')) {

            $searchParam = $request->input('keywords');
            $invoices = $invoices->where('start_time', 'like', '%'.$searchParam.'%')
                        ->orWhere('end_time', 'like', '%'.$searchParam.'%')
                        ->orWhere('status', 'like', '%'.$searchParam.'%');
        }

        $invoices = $invoices->paginate($this->noOfItems);
        $invoices = (new BookingAgreementTermCollection($invoices))->response()->getData();

        if ($request->ajax()) {
            return response()->json(['invoices' => $invoices, 'user_id' => Auth::user()->id,  'paginator' => (string) PaginatorTrait::getPaginator($request, $invoices)->links()]);
        } else {
            return view('pages.warehouse-agreement.index')->with([
                'invoices' => $invoices->data,
                'quantity_units' =>  config('quantity_unit'),
                'table_header' => 'components.warehouse-agreement.index.theader',
                'table_body' => 'components.warehouse-agreement.index.tbody',
                'paginator' => PaginatorTrait::getPaginator($request, $invoices)
            ]);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function view(BookingAgreementTerm $bookingAgreementTerm)
    {
        $this->authorize('view', $bookingAgreementTerm);
        $bookingAgreementTerm = $bookingAgreementTerm->with('booking.bookedBy')->where('id',$bookingAgreementTerm->id)->get();
        $terms = (new ResourcesBookingAgreementTerm($bookingAgreementTerm[0]))->response()->getData();

        return view('pages.warehouse-agreement.view')->with([
            'warehouse_booking' => $terms->data,
            'quantity_units' => config('quantity_unit'),
            'is_owner' => $terms->data->warehouse_booking->booked_by->id == Auth::user()->id ? false : true,
        ]);
    }


    /**
     * Function responsible for showing invoice details
     * and a make payment button to add invoice to cart
     * @return \Illuminate\Http\Response
     */
    public function makePayment(BookingAgreementTerm $bookingAgreementTerm)
    {
        $this->authorize('makePayment', $bookingAgreementTerm);
        $bookingAgreementTerm = $bookingAgreementTerm->with('booking.bookedBy')->where('id',$bookingAgreementTerm->id)->get();
        $terms = (new ResourcesBookingAgreementTerm($bookingAgreementTerm[0]))->response()->getData();

        return view('pages.warehouse-agreement.agreement')->with([
            'warehouse_booking' => $terms->data,
            'quantity_units' => config('quantity_unit'),
            'is_owner' => $terms->data->warehouse_booking->booked_by->id == Auth::user()->id ? false : true,
        ]);
    }

    /**
     * Function responsible for adding booking invoice to cart
     * and display cart details.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\WarehouseBookingAgreementTerms  $warehouseBookingAgreementTerms
     * @return \Illuminate\Http\Response
     */
    public function createPayment(Request $request, BookingAgreementTerm $bookingAgreementTerm)
    {
        $this->authorize('makePayment', $bookingAgreementTerm);

        $message = __('Unable to update agreement status');
        $alertClass = 'alert-error';

        $this->cart->destroy(); // remove any previously selected invoice

        $cartItem = $this->cart->add($bookingAgreementTerm, 1);
        $this->cart->setTax($cartItem->rowId, config('tax.warehouse_booking_requestor'));

        return redirect(route('warehousebookings.invoice-details', ['booking_agreement_term' => $bookingAgreementTerm->id]));

    }

    /*
    * function to show cart and available payment methods
    * and option for payment
    * TODO remove it or add it back to the process
    */
    public function viewDetails(BookingAgreementTerm $bookingAgreementTerm)
    {
        $this->authorize('makePayment', $bookingAgreementTerm);

        return view('pages.warehouse-agreement.invoice-details', [
            'cart' => $this->cart,
            'paymentMethods' => PaymentMethod::getAllActive(),
            'invoice_id' => $bookingAgreementTerm->id
        ]);
    }


    public function proceedPayment(Request $request, BookingAgreementTerm $bookingAgreementTerm)
    {
        $this->authorize('makePayment', $bookingAgreementTerm);

        $request->validate(
            [
                'payment_method' => 'required|exists:payment_methods,id',
                'agree_subscription_terms' => 'accepted'
            ],
            [
                'agree_subscription_terms.accepted' => __('Please accept the terms and conditions to proceed')
            ]
        );

        // redirect back with error message if user is already subscibed to a plan
        if ($bookingAgreementTerm->payment_method_id) {
            $paymentMethod = PaymentMethod::find($bookingAgreementTerm->payment_method_id);
            if(!$paymentMethod->is_online) {
                return redirect(route('warehousebookings.invoice-offline-payment', ['booking_agreement_term' => $bookingAgreementTerm->id]));
            }

        }

        $bookingAgreementTerm->payment_method_id = $request->payment_method;

        if ($bookingAgreementTerm->save()) {
            $paymentMethod = PaymentMethod::find($bookingAgreementTerm->payment_method_id);
            // TransactionLog::create([
            //     'model_id' => $order->id,
            //     'model_type' => get_class($order),
            //     'user_id' => $user->id,
            //     'ip' => $request->getClientIp(),
            //     'action' => 'order_created', // order created in pending state
            // ]);

            if ($paymentMethod->is_online) {
                // TODO: redirect to payment gateway
                // TODO: on payment gateway callback success: mark requestor_payment_status to 1, make insertion in payments table and redirect to warehouse  page
                // TODO: on payment gateway callback failure: redirect to failure page with option to go back and subscribe again
            } else {
                session()->flash('invoice', $bookingAgreementTerm);
                return redirect(route('warehousebookings.invoice-offline-payment', ['booking_agreement_term' => $bookingAgreementTerm->id]));
            }
        } else {
            validator()->getMessageBag()->add('order_failed', 'Oops! Sorry we were unable to perform this transaction at the moment, please try again later.');
            return redirect()->back();
        }
    }

    public function offlinePayment(BookingAgreementTerm $bookingAgreementTerm)
    {
        $this->authorize('makePayment', $bookingAgreementTerm);
        return view('pages.warehouse-agreement.offline_payment_guide')->with([
            'invoice' => $bookingAgreementTerm
        ]);
    }
}
