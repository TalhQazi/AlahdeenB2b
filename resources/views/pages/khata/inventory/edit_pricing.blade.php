@extends('layouts.master')

@push('styles')
    <link rel="stylesheet" href="{{ asset(('css/pages/pricing.css')) }}">
@endpush

@section('page')

    <div class="card col-span-2 xl:col-span-1">
        <div class="card-header">{{ __('Product Pricing') }}</div>
        <form id="product_pricing_form" action="{{ route('khata.inventory.product.pricing.update', ['product_pricing' => $product_pricing->id]) }}" method="POST" class="mt-10">
            @csrf
            @method('PUT')
            <div class="grid grid-flow-row grid-cols-2 grid-rows-12 gap-x-20 gap-y-4 mb-10">

                <div class="col-span-1 row-span-12">
                    <div class="w-full md:w-full px-3 mb-6" id="level_1_div">
                        <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'>{{ __('Select Product') }}</label>
                        <div class="flex-shrink w-full inline-block relative">
                            <select class="block appearance-none text-gray-600 w-full bg-white border border-gray-400 shadow-inner px-4 py-2 pr-8 rounded" name="product_id" id="product_id">
                                <option value="">{{ __('Select Product') }}</option>
                                @foreach ($products as $product)
                                    <option {{$product->id == $product_pricing->product->id ? 'selected' : ''}} value="{{$product->id}}">{{$product->title}}</option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute top-0 mt-3  right-0 flex items-center px-2 text-gray-600">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                            </div>
                        </div>
                    </div>
                    <div class="w-full md:w-full px-3 mb-6">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="total units">{{ __('Total Units') }}</label>
                        <input class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500"
                                name="total_units" id="total_units" type="text" value="{{!empty(old('total_units')) ? old('total_units') : $product_pricing->total_units }}">
                    </div>
                    <div class="w-full md:w-full px-3 mb-6">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="price per unit">{{ __('Price Per Unit') }}</label>
                        <input class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500"
                            name="price_per_unit" id="price_per_unit" type="text" value="{{ !empty(old('price_per_unit')) ? old('price_per_unit') : $product_pricing->price_per_unit }}">
                    </div>
                    <div class="w-full md:w-full px-3 mb-6">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="average cost per unit">{{ __('Average Cost Per Unit') }}</label>
                        <input class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500"
                            name="avg_cost_per_unit" id="avg_cost_per_unit" type="text" value="{{ !empty(old('avg_cost_per_unit')) ? old('avg_cost_per_unit') : $product_pricing->avg_cost_per_unit }}">
                    </div>
                    <div class="w-full md:w-full px-3 mb-6">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="sales tax">{{ __('Sales Tax') }}</label>
                        <input class="appearance-none inline w-96 bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500"
                            name="sales_tax_percentage" id="sales_tax_percentage" type="text" value="{{ !empty(old('sales_tax_percentage')) ? old('sales_tax_percentage') : $product_pricing->sales_tax_percentage }}">
                        <input class="appearance-none inline w-96 bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500" name="sales_tax_value" id="sales_tax_value" type="text">
                    </div>
                    <div class="w-1/4 md:w-full px-3 mb-5">
                        {{__('Allow below cost sale')}}
                        <input class="inline-block bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500"
                            name="allow_below_cost_sale" id="allow_below_cost_sale" type="checkbox" @if(!empty($product_pricing->allow_below_cost_sale)) {{'checked'}} @endif>
                    </div>
                    <div class="w-1/4 md:w-full px-3 mb-5">
                        {{__('Allow Price Change')}}
                        <input class="inline-block bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500"
                            name="allow_price_change" id="allow_price_change" type="checkbox" @if(!empty($product_pricing->allow_price_change)) {{'checked'}} @endif>
                    </div>
                    <div class="w-full md:w-full px-3 mb-6">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="discount">{{ __('Add discount') }}</label>
                        <input class="appearance-none inline w-96 bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500"
                            name="discount_percentage" id="discount_percentage" type="text" value="{{ !empty(old('discount_percentage')) ? old('discount_percentage') : $product_pricing->discount_percentage }}">
                        <input class="appearance-none inline w-96 bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500" name="discount_value" id="discount_value" type="text">
                    </div>

                </div>
                <div class="col-span-1 row-span-12 mt-6 ml-10" id="edit_product_pricing_promotion">
                    @if(!empty($product_pricing->product->promotion_offer))
                        <button type="button" class="btn btn-teal" id="view_promotional_button" data-promotion-id="{{ $product_pricing->product->promotion_offer->id }}"><i class="fas fa-eye"></i>{{ __(' View Promotional Product') }}</button>
                    @else
                        <button type="button" class="btn btn-blue" id="add_promotional_button" data-product-id="{{ $product_pricing->product->id }}"><i class="fas fa-plus"></i>{{ __(' Add Promotional Product') }}</button>
                    @endif
                </div>

            </div>
            <div class="grid grid-flow-row justify-center mb-10">
                <button class="btn btn-teal">{{ __('Save') }}</button>
            </div>
        </form>


    </div>

    @include('components.products.promotions-modal')
@endsection


@push('scripts')
    <script src="{{asset(('js/pages/pricing.js'))}}"></script>
@endpush
