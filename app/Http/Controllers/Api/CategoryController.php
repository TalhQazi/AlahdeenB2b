<?php

namespace App\Http\Controllers\Api;

use App\Events\RecordStatsEvent;
use App\Http\Controllers\Controller;
use App\Http\Resources\Category as ResourcesCategory;
use App\Http\Resources\CategoryCollection;
use App\Http\Resources\ProductCollection;
use App\Models\Category;
use App\Traits\ApiResponser;
use App\Models\Product;
use App\Traits\PaginatorTrait;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use ApiResponser, PaginatorTrait;

    protected $category_ids;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request, Category $categories)
    {

        if(!empty($request->query())) {

            $products = [];
            if(!empty($request->query('title'))) {
                $categories = Category::where('title', 'like', '%'.$request->title.'%');
                $searchTitles = explode(' ', $request->title);

                if($request->has('load')){
                    if(array_key_exists('products', $request->load)) {
                        $products = Product::with(['images', 'company', 'company.additionalDetails'])->where('category', 'like', '%'.$request->title.'%');
                    }
                }

                if(count($searchTitles) > 1) {
                    for($i = 0; $i < count($searchTitles); $i++) {
                        $categories = $categories->orWhere('title', 'like', '%'.$searchTitles[$i].'%');
                        $products = $products->with(['images', 'company', 'company.additionalDetails'])->orWhere('category', 'like', '%'.$searchTitles[$i].'%');
                    }
                }
            } else {
                $categories = Category::orderBy('level');
                if($request->has('load')){
                    $products = Product::with(['images', 'company', 'company.additionalDetails']);
                }
            }

            if($request->has('parent_cat_id')) {
                $categories = $categories->where('parent_cat_id', $request->query('parent_cat_id'));
            }

            if($request->has('level')) {
                $level = explode(',', $request->query('level'));
                $categories = $categories->whereIn('level', $level);
            }

            if($request->has('cat_id')) {
                $categories = $categories->where('bread_crumb', 'like', '%'.';'.$request->query('cat_id').';'.'%');
            }

            if($request->has('limit')) {
                $categories = $categories->limit($request->query('limit'))->get();
                $products = !empty($products) ? $products->limit($request->query('limit'))->get() : [];
                $categories = (new CategoryCollection($categories))->response()->getData();
                $products = !empty($products) ? (new ProductCollection($products))->response()->getData() : [];
                $paginator = [];
                $products_paginator = [];
            } else {
                $perPage  = $request->query('per_page', 20);
                $categories = $categories->paginate($perPage);
                $products = !empty($products) ? (new ProductCollection($products->paginate($perPage)))->response()->getData() : [];
                $categories = (new CategoryCollection($categories))->response()->getData();
                $paginator = PaginatorTrait::getPaginator($request, $categories);
                $products_paginator = !empty($products) ? PaginatorTrait::getPaginator($request, $products) : [];
            }

            RecordStatsEvent::dispatch($categories->data,'no_of_impressions', 'category');

            return $this->success(
                [
                    'products' => $products->data ?? $products,
                    'categories' =>  $categories->data,
                    'category_paginator' =>   $paginator,
                    'product_paginator' => $products_paginator
                ]
            );
        } else {
            return $this->error(
                __('No results found'),
                404
            );
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        $category = Category::with(
            [
                'subCategories',
            ]
        )->where('id', $id)->get();

        if(!empty($category[0])) {

            $this->breadCrumb = [];
            $category = (new ResourcesCategory($category[0]))->response()->getData()->data;

            $breadCrumbs = Category::whereIn('id', explode(";", $category->bread_crumb))->get();
            $category->bread_crumb = $breadCrumbs;

            RecordStatsEvent::dispatch(array($category), 'no_of_views', 'category');
            return $this->success(
                [
                    'category' => $category,
                ],
            );
        } else {
            return $this->error(null, 404);
        }
    }

    /**
     * Display the trending resources based on no of views.
     *
     * @return \Illuminate\Http\Response
     */
    public function trending(Request $request)
    {
        $limit = $request->query('limit', 5);
        $categories = Category::select('categories.*')->with('subCategories')->join('trending_categories', 'categories.id', '=', 'category_id')->limit($limit)->get();

        return $this->success(
            [
                'categories' => (new CategoryCollection($categories))->response()->getData()->data
            ],
        );

    }

    public function getCategoryProducts($id, Request $request) {
        $noOfItems = $request->query('per_page', 20);

        $this->category_ids = [];
        $category = Category::with('allChildCategories')->where('id', $id)->get();

        $category->each(function($item, $key) {
            array_push($this->category_ids, $item->id);
            if($item->allChildCategories) {
                $this->getAllChildCategories($item->allChildCategories);
            }

        });

        $products = Product::with(['company', 'company.additionalDetails', 'images'])->whereIn('category_id', $this->category_ids);
        $products = $products->paginate($noOfItems);
        $products = (new ProductCollection($products))->response()->getData();
        $paginator = PaginatorTrait::getPaginator($request, $products);
        $products = $products->data;


        return $this->success(
            [
                'products' => $products,
                'paginator' => $paginator
            ]
        );

    }


    public function getAllChildCategories($item)
    {
        $item->each(function($data, $key) {
            array_push($this->category_ids, $data->id);
            if($data->allChildCategories) {
                $this->getAllChildCategories($data->allChildCategories);
            } else {
                return true;
            }
        });

    }

}
