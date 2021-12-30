<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;


/*
|--------------------------------------------------------------------------
| Api Responser Trait
|--------------------------------------------------------------------------
|
| This trait will be used for any response we sent to clients.
|
*/

trait FileTrait
{
  /**
   * Return a success JSON response.
   *
   * @param  array|string  $data
   * @param  string  $message
   * @param  int|null  $code
   * @return \Illuminate\Http\JsonResponse
   */
  protected function uploadFile($file, $uploadPath)
  {
      return $file->store($uploadPath);
  }

  protected function moveFile($oldPath, $newPath)
  {
    return Storage::move($oldPath, $newPath);
  }

  protected function deleteFile($filePath)
  {
    return Storage::delete($filePath);
  }

  protected function putFile($path, $content, $fileName)
  {
    Storage::put($path.$fileName, $content);
  }

  protected function getFileUrl($path)
  {
    return Storage::url($path);
  }
}
