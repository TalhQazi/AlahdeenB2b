<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\City;
use App\Models\Locality;

class LocationController extends Controller
{
    public function getLocations(Request $request) {
        $locLevel = $request->input('loc_level');
        $city_id = $request->input('city_id');
        $searchParam = $request->query('query');

        $data = [];

        if(!empty($locLevel)) {
            if($locLevel == 'state') {
                $locations = City::where('admin_name','like','%'.$searchParam.'%')->groupBy('admin_name')->get();
            } else if($locLevel == 'city') {
                $locations = City::where('city','like','%'.$searchParam.'%')->get();
            } else if($locLevel == 'locality') {
                $locations = Locality::with(['parentLocalities', 'childrenLocalities'])->where('city_id','=',$city_id)->where('name','like','%'.$searchParam.'%')->get();
            }

            if(!empty($locations)) {
                foreach ($locations as $key => $location) {
                    if($locLevel == 'state') {
                        $data[] = ["value" => $location->admin_name, "data" => $location];
                    } else if($locLevel == 'city'){
                        $data[] = ["value" => $location->city, "data" => $location];
                    } else {
                        $location->lat = $location->coordinates->getLat();
                        $location->lng = $location->coordinates->getLng();
                        $data[] = ["value" => $location->name, "data" => $location];
                    }
                }
            }

            return response()->json(['suggestions' => $data]);
        } else {
            return $data;
        }


    }


}
