<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BusinessTypeCollection;
use App\Models\BusinessType;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class BusinessTypeController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $businessTypes = BusinessType::getBusinessTypes();
        $businessTypes = (new BusinessTypeCollection($businessTypes))->response()->getData();
        return $this->success(
            [
                'business_types' => $businessTypes->data
            ]
        );
    }

}
