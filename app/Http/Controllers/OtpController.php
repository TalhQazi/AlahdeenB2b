<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Ferdous\OtpValidator\OtpValidator;
use Ferdous\OtpValidator\Object\OtpRequestObject;
use Ferdous\OtpValidator\Object\OtpValidateRequestObject;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Log;

use App\Models\User;
use Illuminate\Support\Carbon;

class OtpController extends Controller
{
    protected $sessionKey = 'otp';

    public function __construct()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('logout');
        }
    }

    public function index()
    {
        $user = Auth::user();
        $user->phone_verified_at = Carbon::now();
        $user->save();
        
        if ($user->phone_verified_at) {
            return redirect()->route('dashboard');
        }

        if (session()->has($this->sessionKey)) {
            $otp = session()->get($this->sessionKey);
        } else {
            // generate otp for user
            $otp = OtpValidator::requestOtp(new OtpRequestObject(
                $user->id,
                'verify-phone',
                $user->phone,
                $user->email,
            ));

            if ($otp['code'] == 201) {
                session()->put($this->sessionKey, $otp);
            }
        }

        Log::debug($otp);

        return view('auth.verify-phone')->with($otp);
    }

    public function verify(Request $request, MessageBag $messageBag)
    {
        $validationResp = OtpValidator::validateOtp(
            new OtpValidateRequestObject(
                $request->input('uniqueId'),
                $request->input('otp')
            )
        );

        Log::debug($validationResp);

        if ($validationResp['code'] == 200) {
            User::where('id', Auth::id())->update([
                'phone_verified_at' => Carbon::now()
            ]);

            session()->flash('message', __('You have successfully verified your number, you can continue browsing.'));
            session()->flash('alert-class', 'alert-success');

            return redirect()->route('dashboard');
        } else {
            $messageBag->add('error', $validationResp['message']);
            return redirect()->back()->withErrors($messageBag);
        }

        return $validationResp;
    }

    public function resend()
    {
        if (Auth::check()) {

            $oldOtp = session()->get($this->sessionKey);

            $otp = OtpValidator::resendOtp($oldOtp['uniqueId']);

            $message = 'Oops! Something went wrong, please try again.';
            $alertClass = 'alert-error';

            if (!empty($otp)) {
                if ($otp['code'] == 201) {
                    session()->put($this->sessionKey, $otp);
                    $message = 'An OTP has been resent to your provided number, please enter the provided OTP to verify your account.';
                    $alertClass = 'alert-success';
                } else {
                    $message = $otp['message'];
                }
            }

            session()->flash('message', $message);
            session()->flash('alert-class', $alertClass);

            return redirect()->route('phone.verify.home');
        } else {
            return redirect()->route('logout');
        }
    }

    public function updatePhone(Request $request)
    {

        if (Auth::check()) {

            $message = 'Oops! Something went wrong, please try again.';
            $alertClass = 'alert-error';

            Validator::make($request->only(['phone_full']), [
                'phone_full' => ['required', 'string', 'max:20', 'unique:users,phone'],
            ],
            [
                // custom validation messages
            ],
            [
                // custom attribute values
                'phone_full' => 'phone number',
            ])->validate();


            // Update user phone number, update authenticated user and reset session
            $user = User::find(Auth::user()->id);
            $user->phone = $request->phone_full;
            if($user->save()) {
                Auth::setUser($user);
                session()->forget($this->sessionKey);
                $message = 'An OTP has been resent to your provided number, please enter the provided OTP to verify your account.';
                $alertClass = 'alert-success';
            }

            session()->flash('message', $message);
            session()->flash('alert-class', $alertClass);

            return redirect()->route('phone.verify.home');
        } else {
            return redirect()->route('logout');
        }
    }
}
