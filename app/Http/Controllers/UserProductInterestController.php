<?php

namespace App\Http\Controllers;

use App\Models\UserProductInterest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;

class UserProductInterestController extends Controller
{
    private $noOfItems;

    public function __construct()
    {
        $this->authorizeResource(UserProductInterest::class);
        $this->noOfItems = !empty(config('pagination.product_of_interests')) ? config('pagination.product_of_interests') : config('pagination.default');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(UserProductInterest $productInterest)
    {

        $productsOfInterest = $productInterest->where('user_id','=',Auth::user()->id)->paginate($this->noOfItems);
        $quantities = config('quantity_unit');

        return view('pages.product-interests.index')->with('products_of_interests',$productsOfInterest)->with('quantity_units',$quantities);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, UserProductInterest $productInterest)
    {
        $productInterestInfo = $request->only(['required_product', 'frequency', 'quantity', 'quantity_unit']);
        $productInterestInfo['user_id'] = Auth::user()->id;


        Validator::make($productInterestInfo, [
            'required_product' => ['required', 'string' ,'alpha'],
            'frequncy' => ['nullable', 'string'],
            'quantity' => ['required', 'numeric'],
            'quantity_unit' => ['required', 'string']
        ],
        [
            // custom validation messages
        ],
        [
            // custom attribute values

        ])->validate();


        if($productInterest->firstOrCreate($productInterestInfo)) {
            Session::flash('message', 'Product of Interest has been saved successfully');
            Session::flash('alert-class', 'alert-success');
            return redirect()->route('product.interest.home');
        } else {
            Session::flash('message', 'Unable to save Product of Interest');
            Session::flash('alert-class', 'alert-erroe');
            return redirect()->route('product.interest.home');
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UserProductInterest  $userProductInterest
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserProductInterest $userProductInterest)
    {
        if($userProductInterest->destroy($userProductInterest->id)) {
            Session::flash('message', 'Product of Interest deleted successfully');
            Session::flash('alert-class', 'alert-success');
            return redirect()->route('product.interest.home');
        } else {
            Session::flash('message', 'Unable to delete Product of Interest');
            Session::flash('alert-class', 'alert-error');
            return redirect()->route('product.interest.home');
        }
    }
}
