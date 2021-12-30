<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserType
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
      if(!Auth::user()->hasRole(['admin', 'super-admin'])) {
        if(!$request->session()->has('user_type')) {
            $userType = 'buyer';
            if(Auth::user()->hasRole(['individual', 'business', 'corporate', 'warehouse-owner'])) {
                $userType = 'seller';
            } else if(Auth::user()->hasRole(['driver'])) {
              $userType = 'driver';
            }
            $request->session()->put('user_type', $userType);
        }

      }

      return $next($request);

    }
}
