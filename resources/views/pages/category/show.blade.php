@extends('layouts.master')

@section('page')
@parent
<div class="pt-2 font-mono">
    <div class="container mx-auto">
        <div class="inputs w-full p-6 mx-auto">
            <h2 class="text-2xl text-gray-900">{{ __('View Category') }}</h2>
<div class="bg-white px-4 py-5 rounded-lg shadow-lg text-center w-48 mb-6">
    <div class="mb-4">
      @if (!empty($category->image_path))
      <img class="w-auto mx-auto object-cover object-center h-40" id="image_path_preview" src="{{asset(str_replace('/storage/','',$category->image_path))}}" alt="Category Image Upload" />
      @else
      <img class="w-auto mx-auto object-cover object-center" id="image_path_preview" src="{{asset('img/camera_icon.png')}}" alt="Category Image Upload" />
      @endif

    </div>
</div>
<div class="mt-6">
    <div class='w-full md:w-full px-3 mb-6'>
        <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2' for='grid-text-1'>{{ __('Title') }}</label>
        <input class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500' name="title" id='grid-text-1' type='text' value="{{$category->title}}" disabled>
    </div>

    <div class='w-full md:w-full px-3 mb-6'>
        <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2' for='grid-text-1'>{{ __('Parent Category') }}</label>
        <input class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500' name="title" id='grid-text-1' type='text' value="{{!empty($category->parent_category) ? $category->parent_category->title : 'NA'}}" disabled>
    </div>
</div>

@endsection



