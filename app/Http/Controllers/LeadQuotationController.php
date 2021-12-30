<?php

namespace App\Http\Controllers;

use App\Models\LeadQuotation;
use App\Models\ProductBuyRequirement;
use App\Traits\ChatTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class LeadQuotationController extends Controller
{
    use ChatTrait;

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(ProductBuyRequirement $productBuyRequirement)
    {
        $conversation = ChatTrait::getParticipantsConversation($productBuyRequirement->buyer, Auth::user());
        if(!empty($conversation)) {
            Session::flash('message', __('Buyer already exists in the contacts list'));
            Session::flash('alert-class', 'alert-success');
            return redirect()->route('chat.messages');
        }

        if(Auth::user()->activeSubscriptions()->isNotEmpty()) {
            if($this->checkFeatureUsage(Auth::user()->activeSubscriptions())) {
                return view('pages.lead-quotation.create')->with([
                    'lead_info' => $productBuyRequirement,
                    'quantity_units' => config('quantity_unit')
                ]);

            } else {
                Session::flash('message', __('Need to purchase new package, since the leads limit has exceeded'));
                Session::flash('alert-class', 'alert-error');
                return redirect()->route('subscription.home');
            }
        } else {
            Session::flash('message', __('Need to purchase one of the packages before you can contact the buyer'));
            Session::flash('alert-class', 'alert-error');
            return redirect()->route('subscription.home');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductBuyRequirement $productBuyRequirement, Request $request)
    {
        Validator::make($request->input(), [
            'product' => ['required', 'string'],
            'quantity' => ['required', 'numeric'],
            'unit' => ['required', 'string', Rule::in(config('quantity_unit'))],
            'price' => ['required', 'numeric']
        ],
        [],
        )->validate();

        $created = LeadQuotation::create([
            'seller_id' => Auth::user()->id,
            'lead_id' => $productBuyRequirement->id,
            'product' => $request->product,
            'quantity' => $request->quantity,
            'unit' => $request->unit,
            'price' => $request->price,
        ]);

        if($created) {
            return redirect()->route('chat.create', ['product_buy_requirement' => $productBuyRequirement]);
        } else {
            $request->session()->flash('message', __('Unable to create quoation'));
            $request->session()->flash('alert-class', 'alert-error');

            return redirect()->route('lead-quotation.create', ['product_buy_requirement' => $productBuyRequirement]);
        }
    }

    public function checkFeatureUsage($subscriptions, $leadType = 'leads')
    {
        foreach($subscriptions as $subscription) {

            $plan = app('rinvex.subscriptions.plan')->find($subscription->plan_id);

            if( $subscription->getFeatureValue('leads_'.$plan->slug) > 0 ) {
                return true;
            }
        }

        return false;
    }
}
