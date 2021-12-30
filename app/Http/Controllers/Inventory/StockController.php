<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductCollection;
use App\Models\Inventory\ProductPricing;
use App\Models\InvoiceItem;
use App\Models\Product;
use App\Traits\PaginatorTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class StockController extends Controller
{
    use PaginatorTrait;

    private $noOfItems;
    private $interval;

    public function __construct()
    {
        $this->noOfItems = config('pagination.inventory_stocks', config('pagination.default'));
        $this->interval = config("inventory_stock.sales_interval", 30);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $productWithDefinitions = $this->stockLevelQuery($request);

        foreach($productWithDefinitions as $product) {
            $product->quantity_status = __('Above Recommended Level');
            $product->quantity_status_class = "text-green-500";

            $product->remaining_products = !empty($product->total_units) ? $product->total_units - $product->total_sold_units : 0;
            $quantityLevel = !empty($product->total_units) ? ($product->remaining_products / $product->total_units) * 100 : 0;
            if($quantityLevel <= 25) {
                $product->quantity_status = __('Below Recommended Level');
                $product->quantity_status_class = "text-red-500";
            } else if($quantityLevel > 25 && $quantityLevel <= 75) {
                $product->quantity_status = __('Maintained Recommended Level');
                $product->quantity_status_class = "text-yellow-500";
            }


        }

        $paginator = (String) $productWithDefinitions->links();

        if($request->ajax()) {
            return response()->json(['products' => $productWithDefinitions, 'paginator' => $paginator]);
        } else {


            return view('pages.khata.stock.index')->with([
                'products' => $productWithDefinitions,
                'paginator' => $paginator
            ]);
        }

    }

    public function stockLevelQuery(Request $request)
    {

        Validator::make($request->query(), [
            'start_date' => ["nullable", "date", "date_format:Y-m-d H:i:s"],
            'end_date' => ["nullable", "date", "date_format:Y-m-d H:i:s", "after:".$request->start_date],
        ])->validate();

        $productWithDefinitions = Product::select(
            'products.id', 'products.title', 'ipd.purchase_production_interval', 'ipd.purchase_production_unit', 'ipp.total_units',
                DB::raw('sum(ii.quantity) as total_sold_units'),
                DB::raw('sum(ii.quantity * ii.rate + ( (ii.quantity * ii.rate) * (ii.gst / 100) ) ) as total_sales'),
                DB::raw(
                    '(select sum(quantity)
                        from invoice_items as i
                        where product_id = products.id and
                        created_at between '.'"'.Carbon::today()->subDays($this->interval).'"' .
                        ' and ' . '"'.Carbon::parse('tomorrow midnight')->format('Y-m-d H:i:s').'"'.' group by product_id) as avg_interval_sale'
                )
            )
            ->leftJoin('inventory_product_definitions as ipd', 'products.id', '=', 'ipd.product_id')
            ->leftJoin('inventory_product_pricings as ipp', 'products.id', '=', 'ipp.product_id')
            ->leftJoin('invoice_items as ii', 'products.id', '=', 'ii.product_id')
            ->where('products.user_id', Auth::user()->id)
            ->whereNotNull('ipd.id')
            ->groupBy('products.id');

            if(!empty($request->start_date) && !empty($request->end_date)) {
                $productWithDefinitions = $productWithDefinitions->whereBetween('ii.created_at', [$request->start_date, $request->end_date]);
            }

            $productWithDefinitions = $productWithDefinitions->paginate($this->noOfItems);

            return $productWithDefinitions;
    }

    /**
     * Store a newly created resource) in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        Validator::make($request->input(), [
            'existing_product' => ['required', Rule::exists('App\Models\Inventory\ProductPricing', 'product_id'), 'numeric'],
            'quantity' => ['required', 'numeric', 'min:1']
        ],
        ['exists' => __('Need to add missing pricing information')]
        )->validate();

        $message = __('Unable to update product quantity in inventory');
        $alertClass = 'alert-error';

        $product = Product::find($request->existing_product);

        if($product->user_id != Auth::user()->id) {
            abort('403');
        }

        $updated = ProductPricing::where('product_id', $request->existing_product)->increment('total_units', $request->quantity);

        if($updated) {
            $message = __('Product Quantity has been updated successfully');
            $alertClass = 'alert-success';
        }

        Session::flash('message', $message);
        Session::flash('alert-class', $alertClass);
        return redirect()->route('khata.inventory.product.stock');
    }


    public function stockLevelReport(Request $request)
    {
        $products = $this->stockLevelQuery($request);

        $totalRevenue = $products->sum('total_sales');

        $paginator = (String) $products->links();

        if($request->ajax()) {
            return response()->json(['products' => $products, 'total_revenue' => $totalRevenue, 'paginator' => $paginator]);
        } else {
            abort(404);
        }
    }

}
