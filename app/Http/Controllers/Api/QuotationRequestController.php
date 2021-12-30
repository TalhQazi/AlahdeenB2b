<?php

namespace App\Http\Controllers\Api;

use App\Events\RecordStatsEvent;
use App\Http\Controllers\Controller;
use App\Mail\ContactSupplier;
use App\Models\Product;
use App\Models\QuotationRequest;
use App\Models\QuotationRequestDetail;
use App\Models\User;
use App\Traits\ApiResponser;
use App\Traits\ChatTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;

class QuotationRequestController extends Controller
{
    use ChatTrait, ApiResponser;

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        Validator::make($request->input(),[
            'product_id'            => ['required', 'exists:App\Models\Product,id'],
            'budget'                => ['required', 'numeric'],
            'quantity'              => ['required','numeric'],
            'unit'                  => ['required','string'],
            'requirements'          => ['nullable','string'],
        ]
        )->validate();


        $sentMessage = [];
        $lastMessage = '';

        $product = Product::find($request->product_id);

        tap(QuotationRequest::create([
            'buyer_id' => Auth::user()->id,
            'seller_id' => $product->user_id
        ]), function(QuotationRequest $quotationRequest) use ($request, $product, &$sentMessage, &$lastMessage){

            $quotationRequest->details()->save(
                new QuotationRequestDetail([
                    'product_id' => $product->id,
                    'product' => $product->title,
                    'budget' => $request->budget,
                    'quantity' => $request->quantity,
                    'unit' => $request->unit,
                    'requirements' => $request->requirements ?? $request->requirements
                ])
            );

            $buyer = Auth::user();
            $seller = $product->user;

            $conversation = ChatTrait::getParticipantsConversation($buyer, $seller);
            if(empty($conversation)) {
                $conversation = ChatTrait::createConversation($buyer, $seller, $quotationRequest->toArray());
            }

            $lastMessage = __('View Quotation Request');
            $sentMessage[] = $message = '<div class="view_quotation_request underline cursor-pointer" data-buyer="'.Auth::user()->id.'" data-quote-req-id="'.$quotationRequest->id.'">'.__('View Request For Quotation').'</div>';
            $message = ChatTrait::sendMessage($conversation, $buyer, $message, 'quotation-request');

            Mail::send(new ContactSupplier($buyer, $seller));

            RecordStatsEvent::dispatch([$product], 'contacted_supplier', 'product');

        });

        return $this->success(
            [],
            __('Supplier has been contacted successfully')
        );

    }

}
