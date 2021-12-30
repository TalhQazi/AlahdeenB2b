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

trait ImageTrait
{
	public function uploadImage($file, $uploadPath)
	{
        return $file->store($uploadPath);
	}

    public function moveImage($oldPath, $newPath)
    {
        return Storage::move($oldPath, $newPath);
    }

    public function deleteImage($filePath)
    {
        return Storage::delete($filePath);
    }

}
