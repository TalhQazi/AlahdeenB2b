<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Traits\PaginatorTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductsListController extends Controller
{

    use PaginatorTrait;

    private $noOfItems;

    public function __construct()
    {
        $this->noOfItems = config('pagination.inventory_products_list', config('pagination.default'));
    }

    public function index()
    {
        $products = Product::with(['inventoryDefinition','inventoryPricing', 'productCategory.parentCategory'])
                ->where('user_id', Auth::user()->id);

        $products = $products->paginate(1);

        return view('pages.khata.products_list.index')->with([
            'products' => $products
        ]);
    }
}
