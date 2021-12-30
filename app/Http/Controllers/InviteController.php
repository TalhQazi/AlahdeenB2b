<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invitation;
use App\Actions\Fortify\PasswordValidationRules;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Hash;

class InviteController extends Controller
{
    use PasswordValidationRules;

    public function accept($token)
    {
        if (!$invite = Invitation::with('user')->where('token', $token)->first()) {
            abort(404);
        }

        $user = $invite->user;

        return view('auth.accept-invite', compact('user'));
    }

    public function approve(Request $request, MessageBag $messageBag)
    {
        $request->validate([
            'invite_token' => 'required',
            'password' => $this->passwordRules(),
        ]);

        if (!$invite = Invitation::with('user')->where('token', $request->invite_token)->first()) {
            $messageBag->add('error', __('Invalid or expired token, Please contact support team for further assistance.'));

            return redirect()->back()->withErrors($messageBag);
        }

        if ($invite->user->forceFill(['password' => Hash::make($request->password)])->save()) {
            $invite->delete();

            return redirect()->route('login')->with('status', __('You have successfully created password, Please login to proceed further.'));
        }

        $messageBag->add('error', __('unable to set password, please try again.'));

        return redirect()->back()->withErrors($messageBag);
    }
}
