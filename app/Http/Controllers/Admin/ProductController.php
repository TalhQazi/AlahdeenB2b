<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\Product as ProductResource;
use App\Http\Resources\CategoryCollection;
use App\Traits\PaginatorTrait;

class ProductController extends Controller
{

    use PaginatorTrait;

    private $noOfItems;

    public function __construct()
    {
        $this->authorizeResource(Product::class);
        $this->noOfItems = config('pagination.products', config('pagination.default'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Product $product, Request $request)
    {
        $products = $product::withTrashed()->with(['productCategory', 'user', 'images']);
        if($request->input('keywords')) {
            $searchParam = $request->input('keywords');
            $products = $products->where('title', 'like', '%'.$searchParam.'%')->orWhere('id', 'like', '%'.$searchParam.'%');
        }

        $products = $products->paginate($this->noOfItems);
        $products = (new ProductCollection($products))->response()->getData();

        if ($request->ajax()) {

          return response()->json(['products' => $products, 'paginator' => (string) PaginatorTrait::getPaginator($request, $products)->links()]);

        } else {

            return view('pages.product.admin_index')->with([
                'products' => $products,
                'table_header' => 'components.products.index.admin.theader',
                'table_body' => 'components.products.index.admin.tbody',
                'paginator' => PaginatorTrait::getPaginator($request, $products)
            ]);
        }

    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        $product->load(['images', 'productCategory', 'details', 'documents', 'videos']);
        $product = (new ProductResource($product))->response()->getData();

        if(!empty($product)) {
            return view('pages.product.show')->with('product', $product->data);
        } else {
            Session::flash('message', __('No such product exists'));
            Session::flash('alert-class', 'alert-error');
            return redirect()->route('admin.product.home');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product, Category $category)
    {
        $product->load(['images', 'productCategory', 'details', 'documents', 'videos']);
        $categories = (new CategoryCollection($category::all()))->response()->getData();
        $product = (new ProductResource($product))->response()->getData();

        if(!empty($product)) {
            return view('pages.product.edit')->with([
                'product' => $product->data,
                'categories' => $categories->data,
                'view_docs_link' => route('admin.product.document-index', ['product' => $product->data->id]),
                'can_be_featured' => 1
            ]);
        } else {
            Session::flash('message', __('No such product exists'));
            Session::flash('alert-class', 'alert-error');
            return redirect('admin.product.home');
        }
    }


    public function showDeleted(Product $product,$productId)
    {

        $product = $product::withTrashed()->find($productId);
        $product = (new ProductResource($product))->response()->getData();

        $this->authorize('view', $product);

        if(!empty($product)) {
            return view('pages.product.show')->with('product', $product->data);
        } else {
            Session::flash('message', __('No such category exists'));
            Session::flash('alert-class', 'alert-error');
            return redirect()->route('admin.product.home');
        }
    }

}
