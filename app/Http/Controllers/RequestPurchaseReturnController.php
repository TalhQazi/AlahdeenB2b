<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePurchaseReturnRequest;
use App\Models\Inventory\ProductPricing;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\PurchaseReturn;
use App\Traits\PaginatorTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class RequestPurchaseReturnController extends Controller
{
    use PaginatorTrait;

    private $noOfItems;

    public function __construct()
    {
        $this->noOfItems = config('pagination.inventory_sales', config('pagination.default'));
    }


    public function store(StorePurchaseReturnRequest $request)
    {

        $message = __('Unable to send purchase return request details');
        $alertClass = 'alert-error';

        $validatedData = $request->validated();

        $invoice_number = $validatedData['invoice_no'];
        $invoice = Invoice::where("number", $invoice_number)->first();

        DB::beginTransaction();


        if (empty($invoice) || !isset($invoice)) {
            DB::rollBack();

            Session::flash('message', "Invoice number do not match our records please check and try again.");
            Session::flash('alert-class', $alertClass);
            return redirect()->back();
        }


        $created = PurchaseReturn::create([
            "product_code" => $validatedData['product_code'],
            "product_name" => $validatedData['product_name'],
            "return_quantity" => $validatedData['return_quantity'],
            "return_amount" => $validatedData['return_amount'],
            "purchase_order_no" => $validatedData['purchase_order_no'],
            "invoice_no" => $validatedData['invoice_no'],
            "comments" => $validatedData['comments'],
            "user_id" => Auth::user()->id,
            "is_return_product" => $validatedData['is_return_product'],
            "product_id" => $validatedData['product_id'],
        ]);

        if ($created) {
            DB::commit();
            Session::flash('message', __('Purchase return request has been sent successfully'));
            Session::flash('alert-class', 'alert-success');
            return redirect()->route('khata.inventory.product.sales');
        } else {
            DB::rollBack();
            Session::flash('message', $message);
            Session::flash('alert-class', $alertClass);
            return redirect()->back();
        }
    }



    public function changeStatus(PurchaseReturn $purchase_return, $status)
    {
        $message = __('Unable to update purchase return request status');
        $alertClass = 'alert-error';

        $last_record = DB::table('invoices')->orderBy('id', 'desc')->first();
        $inv_id = !empty($last_record) ? $last_record->id + 1 : 1;

        DB::beginTransaction();

        $purchase_return->status = $status;

        $this->authorize("update", $purchase_return);


        if ($status == 'Approved') {
            $invoice_number = $purchase_return->invoice_no;
            $invoice = Invoice::where("number", $invoice_number)->first();
            $product_inv = ProductPricing::where('product_id', $purchase_return->product_id)->first();

            if (empty($invoice)) {
                DB::rollBack();

                Session::flash('message', "Invoice number do not match our records please check and try again.");
                Session::flash('alert-class', $alertClass);
                return redirect()->back();
            }

            if ($purchase_return->is_return_product == 1) {
                $invoice_items = InvoiceItem::where('invoice_id', $invoice->id)->get();

                foreach ($invoice_items as $key => $value) {

                    $new_item = $value->replicate();
                    $new_item->quantity = -$value->quantity;
                    $new_item->save();
                }

                $new_invoice = $invoice->replicate();
                $new_invoice->number = Carbon::now()->timestamp . "-" . $inv_id . "-" . Auth::user()->id;

                $new_invoice->ref_invoice = $invoice->number;
                $new_invoice->save();

                $product_inv->total_units = floatval($purchase_return->return_quantity) + floatval($product_inv->total_units);


                $product_inv->update();
            }
        }

        if ($purchase_return->update()) {
            DB::commit();
            Session::flash('message', __('Purchase return request ' . strtolower($status) . ' status has been updated successfully'));
            Session::flash('alert-class', 'alert-success');
            return redirect()->route('khata.inventory.product.sales');
        } else {
            DB::rollBack();
            Session::flash('message', $message);
            Session::flash('alert-class', $alertClass);
            return redirect()->back();
        }
    }


    public function view(Request $request, PurchaseReturn $purchaseReturn)
    {
        if ($request->input('keywords')) {
            $searchParam = $request->input('keywords');

            $purchaseReturn = $purchaseReturn
                ->where('title', 'like', '%' . $searchParam . '%')->orWhere('id', 'like', '%' . $searchParam . '%');

            if (!(Auth::user()->hasRole(['admin', 'super-admin']))) {
                $purchaseReturn = $purchaseReturn->where('user_id', Auth::user()->id)
                    ->where('title', 'like', '%' . $searchParam . '%')->orWhere('id', 'like', '%' . $searchParam . '%');
            }
        }

        $purchaseReturn = $purchaseReturn->paginate($this->noOfItems);
        if (!(Auth::user()->hasRole(['admin', 'super-admin']))) {
            $purchaseReturn = $purchaseReturn->where('user_id', Auth::user()->id)->paginate($this->noOfItems);
        }

        if ($request->ajax()) {
            return response()->json(['purchase_returns' => $purchaseReturn, 'paginator' => (string)PaginatorTrait::getPaginator($request, $purchaseReturn)->links()]);
        }

        return view('pages.purchase-return-request.index')->with([
            'paginator' => (string)$purchaseReturn->links(),
            'purchase_returns' => $purchaseReturn,
        ]);
    }

}
