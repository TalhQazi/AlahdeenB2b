<?php

namespace App\Http\Controllers;

use App\Models\QuotationRequest;
use App\Models\QuotationRequestDetail;
use App\Traits\ChatTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Musonza\Chat\Facades\ChatFacade;


class QuotationRequestController extends Controller
{
  use ChatTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        if($request->ajax()) {

            Validator::make($request->input(),[
                'product'                => ['array'],
                'product.*.id'           => ['exclude_unless:product.*.is_required,on', 'required', 'exists:App\Models\Product,id'],
                'product.*.title'        => ['exclude_unless:product.*.is_required,on', 'required' ,'string'],
                'product.*.budget'       => ['exclude_unless:product.*.is_required,on', 'required', 'numeric'],
                'product.*.quantity'     => ['exclude_unless:product.*.is_required,on','required','numeric'],
                'product.*.unit'         => ['exclude_unless:product.*.is_required,on','required','string'],
                'product.*.requirements' => ['exclude_unless:product.*.is_required,on','nullable','string'],
                'req_seller_id'          => ['required', 'exists:App\Models\User,id'],
                'req_conversation_id'    => ['required'],
                'product.*.is_required'  => ['nullable', Rule::in(['on'])]
            ]
            )->validate();


            if (array_search('on', array_column($request->product, 'is_required')) !== FALSE) {
                if(!empty($request->product)) {

                    $sentMessage = [];
                    $lastMessage = '';

                    tap(QuotationRequest::create([
                        'buyer_id' => Auth::user()->id,
                        'seller_id' => $request->req_seller_id
                    ]), function(QuotationRequest $quotationRequest) use ($request,&$sentMessage,&$lastMessage){
                        foreach($request->product as $key => $product) {
                            if(!empty($product['is_required'])) {

                                $quotationRequest->details()->save(
                                    new QuotationRequestDetail([
                                        'product_id' => $product['id'],
                                        'product' => $product['title'],
                                        'budget' => $product['budget'],
                                        'quantity' => $product['quantity'],
                                        'unit' => $product['unit'],
                                        'requirements' => $product['requirements'] ?? $product['requirements']
                                    ])
                                );
                            }
                        }

                        $conversation = ChatTrait::getConversation($request->req_conversation_id);
                        $type = 'quotation-request';
                        $lastMessage = __('View Quotation Request');
                        $sentMessage[] = $message = '<div class="view_quotation_request underline cursor-pointer" data-quote-req-id="'.$quotationRequest->id.'">'.__('View Request For Quotation').'</div>';
                        $message = ChatTrait::sendMessage($conversation, Auth::user(), $message, $type);

                    });

                }

                return response()->json(['sent_message' => $sentMessage, 'last_message' => $lastMessage]);
            } else {
                return response()->json(['error_message' => __('Select product against which you require quotation by ticking the checkbox')]);
            }


        }
    }




    /**
     * Display the specified resource.
     *
     * @param  \App\Models\QuotationRequest  $quotationRequest
     * @return \Illuminate\Http\Response
     */
    public function show(QuotationRequest $quotationRequest, Request $request)
    {
        if($request->ajax()) {
           $quotationRequestDetails = $quotationRequest::with(['details', 'details.product', 'details.product.images'])->where('id', $quotationRequest->id)->first();
           return response()->json($quotationRequestDetails);
        } else {
          return redirect()->route('dashboard');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\QuotationRequest  $quotationRequest
     * @return \Illuminate\Http\Response
     */
    public function edit(QuotationRequest $quotationRequest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\QuotationRequest  $quotationRequest
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, QuotationRequest $quotationRequest)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\QuotationRequest  $quotationRequest
     * @return \Illuminate\Http\Response
     */
    public function destroy(QuotationRequest $quotationRequest)
    {
        //
    }
}
