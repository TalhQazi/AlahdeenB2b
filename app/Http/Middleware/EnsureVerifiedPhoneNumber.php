<?php

namespace App\Http\Middleware;

use App\Traits\ApiResponser;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureVerifiedPhoneNumber
{
    use ApiResponser;

    protected $exceptRoutes = [
        'update-phone-number',
        'verify-otp',
        'verify-phone',
        'resend-otp',
        'login',
        'logout'
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if($user && is_null($user->phone_verified_at)) {
            if(!in_array($request->path(), $this->exceptRoutes)) {
                if ($request->wantsJson()) {
                    // return JSON-formatted response
                    return $this->redirect(route('phone.verify.home'));

                } else {
                    return redirect()->route('phone.verify.home');
                }
            }
        }


        return $next($request);
    }
}
