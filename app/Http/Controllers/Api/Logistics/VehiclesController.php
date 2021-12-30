<?php

namespace App\Http\Controllers\Api\Logistics;

use App\Http\Controllers\Controller;
use App\Http\Resources\Logistics\VehicleCollection;
use App\Models\Logistics\Vehicle;
use App\Traits\ApiResponser;
use App\Traits\PaginatorTrait;
use Illuminate\Http\Request;

class VehiclesController extends Controller
{
    use PaginatorTrait, ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Vehicle $vehicles)
    {
        if(!empty($request->query())) {
            if($request->has('parent_id')) {
                $vehicles = $vehicles::where('parent_id', $request->query('parent_id'));
            }
        } else {
            $vehicles = $vehicles::where('parent_id', 0);
        }

        $vehicles = $vehicles->paginate($request->query('per_page', 20));
        $vehicles = (new VehicleCollection($vehicles))->response()->getData();
        $paginator = PaginatorTrait::getPaginator($request, $vehicles);

        return $this->success(
            [
                'vehicles' => $vehicles->data,
                'paginator' => $paginator
            ]
        );

    }

}
