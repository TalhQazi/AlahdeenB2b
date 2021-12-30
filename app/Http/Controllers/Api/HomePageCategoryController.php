<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryCollection;
use App\Models\Category;
use App\Models\HomePageCategory;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class HomePageCategoryController extends Controller
{

    use ApiResponser;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, HomePageCategory $homePageCategories)
    {

        $limit = $request->query('limit', 6);
        $subLimit = $request->query('sub_limit', 5);

        if($request->has('display_section')) {


            $homePageCategories = $homePageCategories->where('display_section', $request->display_section)->orderBy('display_order')->limit($limit)->get();
            $categories = $this->getCategoriesInfo($homePageCategories);
            $categories = (new CategoryCollection($categories))->response()->getData();

            if($request->has('load_subcategory')) {

                if(!empty($categories->data)) {
                    foreach($categories->data as $category) {
                        $subCategories = Category::where('parent_cat_id', $category->id)->limit($subLimit)->get();
                        $subCategories = (new CategoryCollection($subCategories))->response()->getData();
                        $category->{'sub_categories'}['data'] = $subCategories->data;
                    }

                }
            }

            return $this->success(
                [
                    'categories' => $categories->data
                ]
            );
        } else {
            $categories = Category::limit($limit)->get();
            $categories = (new CategoryCollection($categories))->response()->getData();
            return $this->success(
                [
                    'categories' => $categories->data
                ]
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\HomePageCategory  $homePageCategory
     * @return \Illuminate\Http\Response
     */
    public function show(HomePageCategory $homePageCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\HomePageCategory  $homePageCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, HomePageCategory $homePageCategory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\HomePageCategory  $homePageCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(HomePageCategory $homePageCategory)
    {
        //
    }

    public function getCategoriesInfo($homePageCategories) {
        $categories = [];
        if(!empty($homePageCategories)) {
            foreach($homePageCategories as $key => $homePageCategory) {
                $categories[] = $homePageCategory->category;
            }
        }

        return $categories;

    }
}
