<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBuyRequirement;
use App\Http\Resources\ProductBuyRequirementCollection;
use App\Models\ProductBuyRequirement;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Traits\ApiResponser;
use App\Traits\FileTrait;
use App\Traits\ImageTrait;
use App\Traits\PaginatorTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductBuyRequirementsController extends Controller
{
    use ApiResponser, PaginatorTrait, FileTrait, ImageTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $noOfItems = $request->query('per_page', 5);
        $productBuyRequirements = ProductBuyRequirement::where('user_id', Auth::user()->id)->paginate($noOfItems);
        $productBuyRequirements = (new ProductBuyRequirementCollection($productBuyRequirements))->response()->getData();

        return $this->success(
            [
                'product_buy_requirements' => $productBuyRequirements->data,
                'paginator' => PaginatorTrait::getPaginator($request, $productBuyRequirements)
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBuyRequirement $request)
    {
        $validatedData = $request->validated();

        $this->category_ids = [];
        $parentCategories = null;
        $productTitle = null;
        if(!empty($validatedData['product_id'])) {

            $product = Product::find($validatedData['product_id']);
            $productTitle = $product->title;

            $category = Category::where('id', $product->category_id)->get();

            $parentCategories = explode(';', $category[0]->bread_crumb);
            $parentCategories = array_diff($parentCategories,['']);
            $parentCategories = implode(',', $parentCategories);
        }


        $newPath = null;

        if(!empty($validatedData['image_path'])) {
            $tempPath = str_replace('/storage', 'public', $validatedData['image_path']);
            $newPath = str_replace('/storage/tmp', 'public/product_buy_requirement', $validatedData['image_path']);
            $isImage = strpos($newPath, 'image');
            if($isImage > 0) {
                $this->moveImage($tempPath, $newPath);
                $this->deleteImage($tempPath);
            } else {
                $this->moveFile($tempPath, $newPath);
                $this->deleteFile($tempPath);
            }
        }

        if( ProductBuyRequirement::create([
            'user_id' => Auth::user()->id,
            'category_ids' => $parentCategories,
            'required_product' => $productTitle ?? $validatedData['product'],
            'requirement_details' => $validatedData['requirement_details'],
            'image_path' => $newPath,
            'quantity' => $validatedData['quantity'],
            'unit' => $validatedData['unit'],
            'budget' => $validatedData['budget'] ?? null,
            'requirement_urgency' => $validatedData['requirement_urgency'] ?? null,
            'requirement_frequency' => $validatedData['requirement_frequency'] ?? null,
            'supplier_location' => $validatedData['supplier_location'] ?? null,
            'terms_and_conditions' => $validatedData['terms_and_conditions'],
        ]) ) {
            return $this->success([], __('Your request has been saved'));
        } else {
            return $this->error(__('Unable to post your buying requirement at the moment'), 500);
        }
    }

    public function lastestRequests(Request $request)
    {
        $noOfItems = $request->query('limit', 5);
        $productBuyRequirements = ProductBuyRequirement::orderBy('created_at', 'desc')->limit($noOfItems)->get();
        $productBuyRequirements = (new ProductBuyRequirementCollection($productBuyRequirements))->response()->getData();

        return $this->success(
            [
                'product_buy_requirements' => $productBuyRequirements->data,
            ]
        );
    }
}
