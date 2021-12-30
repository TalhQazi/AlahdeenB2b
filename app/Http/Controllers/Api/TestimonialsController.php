<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TestimonialCollection;
use App\Models\Testimonial;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class TestimonialsController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $limit = $request->query('limit', 5);

        $testimonials = Testimonial::limit($limit)->get();
        $testimonials = (new TestimonialCollection($testimonials))->response()->getData();

        return $this->success(
            [
                'testimonials' => $testimonials->data
            ]
        );

    }
}
