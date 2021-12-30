<?php

namespace App\Http\Controllers;

use App\Models\CompanyPageProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CompanyPageProductController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, CompanyPageProduct $companyPageProduct)
    {
        Validator::make($request->input(), [
            'banner_products' => ['nullable', 'array'],
            'banner_products.*.product_id' => ['nullable',
                Rule::exists('App\Models\Product', 'id')->where(function ($query) {
                    return $query->where('user_id', Auth::user()->id);
                })
            ],
            'top_products' => ['nullable', 'array'],
            'top_products.*.product_id' => ['nullable',
                Rule::exists('App\Models\Product', 'id')->where(function ($query) {
                    return $query->where('user_id', Auth::user()->id);
                })
            ],
        ])->validate();


        DB::beginTransaction();

        if(!empty(Auth::user()->companyDisplayProducts->isNotEmpty)) {
            if(!CompanyPageProduct::where('user_id', Auth::user()->id)->delete()) {
                DB::rollBack();
                Session::flash('message', __('Unable to upload company products'));
                Session::flash('alert-class', 'alert-error');
                return redirect()->back();
            }
        }

        $products = [];

        if(!empty($request->banner_products)) {
            $productIds = [];

            foreach($request->banner_products as $product) {
                if(!empty($product['product_id'])) {
                    if(!empty($productIds) && in_array($product['product_id'], $productIds)) {
                        DB::rollBack();
                        Session::flash('message', __('Same product can not be added twice in banner products'));
                        Session::flash('alert-class', 'alert-error');
                        return redirect()->back();
                        break;
                    } else {
                        $productInfo['product_id'] = $product['product_id'];
                        $productInfo['display_section'] = "banner";
                        $products[] = $productInfo;
                        $productIds[] = $product['product_id'];
                    }
                }
            }

        }

        if(!empty($request->top_products)) {
            $productIds = [];
            foreach($request->top_products as $product) {
                if(!empty($product['product_id'])) {
                    if(!empty($productIds) && in_array($product['product_id'], $productIds)) {
                        DB::rollBack();
                        Session::flash('message', __('Same product can not be added twice in banner products'));
                        Session::flash('alert-class', 'alert-error');
                        return redirect()->back();
                        break;
                    } else {
                        $productInfo['product_id'] = $product['product_id'];
                        $productInfo['display_section'] = "top_products";
                        $products[] = $productInfo;
                        $productIds[] = $product['product_id'];
                    }
                }
            }
        }


        if(Auth::user()->companyDisplayProducts()->createMany($products)) {
            Session::flash('message', __('Company Products to be displayed have been saved successfully'));
            Session::flash('alert-class', 'alert-success');
            DB::commit();
        } else {
            Session::flash('message', __('Unable to upload company products'));
            Session::flash('alert-class', 'alert-error');
            DB::rollBack();
        }

        return redirect()->back();
    }

}
