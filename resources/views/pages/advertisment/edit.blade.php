@extends('layouts.master')

@push('styles')
    <link rel="stylesheet" href="{{ asset(('css/common/validation_error.css')) }}">
@endpush

@section('page')
    @parent
    <div class="pt-2 font-mono">
        <div class="container mx-auto">
            <div class="inputs w-full p-6 mx-auto">
                <h2 class="text-2xl text-gray-900">{{__('Edit Advertisment')}}</h2>
                <form id="ad_create_form" class="mt-6 border-t border-gray-400 pt-4" method="POST" enctype="multipart/form-data" action="{{route('admin.advertisments.update', ['advertisment' => $advertisment->id])}}">
                    @csrf
                    @method('PUT')
                    <div class='flex flex-wrap -mx-3 mb-6'>
                        <div class="bg-white px-4 py-5 rounded-lg shadow-lg text-center w-48 mb-6" id="image_div">
                            <div class="mb-4">
                                <img class="w-auto mx-auto object-cover object-center h-40" id="image_path_preview" src="{{url($advertisment->image_path)}}"ialt="Advertisment Image Upload" />
                            </div>
                            <label class="cursor-pointer mt-6">
                              <span class="mt-2 leading-normal px-4 py-2 bg-blue-500 text-white text-sm rounded-full" >{{ __('Upload Image') }}</span>
                              <input type='file' name="image_path" id="image_path" class="hidden" :accept="accept" />
                            </label>
                        </div>
                        <div class='w-full md:w-full mb-6'>
                            <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2' for='grid-text-1'>{{ __('Url Link') }}</label>
                            <input class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500' name="url_link" id='url_link' type="text" value="{{$advertisment->url_link}}">
                        </div>
                        <div class='w-full md:w-full mb-6'>
                            <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2' for='grid-text-1'>{{ __('Display Order') }}</label>
                            <input class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500' name="display_order" id='display_order' type='text' value="{{$advertisment->display_order}}">
                        </div>
                        <div class='w-full md:w-full px-3 mb-6'>
                            <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2' for='grid-text-1'>{{__('Is Active')}}</label>
                            <input class='block bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500' name="is_active" id="is_active" type="checkbox" @if ($advertisment->is_active) checked @endif>
                        </div>

                        <div class="personal w-full border-t border-gray-400 pt-4">
                            <div class="flex justify-end">
                                <button class="border border-gray-700 bg-gray-700 text-white rounded-md px-4 py-2 m-2 transition duration-500 ease select-none hover:bg-gray-800 focus:outline-none focus:shadow-outline" type="submit">{{__('Update Advertisment')}}</button>
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
