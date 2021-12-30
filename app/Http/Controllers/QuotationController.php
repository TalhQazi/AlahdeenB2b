<?php

namespace App\Http\Controllers;

use App\Mail\SellerQuotationToBuyer;
use App\Models\ProductBuyRequirement;
use App\Models\User;
use App\Models\Quotation;
use App\Models\QuotationProduct;
use App\Models\QuotationSellerDetail;
use App\Models\QuotationTerm;
use App\Traits\ChatTrait;
use App\Traits\FileTrait;
use App\Traits\PackageUsageTrait;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Musonza\Chat\Models\Conversation;
use Illuminate\Support\Facades\Mail;
use App\Traits\Helpers\MyFileUpload;

class QuotationController extends Controller
{
    use ChatTrait, PackageUsageTrait, FileTrait;
    use MyFileUpload;

    private $error;

    private $buyer;

    private $seller;

    /**
     * Create quotation pdf
     *
     * @return quotation pdf path
     */
    public function createPdf(Request $request)
    {
        if ($request->ajax()) {

            Validator::make(
                $request->all(),
                [
                    'product' => ['array'],
                    'product.*' => ['string', 'required'],
                    'q_image' => ['array'],
                    'q_image.*' => ['nullable', 'file', 'max: 2048'],
                    'q_image_path' => ['array'],
                    'q_image_path.*' => ['nullable', 'string'],
                    'quantity' => ['array'],
                    'quantity.*' => ['required', 'numeric'],
                    'price' => ['array'],
                    'price.*' => ['required', 'numeric'],
                    'unit' => ['array'],
                    'unit.*' => ['string', Rule::in(config('quantity_unit'))],
                    'description' => ['array'],
                    'description.*' => ['nullable', 'string'],

                    'discount' => ['nullable', 'numeric', 'min:0'],
                    'applicable_tax' => ['nullable', 'numeric'],
                    'shipping_tax' => ['nullable', 'numeric'],
                    'period' => ['nullable', 'numeric', 'min:1'],
                    'period_unit' => ['nullable', 'string', Rule::in(['days', 'weeks', 'months'])],
                    'payment_terms' => ['nullable', 'string'],
                    'additional_info' => ['nullable', 'string'],

                    'primary_email' => ['required', 'email'],
                    'alternate_email' => ['nullable', 'email'],
                    'phone' => ['required', 'string'],
                    'address' => ['required', 'string'],

                ],
                [
                    // custom validation messages
                ],
                [
                    'q_image.*' => 'product image',
                    'price.*' => 'price'

                ]
            )->validate();

            return $this->generatePdf($request);
        }
    }

    public function generatePdf(Request $request)
    {
        $buyerDetails = User::with('business')->where('id', $request->buyer_id)->get();

        $data = $request->all();

        $sellerBusinessDetails = '';
        if (!empty(Auth::user()->business)) {
            $sellerBusinessDetails = Auth::user()->business()->first();
        }

        $data['seller_details'] = $sellerBusinessDetails;

        $data['buyer_details'] = $buyerDetails[0];
        $pdf = PDF::loadView('components.quotation.template', ['data' => $data]);
        $fileName =  time() . '.' . 'pdf';

        $content = $pdf->download()->getOriginalContent();
        $this->putFile('public/tmp/quotations/', $content, $fileName);
        $pdfPath = $this->getFileUrl('public/tmp/quotations/' . $fileName);

        return response()->json(['file_path' => $pdfPath]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeAndSend(Request $request)
    {
        if ($request->ajax()) {
            $this->buyer = User::find($request->buyer_id);
            $this->seller = Auth::user();

            $conversation = ChatTrait::getParticipantsConversation($this->buyer, $this->seller);

            //Conversation doesn't exist between buyer and seller
            if (empty($conversation)) {
                $resp = $this->createChatSendQuote($request);
            } else {
                $resp = $this->createSendQuote($request, $conversation);
            }

            if ($resp) {
                return response()->json($resp);
            } else {
                return response()->json([
                    'message' => $this->error,
                    'alert-class' => 'alert-error'
                ]);
            }
        } else {
            return redirect()->route('dashboard');
        }
    }


    public function createQuotation(Request $request)
    {
        $tempPath = str_replace('/storage', 'public', $request->quotation_path);
        $newPath = str_replace('/storage/tmp', 'public', $request->quotation_path);
        $this->moveFile($tempPath, $newPath);
        $this->deleteFile($tempPath);

        return tap(Quotation::create([
            'buyer_id' => $request->buyer_id,
            'seller_id' => Auth::user()->id,
            'quotation_path' => $newPath
        ]), function (Quotation $quotation) use ($request) {
            $products = [];
            foreach ($request->product as $key => $product) {

                $imagePath = $request->file('q_image');
                if (!empty($request->q_image_path[$key])) {
                    $imagePath = $request->q_image_path[$key];
                } else if (!empty($imagePath)) {
                    $productImage = $imagePath;
                    if(isset($productImage))
                    {
                        $imagePath = $this->uploadFile($productImage, 'quotation/pdf', 'quotation-pdf');
                    }
                }


                $products[] = new QuotationProduct([
                    'product' => $product,
                    'image_path' => $imagePath,
                    'quantity' => $request->quantity[$key],
                    'price' => $request->price[$key],
                    'unit' => $request->unit[$key],
                    'description' => $request->description[$key]
                ]);
            }

            $quotation->products()->saveMany($products);

            $quotation->terms()->save(
                new QuotationTerm([
                    'discount' => $request->discount ?? 0,
                    'applicable_taxes' => $request->applicable_tax ?? 0,
                    'shipping_taxes' => $request->shipping_tax ?? 0,
                    'delivery_period' => $request->period ? $request->period . ' ' . $request->period_unit : NULL,
                    'payment_terms' => $request->payment_terms ?? NULL,
                    'additional_info' => $request->additional_info ?? NULL,
                ])
            );

            $quotation->terms()->save(
                new QuotationSellerDetail([
                    'email' => $request->primary_email,
                    'alternate_email' => $request->alternate_email,
                    'phone' => $request->phone_full,
                    'address' => $request->address
                ])
            );
        });
    }

    private function createChatSendQuote(Request $request)
    {
        $subscription = $this->getSubscription($this->seller->activeSubscriptions(), 'leads');

        if ($subscription) {
            if ($this->consumeLead($subscription, 'leads')) {

                //Create conversation
                if (!empty($request->post_buy_req_id)) {
                    $conversation = ChatTrait::createConversation($this->buyer, $this->seller, ProductBuyRequirement::find($request->post_buy_req_id)->toArray());
                } else {
                    $conversation = ChatTrait::createConversation($this->buyer, $this->seller);
                }

                $quotation = $this->createQuotation($request);

                if ($quotation) {
                    ChatTrait::sendMessage($conversation, $this->seller, __('Hi ') . $this->buyer->name);
                    return $this->sendQuote($conversation, $quotation);
                } else {
                    $this->error = __('Unable to send quotation');
                    return false;
                }
            } else {
                //All subscription have the leads consumed
                $this->error = __('Need to purchase additional leads, since the leads limit has exceeded for the current package');
                return false;
            }
        } else {
            $this->error = __('Need to purchase one of the packages before you can contact the buyer');
            return false;
        }
    }

    private function createSendQuote(Request $request, Conversation $conversation)
    {
        $quotation = $this->createQuotation($request);

        if ($quotation) {
            //seller contacts buyer(Already a contact in chat) from buy leads section, we need to consume lead
            if (!empty($request->post_buy_req_id)) {
                $subscription = $this->getSubscription($this->seller->activeSubscriptions(), 'leads');
                if ($subscription) {
                    if ($this->consumeLead($subscription, 'leads')) {
                        return $this->sendQuote($conversation, $quotation);
                    } else {
                        $this->error = __('Need to purchase additional leads, since the leads limit has exceeded for the current package');
                        return false;
                    }
                } else {
                    $this->error = __('Need to purchase one of the packages before you can contact the buyer');
                    return false;
                }
            } else {
                return $this->sendQuote($conversation, $quotation);
            }
        } else {
            $this->error = __('Unable to send quotation');
            return false;
        }
    }

    private function sendQuote(Conversation $conversation, Quotation $quotation): array
    {
        $quotationSent = ChatTrait::sendQuotation($conversation, $quotation->quotation_path, $this->seller);

        if ($this->buyer->email) {
            Mail::send(new SellerQuotationToBuyer($this->buyer, $this->seller, $quotation));
        }

        return [
            'sent_message' => $quotationSent['sent_message'],
            'last_message' => $quotationSent['last_message']
        ];
    }
}
