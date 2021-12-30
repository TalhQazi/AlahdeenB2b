<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Warehouse
{
    protected $acceptedRoles = [
        'individual',
        'business',
        'corporate',
        'warehouse-owner',
        'admin',
        'super-admin'
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
        if(Auth::user()->hasAnyRole($this->acceptedRoles)) {
            return $next($request);
        } else {
            abort(403, __('Access denied'));
        }
    }
}
