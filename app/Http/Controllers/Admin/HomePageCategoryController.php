<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomePageCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


class HomePageCategoryController extends Controller
{
     /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.category.display')->with([
            'display_section' => ['Top Bar', 'Middle Section']
        ]);
    }


    public function store(Request $request)
    {
        Validator::make($request->input(),
            [
                'title' => ['required', 'string'],
                'cat_id' => ['required', 'unique:home_page_categories,category_id'],
                'display_section' => ['required', Rule::in('Top Bar', 'Middle Section')],
                'display_order' => ['required', 'min:1']
            ],
            [
               'cat_id.required' => __('Enter valid category'),
               'cat_id.unique' => __('Category has already been assigned display section')
            ],
            [
                'cat_id' => __('Category')
            ]
        )->validate();

        $created = HomePageCategory::create([
            'category_id' => $request->cat_id,
            'display_section' => $request->display_section,
            'display_order' => $request->display_order
        ]);

        if($created) {
            Session::flash('message', __('Display location and order saved carefully'));
            Session::flash('alert-class', 'alert-success');
        } else {
            Session::flash('message', __('Unable to save'));
            Session::flash('alert-class', 'alert-error');
        }

        return redirect()->back();
    }
}
