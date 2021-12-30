<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\StoreProductDefinition;
use App\Http\Resources\CategoryCollection;
use App\Http\Resources\Inventory\ProductDefinitionCollection;
use App\Http\Resources\Inventory\ProductDefinition as ResourcesInventoryProductDefinition;
use App\Models\Category;
use App\Models\Inventory\ProductDefinition;
use App\Models\Product;
use App\Traits\ImageTrait;
use App\Traits\PaginatorTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Traits\Helpers\FileUpload;

class ProductDefinitionController extends Controller
{
    use ImageTrait, PaginatorTrait;

    use FileUpload;

    private $noOfItems;

    public function __construct()
    {
        $this->authorizeResource(ProductDefinition::class);
        $this->noOfItems = config('pagination.product_definition', config('pagination.default'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $productDefinitions = ProductDefinition::with(['product'])->whereHas('product', function($query) {
            $query->where('user_id', Auth::user()->id);
        });

        if(!empty($request->input('keywords'))) {
            $searchParam = $request->input('keywords');
            $productDefinitions = $productDefinitions->whereHas('product', function($query) use ($searchParam) {
                $query->where('title', 'like', '%'.$searchParam.'%');
            })->orWhere('product_code', 'like', '%'.$searchParam.'%');
        }

        $productDefinitions = $productDefinitions->paginate($this->noOfItems);
        $productDefinitions = (new ProductDefinitionCollection($productDefinitions))->response()->getData();

        if ($request->ajax()) {

          return response()->json(['product_definitions' => $productDefinitions->data, 'paginator' => (string) PaginatorTrait::getPaginator($request, $productDefinitions)->links()]);

        } else {
            return view('pages.khata.inventory.definition')->with([
                'product_definitions' => $productDefinitions->data,
                'paginator' => PaginatorTrait::getPaginator($request, $productDefinitions)
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
        $level1Categories = (new CategoryCollection(Category::where('level', 1)->get()))->response()->getData();
        return view('pages.khata.inventory.create_definition')->with([
            'level_1_categories' => $level1Categories->data,
            'units' => config('quantity_unit')
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductDefinition $request, ProductDefinition $productDefinition)
    {
        $validatedData = $request->validated();

        DB::beginTransaction();
        $productId = 0;
        if(empty($request->product_id)) {

            $categoryIndex = count($request->category);
            if(isset($request->product_image)) {
                $imagePath = $this->uploadFile($request->product_image, 'product-definition/'.uniqid(), 'product-definition');
                $imageTitle = $request->product_image->getClientOriginalName();
                
            }
            $product = tap(
                Product::create([
                    'title' => $request->product_name,
                    'user_id' => Auth::user()->id,
                    'category_id' => $request->category[$categoryIndex],
                ]),
                function(Product $product) use ($request, $imagePath, $imageTitle) {
                    $product->images()->create([
                        'path' => $imagePath,
                        'title' => $imageTitle,
                        'is_main' => 1
                    ]);   
                }
            );
            
            if($product) {
                $productId = $product->id;
            }
        } else {
            $productId = $request->product_id;
        }

        if(!empty($productId)) {

            $isSaved = $this->saveProductDefinition($validatedData, $productId, $productDefinition);

            if($isSaved) {
                DB::commit();
                Session::flash('message', __('Product Definition has been saved successfully in the inventory'));
                Session::flash('alert-class', 'alert-success');
            } else {
                DB::rollBack();
                Session::flash('message', __('Unable to save Product Definition in the inventory'));
                Session::flash('alert-class', 'alert-success');
            }
        } else {
            DB::rollBack();
            Session::flash('message', __('Unable to save Product Definition in the inventory'));
            Session::flash('alert-class', 'alert-success');
        }

        return redirect()->route('khata.inventory.product.definitions.list');

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Inventory\ProductDefinition  $productDefinition
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductDefinition $productDefinition)
    {
        $productDefinition = $productDefinition::with(['product', 'product.productCategory', 'product.images'])->where('id', $productDefinition->id)->get();
        $level1Categories = (new CategoryCollection(Category::where('level', 1)->get()))->response()->getData();
        if(!empty($productDefinition[0])) {
            return view('pages.khata.inventory.edit_definition')->with([
                'product_definition' => (new ResourcesInventoryProductDefinition($productDefinition[0]))->response()->getData()->data,
                'level_1_categories' => $level1Categories->data,
                'units' => config('quantity_unit')
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Inventory\ProductDefinition  $productDefinition
     * @return \Illuminate\Http\Response
     */
    public function update(StoreProductDefinition $request, ProductDefinition $productDefinition)
    {
        $validatedData = $request->validated();

        DB::beginTransaction();
        $productId = 0;
        if(empty($request->product_id)) {

            $categoryIndex = count($request->category);
            $product = tap(
                Product::create([
                    'title' => $request->productName,
                    'user_id' => Auth::user()->id,
                    'category' => $request->category[$categoryIndex]
                ]),
                function(Product $product) use ($request) {
                    $productImage = $request->file('product_image');
                    if(!empty($productImage)) {
                        $imagePath = $this->uploadImage($productImage, 'public/product/images');
                        $product->images->create([
                            'path' => $imagePath,
                            'title' => $imagePath->getClientOriginalName(),
                            'is_main' => 1
                        ]);
                    }
                }
            );

            if($product) {
                $productId = $product->id;
            }
        } else {
            $productId = $request->product_id;
        }

        if(!empty($productId)) {

            $isSaved = $this->saveProductDefinition($validatedData, $productId, $productDefinition);

            if($isSaved) {
                DB::commit();
                Session::flash('message', __('Product Definition has been saved successfully in the inventory'));
                Session::flash('alert-class', 'alert-success');
            } else {
                DB::rollBack();
                Session::flash('message', __('Unable to save Product Definition in the inventory'));
                Session::flash('alert-class', 'alert-success');
            }
        } else {
            DB::rollBack();
            Session::flash('message', __('Unable to save Product Definition in the inventory'));
            Session::flash('alert-class', 'alert-success');
        }

        return redirect()->route('khata.inventory.product.definitions.list');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Inventory\ProductDefinition  $productDefinition
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductDefinition $productDefinition)
    {
        if($productDefinition->delete()) {
            Session::flash('message', __('Product Definition has been deleted successfully from the inventory'));
            Session::flash('alert-class', 'alert-success');
        } else {
            Session::flash('message', __('Unable to delete product definition from the inventory'));
            Session::flash('alert-class', 'alert-success');
        }

        return redirect()->route('khata.inventory.product.definitions.list');
    }

    public function saveProductDefinition($validatedData, $productId, $productDefinition)
    {
        $productDefinition->product_id = $productId;
        $productDefinition->product_code = $validatedData['product_code'];
        $productDefinition->brand_name = $validatedData['brand_name'];
        $productDefinition->purchase_unit = $validatedData['purchase_unit'];
        $productDefinition->product_group = $validatedData['product_group'];
        $productDefinition->purchase_type = $validatedData['purchase_type'];
        $productDefinition->conversion_factor = $validatedData['conversion_factor'];
        $productDefinition->product_gender = $validatedData['product_gender'];
        $productDefinition->value_addition = $validatedData['value_addition'];
        $productDefinition->life_type = $validatedData['life_type'];
        $productDefinition->tax_code = $validatedData['tax_code'];
        $productDefinition->supplier = $validatedData['supplier'];
        $productDefinition->accquire_type = $validatedData['accquire_type'] ?? NULL;
        $productDefinition->additional_attributes = $validatedData['additional_attributes'];
        $productDefinition->technical_details = $validatedData['technical_details'];
        $productDefinition->additional_description = $validatedData['description'];
        $productDefinition->purchase_production_interval = $validatedData['purchase_production_interval'];
        $productDefinition->purchase_production_unit = $validatedData['purchase_production_unit'];

        return $productDefinition->save();
    }
}
