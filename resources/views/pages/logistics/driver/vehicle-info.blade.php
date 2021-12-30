<div id="vehicle_info" class="hidden step-div" data-title="{{ __('Vehicle Info') }}">
    <div class="inline-block bg-white px-4 py-5 rounded-lg shadow-lg text-center w-48 mb-6 image_div">
        <div class="mb-4">
            <img class="w-auto mx-auto object-cover object-center" id="vehicle_image_preview" src="{{ !empty($driver) ? url(Storage::url($driver->vehicle->photo)) : asset('img/camera_icon.png')}}" alt="Vehicle Upload Photo" />
        </div>
        <label class="cursor-pointer mt-6">
          <span class="mt-2 leading-normal px-4 py-2 bg-blue-500 text-white text-sm rounded-full" >{{ __('Add Vehicle Photo') }}</span>
          <input type="file" name="vehicle_image" id="vehicle_image" class="hidden image_path" :accept="accept" />
        </label>
    </div>
    <div class="inline-block bg-white px-4 py-5 rounded-lg shadow-lg text-center w-48 mb-6 image_div">
        <div class="mb-4">
            <img class="w-auto mx-auto object-cover object-center" id="vehicle_reg_certificate_preview" src="{{ !empty($driver) ? url(Storage::url($driver->vehicle->registration_certificate)) : asset('img/camera_icon.png') }}" alt="Vehicle Upload Registration Certificate" />
        </div>
        <label class="cursor-pointer mt-6">
          <span class="mt-2 leading-normal px-4 py-2 bg-blue-500 text-white text-sm rounded-full" >{{ __('Reg Certificate') }}</span>
          <input type="file" name="vehicle_reg_certificate" id="vehicle_reg_certificate" class="hidden image_path" :accept="accept" />
        </label>
    </div>
    <div class="inline-block bg-white px-4 py-5 rounded-lg shadow-lg text-center w-48 mb-6 image_div">
        <div class="mb-4">
            <img class="w-auto mx-auto object-cover object-center" id="vehicle_health_certificate_preview" src="{{ !empty($driver) ? url(Storage::url($driver->vehicle->fitness_certificate)) : asset('img/camera_icon.png') }}" alt="Vehicle Upload Health Certificate" />
        </div>
        <label class="cursor-pointer mt-6">
          <span class="mt-2 leading-normal px-4 py-2 bg-blue-500 text-white text-sm rounded-full" >{{ __('Health Certificate') }}</span>
          <input type="file" name="vehicle_health_certificate" id="vehicle_health_certificate" class="hidden image_path" :accept="accept" />
        </label>
    </div>
    <div class="w-full md:w-full mb-6">
        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="name">{{ __('Company') }}</label>
        <input class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500' name="company" id="company" type="text" value="{{ old('company') ?? !empty($driver) ? $driver->vehicle->company_name : '' }}">
    </div>
    <div class="w-full md:w-full mb-6">
        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="date-of-birthd">{{ __('Number plate no') }}</label>
        <input class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500' name="number_plate_no" id="number_plate_no" type="text" value="{{ old('number_plate_no') ?? !empty($driver) ? $driver->vehicle->number_plate_no : '' }}">
    </div>
</div>
