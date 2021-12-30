<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use App\Models\Plan;
use App\Models\SubscriptionOrder;
use App\Models\TransactionLog;
use App\Models\User;
use App\Traits\PackageUsageTrait;
use Gabievi\Promocodes\Facades\Promocodes;
use Illuminate\Support\Facades\Auth;
use Session;

class SubscriptionController extends Controller
{
    use PackageUsageTrait;

    protected $cart;

    public function __construct()
    {
        $this->cart = Cart::instance('subscription');
    }

    public function index()
    {
        // dd(auth()->user()->roles);

        // dd(Session::get('user_type'));
        $plans = Plan::where('slug', 'not like', 'bonus%')->get();

        $subscriptions = Auth::user()->subscriptions;

        $subscriptionOrders = User::find(Auth::user()->id)->subscriptionOrders;

        return view('pages.subscription.index')->with(
            [
                'plans' => $plans,
                'subscriptions' => $subscriptions,
                'subscriptionOrders' => $subscriptionOrders,
            ]
        );
    }

    public function selectPackage(Request $request)
    {
        $request->validate([
            'plan' => 'required|exists:plans,id'
        ]);

        $plan = Plan::find($request->plan);

        $this->cart->destroy(); // remove any previously selected package
        session()->forget('subscription');

        $this->cart->add($plan, 1);

        return redirect(route('subscription.details'));
    }

    public function show()
    {
        return view('pages.subscription.details', [
            'cart' => $this->cart,
            'paymentMethods' => PaymentMethod::getAllActive(),
        ]);
    }

    public function removeCartItem($rowId)
    {
        $this->cart->remove($rowId);

        return redirect(route('subscription.details'));
    }

    public function applyCoupon(Request $request)
    {
        $request->validate([
            'promocode' => 'required|exists:promocodes,code'
        ]);

        $promocode = Promocodes::check($request->promocode);

        if ($promocode) {
            foreach ($this->cart->content() as $cartItem) {
                $this->cart->setDiscount($cartItem->rowId, $promocode->reward);
            }

            session()->put('subscription', [
                'promocode' => $promocode,
            ]);
        } else {
            validator()->getMessageBag()->add('invalid_coupon', 'Invalid or Already Used Promocode');
        }

        return redirect(route('subscription.details'));
    }

    public function proceedPayment(Request $request)
    {
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
        if (count(Auth::user()->activeSubscriptions()) > 0) {
            $request->session()->flash('message', __('You are already subscribed to a plan, please contact support if you want any changes'));
            $request->session()->flash('alert-class', 'alert-error');
            return redirect()->back();
        }

        $user = Auth::user();
        $orderDetails = $this->cart->content()->first();

        // redeem promocode
        $promocode = session('subscription.promocode.code');
        Promocodes::redeem(session('subscription.promocode.code'));

        $order = SubscriptionOrder::create([
            'user_id' => $user->id,
            'plan_id' => $orderDetails->id,
            'payment_method_id' => $request->payment_method,
            'promo_code' => $promocode,
            'total_amount' => (float) str_replace(',', '', $this->cart->total()),
            'total_tax' => (float) str_replace(',', '', $this->cart->tax()),
            'total_discount' => (float) str_replace(',', '', $this->cart->discount()),
            'notes' => $request->additional_info,
        ]);

        if ($order) {
            $paymentMethod = PaymentMethod::find($order->payment_method_id);
            TransactionLog::create([
                'model_id' => $order->id,
                'model_type' => get_class($order),
                'user_id' => $user->id,
                'ip' => $request->getClientIp(),
                'action' => 'order_created', // order created in pending state
            ]);

            if ($paymentMethod->is_online) {
                // TODO: redirect to payment gateway
                // TODO: on payment gateway callback success: activate package and redirect to subscriptions page
                // TODO: on payment gateway callback failure: redirect to failure page with option to go back and subscribe again
            } else {
                session()->flash('order', $order);
                return redirect(route('subscription.offlinePayment'));
            }
        } else {
            validator()->getMessageBag()->add('order_failed', 'Oops! Sorry we were unable to perform this transaction at the moment, please try again later.');
            return redirect()->back();
        }
    }

    public function offlinePayment()
    {
        if(session()->has('order')) {
            return view('pages.subscription.offline_payment_guide', ['order' => session()->get('order')]);
        }

        return redirect(route('subscription.home'));
    }

    public function checkPackageAvailability(Request $request)
    {
      $canContactBuyer = 1;
      if($this->checkSubscriptionLeads(Auth::user(), 'leads')) {
        $canContactBuyer = 1;
      }

      if($request->ajax()) {
        return response()->json([
          'can_contact' => $canContactBuyer
        ]);
      } else {
        return $canContactBuyer;
      }
    }
}
