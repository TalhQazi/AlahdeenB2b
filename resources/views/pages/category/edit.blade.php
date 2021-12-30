@extends('layouts.master')

@push('styles')
    <link rel="stylesheet" href="{{ asset(('css/common/validation_error.css')) }}">
@endpush

@section('page')
    @parent
    <div class="pt-2 font-mono">
        <div class="container mx-auto">
            <div class="inputs w-full p-6 mx-auto">
                <h2 class="text-2xl text-gray-900">{{ __('Edit Category') }}</h2>
                <form id="category_create_form" class="mt-6 border-t border-gray-400 pt-4" enctype="multipart/form-data" method="post" action="{{route('admin.category.update',['category' => $category_details->id])}}">
                    @csrf
                    {{ method_field('PUT') }}
                    <div class='flex flex-wrap -mx-3 mb-6'>
                        <div class="bg-white px-4 py-5 rounded-lg shadow-lg text-center w-48 mb-6">
                            <div class="mb-4">
                              @if (!empty($category_details->image_path))
                              <img class="w-auto mx-auto object-cover object-center h-40" id="image_path_preview" src="{{asset(str_replace('/storage/','',$category_details->image_path))}}" alt="Category Image Upload" />
                              @else
                              <img class="w-auto mx-auto object-cover object-center" id="image_path_preview" src="{{asset('img/camera_icon.png')}}" alt="{{__('Category Image Upload')}}" />
                              @endif

                            </div>
                            <label class="cursor-pointer mt-6">
                              <span class="mt-2 leading-normal px-4 py-2 bg-blue-500 text-white text-sm rounded-full" >{{ __('Category Image') }}</span>
                              <input type='file' name="image_path" id="image_path" class="hidden" :accept="accept" />
                            </label>
                        </div>
                        <div class='w-full md:w-full px-3 mb-6'>
                            <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2' for='grid-text-1'>{{ __('Category Title') }}</label>
                        <input class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500' name="title" id='grid-text-1' type='text' placeholder='Enter Category Title' value="{{$category_details->title}}"  required>
                        </div>
                        <div class='w-full md:w-full px-3 mb-6'>
                            <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'>{{ __('Choose Parent Category') }}</label>
                            <div class="flex-shrink w-full inline-block relative">
                                <select class="block appearance-none text-gray-600 w-full bg-white border border-gray-400 shadow-inner px-4 py-2 pr-8 rounded" name="parent_cat_id">
                                    <option value="">{{ __('Choose Parent Category') }}</option>
                                    @foreach ($categories as $category)
                                        @if (!empty($category_details->parent_category) && $category->id == $category_details->parent_category->id)
                                        <option selected value="{{$category->id}}">{{$category->title}}</option>
                                        @else
                                            <option value="{{$category->id}}">{{$category->title}}</option>
                                        @endif


                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute top-0 mt-3  right-0 flex items-center px-2 text-gray-600">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                                </div>
                            </div>
                        </div>
                        <div class="personal w-full border-t border-gray-400 pt-4">
                            <div class="flex justify-end">
                                <button class="btn-bs-secondary" type="submit">Save Category</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('modals')
    {{-- @include('components.edit-category-image') --}}
@endsection

@push('scripts')
    <script type="text/javascript" src="{{ asset(('js/pages/create_category.js')) }}"></script>
@endpush
