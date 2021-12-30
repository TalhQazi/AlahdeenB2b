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
        {{--        @include('components.matter_sheet.matter-sheet-steps')--}}
        <div class="mt-8 p-4">
            <form method="POST" id="matter_sheet_product_form" enctype="multipart/form-data"
                  action="{{route('matter-sheet.matter_sheet_product.store')}}">
                @csrf
                <div>
                    <div class='mb-5 px-3 text-xl'>
                        <h1 class='border-b-2 border-gray-600'>{{__("Upload Product")}}</h1>
                    </div>

                    <div class="grid grid-cols-3">
                        <div>
                            <div class="bg-white px-4 py-5 rounded-lg shadow-lg text-center w-48">
                                <div class="mb-4">
                                    @if (!empty($product->image_path))
                                        <img class="w-auto mx-auto object-cover object-center h-40" id="product->image_path_preview"
                                             src="{{ $product->image_path }}" alt="Product Image Logo Upload"/>
                                    @else
                                        <img class="w-auto mx-auto object-cover object-center" id="logo_preview"
                                             src="{{asset('img/camera_icon.png')}}" alt="Product Logo Upload"/>
                                    @endif

                                </div>
                                <label class="cursor-pointer mt-6">
                                    <span
                                        class="mt-2 text-base leading-normal px-4 py-2 bg-blue-500 text-white text-sm rounded-full">{{ __('Product Logo') }}</span>
                                    <input type="file" name="logo" id="logo" class="hidden" :accept="accept"/>
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
                                        name="title" id="title" type="text" required>
                                </div>
                            </div>

                            <div>
                                <div class="w-full md:w-full px-3 mb-6">
                                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                                           for="product-code">{{ __('Product Code') }}</label>
                                    <input
                                        class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500"
                                        name="product_code" id="product_code" type="text">
                                </div>
                            </div>

                            <div>
                                <div class="w-full md:w-full px-3 mb-6">
                                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                                           for="web-category">{{ __('Web Category') }}</label>
                                    <input
                                        class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500"
                                        name="web_category" id="web_category" type="text">
                                </div>
                            </div>

                            <div>
                                <div class="w-full md:w-full px-3 mb-6">
                                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                                           for="brand-name">{{ __('Brand Name') }}</label>
                                    <input
                                        class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500"
                                        name="brand_name" id="brand_name" type="text">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="w-full text-xl my-4">
                    {{ __('Category') }}
                    <span class="text-sm"> {{ '('.__('Category and atleast one sub category needs to be added').')' }}</span>
                </div>

                <div class="grid grid-row">
                    <div class="w-full md:w-full px-3 mb-6">

                        <select
                            class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500 select2 main_category_select2'
                            name="category_id" id="category_id"
                        >
                            <option value="null">{{ __('Select Main Category') }}</option>
                            @foreach (categoryDropdown() as $item)
                                <option value="{{ $item->id }}">
                                    {{ $item->title }}
                                </option>
                            @endforeach

                        </select>
                    </div>
                    <div id="categories_div" class="w-full md:w-full px-3 mb-6"></div>
                </div>

                <div class="grid grid-row">
                    <div class="w-full md:w-full px-3 mb-6 sub_category_box">

                        <select
                            class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500 select2'
                            name="sub_category_id" id="sub_category_id" disabled
                        >
                            <option value="null">{{ __('Select Sub Category') }}</option>

                        </select>
                        
                    </div>
                    <div id="categories_div" class="w-full md:w-full px-3 mb-6"></div>
                </div>

                <div class="w-full text-xl my-4">
                    {{ __('Price and Quantity') }}
                </div>

                <div class="grid grid-row">
                    <div>
                        <div class='w-full md:w-full px-3 mb-6'>
                            <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'
                                   for='grid-text-1'>{{__('Price (PKR)')}}</label>
                            <input type="number"
                                   class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500'
                                   name="price" id='grid-text-1' >
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 col-span-2">
                    <div>
                        <div class='w-full md:w-full px-3 mb-6'>
                            <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'
                                   for='grid-text-1'>{{__('Approx. Price (PKR)')}}</label>
                            <input type="number"
                                   class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500'
                                   name="approx_price" id='grid-text-1' >
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
                                   for='grid-text-1'>{{__('Min. Price (PKR)')}}</label>
                            <input type="number"
                                   class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500'
                                   name="min_price" id='grid-text-1' >
                        </div>
                    </div>

                    <div>
                        <div class='w-full md:w-full px-3 mb-6'>
                            <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'
                                   for='grid-text-1'>{{__('Max. Price (PKR)')}}</label>
                            <input
                                class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500'
                                name="max_price" id='grid-text-1' type='number' >
                        </div>
                    </div>

                </div>

                <div class="grid grid-cols-2 col-span-2">
                    <div>
                        <div class='w-full md:w-full px-3 mb-6'>
                            <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'
                                   for='grid-text-1'>{{__('Minimum Order Quantity')}}</label>
                            <input type="number"
                                   class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500'
                                   name="min_order_quantity" id='grid-text-1' >
                        </div>
                    </div>

                    <div>
                        <div class='w-full md:w-full px-3 mb-6'>
                            <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'
                                   for='grid-text-1'>{{__("Unit of Measure")}}</label>
                            <input
                                class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500'
                                name="unit_measure_quantity" id='unit_measure_quantity' type='text' >
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 col-span-2">
                    <div>
                        <div class='w-full md:w-full px-3 mb-6'>
                            <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'
                                   for='grid-text-1'>{{__('Supply Ability')}}</label>
                            <input type="number"
                                   class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500'
                                   name="supply_ability" id='grid-text-1' >
                        </div>
                    </div>

                    <div>
                        <div class='w-full md:w-full px-3 mb-6'>
                            <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'
                                   for='grid-text-1'>{{__('Unit of Measure')}}</label>
                            <input
                                class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500'
                                name="unit_measure_supply" id='unit_measure_supply' type='text' >
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button class="btn btn-teal">{{ __('Save') }}</button>
                </div>
            </form>
        </div>

        <div class="mt-8 p-4">
            <form method="POST" id="matter_sheet_form" enctype="multipart/form-data"
                  action="{{route('matter-sheet.matter_sheet_product.store_file')}}">
                @csrf
                <div>
                    <div class="text-2xl font-bold my-4">
                        <h1>{{ __('Upload Bulk Products') }}</h1>
                    </div>

                    <div class="grid grid-cols-2 col-span-2">
                        <div class="w-full md:w-full px-3 mb-6'>
                            <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                                   for="category">{{__('Select Category')}}</label>
                            <input
                                class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500"
                                name="bulk_category" id="bulk_category" type="text"  data-level="1" data-target-div="#bulk_categories_div">
                        </div>
                        <div id="bulk_categories_div" class="w-full md:w-full px-3 mb-6"></div>
                        <div>
                            <div class='w-full md:w-full px-3 mb-6'>
                                <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'
                                       for='grid-text-1'>{{ __('Upload CSV') }}</label>
                                <input type="file"
                                       name="matter_sheet_file" accept=".csv">
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button class="btn btn-teal">{{ __('Save') }}</button>
                    </div>
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


@section('js')
    <script>
        onChangeMainCategorySelect2("{{ route('ajax.user.get.sub.categories') }}", ".sub_category_box");
    </script>
@endsection
