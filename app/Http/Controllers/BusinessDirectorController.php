<?php

namespace App\Http\Controllers;

use App\Http\Resources\BusinessDirectorCollection;
use App\Models\BusinessDirector;
use App\Traits\ImageTrait;
use App\Traits\PackageUsageTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class BusinessDirectorController extends Controller
{
    use PackageUsageTrait, ImageTrait;

    public function __construct()
    {
        $this->authorizeResource(BusinessDirector::class);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if($this->canUseFeature('can_add_director_profile')) {
            $businessDirectors = BusinessDirector::where('user_id', Auth::user()->id)->get();
            if(!empty($businessDirectors)) {
                $businessDirectors = (new BusinessDirectorCollection($businessDirectors))->response()->getData();
            }

            return view('pages.profile.director-profile')->with(
                [
                    'directors_profiles' => $businessDirectors->data
                ]
            );
        } else {
            Session::flash('message', __('You need to upgrade your package inorder to add director profile'));
            Session::flash('alert-class', 'alert-error');
            return redirect()->route('dashboard');
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($this->canUseFeature('can_add_director_profile')) {
            Validator::make($request->input(), [
                'director_photo' => ['image', 'nullable', 'mimes:jpeg,jpg,png', 'max:500'],
                'name' => ['required', 'string'],
                'designation' => ['required', 'string'],
                'description' => ['nullable', 'string', 'max:500'],
            ],
            )->validate();

            $directorInfo = $request->only(['name', 'designation', 'description']);

            $directorPhoto = $request->file('director_photo');
            if (!empty($directorPhoto)) {
                $directorInfo['director_photo'] = $this->uploadImage($directorPhoto, 'public/director-profile-pictures');
            }

            $directorInfo['user_id'] = Auth::user()->id;
            $directorInfo['business_id'] = !empty(Auth::user()->business) ? Auth::user()->business->id : NULL;

            $businessDirector = BusinessDirector::where('user_id', Auth::user()->id)->get();

            $businessDirector = BusinessDirector::create($directorInfo);

            if($businessDirector) {
                Session::flash('message', __('Director information has been saved successfully'));
                Session::flash('alert-class', 'alert-success');
            } else {
                Session::flash('message', __('Director information could not be saved'));
                Session::flash('alert-class', 'alert-error');
            }

        } else {
            Session::flash('message', __('You need to upgrade your package inorder to add director profile'));
            Session::flash('alert-class', 'alert-error');
        }

        return redirect()->back();
    }

    public function update(BusinessDirector $businessDirector, Request $request)
    {
        Validator::make($request->all(), [
            'director_photo' => ['image', 'nullable', 'mimes:jpeg,jpg,png', 'max:500'],
            'name' => ['required', 'string'],
            'designation' => ['required', 'string'],
            'description' => ['nullable', 'string', 'max:500'],
        ],
        )->validate();

        $directorInfo = $request->only(['name', 'designation', 'description']);

        $directorPhoto = $request->file('director_photo');
        if (!empty($directorPhoto)) {
            $directorInfo['director_photo'] = $this->uploadImage($directorPhoto, 'public/director-profile-pictures');
        }

        $directorInfo['business_id'] = !empty(Auth::user()->business) ? Auth::user()->business->id : NULL;

        if(!empty($businessDirector->director_photo) && !empty($directorPhoto)) {
            //Checking if record already exists in db and new photo has been uploaded we need to delete old photo
            $this->deleteImage($businessDirector->director_photo);
        }

        $businessDirector = $businessDirector->update($directorInfo);

        if($businessDirector) {
            Session::flash('message', __('Director information has been updated successfully'));
            Session::flash('alert-class', 'alert-success');
        } else {
            Session::flash('message', __('Director information could not be updated'));
            Session::flash('alert-class', 'alert-error');
        }

        return redirect()->back();
    }

    public function destroy(BusinessDirector $businessDirector)
    {
        if($this->deleteImage($businessDirector->director_photo) && $businessDirector->delete()) {
            Session::flash('message', __('Director has been deleted successfully'));
            Session::flash('alert-class', 'alert-success');
        } else {
            Session::flash('message', __('Unable to delete director'));
            Session::flash('alert-class', 'alert-error');
        }

        return redirect()->back();
    }

}
