<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Warehouse\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Warehouse\PropertyType;
use App\Models\Warehouse\WarehouseFeatureKey;
use App\Http\Resources\Warehouse as ResourcesWarehouse;
use App\Http\Resources\WarehouseCollection;
use App\Models\Locality;
use App\Traits\PaginatorTrait;
use Illuminate\Support\Facades\Session;


class WarehouseController extends Controller
{

    private $noOfItems;

    public function __construct()
    {
        $this->authorizeResource(Warehouse::class);
        $this->noOfItems = config('pagination.warehouse', config('pagination.default'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Warehouse $warehouse, Request $request)
    {
        $warehouses = $warehouse::withTrashed()->with(config('relation_configuration.warehouse.index'))->withTrashed()->orderBy('created_at','desc');

        if($request->input('keywords')) {
            $searchParam = $request->input('keywords');
            $warehouses = $warehouse::withTrashed()->with(config('relation_configuration.warehouse.index'))
            ->whereHas('propertyType', function($query) use ($searchParam) {
                $query->where('title', 'like', '%'.$searchParam.'%');
            })
            ->orWhereHas('locality', function($query) use ($searchParam) {
                $query->where('name', 'like', '%'.$searchParam.'%');
            });
        }

        $warehouses = $warehouses->paginate($this->noOfItems);
        $warehouses = (new WarehouseCollection($warehouses))->response()->getData();

        if ($request->ajax()) {
            return response()->json(['warehouses' => $warehouses, 'paginator' => (string) PaginatorTrait::getPaginator($request, $warehouses)->links()]);
        } else {

            return view('pages.warehouse.index')->with([
                'warehouses' => $warehouses->data,
                'table_header' => 'components.warehouse.index.admin.theader',
                'table_body' => 'components.warehouse.index.admin.tbody',
                'paginator' => PaginatorTrait::getPaginator($request, $warehouses),
            ]);
        }
    }




    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Warehouse\Warehouse  $warehouse
     * @return \Illuminate\Http\Response
     */
    public function edit(Warehouse $warehouse)
    {
        $warehouse = (new ResourcesWarehouse($warehouse->with(config('relation_configuration.warehouse.edit'))->find($warehouse->id)))->response()->getData();

        $localities =  Locality::all();
        return view('pages.warehouse.edit')->with([
            'warehouse' => $warehouse->data,
            'city' => $warehouse->data->city,
            'property_types' => PropertyType::getPropertyTypes(),
            'features' => WarehouseFeatureKey::getFeatures(),
            'localities' => $localities
        ]);
    }

    public function approve(Warehouse $warehouse)
    {
        $this->authorize('approve', $warehouse);

        $message = __('Unable to approve warehouse');
        $alertClass = 'alert-error';

        $warehouse->is_approved = 1;

        if($warehouse->save()) {
            $message = __('Warehouse has been approved successfully');
            $alertClass = 'alert-success';
        }

        Session::flash('message', $message);
        Session::flash('alert-class', $alertClass);
        return redirect()->back();
    }

    public function disapprove(Warehouse $warehouse)
    {
        $this->authorize('disapprove', $warehouse);

        $message = __('Unable to disapprove warehouse');
        $alertClass = 'alert-error';

        $warehouse->is_approved = 0;

        if($warehouse->save()) {
            $message = __('Warehouse has been disapproved successfully');
            $alertClass = 'alert-success';
        }

        Session::flash('message', $message);
        Session::flash('alert-class', $alertClass);
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Warehouse\Warehouse  $warehouse
     * @return \Illuminate\Http\Response
     */
    public function destroy(Warehouse $warehouse)
    {
        if($warehouse->delete()) {
            Session::flash('message', __('Warehouse has been deleted successfully'));
            Session::flash('alert-class', 'alert-success');
        } else {
            Session::flash('message', __('Unable to delete warehouse'));
            Session::flash('alert-class', 'alert-error');
        }

        return redirect()->back();
    }

    public function restore(Warehouse $warehouse, $id)
    {
        if($warehouse::withTrashed()->findOrFail($id)->restore()) {
            Session::flash('message', __('Warehouse has been restore successfully'));
            Session::flash('alert-class', 'alert-success');
        } else {
            Session::flash('message', __('Unable to restore warehouse'));
            Session::flash('alert-class', 'alert-error');

        }

        return redirect()->back();

    }


}
