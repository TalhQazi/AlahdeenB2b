<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\Helpers\FileUpload;

class ProfileController extends Controller
{
    use FileUpload;

    public function edit()
    {
        return view("common.profile.edit", get_defined_vars());
    }

    public function update(Request $req)
    {
        $user = auth()->user();
        
        if(isset($req->profile_photo_path))
        {
            $profile_pic = $this->uploadFile($req->profile_photo_path, 'uploads/user-profile-pics/'.auth()->user()->id, 'profile-picture');
        }
        $user->name = $req->name;
        $user->email = $req->email;
        $user->profile_photo_path = $profile_pic ?? $user->profile_photo_path ?? 'common/images/user.svg';
        $user->save();

        return back()->with("success", "Profile Updated Successfully");
    }
}
