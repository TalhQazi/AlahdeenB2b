@extends('layouts.master')

@push('styles')
    <link rel="stylesheet" href="{{ asset(('css/common/validation_error.css')) }}">
@endpush

@section('page')
    @parent
    <div class="pt-2 font-mono">
        <div class="container mx-auto">
            <div class="inputs w-full p-6 mx-auto">
                <h2 class="text-2xl text-gray-900">{{__('Add Advertisment')}}</h2>
                <form id="ad_create_form" class="mt-6 border-t border-gray-400 pt-4" method="POST" enctype="multipart/form-data" action="{{route('admin.advertisments.store')}}">
                    @csrf
                    <div class='flex flex-wrap -mx-3 mb-6'>
                        <div class="bg-white px-4 py-5 rounded-lg shadow-lg text-center w-48 mb-6" id="image_div">
                            <div class="mb-4">
                                <img class="w-auto mx-auto object-cover object-center" id="image_path_preview" src="{{asset('img/camera_icon.png')}}" alt="Advertisment Image Upload" />
                            </div>
                            <label class="cursor-pointer mt-6">
                              <span class="mt-2 leading-normal px-4 py-2 bg-blue-500 text-white text-sm rounded-full" >{{ __('Upload Image') }}</span>
                              <input type='file' name="image_path" id="image_path" class="hidden" :accept="accept" />
                            </label>
                        </div>
                        <div class='w-full md:w-full mb-6'>
                            <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2' for='grid-text-1'>{{ __('Url Link') }}</label>
                            <input class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500' name="url_link" id='url_link' type="text" value="{{old('url_link')}}">
                        </div>
                        <div class='w-full md:w-full mb-6'>
                            <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2' for='grid-text-1'>{{ __('Display Order') }}</label>
                            <input class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500' name="display_order" id='display_order' type='text' value="{{old('display_order')}}">
                        </div>
                        <div class='w-full md:w-full mb-6'>
                            <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'>{{ __('Display Section') }}</label>
                            <div class="flex-shrink w-full inline-block relative">
                                <select class="block appearance-none text-gray-600 w-full bg-white border border-gray-400 shadow-inner px-4 py-2 pr-8 rounded" name="display_section">
                                    <option value="">{{ __('Choose Display Section') }}</option>
                                    @foreach ($display_section as $display_section)
                                        <option value="{{$display_section}}">{{$display_section}}</option>

                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute top-0 mt-3  right-0 flex items-center px-2 text-gray-600">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                                </div>
                            </div>
                        </div>
                        <div class='w-full md:w-full mb-6'>
                            <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2' for='grid-text-1'>{{__('Is Active')}}</label>
                            <input class='block bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500' name='is_active' id='is_active' type='checkbox'>
                        </div>
                        <div class="personal w-full border-t border-gray-400 pt-4">
                            <div class="flex justify-end">
                                <button class="border border-gray-700 bg-gray-700 text-white rounded-md px-4 py-2 m-2 transition duration-500 ease select-none hover:bg-gray-800 focus:outline-none focus:shadow-outline" type="submit">{{__('Save Advertisment')}}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        var base_url = '{{ config('app.url') }}';
    </script>
    <script type="text/javascript" src="{{ asset(('js/pages/ads_add_edit.js')) }}"></script>
    <script type="text/javascript" src="{{ asset(('js/components/image_reader.js')) }}"></script>
@endpush
