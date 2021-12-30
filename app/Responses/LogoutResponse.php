<?php

namespace App\Responses;

use Laravel\Fortify\Contracts\LogoutResponse as LogoutResponseContract;
use Illuminate\Http\JsonResponse;

class LogoutResponse implements LogoutResponseContract
{
  public function toResponse($request)
  {
    setcookie('isAuthenticated', false, ['domain' => config('session.domain'), 'path' => '/']);
    return $request->wantsJson()
              ? new JsonResponse('', 204)
              : redirect(config('fortify.home'));
  }
}
