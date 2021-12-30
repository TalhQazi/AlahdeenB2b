<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Support\Facades\Session;

class Handler extends ExceptionHandler
{
  /**
   * A list of the exception types that are not reported.
   *
   * @var array
   */
  protected $dontReport = [
    //
  ];

  /**
   * A list of the inputs that are never flashed for validation exceptions.
   *
   * @var array
   */
  protected $dontFlash = [
    'password',
    'password_confirmation',
  ];

  /**
   * Register the exception handling callbacks for the application.
   *
   * @return void
   */
  public function register()
  {
    //
  }

  public function render($request, Throwable $exception)
  {
    if ($request->wantsJson()) {
      if ($exception instanceof \Illuminate\Validation\ValidationException) {

        return response()->json([
          'status' => 'Error',
          'message' => 'Invalid Data',
          'data' => $exception->errors()
        ], 422);
      }
    } else {

      if ($exception instanceof \Illuminate\Http\Exceptions\PostTooLargeException) {
        return \Illuminate\Support\Facades\Redirect::back()->withErrors(['message' => 'Max file size exceeding']);
      }
    }

    if (app()->bound('sentry') && $this->shouldReport($exception)) {
      app('sentry')->captureException($exception);
    }

    return parent::render($request, $exception);
  }
}
