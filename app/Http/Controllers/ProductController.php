<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductDetail;
use App\Models\ProductImage;
use App\Models\ProductVideo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Traits\PaginatorTrait;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\Product as ProductResource;
use App\Http\Resources\CategoryCollection;
use App\Traits\PackageUsageTrait;
use Illuminate\Validation\Rule;
use App\Traits\Helpers\FileUpload;

class ProductController extends Controller
{
    use PackageUsageTrait;
    use FileUpload;

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
    public function index(Product $product, User $user, Request $request)
    {

        $products = $product::withTrashed()->with(['images', 'promotionAgainst'])->where('user_id','=',Auth::user()->id)->with(['productCategory'])
        ->orderBy('id', 'DESC');
        if($request->input('keywords')) {
            $searchParam = $request->input('keywords');
            $products = $products->where('title', 'like', '%'.$searchParam.'%')->orWhere('id', 'like', '%'.$searchParam.'%');
        }

        $products = $products->paginate($this->noOfItems);
        $products = (new ProductCollection($products))->response()->getData();

        if ($request->ajax()) {

          return response()->json(['products' => $products, 'paginator' => (string) PaginatorTrait::getPaginator($request, $products)->links()]);

        } else {

            return view('pages.product.index')->with([
                'products' => $products,
                'all_products' => Product::where('user_id', Auth::user()->id)->get(),
                'table_header' => 'components.products.index.theader',
                'table_body' => 'components.products.index.tbody',
                'paginator' => PaginatorTrait::getPaginator($request, $products),
            ]);
        }


    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Category $category)
    {
        // dd(count(Auth::user()->activeSubscriptions()));
        if(count(Auth::user()->activeSubscriptions()) > 0) {

          $productLimit = $this->getFeatureLimit(Auth::user()->activeSubscriptions(), 'no_of_products');
          $featuredLimit = $this->getFeatureLimit(Auth::user()->activeSubscriptions(), 'featured_products');
          if(Product::where('user_id', Auth::user()->id)->get()->count() < $productLimit) {
            $categories = (new CategoryCollection($category::where('level', 1)->get()))->response()->getData();
            return view('pages.product.create')->with([
              'categories'=> $categories->data,
              'can_be_featured' => Product::where('user_id', Auth::user()->id)->where('is_featured', 1)->get()->count() < $featuredLimit ? $featuredLimit : 0
            ]);
          } else {
            Session::flash('message', __('You need to buy package adons, since the current subscriptions allow only ' . $productLimit . __(' products to be added.')));
            Session::flash('alert-class', 'alert-error');

            return redirect()->route('subscription.home');
          }
        } else {
          Session::flash('message', __('You need to purchase a package in order to add your product'));
          Session::flash('alert-class', 'alert-error');

          return redirect()->route('subscription.home');
        }

        // $productLimit = $this->getFeatureLimit(Auth::user()->activeSubscriptions(), 'no_of_products');
        // $featuredLimit = $this->getFeatureLimit(Auth::user()->activeSubscriptions(), 'featured_products');
        // $categories = (new CategoryCollection($category::where('level', 1)->get()))->response()->getData();
        //     return view('pages.product.create')->with([
        //       'categories'=> $categories->data,
        //       'can_be_featured' => Product::where('user_id', Auth::user()->id)->where('is_featured', 1)->get()->count() < $featuredLimit ? $featuredLimit : 0
        //     ]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductRequest $request,Product $product)
    {
        // dd($request->all());
      $productLimit = $this->getFeatureLimit(Auth::user()->activeSubscriptions(), 'no_of_products');
      if(Product::where('user_id', Auth::user()->id)->get()->count() >= $productLimit) {
        Session::flash('message', __('You need to buy package adons, since the current subscriptions allow only ' . $productLimit . __(' products to be added.')));
        Session::flash('alert-class', 'alert-error');
        return redirect()->route('subscription.home');
      }

      $validatedData = $request->validated();

        try {
          DB::beginTransaction();

          $productImages = $request->file('product_images');
          $productDocument = $request->file('product_document');

          $categoryIndex = count($request->category);

          $productDetails = $request->only(['title','description','price']);
          $productDetails['category_id'] = $request->category[$categoryIndex];
          $productDetails['category'] = Category::select('title')->where('id', $productDetails['category_id'])->get('title')->pluck('title')->pop();
          $productDetails['user_id'] = Auth::user()->id;

          if(!empty($request->input('is_featured'))) {
            $data = $this->canBeFeatured();
            if($data['can_be_featured'] == 0) {
              return array('message'=> $data['message'], 'alert-class' => $data['alertClass']);
            } else {
              $productDetails['is_featured'] = 1;
            }
          }

          $product = $product->firstOrCreate($productDetails);

          $mainImageIndex = !empty($request->input('main_image')) ? $request->input('main_image') : 0;
          if(!empty($productImages)) {

            foreach($productImages as $index => $productImage) {
              $imageInfo[$index]['is_main'] = 0;
              if($index == $mainImageIndex) {
                $imageInfo[$index]['is_main'] = 1;
              }

              if(isset($productImage))
                {
                    $imageInfo[$index]['path'] = $this->uploadFile($productImage, 'product/images', 'product-image');
                    $imageInfo[$index]['title'] = $productImage->getClientOriginalName();
                }
            }

            $product->images()->createMany($imageInfo);
          }

          if(!empty($productDocument)) {
            
            if(isset($productDocument))
            {
                $documentInfo['path'] = $this->uploadFile($productDocument, 'product/images', 'product-image');
                $documentInfo['title'] = $productDocument->getClientOriginalName();
            }

            $product->documents()->firstORCreate($documentInfo);
          }

          $productVideoLink = $request->input('product_video_link');
          if(!empty($productVideoLink)) {
            $videoInfo['title'] = "Video-".uniqid();
            $videoInfo['link'] = $productVideoLink;
            $videoInfo['host'] = "youtube";
            $product->videos()->firstORCreate($videoInfo);
          }


          $productSpecificationKeys = $request->get('product_details_key');
          $productSpecificationValues = $request->get('product_details_value');
          $prodSpecs = [];
          foreach($productSpecificationKeys as $index => $detail) {
            $prodSpecs[$index]['key'] = $detail;
            $prodSpecs[$index]['value'] = $productSpecificationValues[$index];
          }


          $product->details()->createMany($prodSpecs);

          DB::commit();

          Session::flash('message', __('Product has been saved successfully'));
          Session::flash('alert-class', 'alert-success');
        } catch (\Exception $e) {
          DB::rollback();
            Session::flash('message', __($e->getMessage()));
            Session::flash('alert-class', 'alert-error');
        }

          return redirect()->route('product.home');
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
          return redirect()->back();
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
        $categories = $category::where('level', 1)->get();
        $categories = (new CategoryCollection($categories))->response()->getData();
        $product = (new ProductResource($product))->response()->getData();

        if(!empty($product)) {
          $featuredLimit = $this->getFeatureLimit(Auth::user()->activeSubscriptions(), 'featured_products');
            $productCategories = $product->data->category->bread_crumb;
            $productCategories = trim($productCategories, ";");
            $productCategories = str_replace(';', ',', $productCategories) . ',' . $product->data->category->id;

            return view('pages.product.edit')->with([
                'product' => $product->data,
                'categories' => $categories->data,
                'view_docs_link' => route('product.document-index', ['product' => $product->data->id]),
                'can_be_featured' => Product::where('user_id', Auth::user()->id)->where('is_featured', 1)->get()->count() < $featuredLimit ? $featuredLimit : 0,
                'product_categories' => $productCategories
            ]);
        } else {
            Session::flash('message', __('No such product exists'));
            Session::flash('alert-class', 'alert-error');
            return redirect()->back();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
      if(!empty($request->input('orignal_image_id'))) {
        $saved = $this->updateProductImages($request,$product);
      } else if(!empty($request->file('product_images'))){
          $saved = $this->addMoreImages($request,$product);
      } else {
          $saved = $this->updateProduct($request,$product);
      }

      Session::flash('message', $saved['message']);
      Session::flash('alert-class', $saved['alert-class']);

      return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        if($product->delete()) {
            Session::flash('message', __('Product has been deleted successfully'));
            Session::flash('alert-class', 'alert-success');
        } else {
            Session::flash('message', __('Unable to delete product'));
            Session::flash('alert-class', 'alert-error');
        }

        return redirect()->back();

    }

    public function updateProductImages(Request $request, Product $product)
    {
        $productImage = $request->file('product_image');
        $message = "";
        $alertClass = "";
        if(!empty($productImage)) {
            $imageId = $request->input('orignal_image_id');
            $productImageModel = new ProductImage();

            $otherImages = $productImageModel::whereNotIn('id',[$imageId])->where('product_id',[$product->id])->get();

            $productImageModel = $productImageModel->find($imageId);
            if(isset($productImage))
            {
                $productImageModel->path = $this->uploadFile($productImage, 'product/images', 'product-image');
                $productImageModel->title = $productImage->getClientOriginalName();
            }

            DB::beginTransaction();
            if(!empty($request->input('is_main'))) {
                $productImageModel->is_main =  1;
            }

            if(!$productImageModel->save()) {
                DB::rollBack();
                $message = __('Unable to save changes');
                $alertClass = "alert-error";
            } else {
                if(!empty($request->input('is_main'))) {
                    foreach($otherImages as $image) { //setting other images is_main to 0
                        $productImageModel = $productImageModel->find($image->id);
                        $productImageModel->is_main = 0;
                        if(!$productImageModel->save()) {
                            DB::rollBack();
                            $message = __('Unable to save changes');
                            $alertClass = "alert-error";
                            return array('message'=> $message, 'alert-class' => $alertClass);

                        }
                    }

                    DB::commit();
                    $message = __('Image has been changed successfully');
                    $alertClass = "alert-success";
                    return array('message'=> $message, 'alert-class' => $alertClass);


                } else {

                    DB::commit();
                    $message = __('Image has been changed successfully');
                    $alertClass = "alert-success";
                    return array('message'=> $message, 'alert-class' => $alertClass);

                }
            }


        } else {
            $message = __('New image needs to be uploaded');
            $alertClass = "alert-error";
        }

        return array('message'=> $message, 'alert-class' => $alertClass);
    }

    public function addMoreImages(Request $request,Product $product)
    {
        $productImages = $request->file('product_images');
        if(!empty($productImages)) {

            foreach($productImages as $index => $productImage) {
                $imageInfo[$index]['is_main'] = 0;

                if(isset($productImage))
                {
                    $imageInfo[$index]['path'] = $this->uploadFile($productImage, 'product/images', 'product-image');
                    $imageInfo[$index]['title'] = $productImage->getClientOriginalName();
                }
            }

            if($product->images()->createMany($imageInfo)) {
                $message = __('Images uploaded successfully');
                $alertClass = "alert-success";
            } else {
                $message = __('Unable to upload images');
                $alertClass = "alert-error";
            }

            return array('message'=> $message, 'alert-class' => $alertClass);
        }
    }

    public function updateProduct(Request $request,Product $product)
    {
        try {
            $message = __('Unable to update product details');
            $alertClass = "alert-error";

            DB::beginTransaction();

            $categoryIndex = count($request->category);
            $productDetails = $request->only(['title','description','price']);
            $productDetails['category_id'] = $request->category[$categoryIndex];
            $productDetails['category'] = Category::select('title')->where('id', $productDetails['category_id'])->get('title')->pluck('title')->pop();

            if(!empty($request->input('is_featured'))) {
              $data = $this->canBeFeatured();
              if($data['can_be_featured'] == 0) {
                return array('message'=> $data['message'], 'alert-class' => $data['alertClass']);
              } else {
                $productDetails['is_featured'] = 1;
              }
            } else {
              $productDetails['is_featured'] = 0;
            }

            $productVideosLink = $request->input('product_videos_link');
            $productVideoIds = $request->input('product_videos_id');

            $isUpdated = $product::where(['id' => $product->id])->update($productDetails);

            if($isUpdated) {


                if(!empty($productVideosLink)) {
                    foreach($productVideosLink as $index => $videoDetails) {
                        if(!empty($productVideoIds[$index])) { //update existing
                            $productVideo = $product->videos()->find($productVideoIds[$index]);
                            $productVideo->link = $productVideosLink[$index];
                            $productVideo->host =  "youtube";
                            $productVideo->title = "Video";
                            if($productVideo->isDirty()) { //update in case of changes only
                                if($productVideo->save()) {
                                    $message = __('Product Details have been updated successfully');
                                    $alertClass = "alert-success";
                                } else {
                                    DB::rollBack();
                                    $message = __('Unable to update product details');
                                    $alertClass = "alert-error";
                                    return array('message'=> $message, 'alert-class' => $alertClass);
                                }
                            }
                        } else { //create new
                            if(!empty($productVideosLink[$index])) {

                                $productVideo = new ProductVideo();
                                $productVideo->product_id = $product->id;
                                $productVideo->link = $productVideosLink[$index];
                                $productVideo->host = "youtube";
                                $productVideo->title = "Video";

                                if($productVideo->save()) {
                                    $message = __('Product Details have been updated successfully');
                                    $alertClass = "alert-success";
                                } else {
                                    DB::rollBack();
                                    $message = __('Unable to update product details');
                                    $alertClass = "alert-error";
                                    return array('message'=> $message, 'alert-class' => $alertClass);
                                }
                            }
                        }

                    }
                }


                $productSpecificationIds = $request->get('product_details_id');
                $productSpecificationKeys = $request->get('product_details_key');
                $productSpecificationValues = $request->get('product_details_value');


                if(!empty($productSpecificationIds)) {
                    /*
                    * delete which were not submitted from the form again
                    * used array_filter to remove null values, otherwise quert won't work
                    */
                    $productDetails = $product->details()->whereNotIn('id',array_filter($productSpecificationIds))->get();
                    $productDetailsId = $productDetails->pluck('id')->toArray();

                    if(!empty($productDetailsId)) {
                        $areProductsDeleted =  $product->details()->whereIn('id', $productDetailsId)->delete();
                        if($areProductsDeleted) {
                            $message = __('Product Details have been updated successfully');
                            $alertClass = "alert-success";
                        } else {
                            DB::rollBack();
                            $message = __('Unable to updated product details');
                            $alertClass = "alert-error";
                            return array('message'=> $message, 'alert-class' => $alertClass);
                        }
                    }
                }

                if(!empty($productSpecificationKeys)) {

                    foreach($productSpecificationKeys as $index => $detail) {
                        if(!empty($productSpecificationIds[$index])) {

                            //update existing
                            $productDetail = $product->details()->find($productSpecificationIds[$index]);
                            $productDetail->key = $productSpecificationKeys[$index];
                            $productDetail->value = $productSpecificationValues[$index];
                            if($productDetail->isDirty()) { //update in case of changes only
                                if($productDetail->save()) {
                                    $message = __('Product Details have been updated successfully');
                                    $alertClass = "alert-success";
                                } else {
                                    DB::rollBack();
                                    $message = __('Unable to updated product details');
                                    $alertClass = "alert-error";
                                    return array('message'=> $message, 'alert-class' => $alertClass);
                                }
                            }

                        } else { //create new
                            $productDetail = new ProductDetail();
                            $productDetail->product_id = $product->id;
                            $productDetail->key = $productSpecificationKeys[$index];
                            $productDetail->value = $productSpecificationValues[$index];

                            if($productDetail->save()) {
                                $message = __('Product Details have been updated successfully');
                                $alertClass = "alert-success";
                            } else {
                                DB::rollBack();
                                $message = __('Unable to update product details');
                                $alertClass = "alert-error";
                                return array('message'=> $message, 'alert-class' => $alertClass);
                            }
                        }

                    }
                }
            } else {
                DB::rollback();
                $message = __('Unable to update product details');
                $alertClass = "alert-error";
            }

            $message = __('Product Details have been updated successfully');
            $alertClass = "alert-success";
            DB::commit();
            return array('message'=> $message, 'alert-class' => $alertClass);
        } catch (\Throwable $th) {
            DB::rollBack();
            dd($th->getMessage());
            $message = __('Unable to update product details');
            $alertClass = "alert-error";
            return array('message'=> $message, 'alert-class' => $alertClass);
        }
    }

    public function setMainImage(Product $product, ProductImage $productImage)
    {
        DB::beginTransaction();

        $productImage->is_main = "1";
        if($productImage->save()) {
            //set other images is_main = 0
            $isUpdated = $productImage::whereNotIn('id',[$productImage->id])->where('product_id',$product->id)->update(['is_main' => 0]);

            if($isUpdated) {
                DB::commit();
                $message = __('New main image is set successfully');
                $alertClass = "alert-sucess";
            } else {
                DB::rollBack();
                $message = __('Unable to save changes');
                $alertClass = "alert-error";
            }
            return response()->json(['message' => $message, 'alert-class' => $alertClass]);

        } else {
            DB::rollBack();
            return response()->json(['message' => __('Main image not updated'), 'alert-class' => 'alert-error']);
        }
    }

    public function deleteImage(Product $product, ProductImage $productImage) {
        if($productImage->destroy($productImage->id)){
            Session::flash('message', __('Image has been deleted successfully'));
            Session::flash('alert-class', 'alert-success');
        } else {
            Session::flash('message', __('Unable to delete image'));
            Session::flash('alert-class', 'alert-danger');
        }

        return redirect()->route('product.edit',['product' => $product->id]);
    }

    public function restore(Product $product,$productId)
    {
        $product = $product::withTrashed()->find($productId);
        $this->authorize('restore', $product);

        if($product::withTrashed()->find($productId)->restore()) {
            Session::flash('message', __('Product has been reactivated successfully'));
            Session::flash('alert-class', 'alert-success');
        } else {
            Session::flash('message', __('Unable to reactivate product'));
            Session::flash('alert-class', 'alert-error');

        }

        return Auth::user()->hasRole(['super-admin','admin']) ? redirect()->route('admin.product.home') : redirect()->route('product.home');

    }

    public function showDeleted(Product $product,$productId)
    {

        $product = $product::withTrashed()->find($productId);
        $product = (new ProductResource($product))->response()->getData();

        $this->authorize('view', $product);

        if(!empty($product)) {
            return view('pages.product.show')->with('product',$product);
        } else {
            Session::flash('message', __('No such category exists'));
            Session::flash('alert-class', 'alert-error');
            return Auth::user()->hasRole(['super-admin','admin']) ? redirect()->route('admin.product.home') : redirect()->route('product.home');
        }
    }

    public function getProducts(Request $request, Product $product)
    {
        $data = [];
        $searchParam = $request->query('query');
        $products = $product::with(['images', 'productCategory', 'promotionAgainst'])->where('title','like','%'.$searchParam.'%');

        if(!empty($request->query('logged_in_user'))) {
            $products = $products->where('user_id', Auth::user()->id);
        }

        if(!empty($request->query('product_id'))) {
            $products = $products->where('id', $request->query('product_id'));
        }

        if(!empty($request->query('product_definition'))) {
            $products = $products->with('inventoryDefinition');
        }

        $products = $products->get();
        $products = (new ProductCollection($products))->response()->getData();

        if(!empty($products->data)) {

            foreach ($products->data as $key => $product) {
                $data[] = ["value" => $product->title, "data" => $product];
            }
        }

        return response()->json(['suggestions' => $data]);
    }


    public function getSellerProducts(Request $request, Product $product)
    {

        $data = [];
        $searchParam = $request->query('keywords');
        $sellerId = $request->query('seller_id');

        $products = $product::with(['images'])->where('user_id', $sellerId);

        if(!empty($searchParam)) {
            $products = $products->where("title", "like", "%$searchParam%");
        }

        $products = $products->get();
        $products = (new ProductCollection($products))->response()->getData();

        return response()->json($products);
    }

    public function canBeFeatured()
    {
      $data = [];
      $data['can_be_featured'] = 0;
      $featuredLimit = $this->getFeatureLimit(Auth::user()->subscriptions, 'featured_products');

      if(Auth::user()->isAdmin()) {
        $data['can_be_featured'] = 1;
        return $data;
      }

      if($featuredLimit == 0) {
        $data['message'] = __('Product can not be saved as featured, you need to buy either subscribe to a package or you need to buy an ad on');
        $data['alertClass'] = 'alert-error';
      } else if(Product::where('user_id', Auth::user()->id)->where('is_featured', 1)->get()->count() == $featuredLimit) {
        $data['message'] = __('Product can not be saved as featured, you need to buy an ad on since your current subscriptions allow only ') . $featuredLimit . __(' products to be featured.');
        $data['alertClass'] = 'alert-error';
      } else {
        $data['can_be_featured'] = 1;
      }
      return $data;
    }

    public function setAsFeatured(Product $product, Request $request) {
      $needsToBeFeatured = $request->is_featured;

      $data = [];
      if($needsToBeFeatured) {
        $data = $this->canBeFeatured();
        if($data['can_be_featured'] == 1) {
          $product->is_featured = $needsToBeFeatured;
          if($product->save()) {
            $data['message'] = __('Product has been marked as featured successfully');
            $data['alertClass'] = 'alert-success';
          }
        }
      } else {
        $product->is_featured = $needsToBeFeatured;
        if($product->save()) {
          $data['message'] = __('Product has been unmarked as featured successfully');
          $data['alertClass'] = 'alert-success';
        } else {
          $data['message'] = __('Unable to unmark Product as featured');
          $data['alertClass'] = 'alert-error';
        }
      }

      return response()->json($data);

    }
}
