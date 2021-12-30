<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\ProductPricingRequest;
use App\Http\Resources\Inventory\ProductPricing as InventoryProductPricing;
use App\Http\Resources\Inventory\ProductPricingCollection;
use App\Http\Resources\ProductCollection;
use App\Models\Inventory\ProductPricing;
use App\Models\Product;
use App\Traits\PaginatorTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Milon\Barcode\Facades\DNS2DFacade;

class ProductPricingController extends Controller
{
    use PaginatorTrait;

    public function __construct()
    {
        // $this->authorizeResource(ProductDefinition::class);
        $this->noOfItems = config('pagination.product_pricing', config('pagination.default'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $productsPricing = ProductPricing::with(['product', 'product.images'])->whereHas('product', function($query) {
            $query->where('user_id', Auth::user()->id);
        });

        if(!empty($request->input('keywords'))) {
            $searchParam = $request->input('keywords');
            $productsPricing = $productsPricing->whereHas('product', function($query) use ($searchParam) {
                $query->where('title', 'like', '%'.$searchParam.'%');
            });
        }

        $productsPricing = $productsPricing->paginate($this->noOfItems);
        $productsPricing = (new ProductPricingCollection($productsPricing))->response()->getData();

        if ($request->ajax()) {

          return response()->json(['products_pricing' => $productsPricing, 'paginator' => (string) PaginatorTrait::getPaginator($request, $productsPricing)->links()]);

        } else {

            return view('pages.khata.inventory.pricing')->with([
                'products_pricing' => $productsPricing->data,
                'paginator' => PaginatorTrait::getPaginator($request, $productsPricing)
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

        $products = Product::where('user_id', Auth::user()->id)->get();
        $products = (new ProductCollection($products))->response()->getData();
        return view('pages.khata.inventory.create_pricing')->with([
            'products' => $products->data,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductPricingRequest $request, ProductPricing $productPricing)
    {
        $validatedData = $request->validated();

        $productId = 0;
        if(!empty($validatedData['product_id'])) {
            $productId = $validatedData['product_id'];
            $saved = $this->saveProductPricing($validatedData, $productId, $productPricing);
            Session::flash('message', __('Product Pricing has been saved successfully in the inventory'));
            Session::flash('alert-class', 'alert-success');
            return redirect()->route('khata.inventory.product.pricing.list');
        } else {
            Session::flash('message', __('Unable to save Product Pricing in the inventory'));
            Session::flash('alert-class', 'alert-error');
            return redirect()->back();
        }
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\InventoryProductPricing  $inventoryProductPricing
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductPricing $productPricing)
    {
        $productPricing = $productPricing::with(['product', 'product.promotionAgainst'])->where('id', $productPricing->id)->get();

        if(!empty($productPricing[0])) {

            $products = Product::where('user_id', Auth::user()->id)->get();
            $products = (new ProductCollection($products))->response()->getData();
            return view('pages.khata.inventory.edit_pricing')->with([
                'product_pricing' => (new InventoryProductPricing($productPricing[0]))->response()->getData()->data,
                'products' => $products->data
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\InventoryProductPricing  $inventoryProductPricing
     * @return \Illuminate\Http\Response
     */
    public function update(ProductPricingRequest $request, ProductPricing $productPricing)
    {
        $validatedData = $request->validated();

        $productId = 0;
        if(!empty($validatedData['product_id'])) {
            $productId = $validatedData['product_id'];
            if($this->saveProductPricing($validatedData, $productId, $productPricing)) {
                Session::flash('message', __('Product Pricing has been updated successfully in the inventory'));
                Session::flash('alert-class', 'alert-success');
                return redirect()->route('khata.inventory.product.pricing.list');
            } else {
                Session::flash('message', __('Unable to update Product Pricing in the inventory'));
                Session::flash('alert-class', 'alert-error');
                return redirect()->back();
            }

        } else {
            Session::flash('message', __('Unable to update Product Pricing in the inventory'));
            Session::flash('alert-class', 'alert-error');
            return redirect()->back();
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\InventoryProductPricing  $inventoryProductPricing
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductPricing $productPricing)
    {
        $message = __('Unable to delete product pricing from inventory');
        $alertClass = 'alert-error';

        if($productPricing->delete()) {
            $message = __('Product Pricing has been deleted successfully from the inventory');
            $alertClass = 'alert-success';
        }

        Session::flash('message', $message);
        Session::flash('alert-class', $alertClass);
        return redirect()->route('khata.inventory.product.pricing.list');
    }

    public function saveProductPricing($validatedData, $productId, $productPricing)
    {
        $productPricing->product_id = $productId;

        foreach($validatedData as $key => $value) {
            if($key == "allow_below_cost_sale" || $key == "allow_price_change") {
                $value = 1;
            }
            $productPricing->{$key} = $value;
        }

        return $productPricing->save();
    }
}
