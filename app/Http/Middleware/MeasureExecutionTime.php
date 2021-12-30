<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MeasureExecutionTime
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

        $startTime = LARAVEL_START;
        $response = $next($request);

        $executionTime = (microtime(true) - $startTime);

        Log::channel('api_log')->info(
            'Start Time: '.$startTime
        );

        Log::channel('api_log')->info(
            'Api Request: '.$request->fullUrl()
        );

        Log::channel('api_log')->info(
            'Execution Time: '.$executionTime
        );

        Log::channel('api_log')->info(
            PHP_EOL
        );

        return $response;

    }
}
