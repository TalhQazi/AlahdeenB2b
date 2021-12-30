<?php

namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use App\Models\Warehouse\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Warehouse\PropertyType;
use App\Models\Warehouse\WarehouseFeatureKey;
use App\Models\Warehouse\WarehouseImage;
use App\Http\Requests\StoreWarehouse;
use App\Http\Resources\Warehouse as ResourcesWarehouse;
use App\Http\Resources\WarehouseCollection;
use App\Models\Locality;
use Grimzy\LaravelMysqlSpatial\Types\Point;
use Illuminate\Support\Facades\Session;
use App\Traits\PaginatorTrait;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use App\Traits\Helpers\FileUpload;

class WarehouseController extends Controller
{

    private $noOfItems;

    use FileUpload;

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
        $warehouses = $warehouse::with(config('relation_configuration.warehouse.index'))->orderBy('created_at','desc')->where('user_id', Auth::user()->id);

        if($request->input('keywords')) {
            $searchParam = $request->input('keywords');
            $warehouses = $warehouse->with(config('relation_configuration.warehouse.index'))
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
                'table_header' => 'components.warehouse.index.theader',
                'table_body' => 'components.warehouse.index.tbody',
                'paginator' => PaginatorTrait::getPaginator($request, $warehouses)
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $locality =  Locality::all();
        return view('pages.warehouse.create')->with([
            'city' => Auth::user()->city,
            'property_types' => PropertyType::getPropertyTypes(),
            'features' => WarehouseFeatureKey::getFeatures(),
            'locality' => $locality,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreWarehouse $request, Warehouse $warehouse)
    {
        $message = __('Unable to save warehouse details');
        $alertClass = 'alert-error';

        $validatedData = $request->validated();

        $created = tap($warehouse::create([
            'user_id' => Auth::user()->id,
            'property_type_id' => $validatedData['property_type_id'],
            'city_id' => $validatedData['city_id'],
            'locality_id' => $validatedData['locality_id'],
            'area' =>  $validatedData['area'],
            'price' =>  $validatedData['price'],
            // 'coordinates' => new Point($validatedData['lat'], $validatedData['lng']),
            'coordinates' => DB::raw('POINT('.$validatedData['lat'].', '.$validatedData['lng'].')'),
            'can_be_shared' => !empty($validatedData['can_be_shared']) ? 1 : 0
        ]), function (Warehouse $warehouse) use ($validatedData) {
            $this->saveWarehouseFeatures($warehouse, $validatedData);
            $this->saveWarehouseImages($warehouse, $validatedData);
        });

        
        if($created) {
            $message = __('Warehouse details have been saved successfully');
            $alertClass = 'alert-success';
        }

        Session::flash('message', $message);
        Session::flash('alert-class', $alertClass);
        return redirect()->route('warehouse.home');

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Warehouse\Warehouse  $warehouse
     * @return \Illuminate\Http\Response
     */
    public function edit(Warehouse $warehouse)
    {
        $warehouse = (new ResourcesWarehouse($warehouse::with(config('relation_configuration.warehouse.edit'))->find($warehouse->id)))->response()->getData();

        $localities =  Locality::all();
        // dd($locality);
        return view('pages.warehouse.edit')->with([
            'warehouse' => $warehouse->data,
            'city' => $warehouse->data->city,
            'property_types' => PropertyType::getPropertyTypes(),
            'features' => WarehouseFeatureKey::getFeatures(),
            // 'locality' => Locality::getLocalities(),
            'localities' => $localities,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Warehouse\Warehouse  $warehouse
     * @return \Illuminate\Http\Response
     */
    public function update(StoreWarehouse $request, Warehouse $warehouse)
    {
        $message = __('Unable to save warehouse details');
        $alertClass = 'alert-error';

        $validatedData = $request->validated();

        $wareHouseDetails = [
            'property_type_id' => $validatedData['property_type_id'],
            'city_id' => $validatedData['city_id'],
            'locality_id' => $validatedData['locality_id'],
            'area' =>  $validatedData['area'],
            'price' =>  $validatedData['price'],
            'coordinates' => DB::raw('POINT('.$validatedData['lat'].', '.$validatedData['lng'].')'),
            'can_be_shared' => !empty($validatedData['can_be_shared']) ? 1 : 0
        ];

        $updated = $warehouse->update($wareHouseDetails);

        $saved = $this->saveWarehouseFeatures($warehouse, $validatedData);

        $imageIds = !empty($validatedData['warehouse_image_id']) ? $validatedData['warehouse_image_id'] : [];
        $this->saveWarehouseImages($warehouse, $validatedData, $imageIds);

        if($updated && $saved) {
            $message = __('Warehouse details have been saved successfully');
            $alertClass = 'alert-success';
        }

        Session::flash('message', $message);
        Session::flash('alert-class', $alertClass);
        return redirect()->back();
    }

    public function saveWarehouseFeatures($warehouse, $requestData)
    {
        $this->authorize('saveWarehouseFeatures', $warehouse);
        if($warehouse->features()->exists()) {
            if(!$warehouse->features()->delete()) {
                return false;
            }
        }

        if(!empty($requestData['feature_name'])) {

            foreach ($requestData['feature_name'] as $key => $feature) {
                if(!empty($feature)) {
                    $features[$key]['feature_id'] = $key;
                    $features[$key]['feature'] = $feature;
                    $features[$key]['warehouse_id'] = $warehouse->id;
                }
            }

            if(!empty($features)) {
                if($warehouse->features()->createMany($features)) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }

        } else {
            return false;
        }
    }



    public function saveWarehouseImages($warehouse, $requestData, $imageIds=[])
    {
        $this->authorize('saveWarehouseImages', $warehouse);

        DB::beginTransaction();
        //No new images uploaded, and no old ones submitted
        if(empty($requestData['warehouse_images']) && empty($imageIds)) {
            DB::commit();
            return $this->deleteImages($warehouse, $imageIds);
        } else { //
            if($this->deleteImages($warehouse, $imageIds)) {

                if(!empty($requestData['warehouse_images'])) {

                    $warehouseImages = $requestData['warehouse_images'];
                    $mainImageIndex = !empty($requestData['main_image']) ? $requestData['main_image'] : 0;

                    //setting other images is_main to 0 if required
                    $this->unsetMainImage($warehouse, $mainImageIndex);

                    $count = 0;
                    foreach ($warehouseImages as $key => $image) {
                        /*
                            calculating correct index since images are submitted are in size desc order
                            so basically is mainImageIndex is set to 0 but the image to be set as main will be
                            the last one in array
                        */
                        if(array_key_last($warehouseImages) - $mainImageIndex == $count) {
                            $warehouseImageInfo[$key]['is_main'] = 1;
                        }
                        $warehouseImageInfo[$key]['image_path'] = $this->uploadFile($image, 'warehouse/'.uniqid(), 'warehouse-image');
                        $warehouseImageInfo[$key]['title'] = "warehouse-".uniqid();

                        $count++;

                    }

                    if($warehouse->images()->createMany($warehouseImageInfo)) {
                        DB::commit();
                        return true;
                    } else {
                        DB::rollback();
                        return false;
                    }
                } else {
                    DB::commit();
                    return true;
                }
            } else {
                //unable to delete images not submitted again
                DB::rollBack();
                return false;
            }
        }
    }

    public function setMainImage(Warehouse $warehouse, WarehouseImage $warehouseImage, Request $request)
    {

        $this->authorize('setMainImage', $warehouse);

        DB::beginTransaction();

        $warehouseImage->is_main = "1";
        if($warehouseImage->save()) {
            //set other images is_main = 0
            $isUpdated = true;
            if($warehouseImage::whereNotIn('id',[$warehouseImage->id])->where('warehouse_id',$warehouse->id)->exists()) {
                $isUpdated = $warehouseImage::whereNotIn('id',[$warehouseImage->id])->where('warehouse_id',$warehouse->id)->update(['is_main' => 0]);
            }


            if($isUpdated) {
                DB::commit();
                $message = __('New main image is set successfully');
                $alertClass = "alert-sucess";
            } else {
                DB::rollBack();
                $message = __('Unable to save changes');
                $alertClass = "alert-error";
            }

            if($request->ajax()) {
                return response()->json(['message' => $message, 'alert-class' => $alertClass]);
            } else {
                Session::flash('message', __('Warehouse new main image has been set successfully'));
                Session::flash('alert-class', 'alert-success');
                return redirect()->back();
            }

        } else {
            if($request->ajax()) {
                DB::rollBack();
                return response()->json(['message' => __('Main image not updated'), 'alert-class' => 'alert-error']);
            } else {
                Session::flash('message', __('Warehouse new main image has been set successfully'));
                Session::flash('alert-class', 'alert-success');
                return redirect()->back();
            }

        }
    }


    /*
    * Function responsible for deleting images, not submitted again at the time of edit
    */
    public function deleteImages(Warehouse $warehouse, $imageIds)
    {
        $this->authorize('deleteImages', $warehouse);

        //No old images were submitted again, delete existing images if any
        if(empty($imageIds)) {
            if($warehouse->images()->exists()) {
                $warehouseImages = $warehouse->images()->get();
                $warehouseImages = $warehouseImages->pluck('image_path')->all();
                if($warehouse->images()->delete() && Storage::delete($warehouseImages)) {
                    return true;
                } else {
                    return false;
                }
            } else {
                //No images existed already
                return true;
            }
        } else {
            //delete existing images if not submitted again/different image uploaded
            if($warehouse->images()->whereNotIn('id',$imageIds)->exists()) {
                $warehouseImages = $warehouse->images()->whereNotIn('id',$imageIds)->get();
                $warehouseImages = $warehouseImages->pluck('image_path')->all();
                if($warehouse->images()->whereNotIn('id',$imageIds)->delete() && Storage::delete($warehouseImages)) {
                    return true;
                } else {
                    return false;
                }
            } else {
                //No images existed already
                return true;
            }
        }
    }

    public function unsetMainImage(Warehouse $warehouse, $mainImageIndex)
    {
        $this->authorize('unsetMainImage', $warehouse);

        //No need to set is_main to 0 for other images since no image uploaded with flag set to is_main
        if(empty($mainImageIndex)) {
            return true;
        } else {
            if($warehouse->images()->update(['is_main' => 0])) {
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Warehouse\Warehouse  $warehouse
     * @return \Illuminate\Http\Response
     */
    public function activate(Warehouse $warehouse)
    {
        $this->authorize('activate', $warehouse);

        $warehouse->is_active = 1;
        if($warehouse->save()) {
            Session::flash('message', __('Warehouse has been reactivated successfully'));
            Session::flash('alert-class', 'alert-success');
        } else {
            Session::flash('message', __('Unable to delete reactivate warehouse'));
            Session::flash('alert-class', 'alert-error');
        }

        return redirect()->back();
    }

    public function deactivate(Warehouse $warehouse)
    {
        $this->authorize('deactivate', $warehouse);

        $warehouse->is_active = 0;
        if($warehouse->save()) {
            Session::flash('message', __('Warehouse has been deactivated successfully'));
            Session::flash('alert-class', 'alert-success');
        } else {
            Session::flash('message', __('Unable to deactivate warehouse'));
            Session::flash('alert-class', 'alert-error');

        }

        return redirect()->back();
    }

}
