<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseReturn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Traits\PaginatorTrait;
use Carbon\Carbon;

class SalesController extends Controller
{
    private $noOfItems;

    public function __construct()
    {
        $this->noOfItems = config('pagination.inventory_sales', config('pagination.default'));
    }

    public function index(Request $request, PurchaseReturn $purchaseReturn)
    {
        if ($request->input('keywords')) {
            $searchParam = $request->input('keywords');
            $purchaseReturn = $purchaseReturn
                ->where('title', 'like', '%' . $searchParam . '%')->orWhere('id', 'like', '%' . $searchParam . '%');

            if (!(Auth::user()->hasRole(['admin', 'super-admin']))) {
                $purchaseReturn = $purchaseReturn->where('user_id', Auth::user()->id)
                    ->where('title', 'like', '%' . $searchParam . '%')->orWhere('id', 'like', '%' . $searchParam . '%')->paginate($this->noOfItems);
            }
        }

        // $purchaseReturn = $purchaseReturn->paginate($this->noOfItems);
        if (!(Auth::user()->hasRole(['admin', 'super-admin']))) {
            $purchaseReturn = $purchaseReturn->where('user_id', Auth::user()->id)->paginate($this->noOfItems);
        }

        if ($request->ajax()) {
            return response()->json(['purchase_returns' => $purchaseReturn, 'paginator' => (string)PaginatorTrait::getPaginator($request, $purchaseReturn)->links()]);
        }

        $products = Product::select(
            'products.id',
            'products.title',
            'ipd.product_code',
            'ii.quantity',
            'ii.rate',
            DB::raw('ii.quantity * rate as total'),
        )
            ->join('inventory_product_definitions as ipd', 'products.id', '=', 'ipd.product_id')
            ->join('inventory_product_pricings as ipp', 'products.id', '=', 'ipp.product_id')
            ->join('invoice_items as ii', 'products.id', '=', 'ii.product_id')
            ->where('products.user_id', Auth::user()->id)
            ->whereNotNull('ipd.id')->paginate($this->noOfItems);


        return view('pages.khata.inventory.sales')->with([
            'products' => $products,
            'paginator' => (string)$purchaseReturn->links(),
            'purchase_returns' => $purchaseReturn,
        ]);
    }
}
