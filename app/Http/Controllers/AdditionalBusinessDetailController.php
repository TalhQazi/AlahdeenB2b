<?php

namespace App\Http\Controllers;

use App\Models\AdditionalBusinessDetail;
use App\Models\BusinessDetail;
use App\Models\City;
use App\Models\ModeOfPayment;
use App\Traits\PackageUsageTrait;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreAdditionalBusinessDetail;
use Illuminate\Validation\ValidationException;
use App\Traits\Helpers\FileUpload;

class AdditionalBusinessDetailController extends Controller
{

    use PackageUsageTrait;
    use FileUpload;

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View|RedirectResponse
     * @throws AuthorizationException
     */
    public function create(BusinessDetail $businessDetail, ModeOfPayment $modeOfPayment, City $city)
    {
        $this->authorize('create', AdditionalBusinessDetail::class);
        $businessDetail = $businessDetail::where(['user_id' => Auth::user()->id])->get();

        $additionalBusinessDetails = [];
        if (!empty($businessDetail[0])) {

            $businessDetail = $businessDetail[0];
            $additionalBusinessDetails = $businessDetail->additionalDetails()->first();
            $businessModePayments = $businessDetail->businessModeOfPayments()->select('mode_of_payment_id')->get();
            $businessModePayments = !empty($businessModePayments) ? $businessModePayments->pluck('mode_of_payment_id')->all() : [];
            $businessPhotos = $businessDetail->businessPhotos()->select(['id', 'photo_path'])->get();
            $businessContacts = $businessDetail->businessContacts()->get()->toArray();
            $additionalPhotosIsAvailable = $this->getFeatureLimit(Auth::user()->activeSubscriptions(), 'can_add_additional_detail_photos') == 1;

            return view('pages.profile.additional-business-details')->with(
                [
                    'additional_business_details' => $additionalBusinessDetails,
                    'mode_of_payments' => $modeOfPayment::getModeOfPayments(),
                    'business_mode_of_payments' => $businessModePayments,
                    'business_photos' => $businessPhotos,
                    'no_of_photos_allowed' => config('images_limit.no_of_company_photos'),
                    'business_contacts' => $businessContacts,
                    'division_types' => config('business_contacts.division_types'),
                    'business_id' => $businessDetail->id,
                    'business_days' => config('business_days_hours.days'),
                    'business_hours' => config('business_days_hours.hours'),
                    'additionalPhotosIsAvailable' => $additionalPhotosIsAvailable,
                    'cities' => $city->all(),
                    'ship_modes' => config('shipment_modes')
                ]
            );
        } else {
            Session::flash('message', __('Business details needs to be added before adding additional details'));
            Session::flash('alert-class', 'alert-error');
            return redirect()->route('profile.business.home');
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreAdditionalBusinessDetail $request
     * @param BusinessDetail $businessDetail
     * @param AdditionalBusinessDetail $additionalBusinessDetail
     * @return RedirectResponse
     *
     * @throws ValidationException
     */

    public function store(StoreAdditionalBusinessDetail $request, BusinessDetail $businessDetail, AdditionalBusinessDetail $additionalBusinessDetail)
    {

        $additionalPhotosIsAvailable = ($this->getFeatureLimit(Auth::user()->activeSubscriptions(), 'can_add_additional_detail_photos') == 1);
        $message = __('Unable to save additional business details');
        $alertClass = 'alert-error';

        Validator::make($request->all(), [])->validate();


        $businessDetails = $businessDetail::select('id')->where(['user_id' => Auth::user()->id])->get();

        if (!empty($businessDetails)) {
            $businessDetails = $businessDetails[0];
            $businessId = $businessDetails->id;
            $businessInfo = $request->only(
                [
                    'description',
                    'start_day',
                    'end_day',
                    'start_time',
                    'end_time',
                    'company_id',
                    'import_export_no',
                    'bank_name',
                    'income_tax_number',
                    'ntn',
                    'no_of_production_units',
                    'affiliation_memberships',
                    'company_branches',
                    'owner_cnic',
                    'infrastructure_size',
                    'cities_to_trade_with',
                    'cities_to_trade_from',
                    'shipment_modes',
                    'payment_modes',
                    'arn_no'
                ]
            );
            $businessInfo['states'] = !empty($request->input('business_states')) ? json_encode($request->input('business_states')) : null;
            $businessInfo['included_cities'] = !empty($request->input('included_cities')) ? json_encode($request->input('included_cities')) : null;
            $businessInfo['excluded_cities'] = !empty($request->input('excluded_cities')) ? json_encode($request->input('excluded_cities')) : null;

            $businessInfo['cities_to_trade_with'] = !empty($request->input('cities_to_trade_with')) ? json_encode($request->input('cities_to_trade_with')) : null;
            $businessInfo['cities_to_trade_from'] = !empty($request->input('cities_to_trade_from')) ? json_encode($request->input('cities_to_trade_from')) : null;
            $businessInfo['shipment_modes'] = !empty($request->input('shipment_modes')) ? json_encode($request->input('shipment_modes')) : null;

            $productImage = $request->file('logo');
            if(isset($productImage))
            {
                $businessInfo['logo'] = $this->uploadFile($productImage, 'additional-business/images', 'additional-business-image');
            }

            $savedDetails = $additionalBusinessDetail->updateOrCreate(['business_id' => $businessId], $businessInfo);

            $modeOfPayments = !empty($request->input('mode_of_payments')) ? $request->input('mode_of_payments') : [];
            $savedPayments = $this->saveModeOfPayments($businessId, $modeOfPayments);

            $businessPhotos = ($additionalPhotosIsAvailable && !empty($request->file('company_photo'))) ? $request->file('company_photo') : [];
            $businessPhotoIds = ($additionalPhotosIsAvailable && !empty($request->input('company_photo_id'))) ? $request->input('company_photo_id') : [];


            if (count($businessPhotos) == 0 && count($businessPhotoIds) == 0 && $additionalPhotosIsAvailable) {
                Session::flash('message', 'Additional photos is not included in package or package is expired');
                Session::flash('alert-class', 'alert-error');
                if ($request->input('matter_sheet_return')) {
                    return redirect()->route('matter-sheet.home');
                } else {
                    return redirect()->route('profile.business.home');
                }
            }

            $savedPhotos = $this->savePhotos($businessId, $businessPhotoIds, $businessPhotos);

            if ($savedDetails || $savedPayments || $savedPhotos) {
                $message = __('Additional business details have been saved successfully');
                $alertClass = 'alert-success';
            }

            Session::flash('message', $message);
            Session::flash('alert-class', $alertClass);

            if ($request->input('matter_sheet_return')) {
                return redirect()->route('matter-sheet.additionalDetails');
            } else {
                return redirect()->route('profile.business.additional-details');
            }

        } else {
            Session::flash('message', __('Business details needs to be added before adding additional details'));
            Session::flash('alert-class', 'alert-error');
            if ($request->input('matter_sheet_return')) {
                return redirect()->route('matter-sheet.home');
            } else {
                return redirect()->route('profile.business.home');
            }
        }

    }

    public function saveModeOfPayments($businessId, $modeOfPayments): bool
    {
        if (empty($modeOfPayments)) {
            return false;
        } else {

            $businessModelObj = new BusinessDetail();
            $businessDetail = $businessModelObj->find($businessId);

            if ($businessDetail->businessModeOfPayments()->exists()) {
                if (!$businessDetail->businessModeOfPayments()->delete()) {
                    return false;
                }
            }

            foreach ($modeOfPayments as $key => $mode_of_payment) {
                $businessPayment[$key]['business_id'] = $businessId;
                $businessPayment[$key]['mode_of_payment_id'] = $mode_of_payment;
            }

            if ($businessDetail->businessModeOfPayments()->createMany($businessPayment)) {
                return true;
            } else {
                return false;
            }

        }
    }

    public function savePhotos($businessId, $businessPhotoIds, $businessPhotos): bool
    {
        if (empty($businessPhotos)) {
            return false;
        } else {

            $businessModelObj = new BusinessDetail();
            $businessDetail = $businessModelObj->find($businessId);

            if ($businessDetail->businessPhotos()->whereNotIn('id', $businessPhotoIds)->exists()) {
                if (!$businessDetail->businessPhotos()->whereNotIn('id', $businessPhotoIds)->delete()) {
                    return false;
                }
            }


            foreach ($businessPhotos as $key => $photo) {
                $businessPhotoInfo[$key]['business_id'] = $businessId;
                $productImage = $photo;
                if(isset($productImage))
                {
                    $businessPhotoInfo[$key]['photo_path'] = $this->uploadFile($productImage, 'additional-business/images', 'additional-business-image');
                }
            }

            if ($businessDetail->businessPhotos()->createMany($businessPhotoInfo)) {
                return true;
            } else {
                return false;
            }
        }
    }
}
