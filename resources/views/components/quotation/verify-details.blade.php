<div class="form_step hidden" id="verify_details">
    <div class="grid grid-rows-none gap-4 alert alert-light alert-close mb-5">
        <h2 class="text-lg text-black font-extrabold">{{__('Your Details')}}</h2>
        <div class="col-span-12">
            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="Primary Email">
                {{ __('Primary Email') }}
            </label>
            <input
                class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500"
                name="primary_email" id="primary_email" type="email" value="{{Auth::user()->email}}" readonly>
        </div>
        <div class="col-span-12">
            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="Alternate Email">
                {{ __('Alternate Email') }}
            </label>
            <input
                class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500"
                name="alternate_email" id='alternate_email' type='email'>
        </div>
        <div class="col-span-12">
            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="phone number">
                {{ __('Phone Number') }}
            </label>
            <input
                class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500"
                name="phone" id="phone" type="tel" value="{{Auth::user()->phone}}">
        </div>
        <div class="col-span-12 mt-3">
            <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'
                for='grid-text-1'>{{ __('Address') }}</label>
            <textarea name="address" id="address" cols="30" rows="3"
                class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500'>{{Auth::user()->address}}</textarea>
        </div>

        <div class="col-span-12 mt-3">
            {{__('Note: The following details will be displayed in the quotation')}}
        </div>

    </div>

</div>
