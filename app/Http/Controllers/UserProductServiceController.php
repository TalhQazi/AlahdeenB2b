<?php

namespace App\Http\Controllers;

use App\Models\UserProductService;
use App\Models\BusinessType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class UserProductServiceController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(UserProductService::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(BusinessType $businessType, UserProductService $userProductService)
    {
        $businessTypes = $businessType->all();
        $productServices = $userProductService::where('user_id',Auth::user()->id)->get();
        $primaryFiltered = $productServices->filter(function ($value, $key) {
            return $value->is_primary === 1;
        });

        $secondaryFiltered = $productServices->filter(function ($value, $key) {
            return $value->is_primary === 0;
        });

        $primaryProductServices = $primaryFiltered->all();
        $secondaryProductServices = $secondaryFiltered->all();

        return view('pages.product-services.index')
                ->with('business_types', $businessTypes)
                ->with('primary_product_services', $primaryProductServices)
                ->with('secondary_product_services', $secondaryProductServices);;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, UserProductService $userProductService)
    {
        $input = $request->all();

        Validator::make($input, [
            'primary_business_type' => ['required'],
            'main_keywords' => ['array',],
            'main_keywords.*' => ['required',],
        ],
        [
            // custom validation messages
        ],
        [
            // custom attribute values

        ])->validate();

        if(!empty($request->input('primary_business_type'))) {

            DB::beginTransaction();

                $userProductServices = $userProductService::where('user_id',Auth::user()->id)->get();
                $userProductService::destroy($userProductServices->pluck('id')->toArray());

                $userProductInfo['user_id'] = Auth::user()->id;
                $userProductInfo['business_type_id'] = $request->input('primary_business_type');
                $userProductInfo['keywords'] = json_encode($request->input('main_keywords'));
                $userProductInfo['is_primary'] = 1;

                if($userProductService->firstOrCreate($userProductInfo)) {
                    if(!empty($request->input('secondary_business_types'))) {
                        $secondaryBusinesses = $request->input('secondary_business_types');
                        $secondaryKeywords = $request->input('secondary_keywords');
                        foreach($secondaryBusinesses as $type_id => $status) {
                            $userProductInfo['user_id'] = Auth::user()->id;
                            $userProductInfo['business_type_id'] = $type_id;
                            if(!empty($secondaryKeywords) && !empty($secondaryKeywords[$type_id])) {
                                $userProductInfo['keywords'] = json_encode($secondaryKeywords[$type_id]);
                            }
                            $userProductInfo['is_primary'] = 0;
                            if(!$userProductService->firstOrCreate($userProductInfo)) {
                                DB::rollBack();
                                Session::flash('message', 'Unable to save category, try again later');
                                Session::flash('alert-class', 'alert-error');
                                return redirect()->route('product.services.home');
                            }
                        }
                    }
                    DB::commit();
                    Session::flash('message', 'Information saved successfully');
                    Session::flash('alert-class', 'alert-success');
                } else {
                    DB::rollback();
                    Session::flash('message', 'Unable to save category, try again later');
                    Session::flash('alert-class', 'alert-error');
                }

                return redirect()->route('product.services.home');
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\UserProductService  $userProductService
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserProductService $userProductService)
    {
        if(!empty($request->input('deleted_keyword'))) {
            $deletedKeyWord = $request->input('deleted_keyword');
            $savedKeywords = !empty($userProductService->keywords) ? json_decode($userProductService->keywords) : [];
            $key = array_search($deletedKeyWord,$savedKeywords);

            if($key !== false) {
                unset($savedKeywords[$key]);
                if(!empty($savedKeywords)) {}
                $userProductService->keywords = json_encode($savedKeywords);
                if(!$userProductService->save()){
                    echo json_encode(array('status' => 400, 'message' => 'Unable to delete keyword'));
                } else {
                    echo json_encode(array('status' => 200, 'message' => 'keyword deleted successfully'));
                }
            }

        }
    }

}
