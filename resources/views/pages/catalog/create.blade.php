@extends('layouts.master')

@section('page')
    @parent
    <div class="min-h-screen pt-2 font-mono">
        <div class="container mx-auto">
            <div class="inputs w-full p-6">
                <h2 class="text-2xl text-gray-900">{{ __('Add Catalog') }}</h2>
                <form id="add_catalog_form" class="mt-6 border-t border-gray-400 pt-4" enctype="multipart/form-data" method="POST" action="{{route('catalog.store')}}">
                    @csrf
                    <div class='flex flex-wrap -mx-3 mb-6'>

                        <div class='w-full md:w-full px-3 mb-6'>
                            <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2' for='title'>{{ __('Title') }}</label>
                            <input class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500' name="title" id='title' type='text'>
                        </div>
                        <div class='w-full md:w-full px-3 mb-6'>
                            <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2' for='catDocument'>{{ __('File') }}</label>
                            <input class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500' name="catDocument" id='catDocument' type='file' accept="application/pdf">
                        </div>
                        <div class="personal w-full border-t border-gray-400 pt-4">
                            <div class="flex justify-end">
                                <button class="border border-gray-700 bg-gray-700 text-white rounded-md px-4 py-2 m-2 transition duration-500 ease select-none hover:bg-gray-800 focus:outline-none focus:shadow-outline" type="submit">{{ __('Save Catalog') }}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

