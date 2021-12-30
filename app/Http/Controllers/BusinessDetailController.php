<?php

namespace App\Http\Controllers;

use App\Models\Badge;
use App\Models\BusinessDetail;
use App\Models\City;
use App\Models\User;
use App\Traits\PackageUsageTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;


class BusinessDetailController extends Controller
{
    use PackageUsageTrait;

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(BusinessDetail $businessDetail, City $city)
    {
        $this->authorize('create', BusinessDetail::class);
        $businessDetails = $businessDetail->where(['user_id' => Auth::user()->id])->first();

        return view('pages.profile.business-details')->with([
            'cities' => $city->all(),
            'ownership_types' => config('ownership_type'),
            'business_details' => $businessDetails,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, BusinessDetail $businessDetail, City $city)
    {
        Validator::make($request->input(), [
            'company_name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string'],
            'locality' => ['string', 'nullable'],
            'city_id' => ['numeric', 'nullable'],
            'zip_code' => ['string', 'max:5', 'nullable'],
            'phone_number' => ['required'],
            'alternate_website' => ['string', 'nullable'],
            'year_of_establishment' => ['required', 'digits:4', 'integer'],
            'no_of_employees' => ['required', 'digits_between:1,6', 'integer'],
            'annual_turnover' => [Rule::RequiredIf(Auth::user()->hasRole('corporate')) ,'integer'],
            'ownership_type' => ['string' ,'nullable', 'max:255'],
            'cnic' => ['string', 'nullable', 'size:13'],
            'ntn' => ['string', 'nullable'],
            'stn' => ['string', 'nullable'],

            'designation' => ['string', 'nullable'],
            'email' => ['string', 'nullable'],
            'phone' => ['string', 'nullable'],
            'name' => ['string', 'nullable'],
        ],
        )->validate();

        $businessInfo = $request->only(
            [
                'company_name',
                'address',
                'locality',
                'city_id',
                'zip_code',
                'phone_number',
                'alternate_website',
                'year_of_establishment',
                'no_of_employees',
                'annual_turnover',
                'ownership_type',
            ]
        );

        $user = Auth::user();
        // $user->designation = $request->designation;
        // $user->email = $request->email;
        // $user->phone = $request->phone;
        // $user->name = $request->name;

        $user->cnic = $request->cnic;
        $user->ntn = $request->ntn;
        $user->stn = $request->stn;

        if($businessDetail->updateOrCreate(['user_id' => Auth::user()->id], $businessInfo) && $user->save() && $this->addVerifiedSellerBadge($user)) {

            Session::flash('message', 'Business details have been saved successfully');
            Session::flash('alert-class', 'alert-success');
        } else {
            Session::flash('message', 'Unable to save business details');
            Session::flash('alert-class', 'alert-error');
        }

        return redirect()->route('profile.business.home');

    }

    public function getCompany(Request $request, BusinessDetail $businessDetail)
    {
        $searchParam = $request->query('query');
        $businessDetails = $businessDetail::select('company_name')->where('company_name', 'like', '%'.$searchParam.'%')->get();

        if($request->ajax()) {
            $data = [];
            if(!empty($businessDetails)) {
                foreach ($businessDetails as $key => $businessDetail) {
                    $data[] = ["value" => $businessDetail->company_name, "data" => $businessDetail->company_name];
                }
            }
            return response()->json(['suggestions' => $data]);
        } else {
            return $businessDetails;
        }
    }

    public function addVerifiedSellerBadge(User $user)
    {
        if(!empty($user->cnic) || !empty($user->ntn) || !empty($user->stn)) {

            $verifiedSeller = config('badges.verified_seller');
            $verifiedSellerBadge = Badge::find($verifiedSeller['id']);

            if(!in_array($verifiedSellerBadge->id, $user->badges()->pluck('badge_id')->toArray())) {
                $user->badges()->attach($verifiedSellerBadge);
                return true;
            }
        } else {
            $verifiedSeller = config('badges.verified_seller');
            $verifiedSellerBadge = Badge::find($verifiedSeller['id']);
            if(!empty($verifiedSellerBadge) && in_array($verifiedSellerBadge->id, $user->badges()->pluck('badge_id')->toArray())) {

                if($user->badges()->detach($verifiedSellerBadge)) {
                    return true;
                } else {
                    return false;
                }
            }
        }

        return true;
    }

}
