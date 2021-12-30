<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class AjaxController extends Controller
{
    public function getSubCategories($id = null)
    {
        if(is_null($id))
        {
            abort(404);
        }
        $list = Category::where('parent_cat_id', $id)->get();
        $cat = Category::where('id', $id)->first();
        $html = view("ajax.common.sub_categories", get_defined_vars());
        $array = ['error' => '', 'html' => $html];
        return response()->json($array);
    }
}
