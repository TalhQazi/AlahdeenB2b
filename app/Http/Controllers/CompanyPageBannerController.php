<?php

namespace App\Http\Controllers;

use App\Models\CompanyPageBanner;
use App\Traits\ImageTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Traits\Helpers\FileUpload;

class CompanyPageBannerController extends Controller
{

    use ImageTrait;
    use FileUpload;
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, CompanyPageBanner $companyPageBanner)
    {
        // dd($request->all());
        Validator::make($request->all(), [
            'company_banner' => ['required', 'file', 'max:2000']
        ])->validate();
        dd("Ok");
        $existingBanner = Auth::user()->companyBanner;
        if(!empty($existingBanner)) {
            $companyPageBanner = $existingBanner;
            if(!$this->deleteImage($existingBanner->banner_image_path)) {
                Session::flash('message', __('Unable to upload company banner'));
                Session::flash('alert-class', 'alert-error');
                return redirect()->back();
            }

            $companyPageBanner->banner_image_path = $this->uploadFile($request->company_banner, 'company-page-banner/'.uniqid(), 'company-page-banner');
        } else {
            $companyPageBanner->banner_image_path = $this->uploadFile($request->company_banner, 'company-page-banner/'.uniqid(), 'company-page-banner');
            $companyPageBanner->user_id = Auth::user()->id;
        }

        if($companyPageBanner->save()) {
            Session::flash('message', __('Company Banner has been uploaded successfully'));
            Session::flash('alert-class', 'alert-success');
        } else {
            Session::flash('message', __('Unable to upload company banner'));
            Session::flash('alert-class', 'alert-error');
        }


        return redirect()->back();
    }

}
