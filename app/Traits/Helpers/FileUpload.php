<?php

namespace App\Traits\Helpers;

use Carbon\Carbon;
use Str;

/*
|--------------------------------------------------------------------------
| Api Responser Trait
|--------------------------------------------------------------------------
|
| This trait will be used for any response we sent to clients.
|
*/

trait FileUpload
{
	/**
     * Return a success JSON response.
     *
     * @param  array|string  $data
     * @param  string  $message
     * @param  int|null  $code
     * @return \Illuminate\Http\JsonResponse
     */
	public function uploadFile($file, $path, $name) 
	{
	    $filename = time().'-'.Str::random(4).'-'.$name.'.'.$file->getClientOriginalExtension();
	    $file->move($path, $filename);
	    return $path.'/'.$filename;
	}

	public function uploadMyFile($file, $path, $name) 
	{
	    $filename = time().'-'.Str::random(4).'-'.$name.'.'.$file->getClientOriginalExtension();
	    $file->move($path, $filename);
	    return $path.'/'.$filename;
	}

}
