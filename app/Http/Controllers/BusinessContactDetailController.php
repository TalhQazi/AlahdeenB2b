<?php

namespace App\Http\Controllers;

use App\Models\BusinessContactDetail;
use App\Models\BusinessDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class BusinessContactDetailController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, BusinessContactDetail $businessContactDetail)
    {

        $message = __('Unable to save additional business details');
        $alertClass = 'alert-error';

        $contactInfo = $request->only(
            [
                'division',
                'contact_person',
                'designation',
                'location',
                'locality',
                'postal_code',
                'address',
                'cell_no',
                'telephone_no',
                'email',
                'toll_free_no'
            ]
        );


            //need to update existing row
            if(!empty($request->input('contact_id'))) {
                $businessContactDetail = $businessContactDetail->find($request->input('contact_id'));
                $this->authorize('update', $businessContactDetail);
                if( $businessContactDetail->update($contactInfo)) {
                    $message = __('Contact Details have been save successfully');
                    $alertClass = 'alert-success';
                }
            } else { //insert new row
                $contactInfo['business_id'] = $request->input('business_id');
                $this->authorize('createBusinessContact', [$businessContactDetail,  $contactInfo['business_id']]);
                if( $businessContactDetail->create($contactInfo) ) {
                    $message = __('Contact Details have been save successfully');
                    $alertClass = 'alert-success';
                }


            }

            Session::flash('message', $message);
            Session::flash('alert-class', $alertClass);
            return redirect()->route('profile.business.additional-details');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BusinessContactDetail  $businessContactDetail
     * @return \Illuminate\Http\Response
     */
    public function edit(BusinessContactDetail $businessContactDetail, Request $request)
    {
        if($request->ajax()) {
            echo json_encode(array('data' => $businessContactDetail, 'status_code' => '200'));
        } else {
            return $businessContactDetail;
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BusinessContactDetail  $businessContactDetail
     * @return \Illuminate\Http\Response
     */
    public function destroy(BusinessContactDetail $businessContactDetail)
    {
        $message = __('Unable to delete contact');
        $alertClass = 'alert-error';

        $this->authorize('delete', $businessContactDetail);
        if($businessContactDetail->delete()) {
            $message = __('Contact deleted successfully');
            $alertClass = 'alert-success';
        }

        Session::flash('message', $message);
        Session::flash('alert-class', $alertClass);
        return redirect()->route('profile.business.additional-details');
    }
}
