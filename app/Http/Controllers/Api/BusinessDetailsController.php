<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BusinessDetail as ResourcesBusinessDetail;
use App\Http\Resources\BusinessDetailCollection;
use App\Models\BusinessDetail;
use App\Traits\ApiResponser;
use App\Traits\PaginatorTrait;
use Illuminate\Http\Request;

class BusinessDetailsController extends Controller
{

  use ApiResponser, PaginatorTrait;

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function index(Request $request, BusinessDetail $businessDetail)
  {
    $noItems = $request->query('per_page', 20);
    $businessDetail = $businessDetail->with(['user', 'additionalDetails', 'productServices']);
    if (!empty($request->query())) {

      if ($request->has('search_params')) {

        if (array_key_exists('company_name', $request->search_params)) {
          $businessDetail = $businessDetail->where('company_name', 'like', '%' . $request->search_params['company_name'] . '%');
        }

        if (array_key_exists('seller', $request->search_params)) {
          $businessDetail = $businessDetail->whereHas('user', function ($query) use ($request) {
            $query->where('name', 'like', '%' . $request->search_params['seller'] . '%');
          });
        }

        if (array_key_exists('business_type', $request->search_params)) {
          $businessDetail = $businessDetail->whereHas('productServices', function ($query) use ($request) {
            $query->where('business_type_id', $request->search_params['business_type']);
          });
        }
      }

      $businessDetail = $businessDetail->paginate($noItems);
      $businessDetail = (new BusinessDetailCollection($businessDetail))->response()->getData();
      $paginator = PaginatorTrait::getPaginator($request, $businessDetail);

    } else {
      $businessDetail = $businessDetail->limit(10)->get();
      $paginator = [];
    }

    return $this->success(
      [
        'companies' => $businessDetail->data,
        'paginator' => $paginator
      ]
    );
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
   * @param  \App\Models\BusinessDetail  $businessDetail
   * @return \Illuminate\Http\JsonResponse
   */
  public function show($id)
  {
    $businessDetail = BusinessDetail::with([
        'user',
        'user.companyBanner',
        'user.companyDisplayProducts',
        'user.companyDisplayProducts.product',
        'user.companyDisplayProducts.product.images',
        'user.badges',
        'additionalDetails',
        'productServices',
        'productServices.businessType',
        'businessCertificates',
        'director',
    ])->where('id', $id)->get();
    if (!empty($businessDetail[0])) {

      $businessDetail = (new ResourcesBusinessDetail($businessDetail[0]))->response()->getData();
      return $this->success(
        [
          'company' => $businessDetail->data
        ]
      );
    } else {
      return $this->error(
        __('Not found'),
        404
      );
    }
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Models\BusinessDetail  $businessDetail
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, BusinessDetail $businessDetail)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\BusinessDetail  $businessDetail
   * @return \Illuminate\Http\Response
   */
  public function destroy(BusinessDetail $businessDetail)
  {
    //
  }
}
