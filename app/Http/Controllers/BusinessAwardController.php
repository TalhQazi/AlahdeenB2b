<?php

namespace App\Http\Controllers;

use App\Models\BusinessAward;
use App\Traits\PackageUsageTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class BusinessAwardController extends Controller
{
    use PackageUsageTrait;

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, BusinessAward $businessAward)
    {
        $certificateAwardsIsAvailable = $this->getFeatureLimit(Auth::user()->activeSubscriptions(), 'can_add_certificates_n_awards') == 1;
        $message = __('Unable to save award information');
        $alertClass = 'alert-error';

        Validator::make($request->all(), [
            'title' => ['required', 'string'],
            'award_image' => ['image', 'nullable', 'mimes:jpeg,jpg,png', 'max:200']
        ],
        [],
        [
            'award_image' => 'award image'

        ])->validate();

        $businessAwardInfo = $request->only(
            [
                'title',
            ]
        );

        $certificationLogo = $request->file('award_image');

        if (!empty($certificationLogo)) {
            $businessAwardInfo['award_image'] = $certificationLogo->store('public/business/award/images');
        }

        if(!empty($request->input('award_id'))) { //update new certification
            $businessAward = $businessAward->find($request->input('award_id'));
            $this->authorize('update', $businessAward);

            if($businessAward->update($businessAwardInfo)) {
                $message = __('Award details have been updated successfully');
                $alertClass = 'alert-success';
            }
        } else { //insert new certification
            $businessAwardInfo['business_id'] = $request->input('award_business_id');

            $this->authorize('createAward', [$businessAward, $businessAwardInfo['business_id']]);

            if($certificateAwardsIsAvailable && $businessAward->create($businessAwardInfo)){
                $message = __('Award details have been saved successfully');
                $alertClass = 'alert-success';
            } else {
                $message = __('Awards is not included in package or package is expired');
                $alertClass = 'alert-error';
            }
        }

        Session::flash('message', $message);
        Session::flash('alert-class', $alertClass);
        return redirect()->route('profile.business.certifications-awards');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\businessAward  $businessAward
     * @return \Illuminate\Http\Response
     */
    public function edit(BusinessAward $businessAward, Request $request)
    {
        if($request->ajax()) {
            return response()->json(['data'=> $businessAward]);
        } else {
            return $businessAward;
        }
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\businessAward  $businessAward
     * @return \Illuminate\Http\Response
     */
    public function destroy(BusinessAward $businessAward)
    {
        $message = __('Unable to delete award');
        $alertClass = 'alert-error';

        $this->authorize('delete', $businessAward);
        if($businessAward->delete()) {
            $message = __('Award deleted successfully');
            $alertClass = 'alert-success';
        }

        Session::flash('message', $message);
        Session::flash('alert-class', $alertClass);
        return redirect()->route('profile.business.certifications-awards');
    }
}
