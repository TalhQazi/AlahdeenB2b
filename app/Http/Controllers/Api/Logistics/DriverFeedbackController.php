<?php

namespace App\Http\Controllers\Api\Logistics;

use App\Http\Controllers\Controller;
use App\Models\Logistics\DriverFeedback;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class DriverFeedbackController extends Controller
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
        $validatedData = Validator::make(
            $request->all(),
            [
                'booking_request_id' => [
                    'required',
                    Rule::exists('App\Models\Logistics\BookingRequest', 'id')->where(function ($query) {
                        return $query->where('shipment_requestor', Auth::user()->id);
                    })
                ],
                'rating' => ['required', 'numeric'],
                'feedback' => ['nullable', 'string']
            ]
        )->validate();

        if(DriverFeedback::create($validatedData)) {
            return $this->success($validatedData, __('Feedback has been saved successfully'));
        } else {
            return $this->success([], __('Unable to save feedback'));
        }
    }

}
