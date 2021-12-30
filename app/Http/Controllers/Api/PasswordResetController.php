<?php

namespace App\Http\Controllers\Api;

use App\Helpers\PasswordResetHelper;
use App\Http\Controllers\Controller;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class PasswordResetController extends Controller
{

    use ApiResponser;

    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function sendPasswordResetLink(Request $request, PasswordResetHelper $passwordResetHelper)
    {
        $response = $passwordResetHelper->sendPasswordResetLink($request);
        return $this->success([], __('We have forwarded your password reset link through Email/SMS!'));
    }

    /**
     * Reset the user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Support\Responsable
     */
    public function store(Request $request, PasswordResetHelper $passwordResetHelper)
    {


        $response = $passwordResetHelper->store($request);
        return $this->success([], __('Password has been sent successfully'));


    }
}
