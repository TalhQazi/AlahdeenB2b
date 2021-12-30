<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseOrderRequest;
use App\Models\PurchaseOrder;
use App\Models\User;
use App\Traits\FileTrait;
use App\Traits\ImageTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PurchaseOrderController extends Controller
{
    use ImageTrait, FileTrait;

    public function __construct()
    {
        $this->authorizeResource(PurchaseOrder::class);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            if (!empty($request->query('keywords'))) {
                $purchaseOrders = PurchaseOrder::with(['createdBy'])->where('number', 'like', '%' . $request->keywords . '%')
                    ->orWhere('po_date', 'like', '%' . $request->keywords . '%')
                    ->orWhere('po_delivery_date', 'like', '%' . $request->keywords . '%')->get();
                return response()->json($purchaseOrders);
            }
        } else {

            $purchaseOrders = PurchaseOrder::with(['createdBy'])->where('created_by', Auth::user()->id)->get();

            return view('pages.khata.purchase_orders')->with([
                'purchase_orders' => $purchaseOrders
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['company_logo'] = !empty(Auth::user()->business->additionalDetails->logo) ? Storage::url(Auth::user()->business->additionalDetails->logo) : NULL;
        return view('pages.khata.create_purchase_order', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PurchaseOrderRequest $request)
    {
        $validatedData = $request->validated();

        $attachment = NULL;
        if (!empty($validatedData['attachment'])) {
            if ($validatedData['attachment']->getClientOriginalExtension() == 'pdf') {
                $attachment = $this->uploadFile($validatedData['attachment'], 'public/khata/purchase-order/attachments');
            } else {
                $attachment = $this->uploadImage($validatedData['attachment'], 'public/khata/purchase-order/attachments');
            }
        }
        $last_record = DB::table('purchase_orders')->orderBy('id', 'desc')->first();

        $purchaseOrder = tap(PurchaseOrder::create([
            'number' => Carbon::now()->timestamp . "-" . !empty($last_record) ? $last_record->id : 1 . "-" .Auth::user()->id,
            'po_date' => $validatedData['po_date'],
            'po_delivery_date' => $validatedData['po_delivery_date'],
            'order_description' => $validatedData['order_description'],
            'payment_details' => $validatedData['payment_details'],
            'created_by' => Auth::user()->id,
            'attachment' => $attachment
        ]), function (PurchaseOrder $purchaseOrder) {
            $this->createPdf($purchaseOrder);
        });

        if ($purchaseOrder) {
            Session::flash('message', __('Purchase Order has been saved successfully'));
            Session::flash('alert-class', 'alert-success');
        } else {
            Session::flash('message', __('Unable to save Purchase Order'));
            Session::flash('alert-class', 'alert-error');
        }

        return redirect()->route('khata.purchase-order.home');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PurchaseOrder  $purchaseOrder
     * @return \Illuminate\Http\Response
     */
    public function edit(PurchaseOrder $purchaseOrder)
    {
        $data['company_logo'] = !empty(Auth::user()->business->additionalDetails->logo) ? Storage::url(Auth::user()->business->additionalDetails->logo) : NULL;

        $purchaseOrder->po_date = Carbon::parse($purchaseOrder->po_date)->isoFormat('YYYY-MM-DD');
        $purchaseOrder->po_delivery_date = Carbon::parse($purchaseOrder->po_delivery_date)->isoFormat('YYYY-MM-DD HH:mm');

        $purchaseOrder->attachmentClass = 'hidden';
        $purchaseOrder->attachmentUrl = asset('img/camera.png');
        $purchaseOrder->attachmentType = '';
        if (!empty($purchaseOrder->attachment)) {
            if (strpos($purchaseOrder->attachment, 'pdf') != false) {
                $purchaseOrder->attachmentType = 'pdf';
                $purchaseOrder->attachmentUrl = asset('img/docs.png');
                $purchaseOrder->attachmentClass = '';
            } else {
                $purchaseOrder->attachmentType = 'image';
                $purchaseOrder->attachmentUrl = url(Storage::url($purchaseOrder->attachment));
                $purchaseOrder->attachmentClass = '';
            }
        }

        $data['purchase_order'] = $purchaseOrder;
        // dd($purchaseOrder->attachment);
        return view('pages.khata.edit_purchase_order', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PurchaseOrder  $purchaseOrder
     * @return \Illuminate\Http\Response
     */
    public function update(PurchaseOrderRequest $request, PurchaseOrder $purchaseOrder)
    {
        $validatedData = $request->validated();

        $attachment = NULL;
        if (!empty($validatedData['attachment'])) {
            if ($validatedData['attachment']->getClientOriginalExtension() == 'pdf') {
                $attachment = $this->uploadFile($validatedData['attachment'], 'public/khata/purchase-order/attachments');
            } else {
                $attachment = $this->uploadImage($validatedData['attachment'], 'public/khata/purchase-order/attachments');
            }
            $this->deleteFile($purchaseOrder->attachment);
        }

        $purchaseOrder->attachment = $attachment;
        $purchaseOrder->number = $validatedData['po_number'];
        $purchaseOrder->po_date = $validatedData['po_date'];
        $purchaseOrder->po_delivery_date = $validatedData['po_delivery_date'];
        $purchaseOrder->order_description = $validatedData['order_description'];
        $purchaseOrder->payment_details = $validatedData['payment_details'];

        if ($purchaseOrder->save()) {
            $this->createPdf($purchaseOrder);
            Session::flash('message', __('Purchase Order has been updated successfully'));
            Session::flash('alert-class', 'alert-success');
        } else {
            Session::flash('message', __('Unable to update Purchase Order'));
            Session::flash('alert-class', 'alert-error');
        }

        return redirect()->route('khata.purchase-order.home');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PurchaseOrder  $purchaseOrder
     * @return \Illuminate\Http\Response
     */
    public function destroy(PurchaseOrder $purchaseOrder)
    {
        if ($this->deleteFile($purchaseOrder->purchase_order_path) && $this->deleteFile($purchaseOrder->attachment) && $purchaseOrder->delete()) {
            Session::flash('message', __('Purchase Order has been removed successfully.'));
            Session::flash('alert-class', 'alert-success');
        } else {
            Session::flash('message', __('Unable to remove Purchase Order.'));
            Session::flash('alert-class', 'alert-error');
        }

        return redirect()->back();
    }

    public function createPdf(PurchaseOrder $purchaseOrder)
    {

        if ($purchaseOrder->purchase_order_path != '/') {
            $this->deleteFile($purchaseOrder->purchase_order_path);
        }

        $sellerDetails = User::with(['business', 'business.additionalDetails'])->where('id', Auth::user()->id)->get();

        if (!empty($sellerDetails[0]) && !empty($sellerDetails[0]->business) && !empty($sellerDetails[0]->business->additionalDetails)) {
            $sellerDetails[0]->business->additionalDetails->logo = Storage::url($sellerDetails[0]->business->additionalDetails->logo);
        }

        $data['seller_details'] = $sellerDetails[0];

        $purchaseOrderInfo = clone $purchaseOrder;
        if (!empty($purchaseOrderInfo->attachment)) {
            if (strpos($purchaseOrderInfo->attachment, 'pdf') !== false) {
                $purchaseOrderInfo->attachmentType = 'pdf';
                $purchaseOrderInfo->attachment_path = url(Storage::url($purchaseOrderInfo->attachment));
                $purchaseOrderInfo->attachment = public_path('img/docs.png');
            } else {
                $purchaseOrderInfo->attachmentType = 'image';
                $purchaseOrderInfo->attachment = public_path(Storage::url($purchaseOrderInfo->attachment));
            }
        } else {
            $purchaseOrderInfo->attachment = null;
        }


        $data['purchaseOrderInfo'] = $purchaseOrderInfo;


        $pdf = PDF::loadView('components.khata.purchase-order-pdf', ['data' => $data]);
        $fileName =  time() . '.' . 'pdf';

        $content = $pdf->download()->getOriginalContent();
        $this->putFile('public/khata/purchase-order/', $content, $fileName);

        $pdfPath = 'public/khata/purchase-order/' . $fileName;

        $purchaseOrder->purchase_order_path = $pdfPath;
        $purchaseOrder->save();
    }

    /**
     * Return pdf file associated with the specified resource
     *
     * @param  \App\Models\PurchaseOrder $purchaseOrder
     * @return \Illuminate\Http\Response
     */
    public function downloadPdf(PurchaseOrder $purchaseOrder)
    {
        $this->authorize('download', $purchaseOrder);
        return Storage::download($purchaseOrder->purchase_order_path);
    }
}
