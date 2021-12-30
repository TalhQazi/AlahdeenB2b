<?php
	
use App\Models\Category;


function categoryDropdown()
{
	return Category::select('id', 'title')->get();
}


function showPhoto($photo)
{
	if(file_exists($photo))
	{
		return $photo;
	}else
	{
		return asset("common/images/default.png");
	}
}


function uploadFileHelper($file, $path, $name) {
    $filename = time().'-'.Str::random(4).'-'.$name.'.'.$file->getClientOriginalExtension();
    $file->move($path, $filename);
    return $path.'/'.$filename;
}