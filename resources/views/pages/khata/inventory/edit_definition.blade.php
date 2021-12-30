@extends('layouts.master')
@push('styles')
    <link rel="stylesheet" href="{{ asset(('css/pages/definition.css')) }}">
@endpush


@section('page')

    <div class="card col-span-2 xl:col-span-1">
        <div class="card-header">{{ __('Product Definition') }}</div>
        <form id="product_definition_form" action="{{ route('khata.inventory.product.definition.update', ['product_definition' => $product_definition->id]) }}" method="POST" enctype="multipart/form-data" class="mt-10">
            @csrf
            @method('PUT')
            <div class="grid grid-flow-row grid-cols-2 grid-rows-12 gap-x-20 gap-y-4 mb-10">

                <div class="col-span-1 row-span-6">
                    <div class="w-full md:w-full px-3 mb-6">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="product_code">{{ __('Product Code') }}</label>
                        <input class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500"
                                name="product_code" id="product_code" type="text" value="{{ !empty(old('product_code')) ? old('product_code') : $product_definition->product_code }}">
                    </div>
                    <div class="w-full md:w-full px-3 mb-6">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="product_name">{{ __('Product Name') }}</label>
                        <input class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500"
                                name="product_name" id="product_name" type="text" value="{{ !empty(old('product_name')) ? old('product_name') : $product_definition->product->title }}">
                        <input type="hidden" name="product_id" id="product_id" value="{{ $product_definition->product->id }}">
                    </div>
                    <div class='w-full md:w-full px-3 mb-6' id="level_1_div">
                        <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'>{{ __('Select Category') }}</label>
                        <div class="flex-shrink w-full inline-block relative">
                            <select class="block appearance-none text-gray-600 w-full bg-white border border-gray-400 shadow-inner px-4 py-2 pr-8 rounded category" data-level="1" name="category[1]" id="category_level_1">
                                <option value="">{{ __('Select 1st Category Level') }}</option>
                                @foreach ($level_1_categories as $category)
                                    <option value="{{$category->id}}">{{$category->title}}</option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute top-0 mt-3  right-0 flex items-center px-2 text-gray-600">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                            </div>
                        </div>
                    </div>
                    <div class="w-full md:w-full px-3 mb-6">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="brand_name">{{ __('Brand Name') }}</label>
                        <input class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500"
                                name="brand_name" id="brand_name" type="text" value="{{ !empty(old('brand_name')) ? old('brand_name') : $product_definition->brand_name }}">
                    </div>
                </div>
                <div class="col-span-1 row-span-6 mx-auto">
                    <div class="bg-white px-4 py-5 rounded-lg shadow-lg text-center w-48">
                        <div class="mb-4">
                            @if (!empty($product_definition->product->images[0]->path))
                            <img class="w-auto mx-auto object-cover object-center h-40" id="product_image_preview" src="{{ url(Storage::url($product_definition->product->images[0]->path)) }}" alt="" />
                            @else
                            <img class="w-auto mx-auto object-cover object-center" id="product_image_preview" src="{{asset('img/camera_icon.png')}}" alt="{{ __('Product Image') }}" />
                            @endif

                        </div>
                        @if (empty($product_definition->product->images[0]->path))
                        <label class="cursor-pointer mt-6" id="upload_image_label">
                            <span class="mt-2 leading-normal px-4 py-2 bg-blue-500 text-white text-sm rounded-full" >{{ __('Product Image') }}</span>
                            <input type="file" name="product_image" id="product_image" class="hidden" :accept="accept" />
                        </label>
                        @endif
                        </div>
                </div>
                <div class="col-span-1 row-span-5">
                    <div class="w-full md:w-full px-3 mb-6">
                        <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'>{{ __('Purchase Unit') }}</label>
                        <div class="flex-shrink w-full inline-block relative">
                            <select class="block appearance-none text-gray-600 w-full bg-white border border-gray-400 shadow-inner px-4 py-2 pr-8 rounded" name="purchase_unit" id="purchase_unit">
                                <option value="">{{ __('Select Purchase Unit') }}</option>
                                @foreach ($units as $unit)
                                <option {{ !empty($product_definition->purchase_unit) && $product_definition->purchase_unit == $unit ? 'selected' : '' }} value="{{ $unit }}">{{$unit}}</option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute top-0 mt-3  right-0 flex items-center px-2 text-gray-600">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                            </div>
                        </div>
                    </div>
                    <div class="w-full md:w-full px-3 mb-6">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="product_code">{{ __('Product Conversion Factor') }}</label>
                        <input class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500"
                                name="conversion_factor" id="conversion_factor" value="1" type="text" value="{{ !empty(old('conversion_factor')) ? old('conversion_factor') : $product_definition->conversion_factor }}">
                    </div>
                    <div class="w-full md:w-full px-3 mb-6">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">{{ __('Product Gender') }}</label>
                        <div class="flex-shrink w-full inline-block relative">
                            <select class="block appearance-none text-gray-600 w-full bg-white border border-gray-400 shadow-inner px-4 py-2 pr-8 rounded" name="product_gender" id="product_gender">
                                <option value="">{{ __('Select Product Gender') }}</option>
                                <option {{ !empty($product_definition->product_gender) && $product_definition->product_gender == 'common' ? 'selected' : '' }} value="common">{{ __('Common') }}</option>
                            </select>
                            <div class="pointer-events-none absolute top-0 mt-3  right-0 flex items-center px-2 text-gray-600">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                            </div>
                        </div>
                    </div>
                    <div class="w-full md:w-full px-3 mb-6">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">{{ __('Value Addition') }}</label>
                        <div class="flex-shrink w-full inline-block relative">
                            <select class="block appearance-none text-gray-600 w-full bg-white border border-gray-400 shadow-inner px-4 py-2 pr-8 rounded" name="value_addition" id="value_addition">
                                <option value="">{{ __('Select Value Addition') }}</option>
                                <option {{ !empty($product_definition->value_addition) && $product_definition->value_addition == 'premium' ? 'selected' : '' }} value="premium">{{ __('Premium') }}</option>
                            </select>
                            <div class="pointer-events-none absolute top-0 mt-3  right-0 flex items-center px-2 text-gray-600">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                            </div>
                        </div>
                    </div>
                    <div class="w-full md:w-full px-3 mb-6">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">{{ __('Life Type') }}</label>
                        <div class="flex-shrink w-full inline-block relative">
                            <select class="block appearance-none text-gray-600 w-full bg-white border border-gray-400 shadow-inner px-4 py-2 pr-8 rounded" name="life_type" id="life_type">
                                <option value="">{{ __('Select Life Type') }}</option>
                                <option {{ !empty($product_definition->life_type) && $product_definition->life_type == '6 Months' ? 'selected' : '' }} value="6 Months">{{ __('6 Months') }}</option>
                                <option {{ !empty($product_definition->life_type) && $product_definition->life_type == '12 Months' ? 'selected' : '' }} value="12 Months">{{ __('12 Months') }}</option>
                            </select>
                            <div class="pointer-events-none absolute top-0 mt-3  right-0 flex items-center px-2 text-gray-600">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                            </div>
                        </div>
                    </div>
                    <div class="w-full md:w-full px-3 mb-6">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="tax_code">{{ __('Tax Code') }}</label>
                        <input class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500"
                                name="tax_code" id="tax_code" type="text" value="{{ !empty(old('tax_code')) ? old('tax_code') : $product_definition->tax_code}}">
                    </div>
                </div>
                <div class="col-span-1 row-span-6">
                    <div class="w-full md:w-full px-3 mb-6">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">{{ __('Product Group') }}</label>
                        <div class="flex-shrink w-full inline-block relative">
                            <select class="block appearance-none text-gray-600 w-full bg-white border border-gray-400 shadow-inner px-4 py-2 pr-8 rounded" name="product_group" id="product_group">
                                <option value="">{{ __('Select Product Group') }}</option>
                                <option {{ !empty($product_definition->product_group) && $product_definition->product_group == 'casual use' ? 'selected' : '' }} value="casual use">{{ __('Casual Use') }}</option>
                            </select>
                            <div class="pointer-events-none absolute top-0 mt-3  right-0 flex items-center px-2 text-gray-600">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                            </div>
                        </div>
                    </div>
                    <div class="w-full md:w-full px-3 mb-6">
                        <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'>{{ __('Supplier') }}</label>
                        <div class="flex-shrink w-full inline-block relative">
                            <select class="block appearance-none text-gray-600 w-full bg-white border border-gray-400 shadow-inner px-4 py-2 pr-8 rounded" name="supplier" id="supplier">
                                <option value="">{{ __('Select Supplier') }}</option>
                                <option {{ !empty($product_definition->supplier) && $product_definition->supplier == 'common' ? 'selected' : '' }} value="common">{{ __('Common') }}</option>
                            </select>
                            <div class="pointer-events-none absolute top-0 mt-3  right-0 flex items-center px-2 text-gray-600">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                            </div>
                        </div>
                    </div>
                    <div class="w-1/4 md:w-full px-3 mb-5">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="accquire_type">{{__('Accquire Type')}}</label>
                        {{__('Purchased')}}
                        <input class="inline-block bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500"
                            name="accquire_type" id="accquire_type" value="purchased" type="radio" {{ !empty($product_definition->accquire_type) && $product_definition->accquire_type == 'purchased' ? 'checked' : '' }}>
                        {{__('Manafactured')}}
                        <input class="inline-block bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500"
                                 name="accquire_type" id="accquire_type" value="manafactured" type="radio" {{ !empty($product_definition->accquire_type) && $product_definition->accquire_type == 'manafactured' ? 'checked' : '' }}>
                    </div>
                    <div class="w-full md:w-full px-3 mb-6">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">{{ __('Purchase Type') }}</label>
                        <div class="flex-shrink w-full inline-block relative">
                            <select class="block appearance-none text-gray-600 w-full bg-white border border-gray-400 shadow-inner px-4 py-2 pr-8 rounded" name="purchase_type" id="purchase_type">
                                <option value="">{{ __('Select Purchase Type') }}</option>
                                <option {{ !empty($product_definition->purchase_type) && $product_definition->purchase_type == 'local' ? 'selected' : '' }} value="local">{{ __('Local') }}</option>
                                <option {{ !empty($product_definition->purchase_type) && $product_definition->purchase_type == 'imported' ? 'selected' : '' }} value="imported">{{ __('Imported') }}</option>
                            </select>
                            <div class="pointer-events-none absolute top-0 mt-3  right-0 flex items-center px-2 text-gray-600">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                            </div>
                        </div>
                    </div>
                    <div class="w-full md:w-full px-3 mb-6">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">{{ __('Additional Attributes') }}</label>
                        <div class="flex-shrink w-full inline-block relative">
                            <select class="block appearance-none text-gray-600 w-full bg-white border border-gray-400 shadow-inner px-4 py-2 pr-8 rounded" name="additional_attributes" id="additional_attributes">
                                <option value="">{{ __('Select Additional Attributes') }}</option>
                                <option {{ !empty($product_definition->additional_attributes) && $product_definition->additional_attributes == 'out sourced' ? 'selected' : '' }} value="out sourced">{{ __('Outsourced') }}</option>
                            </select>
                            <div class="pointer-events-none absolute top-0 mt-3  right-0 flex items-center px-2 text-gray-600">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                            </div>
                        </div>
                    </div>
                    <div class="w-full md:w-full px-3 mb-6">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="purchase_interval">{{ __('Purchase/Production  Interval') }}</label>
                        <input class="appearance-none inline w-96 bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500"
                                name="purchase_production_interval" id="purchase_production_interval" type="text" placeholder="5" value="{{ !empty(old('purchase_production_interval')) ? old('purchase_production_interval') : $product_definition->purchase_production_interval }}">
                        <select class="inline text-gray-600 w-96 bg-white border border-gray-400 shadow-inner px-4 py-3 pr-8 rounded leading-tight" name="purchase_production_unit" id="purchase_production_unit">
                            <option {{ !empty($product_definition->purchase_production_unit) && $product_definition->purchase_production_unit == 'days' ? 'selected' : '' }} value="days">{{ __('Days') }}</option>
                            <option {{ !empty($product_definition->purchase_production_unit) && $product_definition->purchase_production_unit == 'months' ? 'selected' : '' }} value="months">{{ __('Months') }}</option>
                            <option {{ !empty($product_definition->purchase_production_unit) && $product_definition->purchase_production_unit == 'years' ? 'selected' : '' }} value="years">{{ __('Years') }}</option>
                        </select>
                    </div>
                </div>
                <div class="col-span-2">
                    <div class="w-full md:w-full px-3 mb-6">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="brand_name">{{ __('Any Technical Product Detail') }}</label>
                        <textarea
                            name="technical_details" id="technical_details"
                            cols="30" rows="5"
                            class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner
                            rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500"
                            placeholder="{{ __('Add any further technical details of the product that you want users to know.....') }}"
                        >{{ !empty(old('technical_details')) ? old('technical_details') : $product_definition->technical_details }}</textarea>
                    </div>
                    <div class="w-full md:w-full px-3 mb-6">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="additional product description">{{ __('Any Additional Product Description') }}</label>
                        <textarea
                            name="description" id="description"
                            cols="30" rows="5"
                            class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner
                            rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500"
                            placeholder="{{ __('Add any further details of the product that you want users to know.....') }}"
                        >{{ !empty(old('description')) ? old('description') : $product_definition->additional_description }}</textarea>
                    </div>
                </div>

            </div>
            <div class="grid grid-flow-row justify-center mb-10">
                <button class="btn btn-teal">{{ __('Save') }}</button>
            </div>
        </form>




    </div>
@endsection

@push('scripts')
    <script src="{{asset(('js/pages/definition.js'))}}"></script>
@endpush
