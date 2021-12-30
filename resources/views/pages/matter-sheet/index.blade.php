@extends('layouts.master')

@push('styles')
    <link rel="stylesheet" href="{{ asset(('css/common/flags.css')) }}">
    <link rel="stylesheet" href="{{ asset(('css/common/validation_error.css')) }}">
@endpush

@section('page')
    <div class="p-5">
{{--        @include('components.matter_sheet.matter-sheet-steps')--}}
        <div class="mt-8 p-4">
            <form method="POST" id="business_form" action="{{route('profile.business.store')}}">
                @csrf
                <?php
                $company_name = old('company_name');
                $address = old('address');
                $locality = old('locality');
                $city_id = old('city_id');
                $zip_code = old('zip_code');
                $phone_number = old('phone_number');
                $alternate_website = old('alternate_website');
                $year_of_establishment = old('year_of_establishment');
                $no_of_employees = old('no_of_employees');
                $annual_turnover = old('annual_turnover');
                $old_ownership_type = old('ownership_type');
                $name = old('name');
                $designation = old('designation');
                $email = old('email');
                $phone = old('phone');
                if (!empty($business_details)) {

                    if (empty($company_name) && !empty($business_details->company_name)) {
                        $company_name = $business_details->company_name;
                    }
                    if (empty($address) && !empty($business_details->address)) {
                        $address = $business_details->address;
                    }
                    if (empty($locality) && !empty($business_details->locality)) {
                        $locality = $business_details->locality;
                    }
                    if (empty($city_id) && !empty($business_details->city_id)) {
                        $city_id = $business_details->city_id;
                    }
                    if (empty($zip_code) && !empty($business_details->zip_code)) {
                        $zip_code = $business_details->zip_code;
                    }
                    if (empty($phone_number) && !empty($business_details->phone_number)) {
                        $phone_number = $business_details->phone_number;
                    }
                    if (empty($alternate_website) && !empty($business_details->alternate_website)) {
                        $alternate_website = $business_details->alternate_website;
                    }
                    if (empty($year_of_establishment) && !empty($business_details->year_of_establishment)) {
                        $year_of_establishment = $business_details->year_of_establishment;
                    }
                    if (empty($no_of_employees) && !empty($business_details->no_of_employees)) {
                        $no_of_employees = $business_details->no_of_employees;
                    }
                    if (empty($annual_turnover) && !empty($business_details->annual_turnover)) {
                        $annual_turnover = $business_details->annual_turnover;
                    }
                    if (empty($ownership_type) && !empty($business_details->ownership_type)) {
                        $old_ownership_type = $business_details->ownership_type;
                    }
                    if (empty($name) && !empty($user->name)) {
                        $name = $user->name;
                    }
                    if (empty($designation) && !empty($user->designation)) {
                        $designation = $user->designation;
                    }
                    if (empty($email) && !empty($user->email)) {
                        $email = $user->email;
                    }
                    if (empty($phone) && !empty($user->phone)) {
                        $phone = $user->phone;
                    }
                }
                ?>
                <div>
                    <div class='mb-5 px-3 text-xl'>
                        <h1 class='border-b-2 border-gray-600'>Personal Information</h1>
                    </div>
                    <div class="flex md:grid">
                        <div class='flex-grow-1 w-full md:w-full px-3 mb-6'>
                            <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'
                                   for='grid-text-1'>
                                {{__('Name')}}
                                <i class="fa fa-asterisk text-red-500"></i>
                            </label>
                            <input
                                class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500"
                                name="name" id="name" {{ !empty($name) ? "disabled" : "" }} type="text" value="{{ $name }}">
                        </div>

                        <div class='w-full md:w-full px-3 mb-6'>
                            <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'
                                   for='grid-text-1'>
                                {{__('Designation')}}
                                <i class="fa fa-asterisk text-red-500"></i>
                            </label>
                            <input
                                class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500"
                                name="designation" id="designation" type="text" {{ !empty($designation) ? "disabled" : "" }} value="{{ $designation }}">
                        </div>
                    </div>

                    <div class="flex md:grid">
                        <div class='flex-grow-1 w-full md:w-full px-3 mb-6'>
                            <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'
                                   for='grid-text-1'>
                                {{__('Email')}}
                                <i class="fa fa-asterisk text-red-500"></i>
                            </label>
                            <input
                                class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500"
                                name="email" id="email" type="text" {{ !empty($email) ? "disabled" : "" }} value="{{ $email }}">
                        </div>

                        <div class='w-full md:w-full px-3 mb-6'>
                            <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'
                                   for='grid-text-1'>
                                {{__('Phone')}}
                                <i class="fa fa-asterisk text-red-500"></i>
                            </label>
                            <input
                                class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500"
                                name="phone" id="phone" type="text" {{ !empty($phone) ? "disabled" : "" }} value="{{ $phone }}">
                        </div>
                    </div>

                    <div class='mb-5 px-3 text-xl'>
                        <h1 class='border-b-2 border-gray-600'>Company Profile</h1>
                    </div>

                    <div class='w-full md:w-full px-3 mb-6'>
                        <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'
                               for='grid-text-1'>
                            {{__('Company Name')}}
                            <i class="fa fa-asterisk text-red-500"></i>
                        </label>
                        <input
                            class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500"
                            name="company_name" id="company_name" type="text" {{ !empty($company_name) ? "disabled" : "" }} value="{{ $company_name }}">
                    </div>
                    <div class='w-full md:w-full px-3 mb-6'>
                        <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'
                               for='grid-text-1'>
                            {{__('Address')}}
                            <i class="fa fa-asterisk text-red-500"></i>
                        </label>
                        <input
                            class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500"
                            name="address" id="address" type="text" {{ !empty($address) ? "disabled" : "" }} value="{{ $address }}">
                    </div>
                    <div class='w-full md:w-full px-3 mb-6'>
                        <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'
                               for='grid-text-1'>{{__('Locality')}}</label>
                        <input
                            class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500'
                            name="locality" id="locality" type="text" {{ !empty($locality) ? "disabled" : "" }} value="{{ $locality }}">
                    </div>
                    <div class='w-full md:w-full px-3 mb-6'>
                        <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'
                               for='grid-text-1'>{{__('City')}}</label>
                        <select
                            class="block appearance-none text-gray-600 w-full bg-white border border-gray-400 shadow-inner px-4 py-2 pr-8 rounded"
                            name="city_id" id="city_id">
                            <option value="">{{__('Select City')}}</option>
                            @foreach ($cities as $city)
                                <option
                                    <?php echo $city_id == $city->id ? 'selected' : ''; ?> value="{{$city->id}}">{{$city->city}}</option>
                            @endforeach
                        </select>
                        <div
                            class="pointer-events-none absolute top-0 mt-3  right-0 flex items-center px-2 text-gray-600">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path
                                    d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class='w-full md:w-full px-3 mb-6'>
                        <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'
                               for='grid-text-1'>{{__('Postal/Zip Code')}}</label>
                        <input
                            class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500'
                            name="zip_code" id="zip_code" type="text" {{ !empty($zip_code) ? "disabled" : "" }} value="{{ $zip_code }}">
                    </div>
                    <div class='w-full md:w-full px-3 mb-6'>
                        <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'
                               for='grid-text-1'>
                            {{__('Phone Number')}}
                            <i class="fa fa-asterisk text-red-500"></i>
                        </label>
                        <input
                            class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500'
                            name="phone_number" id="phone_number" type="text" {{ !empty($phone_number) ? "disabled" : "" }} value="{{ $phone_number }}">
                    </div>
                    <div class='w-full md:w-full px-3 mb-6'>
                        <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'
                               for='grid-text-1'>{{__('Alternate Website')}}</label>
                        <input
                            class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500'
                            name="alternate_website" id="alternate_website" type="text"
                            {{ !empty($alternate_website) ? "disabled" : "" }} value="{{ $alternate_website }}">
                    </div>
                    <div class='w-full md:w-full px-3 mb-6'>
                        <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'
                               for='grid-text-1'>
                            {{__('Year of Establishment')}}
                            <i class="fa fa-asterisk text-red-500"></i>
                        </label>
                        <input
                            class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500'
                            name="year_of_establishment" id="year_of_establishment" type="text"
                            {{ !empty($year_of_establishment) ? "disabled" : "" }} value="{{ $year_of_establishment }}">
                    </div>
                    <div class='w-full md:w-full px-3 mb-6'>
                        <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'
                               for='grid-text-1'>
                            {{__('No of Employees')}}
                            <i class="fa fa-asterisk text-red-500"></i>
                        </label>
                        <input
                            class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500'
                            name="no_of_employees" id="no_of_employees" type="text" {{ !empty($no_of_employees) ? "disabled" : "" }} value="{{ $no_of_employees }}">
                    </div>
                    @hasanyrole('corporate')
                    <div class='w-full md:w-full px-3 mb-6'>
                        <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'
                               for='grid-text-1'>
                            {{__('Annual Turnover')}}
                            <i class="fa fa-asterisk text-red-500"></i>
                        </label>
                        <input
                            class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500'
                            name="annual_turnover" id="annual_turnover" type="text" {{ !empty($annual_turnover) ? "disabled" : "" }} value="{{ $annual_turnover }}">
                    </div>
                    <div class='w-full md:w-full px-3 mb-6'>
                        <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'
                               for='grid-text-1'>{{__('Ownership Type')}}</label>
                        <select name="ownership_type"
                                class="block appearance-none text-gray-600 w-full bg-white border border-gray-400 shadow-inner px-4 py-2 pr-8 rounded">
                            <option value="">--Choose one--</option>
                            @foreach ($ownership_types as $ownership_type)
                                <option
                                    <?php echo $old_ownership_type == $ownership_type ? 'selected' : ''; ?> {{ !empty($ownership_type) ? "disabled" : "" }} value="{{$ownership_type}}">{{$ownership_type}}</option>
                            @endforeach
                        </select>
                        <div
                            class="pointer-events-none absolute top-0 mt-3  right-0 flex items-center px-2 text-gray-600">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path
                                    d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"></path>
                            </svg>
                        </div>
                    </div>
                    @endhasanyrole
                </div>
                <div>
                    <button class="btn btn-teal">Save</button>
                </div>
            </form>
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        var base_url = '{{ config('app.url') }}';
    </script>
    <script type="text/javascript" src="{{ asset(('js/pages/business_profile.js')) }}"></script>
@endpush
