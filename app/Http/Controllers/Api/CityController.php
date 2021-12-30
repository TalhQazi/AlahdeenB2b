<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class CityController extends Controller
{

    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->has('title')) {
            $cities = City::where('city','like','%'.$request->query('title').'%')->get();
        } else {
            $cities = City::all();
        }

        $data = [];
        if(!empty($cities)) {
            foreach ($cities as $key => $city) {
                $data[] = ["id" => $city->id, "value" => $city->city];
            }

            return $this->success(
                [
                    'cities' => $data
                ]
            );
        } else {
            return $this->error(
                __('No such city found'),
                200
            );
        }
    }

}
