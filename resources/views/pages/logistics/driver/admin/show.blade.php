@extends('layouts.master')

@push('styles')
@endpush

@section('page')
    @parent
    <div class="card col-span-2 xl:col-span-1">
        <div class="card-header">{{ __('Driver Information') }}</div>
        <div class="form-wrapper p-10">
                <form id="driver_info_form" method="POST" action="{{ !empty($driver) ? route('admin.logistics.driver.verify', ['driver' => $driver->id]) : route('logistics.drivers.store') }}">
                    @csrf
                    @method('PUT')
                    <div id="about">
                        <div class="bg-white px-4 py-5 rounded-lg shadow-lg text-center w-48 mb-6 image_div" id="image_div">
                            <div class="mb-4">
                                <img class="w-auto mx-auto object-cover object-center" id="image_path_preview" src="{{ !empty($driver) ? url(Storage::url($driver->image)) : asset('img/camera_icon.png')}}" alt="Driver Image Upload" />
                            </div>
                            <label class="mt-6">
                                <span class="mt-2 leading-normal px-4 py-2 bg-blue-500 text-white text-sm rounded-full" >{{ __('Driver Photo') }}</span>
                            </label>
                        </div>
                        <div class="w-full md:w-full mb-6">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="name">{{ __('Name') }}</label>
                            <input class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500' name="name" id="name" type="text" value="{{ Auth::user()->name }}" readonly>
                        </div>
                        <div class="w-full md:w-full mb-6">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="date-of-birthd">{{ __('Date of birth') }}</label>
                            <input class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500' name="dob" id="dob" type="text" value="{{ $driver->dob }}" readonly>
                        </div>
                        <div class="w-full md:w-full mb-6">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="name">{{ __('Email') }}</label>
                            <input class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500' name="email" id="email" type="text" value="{{ $driver->user->email }}" readonly>
                        </div>
                    </div>
                    <div id="license">
                        <div class="bg-white px-4 py-5 rounded-lg shadow-lg text-center w-48 mb-6 image_div">
                            <div class="mb-4">
                                <img class="w-auto mx-auto object-cover object-center" id="license_path_preview" src="{{ !empty($driver) ? url(Storage::url($driver->license_photo)): asset('img/camera_icon.png')}}" />
                            </div>
                            <label class="mt-6">
                                <span class="mt-2 leading-normal px-4 py-2 bg-blue-500 text-white text-sm rounded-full" >{{ __('License Photo') }}</span>
                            </label>
                        </div>
                        <div class="w-full md:w-full mb-6">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="liscense-no">{{ __('License No') }}</label>
                            <input class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500' name="license_no" id="license_no" type="text" value="{{ $driver->license_number }}" readonly>
                        </div>
                        <div class="w-full md:w-full mb-6">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="date-of-expiry">{{ __('Date of Expiry') }}</label>
                            <input class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500' name="date_of_expiry" id="date_of_expiry" type="text" value="{{ $driver->license_expiry_date }}" readonly>
                        </div>
                    </div>
                    <div id="cnic">
                        <div class="inline-block bg-white px-4 py-5 rounded-lg shadow-lg text-center w-48 mb-6 image_div">
                            <div class="mb-4">
                                <img class="w-auto mx-auto object-cover object-center" id="cnic_front_path_preview" src="{{ !empty($driver) ? url(Storage::url($driver->cnic_front)): asset('img/camera_icon.png') }}"/>
                            </div>
                            <label class="mt-6">
                                <span class="mt-2 leading-normal px-4 py-2 bg-blue-500 text-white text-sm rounded-full" >{{ __('CNIC Front Photo') }}</span>
                            </label>
                        </div>
                        <div class="inline-block bg-white px-4 py-5 rounded-lg shadow-lg text-center w-48 mb-6 image_div">
                            <div class="mb-4">
                                <img class="w-auto mx-auto object-cover object-center" id="cnic_back_path_preview" src="{{ !empty($driver) ? url(Storage::url($driver->cnic_back)): asset('img/camera_icon.png') }}" alt="CNIC Back Image Upload" />
                            </div>
                            <label class="mt-6">
                                <span class="mt-2 leading-normal px-4 py-2 bg-blue-500 text-white text-sm rounded-full" >{{ __('CNIC Back Photo') }}</span>
                            </label>
                        </div>
                    </div>
                    <div id="vehicle_info">
                        <div class="inline-block bg-white px-4 py-5 rounded-lg shadow-lg text-center w-48 mb-6 image_div">
                            <div class="mb-4">
                                <img class="w-auto mx-auto object-cover object-center" id="vehicle_image_preview" src="{{ !empty($driver) ? url(Storage::url($driver->vehicle->photo)) : asset('img/camera_icon.png')}}" alt="Vehicle Upload Photo" />
                            </div>
                            <label class="mt-6">
                                <span class="mt-2 leading-normal px-4 py-2 bg-blue-500 text-white text-sm rounded-full" >{{ __('Vehicle Photo') }}</span>
                            </label>
                        </div>
                        <div class="inline-block bg-white px-4 py-5 rounded-lg shadow-lg text-center w-48 mb-6 image_div">
                            <div class="mb-4">
                                <img class="w-auto mx-auto object-cover object-center" id="vehicle_reg_certificate_preview" src="{{ !empty($driver) ? url(Storage::url($driver->vehicle->registration_certificate)) : asset('img/camera_icon.png') }}" alt="Vehicle Upload Registration Certificate" />
                            </div>
                            <label class="mt-6">
                                <span class="mt-2 leading-normal px-4 py-2 bg-blue-500 text-white text-sm rounded-full" >{{ __('Reg Certificate') }}</span>
                            </label>
                        </div>
                        <div class="inline-block bg-white px-4 py-5 rounded-lg shadow-lg text-center w-48 mb-6 image_div">
                            <div class="mb-4">
                                <img class="w-auto mx-auto object-cover object-center" id="vehicle_health_certificate_preview" src="{{ !empty($driver) ? url(Storage::url($driver->vehicle->fitness_certificate)) : asset('img/camera_icon.png') }}" alt="Vehicle Upload Health Certificate" />
                            </div>
                            <label class="mt-6">
                                <span class="mt-2 leading-normal px-4 py-2 bg-blue-500 text-white text-sm rounded-full" >{{ __('Fitness Certificate') }}</span>
                            </label>
                        </div>
                        <div class="w-full md:w-full mb-6">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="name">{{ __('Company') }}</label>
                            <input class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500' name="company" id="company" type="text" value="{{ $driver->vehicle->company_name }}" readonly>
                        </div>
                        <div class="w-full md:w-full mb-6">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="date-of-birthd">{{ __('Number plate no') }}</label>
                            <input class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500' name="number_plate_no" id="number_plate_no" type="text" value="{{ $driver->vehicle->number_plate_no }}" readonly>
                        </div>
                    </div>

                    @if ($driver->is_verified == 0)
                        <div class="flex p-2 mt-4">
                            <div class="flex-auto flex flex-row-reverse">
                                <button class="text-base  ml-2  hover:scale-110 focus:outline-none flex justify-center px-4 py-2 rounded font-bold cursor-pointer
                                    hover:bg-teal-600
                                    bg-teal-600
                                    text-teal-100
                                    border duration-200 ease-in-out
                                    border-teal-600 transition">
                                    {{__('Mark as Verified')}}
                                </button>
                            </div>
                        </div>
                    @endif

                </form>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    var base_url = '{{ config('app.url') }}';
</script>
@endpush
