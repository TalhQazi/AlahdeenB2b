<div id="about" class="step-div" data-title="{{ __('About') }}">
    <div class="bg-white px-4 py-5 rounded-lg shadow-lg text-center w-48 mb-6 image_div" id="image_div">
        <div class="mb-4">
            <img class="w-auto mx-auto object-cover object-center" id="image_path_preview" src="{{ !empty($driver) ? asset(str_replace('/storage/','',$driver->image)) : asset('img/camera_icon.png')}}" alt="Driver Image Upload" />
        </div>
        <label class="cursor-pointer mt-6">
          <span class="mt-2 leading-normal px-4 py-2 bg-blue-500 text-white text-sm rounded-full" >{{ __('Add Photo') }}</span>
          <input type="file" name="image_path" id="image_path" class="hidden image_path" :accept="accept" />
        </label>
    </div>
    <div class="w-full md:w-full mb-6">
        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="name">{{ __('Name') }}</label>
        <input class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500' name="name" id="name" type="text" value="{{ Auth::user()->name }}" readonly>
    </div>
    <div class="w-full md:w-full mb-6">
        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="date-of-birthd">{{ __('Date of birth') }}</label>
        <input class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500' name="dob" id="dob" type="text" value="{{ old('dob') ?? !empty($driver) ? $driver->dob : '' }}">
    </div>
    <div class="w-full md:w-full mb-6">
        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="name">{{ __('Email') }}</label>
        <input class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500' name="email" id="email" type="text" value="{{ Auth::user()->email }}" readonly>
    </div>
</div>
