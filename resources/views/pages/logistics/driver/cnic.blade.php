<div id="cnic" class="hidden step-div" data-title="{{ __('CNIC Info') }}">
    <div class="inline-block bg-white px-4 py-5 rounded-lg shadow-lg text-center w-48 mb-6 image_div">
        <div class="mb-4">
            <img class="w-auto mx-auto object-cover object-center" id="cnic_front_path_preview" src="{{ !empty($driver) ? url(Storage::url($driver->cnic_front)): asset('img/camera_icon.png') }}" alt="CNIC Front Image Upload" />
        </div>
        <label class="cursor-pointer mt-6">
          <span class="mt-2 leading-normal px-4 py-2 bg-blue-500 text-white text-sm rounded-full" >{{ __('CNIC Front Photo') }}</span>
          <input type="file" name="cnic_front_path" id="cnic_front_path" class="hidden image_path" :accept="accept" />
        </label>
    </div>

    <div class="inline-block bg-white px-4 py-5 rounded-lg shadow-lg text-center w-48 mb-6 image_div">
        <div class="mb-4">
            <img class="w-auto mx-auto object-cover object-center" id="cnic_back_path_preview" src="{{ !empty($driver) ? url(Storage::url($driver->cnic_back)): asset('img/camera_icon.png') }}" alt="CNIC Back Image Upload" />
        </div>
        <label class="cursor-pointer mt-6">
          <span class="mt-2 leading-normal px-4 py-2 bg-blue-500 text-white text-sm rounded-full" >{{ __('CNIC Back Photo') }}</span>
          <input type="file" name="cnic_back_path" id="cnic_back_path" class="hidden image_path" :accept="accept" />
        </label>
    </div>
</div>

