<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AdvertismentCollection;
use App\Models\Advertisment;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;


class AdvertismentsController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $limit = 10)
    {
        $limit = $request->query('limit', 10);
        $advertisments = Advertisment::where('is_active',1);
        if($request->has('display_section')) {
            $advertisments = $advertisments->where('display_section', $request->display_section)->where('is_active',1);
        }

        $advertisments = $advertisments->orderBy('display_order')->limit($limit)->get();


        $advertisments = (new AdvertismentCollection($advertisments))->response()->getData();
        return $this->success([
            'advertisments' => $advertisments->data
        ]);

    }

}
