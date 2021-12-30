<?php

namespace App\Http\Controllers;

use App\Http\Requests\MatterSheetProductRequest;
use App\Models\Category;
use App\Models\MatterSheet;
use App\Models\MatterSheetProduct;
use App\Traits\ImageTrait;
use App\Traits\PaginatorTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class MatterSheetProductsController extends Controller
{
    use PaginatorTrait, ImageTrait;

    private $noOfItems;

    public function __construct()
    {
        $this->noOfItems = config('pagination.products', config('pagination.default'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $matterSheetId, MatterSheetProduct $matterSheetProduct)
    {
        $matter_sheet = MatterSheet::find($matterSheetId);
        $this->authorize('view', $matter_sheet);

        $matterSheetProduct = $matterSheetProduct->where('matter_sheet_id', $matterSheetId);
        if ($request->input('keywords')) {
            $searchParam = $request->input('keywords');

            $matterSheetProduct = $matterSheetProduct->where('title', 'like', '%' . $searchParam . '%');

            if (!(Auth::user()->hasRole(['admin', 'super-admin']))) {
                $matterSheetProduct = $matterSheetProduct->where('used_id', Auth::user()->id)->where('title', 'like', '%' . $searchParam . '%');
            }
        }

        $matterSheetProduct = $matterSheetProduct->paginate($this->noOfItems);

        if ($request->ajax()) {
            return response()->json(['products' => $matterSheetProduct, 'paginator' => (string)PaginatorTrait::getPaginator($request, $matterSheetProduct)->links()]);
        } else {

            return view('pages.matter-sheet.listings.product.product-listing')->with([
                'products' => $matterSheetProduct,
                'paginator' => (string)$matterSheetProduct->links()
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MatterSheetProduct  $matterSheetProduct
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, MatterSheetProduct $matterSheetProduct)
    {

        $this->authorize('update', $matterSheetProduct);

        $category = Category::find($matterSheetProduct->category_id);
        $breadcrumb = explode(';', trim($category->bread_crumb, ";"));

        $parentCats = Category::whereIn('id', $breadcrumb)->get()->pluck('title', 'id')->all();

        $parentCats[$matterSheetProduct->category_id] = $matterSheetProduct->category;

        return view('pages.matter-sheet.edit-product')->with(['product' => $matterSheetProduct, 'parentCats' => $parentCats]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MatterSheetProduct  $matterSheetProduct
     * @return \Illuminate\Http\Response
     */
    public function update(MatterSheetProductRequest $request, MatterSheetProduct $matterSheetProduct)
    {
        $this->authorize('update', $matterSheetProduct);

        $validatedData = $request->validated();

        $categoryIndex = count($request->categories);

        if (!empty($validatedData['logo'])) {
            $this->deleteImage($matterSheetProduct->image_path);
            $matterSheetProduct->image_path = $this->uploadImage($validatedData['logo'], "public/matter_sheet/images");
        }

        $matterSheetProduct->category_id = $validatedData['categories'][$categoryIndex];
        $matterSheetProduct->category = $validatedData['categories_name'][$categoryIndex];
        $matterSheetProduct->title = $validatedData['title'];
        $matterSheetProduct->product_code = $validatedData['product_code'];
        $matterSheetProduct->web_category = $validatedData['web_category'];
        $matterSheetProduct->price = $validatedData['price'];
        $matterSheetProduct->approx_price = $validatedData['approx_price'];
        $matterSheetProduct->currency_1 = 'PKR';
        $matterSheetProduct->currency_2 = 'PKR';
        $matterSheetProduct->min_price = $validatedData['min_price'];
        $matterSheetProduct->max_price = $validatedData['max_price'];
        $matterSheetProduct->min_order_quantity = $validatedData['min_order_quantity'];
        $matterSheetProduct->unit_measure_supply = $validatedData['unit_measure_supply'];
        $matterSheetProduct->unit_measure_quantity = $validatedData['unit_measure_quantity'];
        $matterSheetProduct->supply_ability = $validatedData['supply_ability'];

        if ($matterSheetProduct->save()) {
            Session::flash('message', __('Product details have been updated successfully'));
            Session::flash('alert-class', 'alert-success');
        } else {
            Session::flash('message', __('Unable to update product details'));
            Session::flash('alert-class', 'alert-error');
        }

        return redirect()->route('matter-sheet.matter_sheet_product.listing', ['matter_sheet_id' => $matterSheetProduct->matter_sheet_id]);
    }
}
