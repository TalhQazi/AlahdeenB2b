@extends('layouts.master')

@push('styles')
    <link rel="stylesheet" href="{{ asset(('css/auto_complete.css')) }}">
    <link rel="stylesheet" href="{{ asset(('css/common/flags.css')) }}">
    <link rel="stylesheet" href="{{ asset(('css/common/validation_error.css')) }}">
@endpush
@section('page')
        <div class="p-5">
            @if(!isset($hide_steps_bar))
                @include('components.business_profile.business-details-steps')
            @endif
            <div class="mt-8 p-4">
                <form method="POST" enctype="multipart/form-data" action="{{route('profile.business.additional.store')}}">
                    @csrf
                    <input type="hidden" name="matter_sheet_return" value="{{true}}" />
                    <?php
                        $company_logo = '';
                        $description = old('description');
                        $start_day = old('start_day');
                        $end_day = old('end_day');
                        $start_time = old('start_time');
                        $end_time = old('end_time');
                        $business_states = [];
                        $included_cities = [];
                        $excluded_cities = [];

                        $company_id = old('company_id');
                        $import_export_no = old('import_export_no');
                        $bank_name = old('bank_name');
                        $income_tax_number = old('income_tax_number');
                        $ntn = old('ntn');
                        $no_of_production_units = old('no_of_production_units');
                        $affiliation_memberships = old('affiliation_memberships');
                        $company_branches = old('company_branches');
                        $owner_cnic = old('owner_cnic');
                        $infrastructure_size = old('infrastructure_size');
                        $cities_to_trade_with = old('cities_to_trade_with');
                        $cities_to_trade_from = old('cities_to_trade_from');
                        $shipment_modes = old('shipment_modes');
                        $payment_modes = old('payment_modes');
                        $arn_no = old('arn_no');

                        if(!empty($additional_business_details)) {
                            if(empty($logo) && !empty($additional_business_details->logo)) {
                                $logo = url('').'/'.str_replace('public','storage', $additional_business_details->logo);
                            }
                            if(empty($description) && !empty($additional_business_details->description)) {
                                $description = $additional_business_details->description;
                            }
                            if(empty($start_day) && !empty($additional_business_details->start_day)) {
                                $start_day = $additional_business_details->start_day;
                            }
                            if(empty($end_day) && !empty($additional_business_details->end_day)) {
                                $end_day = $additional_business_details->end_day;
                            }
                            if(empty($start_time) && !empty($additional_business_details->start_time)) {
                                $start_time = $additional_business_details->start_time;
                            }
                            if(empty($end_time) && !empty($additional_business_details->end_time)) {
                                $end_time = $additional_business_details->end_time;
                            }
                            if(!empty($additional_business_details->states)) {
                               $business_states = json_decode($additional_business_details->states);
                            }
                            if(!empty($additional_business_details->included_cities)) {
                               $included_cities = json_decode($additional_business_details->included_cities);
                            }
                            if(!empty($additional_business_details->excluded_cities)) {
                               $excluded_cities = json_decode($additional_business_details->excluded_cities);
                            }
                            if(!empty($additional_business_details->company_id)) {
                               $company_id = $additional_business_details->company_id;
                            }
                            if(!empty($additional_business_details->import_export_no)) {
                               $import_export_no = $additional_business_details->import_export_no;
                            }
                            if(!empty($additional_business_details->bank_name)) {
                               $bank_name = $additional_business_details->bank_name;
                            }
                            if(!empty($additional_business_details->income_tax_number)) {
                               $income_tax_number = $additional_business_details->income_tax_number;
                            }
                            if(!empty($additional_business_details->ntn)) {
                               $ntn = $additional_business_details->ntn;
                            }
                            if(!empty($additional_business_details->no_of_production_units)) {
                               $no_of_production_units = $additional_business_details->no_of_production_units;
                            }
                            if(!empty($additional_business_details->affiliation_memberships)) {
                               $affiliation_memberships = $additional_business_details->affiliation_memberships;
                            }
                            if(!empty($additional_business_details->company_branches)) {
                               $company_branches = $additional_business_details->company_branches;
                            }
                            if(!empty($additional_business_details->owner_cnic)) {
                               $owner_cnic = $additional_business_details->owner_cnic;
                            }
                            if(!empty($additional_business_details->infrastructure_size)) {
                               $infrastructure_size = $additional_business_details->infrastructure_size;
                            }
                            if(!empty($additional_business_details->cities_to_trade_with)) {
                               $cities_to_trade_with = json_decode($additional_business_details->cities_to_trade_with);
                            }
                            if(!empty($additional_business_details->cities_to_trade_from)) {
                               $cities_to_trade_from = json_decode($additional_business_details->cities_to_trade_from);
                            }
                            if(!empty($additional_business_details->shipment_modes)) {
                               $shipment_modes = json_decode($additional_business_details->shipment_modes);
                            }
                            if(!empty($additional_business_details->arn_no)) {
                               $arn_no = $additional_business_details->arn_no;
                            }
                        }
                    ?>

                    <div>
                        <div class="px-3 py-3 center mx-auto">
                            <div class="bg-white px-4 py-5 rounded-lg shadow-lg text-center w-48">
                                <div class="mb-4">
                                    @if (!empty($logo))
                                        <img class="w-auto mx-auto object-cover object-center h-40" id="logo_preview"
                                             src="{{ $logo }}" alt="Company Logo Upload"/>
                                    @else
                                        <img class="w-auto mx-auto object-cover object-center" id="logo_preview"
                                             src="{{asset('img/camera_icon.png')}}" alt="Company Logo Upload"/>
                                    @endif

                                </div>
                                <label class="cursor-pointer mt-6">
                                    <span
                                        class="mt-2 text-base leading-normal px-4 py-2 bg-blue-500 text-white text-sm rounded-full">{{ __('Company Logo') }}</span>
                                    <input type='file' name="logo" id="logo" class="hidden" :accept="accept"/>
                                </label>
                            </div>
                        </div>
                        <div class='w-full md:w-full px-3 mb-6'>
                            <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2' for='grid-text-1'>{{__('Description')}}</label>
                            <textarea name="description" id="description" cols="30" rows="10" class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none focus:border-gray-500 valid">{{ $description }}</textarea>
                        </div>

                        <div class="flex md:grid">
                            <div class='flex-grow-1 w-full md:w-full px-3 mb-6'>
                                <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'
                                       for='grid-text-1'>
                                    {{__('Company ID')}}
                                </label>
                                <input
                                    class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500"
                                    name="company_id" id="company_id" type="text" value="{{ $company_id }}">
                            </div>

                            <div class='w-full md:w-full px-3 mb-6'>
                                <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'
                                       for='grid-text-1'>
                                    {{__('Import Export No')}}
                                </label>
                                <input
                                    class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500"
                                    name="import_export_no" id="import_export_no" type="text" value="{{ $import_export_no }}">
                                    {{__('Company Branches')}}
                                </label>
                                <input
                                    class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500"
                                    name="company_branches" id="company_branches" type="text" value="{{ $company_branches }}">
                            </div>
                        </div>

                        <div class="flex md:grid">
                            <div class='flex-grow-1 w-full md:w-full px-3 mb-6'>
                                <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'
                                       for='grid-text-1'>
                                    {{__('Bank Name')}}
                                </label>
                                <input
                                    class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500"
                                    name="bank_name" id="bank_name" type="text" value="{{ $bank_name }}">
                            </div>

                            <div class='w-full md:w-full px-3 mb-6'>
                                <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'
                                       for='grid-text-1'>
                                    {{__('Income Tax No.')}}
                                </label>
                                <input
                                    class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500"
                                    name="income_tax_number" id="income_tax_number" type="text" value="{{ $income_tax_number }}">
                            </div>
                        </div>

                        <div class="flex md:grid">
                            <div class='flex-grow-1 w-full md:w-full px-3 mb-6'>
                                <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'
                                       for='grid-text-1'>
                                    {{__('NTN')}}
                                </label>
                                <input
                                    class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500"
                                    name="ntn" id="ntn" type="number" value="{{ $ntn }}">
                            </div>

                            <div class='w-full md:w-full px-3 mb-6'>
                                <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'
                                       for='grid-text-1'>
                                    {{__('No. Of Production Units')}}
                                </label>
                                <input
                                    class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500"
                                    name="no_of_production_units" id="no_of_production_units" type="number" value="{{ $no_of_production_units }}">
                            </div>
                        </div>

                        <div class="flex md:grid">
                            <div class='flex-grow-1 w-full md:w-full px-3 mb-6'>
                                <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'
                                       for='grid-text-1'>
                                    {{__('Affiliation Memberships')}}
                                </label>
                                <input
                                    class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500"
                                    name="affiliation_memberships" id="affiliation_memberships" type="text" value="{{ $affiliation_memberships }}">
                            </div>

                            <div class='w-full md:w-full px-3 mb-6'>
                                <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'
                                       for='grid-text-1'>
                                    {{__('Company Branches')}}
                                </label>
                                <input
                                    class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500"
                                    name="company_branches" id="company_branches" type="text" value="{{ $company_branches }}">
                            </div>
                        </div>

                        <div class="flex md:grid">
                            <div class='flex-grow-1 w-full md:w-full px-3 mb-6'>
                                <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'
                                       for='grid-text-1'>
                                    {{__('Owner CNIC')}}
                                </label>
                                <input
                                    class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500"
                                    name="owner_cnic" id="owner_cnic" type="text" value="{{ $owner_cnic }}">
                            </div>

                            <div class='w-full md:w-full px-3 mb-6'>
                                <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'
                                       for='grid-text-1'>
                                    {{__('Infrastructure Size')}}
                                </label>
                                <input
                                    class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500"
                                    name="infrastructure_size" id="infrastructure_size" type="number" value="{{ $infrastructure_size }}">
                            </div>
                        </div>

                        <div class="flex md:grid">
                            <div class='flex-grow-1 w-full md:w-full px-3 mb-6'>
                                <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'
                                       for='grid-text-1'>
                                    {{__('ARN No.')}}
                                </label>
                                <input
                                    class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500"
                                    name="arn_no" id="arn_no" type="text" value="{{ $arn_no }}">
                            </div>

                        </div>

                        <div class="grid grid-rows-3 px-3 mb-6">
                            <div class="row-span-3 col-span-12 content-center">
                                <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2' for='grid-text-1'>{{__('Working Days')}}</label>
                            </div>
                            <div class="col-span-5">
                                <select class="block appearance-none text-gray-600 w-full bg-white border border-gray-400 shadow-inner px-4 py-2 pr-8 rounded" name="start_day" id="start_day">
                                    @foreach ($business_days as $day)
                                        <option {{$start_day == $day ? 'selected' : ''}} value="{{$day}}">{{$day}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-span-2 py-3 mx-auto">
                                To
                            </div>
                            <div class="col-span-5">
                                <select class="block appearance-none text-gray-600 w-full bg-white border border-gray-400 shadow-inner px-4 py-2 pr-8 rounded" name="end_day" id="end_day">
                                    @foreach ($business_days as $day)
                                        <option {{$end_day == $day ? 'selected' : ''}} value="{{$day}}">{{$day}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-span-5">
                                <select class="mt-6 block appearance-none text-gray-600 w-full bg-white border border-gray-400 shadow-inner px-4 py-2 pr-8 rounded" name="start_time" id="start_time">
                                    @foreach ($business_hours as $hour)
                                        <option {{$start_time == $hour ? 'selected' : ''}} value="{{$hour}}">{{$hour}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-span-2 py-6 mx-auto">
                                To
                            </div>
                            <div class="col-span-5">
                                <select class="mt-6 block appearance-none text-gray-600 w-full bg-white border border-gray-400 shadow-inner px-4 py-2 pr-8 rounded" name="end_time" id="end_time">
                                    @foreach ($business_hours as $hour)
                                        <option {{$end_time == $hour ? 'selected' : ''}} value="{{$hour}}">{{$hour}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class='w-full md:w-full px-3 mb-6'>
                            <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2' for='grid-text-1'>{{__('Business States')}}</label>
                            <input class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500' data-target-div='#business_states_div' data-loc-target="states" name="business_states_input" id='business_states_input' type='text'>
                        </div>

                        <div id="business_states_div" class='w-full md:w-full px-3 mb-6'>
                            @if (!empty($business_states))
                                @foreach ($business_states as $business_state)
                                    <span id='{{str_replace(" ", "_", strtolower($business_state))}}' class='inline-block rounded-full text-gray-600 bg-green-200 px-4 py-2 text-xs font-bold mr-3'>
                                        {{$business_state}}
                                        <i class="fa fa-times ml-2 delete-keyword"></i>
                                        <input type="hidden" name="business_states[]" value="{{$business_state}}">
                                    </span>
                                @endforeach
                            @endif
                        </div>

                        <div class='w-full md:w-full px-3 mb-6'>
                            <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2' for='grid-text-1'>{{__('Included Business Cities')}}</label>
                            <input class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500 keywords'
                                   data-target-div='#business_incl_cities_div' name="business_incl_cities_input" id='business_incl_cities_input' type='text'>
                        </div>

                        <div id="business_incl_cities_div" class='w-full md:w-full px-3 mb-6'>
                            @if (!empty($included_cities))
                                @foreach ($included_cities as $included_city)
                                    <span id='{{str_replace(" ", "_", strtolower($included_city))}}' class='inline-block rounded-full text-gray-600 bg-green-200 px-4 py-2 text-xs font-bold mr-3'>
                                        {{$included_city}}
                                        <i class="fa fa-times ml-2 delete-keyword"></i>
                                        <input type="hidden" name="included_cities[]" value="{{$included_city}}">
                                    </span>
                                @endforeach
                            @endif
                        </div>

                        <div class='w-full md:w-full px-3 mb-6'>
                            <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2' for='grid-text-1'>{{__('Excluded Business Cities')}}</label>
                            <input class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500 keywords' data-target-div='#business_excl_cities_div' name="business_excl_cities_input" id='business_excl_cities_input' type='text'>
                        </div>

                        <div id="business_excl_cities_div" class='w-full md:w-full px-3 mb-6'>
                            @if (!empty($excluded_cities))
                                @foreach ($excluded_cities as $excluded_city)
                                    <span id='{{str_replace(" ", "_", strtolower($excluded_city))}}' class='inline-block rounded-full text-gray-600 bg-green-200 px-4 py-2 text-xs font-bold mr-3'>
                                        {{$excluded_city}}
                                        <i class="fa fa-times ml-2 delete-keyword"></i>
                                        <input type="hidden" name="excluded_cities[]" value="{{$excluded_city}}">
                                    </span>
                                @endforeach
                            @endif
                        </div>

                        <div class="flex md:grid">
                            <div class='flex-grow-1 w-full md:w-full px-3 mb-6'>
                                <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2' for='grid-text-1'>{{__('Cities To Trade With')}}</label>
                                <select class="block appearance-none text-gray-600 w-full bg-white border border-gray-400 shadow-inner px-4 py-2 pr-8 rounded" name="cities_to_trade_with[]" id="cities_to_trade_with" multiple>
                                    @foreach ($cities as $city)
                                        @if(!empty($cities_to_trade_with))
                                            <option value="{{ $city->id }}" <?php echo in_array($city->id, $cities_to_trade_with) ? 'selected' : ''; ?>>{{ $city->city }}</option>
                                        @else
                                            <option value="{{ $city->id }}">{{ $city->city }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                            <div class='w-full md:w-full px-3 mb-6'>
                                <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2' for='grid-text-1'>{{__('Cities To Trade From')}}</label>
                                <select class="block appearance-none text-gray-600 w-full bg-white border border-gray-400 shadow-inner px-4 py-2 pr-8 rounded" name="cities_to_trade_from[]" id="cities_to_trade_from" multiple>
                                    @foreach ($cities as $city)
                                        @if(!empty($cities_to_trade_from))
                                            <option value="{{ $city->id }}" <?php echo in_array($city->id, $cities_to_trade_from) ? 'selected' : ''; ?>>{{ $city->city }}</option>
                                        @else
                                            <option value="{{ $city->id }}">{{ $city->city }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="flex md:grid">
                            <div class='flex-grow-1 w-full md:w-full px-3 mb-6'>
                                <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2' for='grid-text-1'>{{__('Mode of Payment')}}</label>
                                <select class="block appearance-none text-gray-600 w-full bg-white border border-gray-400 shadow-inner px-4 py-2 pr-8 rounded" name="mode_of_payments[]" id="mode_of_payments" multiple>
                                    @foreach ($mode_of_payments as $mode)
                                        @if(!empty($business_mode_of_payments))
                                            <option value="{{ $mode->id }}" <?php echo in_array($mode->id, $business_mode_of_payments) ? 'selected' : ''; ?>>{{ $mode->mode_of_payment }}</option>
                                        @else
                                            <option value="{{ $mode->id }}">{{ $mode->mode_of_payment }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                            <div class='w-full md:w-full px-3 mb-6'>
                                <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2' for='grid-text-1'>{{__('Shipment Modes')}}</label>
                                <select class="block appearance-none text-gray-600 w-full bg-white border border-gray-400 shadow-inner px-4 py-2 pr-8 rounded" name="shipment_modes[]" id="shipment_modes" multiple>
                                    @foreach ($ship_modes as $key => $s_mode)
                                        @if(!empty($shipment_modes))
                                            <option value="{{ $key }}" <?php echo in_array($key, $shipment_modes) ? 'selected' : ''; ?>>{{ $s_mode }}</option>
                                        @else
                                            <option value="{{ $key }}">{{ $s_mode }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>

						{{--			Additional company details -- multiple photo section			--}}
                        @if($additionalPhotosIsAvailable)
							<div class="w-full md:w-full border-solid border-2 border-gray-300 p-3 mb-6 grid-rows-2 gap-4">
                            <div class="col-span-full mx-auto">
                                <p class="leading-10 text-center"> 200KB max. JPEG or PNG format only. Suggested photo width and height for the new version Minisite: 270*270px </p>
                            </div>
                            <div class="col-span-12 text-center">
                            @if (!empty($business_photos) )
                                @for ($i = 0; $i < $no_of_photos_allowed; $i++)
                                    @if(!empty($business_photos[$i]))
                                        <?php $photo = url('').'/'.str_replace('public','storage', $business_photos[$i]->photo_path); ?>
                                        <div class="inline-block bg-white px-4 py-5 rounded-lg shadow-lg text-center w-48">
                                            <div class="mb-4">
                                                <img class="w-auto mx-auto object-cover object-center h-40" id="{{'photo_'.$i.'_preview'}}" src="{{ $photo }}" alt="Company Logo Upload" />
                                            </div>
                                            <label class="cursor-pointer mt-6">
                                                <span class="mt-2 text-base leading-normal px-4 py-2 bg-blue-500 text-white text-sm rounded-full" >{{ __('Company Photo') }}</span>
                                                <input type="hidden" name="company_photo_id[{{$i}}]" id="company_photo_id_{{$i}}" value="{{$business_photos[$i]->id}}">
                                                <input type='file' name="company_photo[{{$i}}]" id="photo_{{$i}}" class="hidden company_photo" :accept="accept" />
                                            </label>
                                        </div>
                                    @else
                                        <div class="inline-block bg-white px-4 py-5 rounded-lg shadow-lg text-center w-48">
                                            <div class="mb-4">
                                                <img class="w-auto mx-auto object-cover object-center" id="{{'photo_'.$i.'_preview'}}" src="{{asset('img/camera_icon.png')}}" alt="Company Logo Upload" />
                                            </div>
                                            <label class="cursor-pointer mt-6">
                                                <span class="mt-2 text-base leading-normal px-4 py-2 bg-blue-500 text-white text-sm rounded-full" >{{ __('Company Photo') }}</span>
                                                <input type='file' name="company_photo[{{$i}}]" id="photo_{{$i}}" class="hidden company_photo" :accept="accept" />
                                            </label>
                                        </div>
                                    @endif
                                @endfor
                            @else
                                @for ($i = 0; $i < 6; $i++)
                                    <div class="inline-block bg-white px-4 py-5 rounded-lg shadow-lg text-center w-48">
                                        <div class="mb-4">
                                            <img class="w-auto mx-auto object-cover object-center" id="{{'photo_'.$i.'_preview'}}" src="{{asset('img/camera_icon.png')}}" alt="Company Logo Upload" />
                                        </div>
                                        <label class="cursor-pointer mt-6">
                                            <span class="mt-2 text-base leading-normal px-4 py-2 bg-blue-500 text-white text-sm rounded-full" >{{ __('Company Photo') }}</span>
                                            <input type='file' name="company_photo[{{$i}}]" id="photo_{{$i}}" class="hidden company_photo" :accept="accept" />
                                        </label>
                                    </div>
                                @endfor
                            @endif
                            </div>
                        </div>
						@endif
                    </div>
                    <div><button class="btn btn-teal">Save</button></div>
                </form>
                    <div class="card col-span-2 xl:col-span-1 mt-10">
                        <div class="card-header">
                            {{__('Additional Contact Details')}}
                            <button id="add_contact_btn" class="btn btn-gray xs:float-none sm:float-none md:float-none float-right sm:mt-2" >{{__('Add Contact Details')}}<i class="fa fa-plus ml-2"></i></button>
                        </div>
                        <div class="bg-white pb-4 px-4 rounded-md w-full">
                            <div class="w-full flex justify-end px-2 mt-2">
                                <div class="sm:w-64 inline-block relative ">
                                    <input type="" name="" class="leading-snug border border-gray-300 block w-full appearance-none bg-gray-100 text-sm text-gray-600 py-1 px-4 pl-8 rounded-lg" placeholder="Search" />

                                    <div class="pointer-events-none absolute pl-3 inset-y-0 left-0 flex items-center px-2 text-gray-300">
                                        <svg class="fill-current h-3 w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 511.999 511.999">
                                            <path d="M508.874 478.708L360.142 329.976c28.21-34.827 45.191-79.103 45.191-127.309C405.333 90.917 314.416 0 202.666 0S0 90.917 0 202.667s90.917 202.667 202.667 202.667c48.206 0 92.482-16.982 127.309-45.191l148.732 148.732c4.167 4.165 10.919 4.165 15.086 0l15.081-15.082c4.165-4.166 4.165-10.92-.001-15.085zM202.667 362.667c-88.229 0-160-71.771-160-160s71.771-160 160-160 160 71.771 160 160-71.771 160-160 160z" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            <div class="overflow-x-auto mt-6 px-3">
                                <table class="table-auto border-collapse w-full">
                                    <thead>
                                        <tr class="rounded-lg text-sm font-medium text-gray-700 text-left" style="font-size: 0.9674rem">
                                        <th class="px-4 py-2 bg-gray-200 " style="background-color:#f8f8f8">{{ __('Contact Person') }}</th>
                                        <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Division') }}</th>
                                        <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Designation') }}</th>
                                        <th class="px-4 py-2 " style="background-color:#f8f8f8">Controls</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-sm font-normal text-gray-700">
                                        @if (!empty($business_contacts))

                                            @foreach ($business_contacts as $contact_details)
                                                <tr class="hover:bg-gray-100 border-b border-gray-200 py-10">
                                                    <td class="px-4 py-4">{{$contact_details['contact_person']}}</td>
                                                    <td class="px-4 py-4">{{$contact_details['division']}}</td>
                                                    <td class="px-4 py-4">{{$contact_details['designation']}}</td>
                                                    <td class="px-4 py-4">
                                                    <?php
                                                        $editRoute = route('profile.business.contact.edit',['business_contact_detail' => $contact_details['id']]);
                                                        $deleteRoute = route('profile.business.contact.delete',['business_contact_detail' => $contact_details['id']]);
                                                    ?>

                                                        <a href="#" data-url="{{$editRoute}}" title="{{ __('Edit Contact Details') }}" class="mx-0.5 edit_contact_btn">
                                                            <i class="fa fa-pencil mx-0.5"></i>
                                                        </a>

                                                        <a data-url="{{$deleteRoute}}" title="{{ __('Delete Contact') }}" class="ml-1 delete_contact_btn">
                                                            <i class="fa fa-trash"></i>
                                                        </a>

                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr class="hover:bg-gray-100 border-b border-gray-200 py-10">
                                                <td class="px-4 py-4" colspan="100">
                                                    No Contacts Found
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    {{-- <div id="pagination" class="w-full flex justify-center border-t border-gray-100 pt-4 items-center">
                        {{$products->links('')}}


                    </div> --}}
                    </div>

            </div>

        </div>
@endsection

@section('modals')
    @include('components.business_profile.contacts-modal')
    @include('components.business_profile.delete-contact-modal')
@endsection


@push('scripts')
<script src="https://cdn.tiny.cloud/1/nkcpprlcvgg1ldeqgx3dn4mhqmutceszm1yqqf73vsyqhoq9/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: '#description'
        });
    </script>
    <script type="text/javascript" src="{{ asset(('js/business_additional_details.js')) }}"></script>
@endpush
