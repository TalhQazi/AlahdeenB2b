<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PromotionalProductCollection;
use App\Models\PromotionalProduct;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class PromotionalProductsController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $promotions = PromotionalProduct::with(['promotionAgainst', 'productOnPromotion'])->where('is_active', 1)->get();
        $promotions = (new PromotionalProductCollection($promotions))->response()->getData();
        return $this->success($promotions);

    }

}
