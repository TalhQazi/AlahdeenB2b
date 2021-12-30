<?php

namespace App\Http\Controllers\Api\Logistics;

use App\Http\Controllers\Controller;
use App\Http\Resources\Logistics\DriverLocation as LogisticsDriverLocation;
use App\Models\Logistics\DriverLocation;
use App\Models\Logistics\Driver;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class DriversLocationController extends Controller
{
    use ApiResponser;

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Validator::make($request->all(),
        [
            'driver_id' => ['required', Rule::exists('App\Models\Logistics\Driver', 'id')],
            'lat' => ['required', 'numeric'],
            'lng' => ['required', 'numeric']
            ]
        )->validate();

        $driver = Driver::find($request->driver_id);

        if(Auth::user()->id == $driver->user_id) {

            $updated = DriverLocation::updateOrCreate(
                [
                    'driver_id' => $request->driver_id
                ],
                [
                    'lat' => $request->lat,
                    'lng' => $request->lng
                ]
            );

            if($updated) {
                $updated = (new LogisticsDriverLocation($updated))->response()->getData();
                return $this->success($updated, __('Driver Location has been updated'));
            } else {
                return $this->error(__('Unable to updated driver location'), 500);
            }
        } else {
            return $this->error(__('Unauthorized'), 403);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Logistics\DriverLocation  $driverLocation
     * @return \Illuminate\Http\Response
     */
    public function show(Driver $driver)
    {
        $driverLocation = $driver->location;
        if(!empty($driverLocation)) {
            $driverLocation = (new LogisticsDriverLocation($driverLocation))->response()->getData();
            return $this->success($driverLocation);
        } else {
            return $this->error(__('Driver location not found'), 404);
        }

    }

}
