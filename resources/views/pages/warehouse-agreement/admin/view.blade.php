@extends('layouts.master')

@push('styles')

@endpush


@section('page')
    <div class="min-h-screen pt-2 font-mono">
        <div class="container mx-auto">
            <div class="inputs w-full p-6">
                <h2 class="text-2xl text-gray-900">{{__('Warehouse Agreement')}}</h2>

                    <div class='flex flex-wrap -mx-3 mb-6'>
                        <div class='w-full md:w-full px-3 mb-6'>
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="title">
                                {{ __('Item') }}
                            </label>
                            <input class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none focus:border-gray-500" name="item" id="item" type="text" value="{{$invoice_details->item}}">
                        </div>
                        <div class='w-full md:w-full px-3 mb-6'>
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="description">
                                {{__('Details')}}
                            </label>
                            <textarea name="description" id="description" cols="30" rows="10" class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500'>{{$invoice_details->description}}</textarea>
                        </div>
                        <div class='w-full md:w-full px-3 mb-6'>
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="start date">
                                {{ __('Start Date') }}

                            </label>
                            <input class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500" name="start_date" id="start_date" type="text" value="{{$invoice_details->start_date}}">
                        </div>
                        <div class='w-full md:w-full px-3 mb-6'>
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="start time">
                                {{ __('Start Time') }}

                            </label>
                            <input class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500" name="start_time" id="start_time" type="text" value="{{$invoice_details->time_start}}">
                        </div>
                        <div class='w-full md:w-full px-3 mb-6'>
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="end date">
                                {{ __('End Date') }}

                            </label>
                            <input class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500" name="end_date" id="end_date" type="text" value="{{$invoice_details->end_date}}">
                        </div>
                        <div class='w-full md:w-full px-3 mb-6'>
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="start time">
                                {{ __('End Time') }}

                            </label>
                            <input class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500" name="end_time" id="end_time" type="text" value="{{$invoice_details->time_end}}">
                        </div>
                        <div class='w-full md:w-full px-3 mb-6'>
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="quantity">
                                {{ __('Quantity') }}
                            </label>
                            <input class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none focus:border-gray-500" name="quantity" id="quantity" type="text" value="{{$invoice_details->quantity}}">
                        </div>
                        <div class='w-full md:w-full px-3 mb-6'>
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="unit">
                                {{ __('Unit') }}
                            </label>
                            <input class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none focus:border-gray-500" name="quantity" id="quantity" type="text" value="{{$invoice_details->unit}}">
                        </div>
                        <div class='w-full md:w-full px-3 mb-6'>
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="goods value">
                                {{ __('Goods Value') }}
                            </label>
                            <input class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none focus:border-gray-500" name="goods_value" id="goods_value" type="text" value="{{$invoice_details->goods_value}}">
                        </div>
                        <div class='w-full md:w-full px-3 mb-6'>
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="booking type">
                                {{ __('Booking Type') }}
                            </label>
                            <input class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none focus:border-gray-500" name="goods_value" id="goods_value" type="text" value="{{$invoice_details->booking_type}}">
                        </div>
                        @if ($invoice_details->booking_type == 'fully')
                        <div class='w-full md:w-full px-3 mb-6 area hidden'>
                        @else
                        <div class='w-full md:w-full px-3 mb-6 area'>
                        @endif
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="area">
                                {{ __('Area Required') }}
                            </label>
                            <input class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none focus:border-gray-500" name="area" id="area" type="text" value="{{$invoice_details->area}}">
                        </div>
                        <div class='w-full md:w-full px-3 mb-6'>
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="goods value">
                                {{ __('Price') }}
                            </label>
                            <input class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none focus:border-gray-500" name="price" id="price" type="text" value="{{$invoice_details->price}}">
                        </div>
                        <div class='w-full md:w-full px-3 mb-6'>
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="description">
                                {{__('Terms for user')}}

                            </label>
                            <textarea name="user_terms" id="user_terms" cols="30" rows="10" class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500'>{{$invoice_details->user_terms}}</textarea>
                        </div>
                        <div class='w-full md:w-full px-3 mb-6'>
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="description">
                                {{__('Terms for warehouse owner')}}

                            </label>
                            <textarea name="owner_terms" id="owner_terms" cols="30" rows="10" class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500'>{{$invoice_details->owner_terms}}</textarea>
                        </div>
                    </div>
            </div>
        </div>
    </div>

@endsection


