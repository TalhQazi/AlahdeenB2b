<?php

namespace App\Http\Controllers\Api\Warehouse;

use App\Events\RecordStatsEvent;
use App\Helpers\ApiHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\Warehouse as ResourcesWarehouse;
use App\Http\Resources\WarehouseCollection;
use App\Models\Warehouse\Warehouse;
use App\Traits\ApiResponser;
use App\Traits\PaginatorTrait;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    use ApiResponser, PaginatorTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Warehouse $warehouse)
    {
        $noItems = $request->query('per_page', 10);
        if(!empty($request->query())) {

            if($request->has('load')) {
                $warehouse = apiHelper::loadRelations($warehouse, $request->load);
            }

            if($request->has('search_params')) {

                if(array_key_exists('city', $request->search_params)) {
                  $warehouse = $warehouse->whereHas('city', function($query) use ($request) {
                        $query->where('city', 'like', '%'.$request->search_params['city'].'%');
                    });
                }

                if(array_key_exists('locality', $request->search_params)) {
                  $warehouse = $warehouse->whereHas('locality', function($query) use ($request) {
                        $query->where('name', 'like', '%'.$request->search_params['locality'].'%');
                    });
                }

                if(array_key_exists('property_type', $request->search_params)) {
                  $warehouse = $warehouse->whereHas('propertyType', function($query) use ($request) {
                        $query->where('title', $request->search_params['property_type']);
                    });
                }

                if(array_key_exists('min_area', $request->search_params)) {
                    $warehouse = $warehouse->where('area', '>=', (integer) $request->search_params['min_area']);
                }

                if(array_key_exists('max_area', $request->search_params)) {
                  $warehouse = $warehouse->where('area', '<=', $request->search_params['max_area']);
                }

                if(array_key_exists('min_price', $request->search_params)) {
                    $warehouse->where('price', '>=', $request->search_params['min_price']);
                }

                if(array_key_exists('max_price', $request->search_params)) {
                    $warehouse->where('price', '<=', $request->search_params['max_price']);
                }

                if(array_key_exists('can_be_shared', $request->search_params)) {
                  $warehouse = $warehouse->where('can_be_shared', $request->search_params['can_be_shared']);
                }

                if(array_key_exists('is_verified', $request->search_params)) {
                  $warehouse = $warehouse->where('is_verified', $request->search_params['is_verified']);
                }

            }


            if($request->has('selectors')) {
                $warehouse = apiHelper::fetchColumns($warehouse, $request->selectors);
            }
        }

        $warehouse = $warehouse->where([
            'is_active' => 1,
            'is_approved' => 1,
        ]);

        $warehouse = $warehouse->paginate($noItems);
        $warehouse = (new WarehouseCollection($warehouse))->response()->getData();

        RecordStatsEvent::dispatch($warehouse->data, 'no_of_impressions', 'warehouse');

        return $this->success(
            [
                'warehouse' => $warehouse->data,
                'paginator' => PaginatorTrait::getPaginator($request, $warehouse)
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
     * @param  \App\Models\Warehouse\Warehouse  $warehouse
     * @return \Illuminate\Http\Response
     */
    public function show(Warehouse $warehouse)
    {
        $warehouse = $warehouse::with(['images', 'features', 'city', 'locality', 'propertyType', 'owner', 'owner.business'])->where([
            'is_active' => 1,
            'is_approved' => 1,
            'id' => $warehouse->id
        ])->get();

        if(!empty($warehouse) && !empty($warehouse[0])) {

            $warehouse = (new ResourcesWarehouse($warehouse[0]))->response()->getData();
            RecordStatsEvent::dispatch(array($warehouse->data), 'no_of_views', 'warehouse');
            return $this->success(
                [
                    'warehouse' => $warehouse->data
                ]
            );
        } else {
            return $this->error(
                __('Not Found'),
                404
            );
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Warehouse\Warehouse  $warehouse
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Warehouse $warehouse)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Warehouse\Warehouse  $warehouse
     * @return \Illuminate\Http\Response
     */
    public function destroy(Warehouse $warehouse)
    {
        //
    }

    public function relatedWarehouses(Warehouse $warehouse, Request $request)
    {
      $limit = $request->query('limit', 6);
      $users = Warehouse::select('user_id')->where('user_id', '<>', $warehouse->user_id)->where('property_type_id', $warehouse->property_type_id)->groupBy('user_id')->distinct()->pluck('user_id')->toArray();

      $warehouses = [];
      for($i = 0; $i < $limit; $i++) {
        if(!empty($users[$i])) {
          $warehouse = Warehouse::with(['images', 'locality', 'city'])->where('user_id', $users[$i])->where('property_type_id', $warehouse->property_type_id)->get();
          $warehouses[] = (new ResourcesWarehouse($warehouse[0]))->response()->getData()->data;
        }
      }

      return $this->success(
        [
          'related_warehouses' => $warehouses
        ]
      );
    }


     /**
     * Display the trending resources based on no of views.
     *
     * @return \Illuminate\Http\Response
     */
    public function popularWarehouses(Request $request)
    {
        $limit = $request->query('limit', 5);
        $warehouses = Warehouse::select('warehouses.*')->with(['images', 'locality', 'city', 'owner', 'owner.business'])->join('popular_warehouses', 'warehouses.id', '=', 'warehouse_id')->limit($limit)->get();

        return $this->success(
            [
                'warehouses' => (new WarehouseCollection($warehouses))->response()->getData()->data
            ],
        );

    }
}
