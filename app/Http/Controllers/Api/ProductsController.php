<?php

namespace App\Http\Controllers\Api;

use App\Events\RecordStatsEvent;
use App\Helpers\ApiHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\Product as ResourcesProduct;
use App\Http\Resources\ProductCollection;
use App\Models\Product;
use App\Models\ProductImpressionStat;
use App\Traits\ApiResponser;
use App\Traits\PaginatorTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class ProductsController extends Controller
{
    use ApiResponser, PaginatorTrait;

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        $product = $product::with(
                                'images',
                                'details',
                                'videos',
                                'documents',
                                'company',
                                'company.additionalDetails',
                                'promotionAgainst',
                                'promotionAgainst.productOnPromotion',
                                'promotionAgainst.productOnPromotion.images'
                            )->where('id', $product->id)->get();
        $product = (new ResourcesProduct($product[0]))->response()->getData();
        RecordStatsEvent::dispatch(array($product->data), 'no_of_views', 'product');
        return $this->success(
            [
                'product' => $product->data
            ]
        );
    }

    public function search(Request $request, Product $products, ApiHelper $apiHelper)
    {

        $noItems = $request->query('per_page', 20);
        if(!empty($request->query())) {

            if($request->has('load')) {
                $products = apiHelper::loadRelations($products, $request->load);
            }

            if($request->has('search_params')) {

                if(array_key_exists('title', $request->search_params)) {
                  $products = $products->where('title', 'like', '%'.$request->search_params['title'].'%');
                }

                if(array_key_exists('keywords', $request->search_params)) {
                    $products = $products->whereHas('details', function($query) use ($request) {
                        $featureKeywords = explode(',', $request->search_params['keywords']);
                        foreach($featureKeywords as $keyword) {
                            $query->where('key', 'like' ,'%'.$keyword.'%')->orWhere('value', 'like', '%'.$keyword.'%');
                        }

                    });
                }


                if(array_key_exists('min_price', $request->search_params)) {
                  $products = $products->where('price', '>=', $request->search_params['min_price']);
                }

                if(array_key_exists('max_price', $request->search_params)) {
                  $products = $products->where('price', '<=', $request->search_params['max_price']);
                }

                if(array_key_exists('business_type', $request->search_params)) {

                    $products = $products->whereHas('productServices', function($query) use ($request) {
                        $query->where('business_type_id', $request->search_params['business_type'])->where('keywords', 'like', '%'.$request->search_params['title'].'%');
                    });

                }

                if(array_key_exists('company_id', $request->search_params)) {

                  $products = $products->whereHas('company', function($query) use ($request) {
                      $query->where('business_details.id', $request->search_params['company_id']);
                  });

              }

            }


            if($request->has('selectors')) {
                $products = apiHelper::fetchColumns($products, $request->selectors);
            }

        }

        $products = $products->orderBy('is_featured', 'desc')->paginate($noItems);
        $products = (new ProductCollection($products))->response()->getData();


        RecordStatsEvent::dispatch($products->data, 'no_of_impressions', 'product');
        return $this->success(
            [
                'products' => $products->data,
                'paginator' => PaginatorTrait::getPaginator($request, $products)
            ]
        );
    }

    /**
     * Display the trending resources based on no of views.
     *
     * @return \Illuminate\Http\Response
     */
    public function trending(Request $request)
    {
        $limit = $request->query('limit', 5);
        $products = Product::with('images')
            ->select('products.*')
            ->join('trending_products', 'products.id', '=', 'product_id')->limit($limit)->get();

        return $this->success(
            [
                'products' => (new ProductCollection($products))->response()->getData()->data
            ],
        );

    }

    public function relatedProducts(Product $product, Request $request)
    {
      $limit = $request->query('limit', 6);
      $users = Product::select('user_id')->where('user_id', '<>', $product->user_id)->where('category_id', $product->category_id)->groupBy('user_id')->distinct()->pluck('user_id')->toArray();
      $categoryId = $product->category_id;
      $products = [];
      for($i = 0; $i < $limit; $i++) {
        if(!empty($users[$i])) {

            $product = Product::with('images')->where('user_id', $users[$i])->where('category_id', $categoryId)->get();
            $products[] = (new ResourcesProduct($product[0]))->response()->getData()->data;
        }
      }


      return $this->success(
        [
          'related_products' => $products
        ]
      );
    }
}
