@extends('layouts.master')

@push('styles')
    <link rel="stylesheet" href="{{ asset(('css/pages/lead_quotation.css')) }}">
@endpush

@section('page')
    @parent
    <div class="pt-2 font-mono">
        <div class="container mx-auto">
            <div class="inputs w-full p-6 mx-auto">
                <h2 class="text-2xl text-gray-900">{{__('Create Quote')}}</h2>
                <div class="alert alert-default">
                    <span>{{__('Buyer Requirements')}}</span>
                    <div>
                        <span>{{__('Product')}}: {{$lead_info->required_product}}</span>
                    </div>
                    <div>
                        <span>{{__('Requirement Details')}}: <span>
                        <p>{{$lead_info->requirement_details}}</p>
                    </div>
                    <div>
                        <span>{{__('Quantity')}}: {{$lead_info->quantity . " ". $lead_info->unit}}</span>
                    </div>
                    <div>
                        <span>{{__('Budget')}}: {{'Rs '.$lead_info->budget}}</span>
                    </div>
                    <div>
                        <span>{{__('Requirement Urgency')}}: {{$lead_info->requirement_urgency}}</span>
                    </div>
                    <div>
                        <span>{{__('Requirement Frequency')}}: {{$lead_info->requirement_frequency}}</span>
                    </div>
                </div>
                <form id="quotation_form" class="mt-6 border-t border-gray-400 pt-4" method="POST" action="{{route('lead-quotation.store', ['product_buy_requirement' => $lead_info->id])}}">
                    @csrf
                    <div class='flex flex-wrap -mx-3 mb-6'>
                        <div class='w-full md:w-full mb-6'>
                            <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2' for='grid-text-1'>{{ __('Product') }}</label>
                            <input class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500' name="product" id='product' type='text' value="{{old('product')}}" required>
                        </div>
                        <div class='w-full md:w-full mb-6'>
                            <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2' for='grid-text-1'>{{ __('Quantity') }}</label>
                            <input class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500' name="quantity" id="quantity" type="text" value="{{old('quantity')}}" required>
                        </div>
                        <div class='w-full md:w-full mb-6'>
                            <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'>{{ __('Unit') }}</label>
                            <div class="flex-shrink w-full inline-block relative">
                                <select class="block appearance-none text-gray-600 w-full bg-white border border-gray-400 shadow-inner px-4 py-2 pr-8 rounded" name="unit" id="unit">
                                    <option value="">{{ __('Select Unit') }}</option>
                                    @foreach ($quantity_units as $unit)
                                        <option <?php echo old('unit') == $unit ? "selected" : "" ?> value="{{$unit}}">{{$unit}}</option>

                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute top-0 mt-3  right-0 flex items-center px-2 text-gray-600">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                                </div>
                            </div>
                        </div>
                        <div class='w-full md:w-full mb-6'>
                            <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2' for='grid-text-1'>{{ __('Price') }}</label>
                            <input class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500' name="price" id="price" type="text" value="{{old('price')}}" required>
                        </div>
                        <div class="personal w-full border-t border-gray-400 pt-4">
                            <div class="flex justify-end">
                                <button class="border border-gray-700 bg-gray-700 text-white rounded-md px-4 py-2 m-2 transition duration-500 ease select-none hover:bg-gray-800 focus:outline-none focus:shadow-outline" type="submit">{{__('Submit Quotation')}}</button>
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
<script type="text/javascript" src="{{ asset(('js/pages/lead_quotation.js')) }}"></script>
@endpush
