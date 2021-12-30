@extends('layouts.master')

@push('styles')
    <link rel="stylesheet" href="{{ asset(('css/common/validation_error.css')) }}">
    <link rel="stylesheet" href="{{ asset(('css/auto_complete.css')) }}">

    <style>
        .separator {
            display: flex;
            align-items: center;
            text-align: center;
        }

        .separator::before,
        .separator::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #000;
        }

        .separator:not(:empty)::before {
            margin-right: .25em;
        }

        .separator:not(:empty)::after {
            margin-left: .25em;
        }

    </style>
@endpush

@section('page')
    <div class="p-5">
        {{-- @include('components.matter_sheet.matter-sheet-steps') --}}
        <div class="mt-8 p-4">
            <form method="POST" id="matter_sheet_product_form" enctype="multipart/form-data"
                action="{{ route('matter-sheet.matter_sheet_product.update', ['matter_sheet_product' => $product->id]) }}">
                @csrf
                @method('PUT')
                <div>
                    <div class='mb-5 px-3 text-xl'>
                        <h1 class='border-b-2 border-gray-600'>{{ __('Upload Product') }}</h1>
                    </div>

                    <div class="grid grid-cols-3">
                        <div>
                            <div class="bg-white px-4 py-5 rounded-lg shadow-lg text-center w-48">
                                <div class="mb-4">
                                    @if (!empty($product->image_path))
                                        <img class="w-auto mx-auto object-cover object-center h-40" id="logo_preview"
                                            src="{{ url(Storage::url($product->image_path)) }}"
                                            alt="Product Image Upload" />
                                    @else
                                        <img class="w-auto mx-auto object-cover object-center" id="logo_preview"
                                            src="{{ asset('img/camera_icon.png') }}" alt="Product Image Upload" />
                                    @endif

                                </div>
                                <label class="cursor-pointer mt-6">
                                    <span
                                        class="mt-2 text-base leading-normal px-4 py-2 bg-blue-500 text-white text-sm rounded-full">{{ __('Product Logo') }}</span>
                                    <input type="file" name="logo" id="logo" class="hidden" :accept="accept" />
                                </label>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 col-span-2">
                            <div>
                                <div class="w-full md:w-full px-3 mb-6">
                                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                                        for="product-title">{{ __('Product Title') }}</label>
                                    <input
                                        class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500"
                                        name="title" id="title" type="text" value="{{ $product->title ?? old('title') }}"
                                        required>
                                </div>
                            </div>

                            <div>
                                <div class="w-full md:w-full px-3 mb-6">
                                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                                        for="product-code">{{ __('Product Code') }}</label>
                                    <input
                                        class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500"
                                        name="product_code" id="product_code" type="text"
                                        value="{{ $product->product_code ?? old('product_code') }}">
                                </div>
                            </div>

                            <div>
                                <div class="w-full md:w-full px-3 mb-6">
                                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                                        for="web-category">{{ __('Web Category') }}</label>
                                    <input
                                        class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500"
                                        name="web_category" id="web_category" type="text"
                                        value="{{ $product->web_category ?? old('web_category') }}">
                                </div>
                            </div>

                            <div>
                                <div class="w-full md:w-full px-3 mb-6">
                                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                                        for="brand-name">{{ __('Brand Name') }}</label>
                                    <input
                                        class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500"
                                        name="brand_name" id="brand_name" type="text"
                                        value="{{ $product->brand_name ?? old('brand_name') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="w-full text-xl my-4">
                    {{ __('Category') }}
                    <span class="text-sm">
                        {{ '(' . __('Category and atleast one sub category needs to be added') . ')' }}</span>
                </div>

                <div class="grid grid-row">
                    <div class="w-full md:w-full px-3 mb-6">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                            for="category">{{ __('Category') }}</label>
                        <input
                            class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500"
                            name="category" id="category" type="text"
                            data-level={{ !empty($parentCats) ? count($parentCats) : 1 }}
                            data-target-div="#categories_div">
                    </div>
                    <div id="categories_div" class="w-full md:w-full px-3 mb-6">
                        @if (!empty($parentCats))
                            @foreach ($parentCats as $key => $value)
                                <span id="${suggestionSpanId}"
                                    class="inline-block rounded-full text-gray-600 bg-green-200 px-4 py-2 text-xs font-bold"
                                    data-level="{{ $loop->iteration }}" data-cat-id="{{ $key }}}">
                                    {{ $value }}
                                    <i class="fa fa-times ml-2 delete-category"></i>
                                    <input type="hidden" name="categories[{{ $loop->iteration }}]"
                                        value="{{ $key }}">
                                    <input type="hidden" name="categories_name[{{ $loop->iteration }}]"
                                        value="{{ $value }}}">
                                </span>

                                @if (count($parentCats) != $loop->iteration)
                                    <span class="mx-1" data-level="{{ $loop->iteration }}">></span>
                                @endif
                            @endforeach
                        @endif
                    </div>
                </div>

                <div class="w-full text-xl my-4">
                    {{ __('Price and Quantity') }}
                </div>

                <div class="grid grid-row">
                    <div>
                        <div class='w-full md:w-full px-3 mb-6'>
                            <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'
                                for='grid-text-1'>{{ __('Price (PKR)') }}</label>
                            <input type="number"
                                class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500'
                                name="price" id='grid-text-1' value="{{ $product->price ?? old('price') }}">
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 col-span-2">
                    <div>
                        <div class='w-full md:w-full px-3 mb-6'>
                            <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'
                                for='grid-text-1'>{{ __('Approx. Price (PKR)') }}</label>
                            <input type="number"
                                class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500'
                                name="approx_price" id='grid-text-1'
                                value="{{ $product->approx_price ?? old('approx_price') }}">
                        </div>
                    </div>

                    <div>

                    </div>
                </div>

                <div style="border: 0 !important;" class="separator w-full text-3xl">
                    Or
                </div>

                <div class="grid grid-cols-2 col-span-2">
                    <div>
                        <div class='w-full md:w-full px-3 mb-6'>
                            <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'
                                for='grid-text-1'>{{ __('Min. Price (PKR)') }}</label>
                            <input type="number"
                                class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500'
                                name="min_price" id='grid-text-1' value="{{ $product->min_price ?? old('min_price') }}">
                        </div>
                    </div>

                    <div>
                        <div class='w-full md:w-full px-3 mb-6'>
                            <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'
                                for='grid-text-1'>{{ __('Max. Price (PKR)') }}</label>
                            <input
                                class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500'
                                name="max_price" id='grid-text-1' type='number'
                                value="{{ $product->max_price ?? old('max_price') }}">
                        </div>
                    </div>

                </div>

                <div class="grid grid-cols-2 col-span-2">
                    <div>
                        <div class='w-full md:w-full px-3 mb-6'>
                            <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'
                                for='grid-text-1'>{{ __('Minimum Order Quantity') }}</label>
                            <input type="number"
                                class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500'
                                name="min_order_quantity" id='grid-text-1'
                                value="{{ $product->min_order_quantity ?? old('min_order_quantity') }}">
                        </div>
                    </div>

                    <div>
                        <div class='w-full md:w-full px-3 mb-6'>
                            <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'
                                for='grid-text-1'>{{ __('Unit of Measure') }}</label>
                            <input
                                class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500'
                                name="unit_measure_quantity" id='unit_measure_quantity' type='text'
                                value="{{ $product->unit_measure_quantity ?? old('unit_measure_quantity') }}">
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 col-span-2">
                    <div>
                        <div class='w-full md:w-full px-3 mb-6'>
                            <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'
                                for='grid-text-1'>{{ __('Supply Ability') }}</label>
                            <input type="number"
                                class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500'
                                name="supply_ability" id='grid-text-1'
                                value="{{ $product->supply_ability ?? old('supply_ability') }}">
                        </div>
                    </div>

                    <div>
                        <div class='w-full md:w-full px-3 mb-6'>
                            <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'
                                for='grid-text-1'>{{ __('Unit of Measure') }}</label>
                            <input
                                class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500'
                                name="unit_measure_supply" id='unit_measure_supply' type='text'
                                value="{{ $product->unit_measure_supply ?? old('unit_measure_supply') }}">
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button class="btn btn-teal">{{ __('Save') }}</button>
                </div>
            </form>
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        var base_url = '{{ config('app.url') }}';
    </script>
    <script type="text/javascript" src="{{ asset(('js/pages/upload_matter_sheet_product.js')) }}"></script>
@endpush
