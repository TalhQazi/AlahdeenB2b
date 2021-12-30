<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class ConfigurationController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $configurations = [];
        if(!empty($request->query("load"))) {
            $load = $request->query("load");

            if(array_key_exists('quantity_units', $load)) {
                $configurations['quantity_units'] = config('quantity_unit');
            }

            if(array_key_exists('supplier_location', $load)) {
                $configurations['supplier_location'] = config('configurations.supplier_location');
            }

            if(array_key_exists('requirement_frequency', $load)) {
                $configurations['requirement_frequency'] = config('configurations.requirement_frequency');
            }

            if(array_key_exists('requirement_urgency', $load)) {
                $configurations['requirement_urgency'] = config('configurations.requirement_urgency');
            }


        } else {

            $configurations['quantity_units'] = config('quantity_unit');
            $configurations['supplier_location'] = config('configurations.supplier_location');
            $configurations['requirement_frequency'] = config('configurations.requirement_frequency');
            $configurations['requirement_urgency'] = config('configurations.requirement_urgency');

        }

        return $this->success(
            [
                'configurations' => $configurations
            ]
        );
    }

}
