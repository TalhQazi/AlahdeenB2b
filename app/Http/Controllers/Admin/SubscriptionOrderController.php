<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\SubscriptionOrderCollection;
use Illuminate\Http\Request;
use App\Models\SubscriptionOrder;
use App\Models\SubscriptionPaymentLog;
use App\Models\TransactionLog;
use App\Traits\PaginatorTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Traits\Helpers\FileUpload;

class SubscriptionOrderController extends Controller
{

    private $noOfItems;
    private $settings;

    use FileUpload;
    
    public function __construct()
    {
        $this->noOfItems = 10; //config('pagination.subscriptions', config('pagination.default'));
        $this->settings =  config('images_configuration.subscription_payments', config('images_configuration.default'));
    }

    public function index(SubscriptionOrder $subscriptionOrder, Request $request)
    {

        $this->authorize('viewAny', SubscriptionOrder::class);
        $subscriptionOrders = '';

        if($request->input('keywords')) {
            $searchParam = $request->input('keywords');

            $subscriptionOrders = $subscriptionOrder->with(['user', 'paymentMethod'])
            ->whereHas('user', function($query) use ($searchParam) {
                $query->where('name', 'like', '%'.$searchParam.'%');
            })
            ->orWhereHas('paymentMethod', function($query) use ($searchParam) {
                $query->where('name', 'like', '%'.$searchParam.'%');
            })
            ->paginate($this->noOfItems);
        } else {

            $subscriptionOrders = $subscriptionOrder->paginate($this->noOfItems);
        }

        $subscriptionOrders = (new SubscriptionOrderCollection($subscriptionOrders))->response()->getData();

        if($request->ajax()) {
            return response()->json(
                [
                    'subscription_orders' => $subscriptionOrders,
                    'paginator' => (string) PaginatorTrait::getPaginator($request, $subscriptionOrders)->links()
                ]
            );
        } else {

             return view('pages.subscription.admin.index')->with([
                'subscription_orders' => $subscriptionOrders,
                'paginator' => PaginatorTrait::getPaginator($request, $subscriptionOrders),
                'subscription_statuses' => $subscriptionOrder->payment_status
             ]);
        }
    }


    public function updatePaymentStatus(Request $request, SubscriptionPaymentLog $subscriptionPaymentLog)
    {
        // dd($request->subscription_id);
        $subscriptionOrder = subscriptionOrder::findOrFail($request->subscription_id);
        // dd($subscriptionOrder);
        $this->authorize('updatePaymentStatus', $subscriptionOrder);
        Validator::make($request->all(), [
            'payment_images' => ['array'],
            'payment_images.*' => ['file', 'nullable', 'max:'.$this->settings['max']],
            'description' => ['required', 'string'],
            'status' => ['required', 'string', Rule::in($subscriptionOrder->payment_status)],
            'amount' => [Rule::RequiredIf($request->status == 'paid' || $request->status == 'refunded'), 'numeric', 'nullable'],
        ])->validate();
        if($subscriptionOrder->status != $request->status) {

            DB::beginTransaction();
                $oldStatus = $subscriptionOrder->status;
                $subscriptionOrder->status = $request->status;

                if($subscriptionOrder->save()) {

                    $subscriptionPaymentLog = tap($subscriptionPaymentLog::create([
                        'subscription_order_id' => $subscriptionOrder->id,
                        'description' => $request->description,
                        'old_status' => $oldStatus,
                        'added_by' => Auth::user()->id,
                        'amount' => ($request->status == "paid" || $request ->status == 'refunded') ? $request->amount : null,
                        'is_closed' => !empty($request->close_subscription) && ($request->status == "cancelled" || $request ->status == "refunded") ? 1 : 0
                    ]),
                    function(SubscriptionPaymentLog $subscriptionPaymentLog) use ($request) {
                        $this->savePaymentImages($request, $subscriptionPaymentLog);
                    });

                    $planSubscribed = true;
                    if($subscriptionOrder->status == "paid") {
                        $planSubscribed = $subscriptionOrder->user->newSubscription(config('subscription.name'), $subscriptionOrder->plan);
                    } else if($oldStatus == "paid" && ( $subscriptionOrder->status == "cancelled" || $subscriptionOrder->status == "refunded" )) {
                        if(!empty($request->close_subscription)) {
                            $planSubscribed = $subscriptionOrder->user->subscription(config('subscription.name'))->cancel(true);
                        } else {
                            $planSubscribed = $subscriptionOrder->user->subscription(config('subscription.name'))->cancel();
                        }
                    }

                    $transactionLogCreated = $this->createTransactionLog($subscriptionOrder, $request);


                    if(($subscriptionPaymentLog != null || $subscriptionPaymentLog->paymentImages != null) && $planSubscribed && $transactionLogCreated) {
                        Session::flash('message', __('Payment status has been updated successfully'));
                        Session::flash('alert-class', 'alert-success');
                        DB::commit();
                    } else {
                        Session::flash('message', __('Unable to update payment status'));
                        Session::flash('alert-class', 'alert-error');
                        DB::rollBack();
                    }


                } else {
                    Session::flash('message', __('Unable to update payment status'));
                    Session::flash('alert-class', 'alert-error');
                    DB::rollBack();
                }


        } else {
            Session::flash('message', __('Selected status is the same as the previous one'));
            Session::flash('alert-class', 'alert-error');
        }

        return redirect()->route('admin.subscriptions.home')->with(['alert-class' => 'alert-success', 'message' => 'Subscription Updated Successfully.']);
    }


    public function savePaymentImages(Request $request, SubscriptionPaymentLog $subscriptionPaymentLog)
    {
      
        $images = $request->payment_images;
        if(!empty($images)) {

            foreach($images as $index => $image) {
                $productImage = $image;
                if(isset($productImage))
                {
                    $imageInfo[$index]['image_path'] = $this->uploadFile($productImage, 'subscription/payment/images', 'subscription-payment-image');
                }
                
            }

            $subscriptionPaymentLog->paymentImages()->createMany($imageInfo);
        }
    }

    public function createTransactionLog(SubscriptionOrder $subscriptionOrder, Request $request)
    {
        return TransactionLog::create([
            'model_id' => $subscriptionOrder->id,
            'model_type' => get_class($subscriptionOrder),
            'user_id' => Auth::user()->id,
            'ip' => $request->getClientIp(),
            'action' => $subscriptionOrder->status == 'pending' ? 'order_created' : 'order_'.$subscriptionOrder->status // order created in pending state
        ]);
    }

}
