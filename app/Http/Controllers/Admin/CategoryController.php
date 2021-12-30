<?php

namespace App\Http\Controllers\Admin;

use App\Events\SaveProductsCategoryEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\CategoryCollection;
use App\Http\Resources\Category as CategoryResource;
use App\Traits\PaginatorTrait;
use App\Traits\Helpers\FileUpload;

class CategoryController extends Controller
{

    private $noOfItems;
    use FileUpload;

    public function __construct()
    {
        $this->authorizeResource(Category::class);
        $this->noOfItems = config('pagination.category', config('pagination.default'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Category $category, Request $request)
    {
        $categories = $category::withTrashed()->with(['parentCategory', 'homePageCategory']);

        if($request->input('keywords')) {
            $searchParam = $request->input('keywords');
            $categories = $categories->where('title', 'like', '%'.$searchParam.'%')->orWhere('id', 'like', '%'.$searchParam.'%');
        }

        $categories = $categories->paginate($this->noOfItems);
        $categories = (new CategoryCollection($categories))->response()->getData();
        if($request->ajax()) {

            return response()->json(['categories' => $categories, 'paginator' => (string) PaginatorTrait::getPaginator($request, $categories)->links()]);

        } else {

            return view('pages.category.index')->with([
                'categories' => $categories,
                'paginator' => PaginatorTrait::getPaginator($request, $categories)
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
        $categories = $category->all();
        return view('pages.category.create')->with('categories', $categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCategoryRequest $request, Category $category)
    {
        $validatedData = $request->validated();

        $categoryImage = $validatedData['image_path'];
        if(isset($validatedData['image_path']))
        {
            $categoryImagePath = $this->uploadFile($validatedData['image_path'], 'category/images/', 'category');
        }
        $level = 1;
        $breadCrumb = null;
        if(!empty($validatedData['parent_cat_id'])) {
            $parentCategory = Category::select('level', 'bread_crumb')->where('id', $validatedData['parent_cat_id'])->get();
            $level = $parentCategory->pluck('level')->all();
            $breadCrumb = $parentCategory->pluck('bread_crumb')->all();

            $level = $level[0] + 1;
            if(!empty($breadCrumb[0])) {
                $breadCrumb = $breadCrumb[0] . $validatedData['parent_cat_id'] . ";";
            } else {
                $breadCrumb = ";" . $validatedData['parent_cat_id'] . ";";
            }

        }

        $category = $category::withTrashed()->firstOrNew(
            [
                'title' => $validatedData['title']
            ],
            [
                'parent_cat_id' => $validatedData['parent_cat_id'],
                'image_path'  => $categoryImagePath ?? 'default.png',
                'level' => $level,
                'bread_crumb' => $breadCrumb
            ]
        );

        if (!$category->exists) {

            if ($category->save()) {
                Session::flash('message', __('Category has been saved successfully'));
                Session::flash('alert-class', 'alert-success');
                return redirect()->route('admin.category.home');
            } else {
                Session::flash('message', __('Unable to save category, try again later'));
                Session::flash('alert-class', 'alert-error');
                return redirect()->route('admin.category.create');
            }
        } else {
            Session::flash('message', __('Category with the following title already exists'));
            Session::flash('alert-class', 'alert-error');
            return redirect()->route('admin.category.create');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        $category->load('parentCategory');
        $category = (new CategoryResource($category))->response()->getData();

        if (!empty($category)) {
            return view('pages.category.show')->with('category', $category->data);
        } else {
            Session::flash('message', __('No such category exists'));
            Session::flash('alert-class', 'alert-error');
            return redirect()->route('admin.category.home');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        $categories = $category->all();
        $category->load('parentCategory');
        $category = (new CategoryResource($category))->response()->getData();

        if (!empty($category)) {
            return view('pages.category.edit')->with([
                'categories' => $categories,
                'category_details' => $category->data
            ]);

        } else {
            Session::flash('message', __('No such category exists'));
            Session::flash('alert-class', 'alert-error');
            return redirect()->route('admin.category.home');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(StoreCategoryRequest $request, Category $category)
    {

        $validatedData = $request->validated();

        if(isset($validatedData['image_path']))
        {
            $category->image_path = $this->uploadFile($validatedData['image_path'], 'category/images/', 'category');
        }

        $level = 1;
        $breadCrumb = null;
        if(!empty($validatedData['parent_cat_id'])) {
            $parentCategory = Category::select('level', 'bread_crumb')->where('id', $validatedData['parent_cat_id'])->get();
            $level = $parentCategory->pluck('level')->all();
            $breadCrumb = $parentCategory->pluck('bread_crumb')->all();

            $level = $level[0] + 1;
            if(!empty($breadCrumb[0])) {
                $breadCrumb = $breadCrumb[0] . $validatedData['parent_cat_id'] . ";";
            } else {
                $breadCrumb = ";" . $validatedData['parent_cat_id'] . ";";
            }
        }

        $category->title = $validatedData['title'];
        $category->parent_cat_id = $validatedData['parent_cat_id'];
        $category->level = $level;
        $category->bread_crumb = $breadCrumb;


        if($category->save()) {
            SaveProductsCategoryEvent::dispatch($category);
            Session::flash('message',  __('Category has been updated successfully'));
            Session::flash('alert-class', 'alert-success');
            return redirect()->route('admin.category.edit', ['category' => $category->id]);
        } else {
            Session::flash('message', __('Unable to save changes'));
            Session::flash('alert-class', 'alert-error');
            return redirect()->route('admin.category.edit', ['category' => $category->id]);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        if (!empty($category->products->toArray())) {
            Session::flash('message', __('Unable to delete category, since category has products'));
            Session::flash('alert-class', 'alert-error');
        } else if (!empty($category->subCategories->toArray())) {
            if (!empty($category->subCategories->load('products')->toArray())) {
                Session::flash('message', __('Unable to delete category, since category has subcategories which have products'));
                Session::flash('alert-class', 'alert-error');
            } else {
                if (!$category->delete()) {
                    Session::flash('message', __('Unable to delete category'));
                    Session::flash('alert-class', 'alert-error');
                } else {
                    Session::flash('message', __('Category has been deleted successfully'));
                    Session::flash('alert-class', 'alert-success');
                }
            }
        } else {
            if (!$category->delete()) {
                Session::flash('message', __('Unable to delete category'));
                Session::flash('alert-class', 'alert-error');
            } else {
                Session::flash('message', __('Category has been deleted successfully'));
                Session::flash('alert-class', 'alert-success');
            }
        }

        return redirect()->route('admin.category.home');
    }

    public function showDeleted(Category $category, $categoryId)
    {
        $this->authorize('view', $category);

        $category = $category::withTrashed()->find($categoryId);
        $category = (new CategoryResource($category))->response()->getData();

        if (!empty($category)) {
            return view('pages.category.show')->with('category', $category->data);
        } else {
            Session::flash('message', __('No such category exists'));
            Session::flash('alert-class', 'alert-error');
            return redirect()->route('admin.category.home');
        }
    }

    public function restore(Category $category, $categoryId)
    {
        if ($category::withTrashed()->find($categoryId)->restore()) {
            Session::flash('message', __('Catrgory has been reactivated successfully'));
            Session::flash('alert-class', 'alert-success');
        } else {
            Session::flash('message', __('Unable to reactivate category'));
            Session::flash('alert-class', 'alert-error');
        }
        return redirect()->route('admin.category.home');
    }


    public function getCategories(Request $request, Category $categories)
    {

        $data = [];
        $searchParam = $request->query('query');
        $level = $request->query('level');
        $parent_cat_id = $request->query('parent_cat_id');

        if(!empty($searchParam)) {
          $categories = $categories->where('title','like','%'.$searchParam.'%');
        }

        if(!empty($level)) {
          $categories = $categories->where('level', $level);
        }

        if(!empty($parent_cat_id)) {
            $categories = $categories->where('parent_cat_id', $parent_cat_id);
        }

        $categories = $categories->get();

        $categories = (new CategoryCollection($categories))->response()->getData();

        if(!empty($categories->data)) {

            foreach ($categories->data as $key => $category) {
                $data[] = ["value" => $category->title, "data" => $category];
            }
        }

        return response()->json(['suggestions' => $data]);
    }
}
