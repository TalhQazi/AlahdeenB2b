<div id="license" class="hidden step-div" data-title="{{ __('License Info') }}">
    <div class="bg-white px-4 py-5 rounded-lg shadow-lg text-center w-48 mb-6 image_div">
        <div class="mb-4">
            <img class="w-auto mx-auto object-cover object-center" id="license_path_preview" src="{{ !empty($driver) ? url(Storage::url($driver->license_photo)): asset('img/camera_icon.png')}}" alt="License Image Upload" />
        </div>
        <label class="cursor-pointer mt-6">
          <span class="mt-2 leading-normal px-4 py-2 bg-blue-500 text-white text-sm rounded-full" >{{ __('Add License Photo') }}</span>
          <input type="file" name="license_path" id="license_path" class="hidden image_path" :accept="accept" />
        </label>
    </div>
    <div class="w-full md:w-full mb-6">
        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="liscense-no">{{ __('License No') }}</label>
        <input class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500' name="license_no" id="license_no" type="text" value="{{ old('license_no') ?? !empty($driver) ? $driver->license_number : '' }}">
    </div>
    <div class="w-full md:w-full mb-6">
        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="date-of-expiry">{{ __('Date of Expiry') }}</label>
        <input class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500' name="date_of_expiry" id="date_of_expiry" type="text" value="{{ old('date_of_expiry') ?? !empty($driver) ? $driver->license_expiry_date : '' }}">
    </div>
</div>

