<?php

namespace App\Http\Controllers;

use App\Helpers\PasswordResetHelper;
use Illuminate\Http\Request;

class PasswordResetController extends Controller
{

    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function sendPasswordResetLink(Request $request, PasswordResetHelper $passwordResetHelper)
    {
        return $passwordResetHelper->sendPasswordResetLink($request);
    }

    /**
     * Reset the user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Support\Responsable
     */
    public function store(Request $request,  PasswordResetHelper $passwordResetHelper)
    {
        return $passwordResetHelper->store($request);
    }
}
