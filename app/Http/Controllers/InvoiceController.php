<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInvoice;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\InvoiceAttachment;
use App\Models\InvoiceItem;
use App\Models\PromotionalProduct;
use App\Models\User;
use App\Notifications\ShareInvoice;
use App\Traits\FileTrait;
use App\Traits\ImageTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use App\Traits\Helpers\MyFileUpload;

class InvoiceController extends Controller
{
    use ImageTrait, FileTrait, MyFileUpload;

    public function __construct()
    {
        $this->authorizeResource(Invoice::class);
    }

    public function index(Request $request)
    {

        if($request->session()->get('user_type') == "seller") {

            $invoices = Auth::user()->invoicesIssued->toArray();
            foreach($invoices as $key => $invoiceIssued) 
            {
                $invoices[$key]['seller_details'] = json_decode($invoiceIssued['seller_details']);
                $invoices[$key]['buyer_details'] = json_decode($invoiceIssued['buyer_details']);
                $invoices[$key]['terms_and_conditions'] = json_decode($invoiceIssued['terms_and_conditions']);
                $invoices[$key]['created_by'] = User::find($invoiceIssued['updated_by']);
            }
          } else if($request->session()->get('user_type') == "buyer") {

            $invoices = Auth::user()->invoicesReceived->toArray();
            foreach($invoices as $key => $invoiceReceived) {

              $invoices[$key]['seller_details'] = json_decode($invoiceReceived['seller_details']);
              $invoices[$key]['buyer_details'] = json_decode($invoiceReceived['buyer_details']);
              $invoices[$key]['terms_and_conditions'] = json_decode($invoiceReceived['terms_and_conditions']);
              $invoices[$key]['created_by'] = User::find($invoiceReceived['updated_by']);
            }
          }

          return view('pages.khata.invoices', [
              'invoices' => $invoices
          ]);
    }

    public function create(Client $client)
    {
      $data['clients'] = User::whereIn('id', Auth::user()->clients->pluck('client_id'))->get();

      if(Auth::user()->hasRole(['corporate', 'business']) && empty(Auth::user()->business)) {

        Session::flash('message', __('Company details needs to be added before trying to create Invoice'));
        Session::flash('alert-class', 'alert-error');

        return redirect()->route('profile.business.home');

      } else if($data['clients']->isEmpty()) {
        Session::flash('message', __('Clients needs to be added or invited before trying to create Invoice'));
        Session::flash('alert-class', 'alert-error');

        return redirect()->route('khata.client.home');
      } else {

        $data['client'] = $client ?? null;
        $data['user'] = Auth::user();
        $data['quantity_units'] = config('quantity_unit');
        $data['company_logo'] = !empty(Auth::user()->business->additionalDetails->logo) ? Storage::url(Auth::user()->business->additionalDetails->logo) : NULL;
        return view('pages.khata.create_invoice', $data);
      }
    }

    public function store(StoreInvoice $request)
    {

      $validatedData = $request->validated();
      DB::beginTransaction();
      $last_record = DB::table('invoices')->orderBy('id','desc')->first();
      $sellerDetails = [
        'company_name' => !empty(Auth::user()->business) && !empty(Auth::user()->business->company_name) ? Auth::user()->business->company_name : NULL,
        'address' =>  !empty(Auth::user()->business) && !empty(Auth::user()->business->address) ? Auth::user()->business->address : Auth::user()->address,
        'phone' =>  !empty(Auth::user()->business) && !empty(Auth::user()->business->phone_number) ? Auth::user()->business->phone_number : Auth::user()->phone,
        'alternate_website' => !empty(Auth::user()->business) && !empty(Auth::user()->business->alternate_website) ? Auth::user()->business->alternate_website : NULL,
      ];


      $buyer = User::find($validatedData['client_id']);
      $buyerDetails = [
        'name' => $buyer->name,
        'company_name' => !empty($buyer->business) && !empty($buyer->business->company_name) ? $buyer->business->company_name : NULL,
        'address' =>  !empty($buyer->business) && !empty($buyer->business->address) ? $buyer->business->address : $buyer->address,
        'phone' =>  !empty($buyer->business) && !empty($buyer->business->phone_number) ? $buyer->business->phone_number : $buyer->phone,
        'alternate_website' => !empty($buyer->business) && !empty($buyer->business->alternate_website) ? $buyer->business->alternate_website : NULL,
      ];


      $inv_id = !empty($last_record) ? $last_record->id + 1 : 1;

      $invoice = tap(Invoice::create([
        'number' => Carbon::now()->timestamp . "-" . $inv_id . "-" .Auth::user()->id,
        'invoice_date' => $validatedData['invoice_date'],
        'payment_due_date' => $validatedData['payment_due_date'],
        'delivery_date' => $validatedData['delivery_date'],
        'seller_id' => Auth::user()->id,
        'seller_details' => json_encode($sellerDetails),
        'buyer_id' => $validatedData['client_id'],
        'buyer_details' => json_encode($buyerDetails),
        'terms_and_conditions' => !empty($validatedData['terms_and_conditions']) ? json_encode($validatedData['terms_and_conditions']) : NULL,
        'contact_email' => $validatedData['contact_email'] ?? NULL,
        'contact_phone' => $validatedData['contact_phone'] ?? NULL,
        'freight_charges' => $validatedData['freight_charges'] ?? 0,
        'updated_by' => Auth::user()->id,
      ]), function(Invoice $invoice) use($validatedData) {
        $items = [];
        foreach ($validatedData['item'] as $key => $product) {

          if(!empty($product['promotion_id'])) {

              $promotion = PromotionalProduct::find($product['promotion_id']);

              if(!empty($promotion) && $promotion->by_no_of_units == 1) {
                  $promotion->remaining_no_of_units = $promotion->remaining_no_of_units - $product['qty'];
                  if($promotion->remaining_no_of_units == 0) {
                    $promotion->is_active = 0;
                  }
                  $promotion->save();
              }
          }

          $items[] = new InvoiceItem([
            'product_id' => $product['id'],
            'name' => $product['name'],
            'code' => $product['code'],
            'quantity' => $product['qty'],
            'quantity_unit' => $product['unit'],
            'rate' => $product['rate'],
            'gst' => $product['gst']
          ]);
        }

        $invoice->items()->saveMany($items);

        $attachmentPaths = $this->uploadAttachments($validatedData);
        $this->saveAttachments($invoice, $attachmentPaths);

        $this->createPdf($invoice);

      });

      if($invoice) {
        DB::commit();
        Session::flash('message', __('Invoice has been saved successfully'));
        Session::flash('alert-class', 'alert-success');
      } else {
        DB::rollBack();
        Session::flash('message', __('Unable to save Invoice'));
        Session::flash('alert-class', 'alert-error');
      }

      return redirect()->route('khata.invoice.home');
    }

    public function uploadAttachments($validatedData)
    {
      $poAttachmentPath = NULL;
      $dcAttachmentPath = NULL;
      $srAttachmentPath = NULL;
      $tcAttachmentPath = NULL;
      $dsAttachmentPath = NULL;

      if(isset($validatedData['purchase_order']))
      {

            if( $validatedData['purchase_order']->getClientOriginalExtension() == 'pdf' ) {
                $poAttachmentPath = $this->uploadMyFile($validatedData['purchase_order'], 'khata/invoice/purchase-order', 'purchase-order');
            } else {
                $poAttachmentPath = $this->uploadMyFile($validatedData['purchase_order'], 'khata/invoice/purchase-order', 'purchase-order');
            }
      }

        if(isset($validatedData['delivery_challan']))
        {
            if( $validatedData['delivery_challan']->getClientOriginalExtension() == 'pdf' ) {
                $dcAttachmentPath = $this->uploadMyFile($validatedData['delivery_challan'], 'khata/invoice/challan', 'challan');
            } else {
                $dcAttachmentPath = $this->uploadMyFile($validatedData['delivery_challan'], 'khata/invoice/challan', 'challan');
            }
        }

        if(isset($validatedData['shipment_receipt']))
        {
            if( $validatedData['shipment_receipt']->getClientOriginalExtension() == 'pdf' ) {
                $srAttachmentPath = $this->uploadMyFile($validatedData['shipment_receipt'], 'khata/invoice/shipment-receipt', 'shipment-receipt');
            } else {
                $srAttachmentPath = $this->uploadMyFile($validatedData['shipment_receipt'], 'khata/invoice/shipment-receipt', 'shipment-receipt');
            }
        }

        if(isset($validatedData['tax_certificate']))
        {
            if( $validatedData['tax_certificate']->getClientOriginalExtension() == 'pdf' ) {
                $tcAttachmentPath = $this->uploadMyFile($validatedData['tax_certificate'], 'khata/invoice/tax-certificate', 'tax-certificate');
            } else {
                $tcAttachmentPath = $this->uploadMyFile($validatedData['tax_certificate'], 'khata/invoice/tax-certificate', 'tax-certificate');
            }
        }

        if(isset($validatedData['digital_signature']))
        {
            if( $validatedData['digital_signature']->getClientOriginalExtension() == 'pdf' ) {
                $dsAttachmentPath = $this->uploadMyFile($validatedData['digital_signature'], 'khata/invoice/digital-signature', 'digital-signature');
            } else {
                $dsAttachmentPath = $this->uploadMyFile($validatedData['digital_signature'], 'khata/invoice/digital-signature', 'digital-signature');
            }
        }

        return [
            'purchase_order' => $poAttachmentPath,
            'delivery_receipt' => $dcAttachmentPath,
            'shipment_receipt' => $srAttachmentPath,
            'tax_certificate' => $tcAttachmentPath,
            'signature' => $dsAttachmentPath
        ];

    }

    public function saveAttachments(Invoice $invoice, $attachmentPaths)
    {
      $attachments = [];
      foreach ($attachmentPaths as $attachmentType => $path) {
        if(!empty($path)) {
          $attachments[] = new InvoiceAttachment([
            'type' => $attachmentType,
            'path' => $path,
          ]);
        }
      }

      if(!empty($attachments)) {
        $invoice->attachments()->saveMany($attachments);
      }
    }

    public function createPdf(Invoice $invoice)
    {
      $invoiceInfo = clone $invoice;
      $invoiceInfo->seller_details = json_decode($invoiceInfo->seller_details);
      $invoiceInfo->buyer_details = json_decode($invoiceInfo->buyer_details);
      $invoiceInfo->terms_and_conditions = json_decode($invoiceInfo->terms_and_conditions);

      $data['invoice'] = $invoiceInfo;
      $data['seller'] = User::find($invoiceInfo->seller_id);
      $data['buyer'] = User::find($invoiceInfo->buyer_id);
      $data['products'] = $invoiceInfo->items;
      $data['attachments'] = $invoiceInfo->attachments;

      if(!empty($data['attachments'])) {
        foreach($data['attachments'] as $key => $attachment) {
          if(strpos($attachment, 'pdf') !== false) {
            $attachment->attachmentType = 'pdf';
            $attachment->attachmentPath = url(Storage::url($attachment->path));
            $attachment->attachment = public_path('img/docs.png');
          } else {
            $attachment->attachmentType = 'image';
            $attachment->attachment = public_path(Storage::url($attachment->path));
          }
        }
      }

      // dd(public_path($data['seller']->business->additionalDetails->logo));
      $pdf = PDF::loadView('components.khata.invoice-pdf', ['data' => $data]);
      $fileName =  time() . '.' . 'pdf';

      $content = $pdf->download()->getOriginalContent();
      $this->putFile('public/khata/invoice/', $content, $fileName);

      $pdfPath = 'public/khata/invoice/'.$fileName;

      $invoice->invoice_path = $pdfPath;
      $invoice->save();

    }

    /**
     * Return pdf file associated with the specified resource
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function downloadPdf(Invoice $invoice)
    {
      $this->authorize('download', $invoice);
      return Storage::download($invoice->invoice_path);
    }

    public function sendPdf(Invoice $invoice, Request $request)
    {
      if($request->ajax()) {
        if(!$invoice->is_shared) {
          $buyer = User::find($invoice->buyer_id);
          Notification::send($buyer, new ShareInvoice($invoice));
          $invoice->is_shared = 1;
          $invoice->is_shared_date = Carbon::now()->format('Y-m-d H:i:s');
          $invoice->save();

          $buyer->invoices_received = $buyer->invoices_received + 1;
          $buyer->save();

          return response()->json([
            'email_sent' => 1
          ]);

        } else {
          return response()->json([
            'email_sent' => 0,
            'message' => __('Invoice has already been shared')
          ]);
        }
      }

      abort(400, 'bad request');
    }
}
