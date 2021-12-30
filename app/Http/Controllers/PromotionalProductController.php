<?php

namespace App\Http\Controllers;

use App\Models\PromotionalProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PromotionalProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Validator::make($request->input(), [
            'promotion_against_id' => ['required',
                Rule::exists('App\Models\Product', 'id')->where(function ($query) {
                    return $query->where('user_id', Auth::user()->id);
                })
            ],
            'promotional_product_id' => ['required',
                Rule::exists('App\Models\Product', 'id')->where(function ($query) {
                    return $query->where('user_id', Auth::user()->id);
                })
            ],
            'promotional_discount_percentage' => ['required', 'numeric'],
            'by_date' => [Rule::requiredIf($request->by_no_of_units == null), Rule::in(['on'])],
            'by_no_of_units' => [Rule::requiredIf($request->by_date == null), Rule::in(['on'])],
            'start_date' => [Rule::requiredIf($request->by_date != null), 'nullable', 'date', 'date_format:Y-m-d'],
            'end_date' => [Rule::requiredIf($request->by_date != null), 'nullable', 'date',  "after:".$request->start_time, 'date_format:Y-m-d'],
            'no_of_units' => [Rule::requiredIf($request->by_no_of_units != null), 'nullable', 'integer'],
            'promotion_description' => ['required', 'string']
        ])->validate();

        $created = PromotionalProduct::create([
            'product_id' => $request->promotion_against_id,
            'promotional_product_id' => $request->promotional_product_id,
            'discount_percentage' => $request->promotional_discount_percentage,
            'by_date' => !empty($request->by_date) ? 1 : 0,
            'by_no_of_units' => !empty($request->by_no_of_units) ? 1 : 0,
            'start_date' => $request->start_date ?? NULL,
            'end_date' => $request->end_date ?? NULL,
            'no_of_units' => $request->no_of_units ?? NULL,
            'remaining_no_units' => $request->no_of_units ?? 0,
            'description' => $request->promotion_description
        ]);

        if($created) {
            Session::flash('alert-success', __('Promotion has been saved successfully'));
        } else {
            Session::flash('alert-error', __('Unable to save promotion'));
        }

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PromotionalProduct  $promotionalProduct
     * @return \Illuminate\Http\Response
     */
    public function show(PromotionalProduct $promotionalProduct, Request $request)
    {
        if($request->ajax()) {
            $promotionalProduct = $promotionalProduct::with(['productOnPromotion'])->where('id', $promotionalProduct->id)->where('is_active', 1)->get();
            if(!empty($promotionalProduct)) {
                return response()->json($promotionalProduct[0]);
            } else {
                return response()->json();
            }
        }


        abort(400, 'bad request');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PromotionalProduct  $promotionalProduct
     * @return \Illuminate\Http\Response
     */
    public function edit(PromotionalProduct $promotionalProduct)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PromotionalProduct  $promotionalProduct
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PromotionalProduct $promotionalProduct)
    {
        if($request->ajax()) {

            $promotionalProduct->is_active = 0;
            if($promotionalProduct->save()) {
                return response()->json(['cancelled' => 1]);
            } else {
                return response()->json(['cancelled' => 0]);
            }
        } else {
            abort(400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PromotionalProduct  $promotionalProduct
     * @return \Illuminate\Http\Response
     */
    public function destroy(PromotionalProduct $promotionalProduct)
    {
        //
    }
}
