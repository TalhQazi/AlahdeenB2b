<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductDocument;
use App\Http\Resources\Product as ProductResource;
use App\Http\Resources\ProductDocumentCollection;

class ProductDocumentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Product $product)
    {
        $product = (new ProductResource($product))->response()->getData();
        return view('pages.product.documents')->with('product', $product->data);
    }

}
