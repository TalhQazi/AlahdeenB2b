<?php

namespace App\Http\Controllers;

use App\Models\BusinessCertification;
use App\Models\BusinessDetail;
use App\Traits\PackageUsageTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class BusinessCertificationController extends Controller
{

    use PackageUsageTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(BusinessDetail $businessDetail)
    {
        $this->authorize('viewAny', BusinessCertification::class);

        $businessDetail = $businessDetail::select('id')->where(['user_id' => Auth::user()->id])->get();

        if(!empty($businessDetail[0])) {
            $businessDetail = $businessDetail[0];
            $certificates = $businessDetail->businessCertificates()->paginate(10);
            $awards = $businessDetail->businessAwards()->paginate(10);
            $certificateAwardsIsAvailable = $this->getFeatureLimit(Auth::user()->activeSubscriptions(), 'can_add_certificates_n_awards') == 1;


            return view('pages.profile.certiticates-award')->with([
                'business_id' => $businessDetail->id,
                'business_certificates' => $certificates,
                'business_awards' => $awards,
                'certificateAwardsIsAvailable' => $certificateAwardsIsAvailable
            ]);
        } else {
            Session::flash('message', __('Business details needs to be added before adding certifications'));
            Session::flash('alert-class', 'alert-error');
            return redirect()->route('profile.business.home');
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, BusinessCertification $businessCertification)
    {
        $certificateAwardsIsAvailable = $this->getFeatureLimit(Auth::user()->activeSubscriptions(), 'can_add_certificates_n_awards') == 1;
        $message = __('Unable to save certification information');
        $alertClass = 'alert-error';

        Validator::make($request->all(), [
            'certification' => ['required', 'string'],
            'membership' => ['nullable', 'string'],
            'image_path' => ['image', 'nullable', 'mimes:jpeg,jpg,png', 'max:200']
        ],
        [],
        [
            'image_path' => 'certification image'

        ])->validate();

        $businessCertificationInfo = $request->only(
            [
                'certification',
                'membership',
            ]
        );

        $certificationLogo = $request->file('image_path');

        if (!empty($certificationLogo)) {
            $businessCertificationInfo['image_path'] = $certificationLogo->store('public/business/certification/images');
        }

        if(!empty($request->input('certification_id'))) { //update new certification
            $businessCertification = $businessCertification->find($request->input('certification_id'));
            $this->authorize('update',$businessCertification);
            if($businessCertification->update($businessCertificationInfo)) {
                $message = __('Certification has been updated successfully');
                $alertClass = 'alert-success';
            }
        } else {
            //insert new certification
            $businessCertificationInfo['business_id'] = $request->input('business_id');
            $this->authorize('createCertificate',[$businessCertification, $businessCertificationInfo['business_id']]);
            if($certificateAwardsIsAvailable && $businessCertification->create($businessCertificationInfo)){
                $message = __('Certification details have been saved successfully');
                $alertClass = 'alert-success';
            } else {
                $message = __('Certification is not included in package or package is expired');
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
     * @param  \App\Models\BusinessCertification  $businessCertification
     * @return \Illuminate\Http\Response
     */
    public function edit(BusinessCertification $businessCertification, Request $request)
    {
        if($request->ajax()) {
            return response()->json(['data'=> $businessCertification]);
        } else {
            return $businessCertification;
        }
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BusinessCertification  $businessCertification
     * @return \Illuminate\Http\Response
     */
    public function destroy(BusinessCertification $businessCertification)
    {
        $message = __('Unable to delete certification');
        $alertClass = 'alert-error';

        $this->authorize('delete', $businessCertification);
        if($businessCertification->delete()) {
            $message = __('Certification deleted successfully');
            $alertClass = 'alert-success';
        }

        Session::flash('message', $message);
        Session::flash('alert-class', $alertClass);
        return redirect()->route('profile.business.certifications-awards');
    }
}
