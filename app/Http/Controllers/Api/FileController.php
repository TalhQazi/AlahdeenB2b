<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\FileRequest;
use App\Http\Requests\ImageRequest;
use App\Traits\ApiResponser;
use App\Traits\FileTrait;
use App\Traits\ImageTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    use FileTrait, ImageTrait, ApiResponser;
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeImage(ImageRequest $request)
    {
        $validatedData = $request->validated();
        $paths = [];
        if(!empty($validatedData['uploads'])) {
            foreach($validatedData['uploads'] as $key => $uploadedFile) {
                $paths[] = Storage::url($this->uploadImage($uploadedFile, 'public/tmp/image'));
            }
            return $this->success(
                [
                    'paths' => $paths
                ]
            );
        } else {
            return $this->error(
                __('Unable to upload files'),
                200
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeDocument(FileRequest $request)
    {
        $validatedData = $request->validated();
        $paths = [];
        if(!empty($validatedData['uploads'])) {
            foreach($validatedData['uploads'] as $key => $uploadedFile) {
                $paths[] = Storage::url($this->uploadFile($uploadedFile, 'public/tmp/document'));
            }
            return $this->success(
                [
                    'paths' => $paths
                ]
            );
        } else {
            return $this->error(
                __('Unable to upload files'),
                200
            );
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function uploadToTemp(Request $request) {

    }
}
