n<div class="flex items-center justify-center fixed left-0 bottom-0 w-full h-full bg-gray-800 bg-opacity-75 z-10 modal hidden" id="business-contact-modal">
    <div class="bg-white rounded-lg w-1/2 sm:w-3/4" style="height: 500px; overflow-y:scroll;">
      <div class="flex flex-col items-start p-4">
        <div class="flex items-center w-full border-b-1">
            <div class="text-gray-900 font-medium text-lg" id="modal-title">{{ __('Additional Contact Details') }}</div>
            <svg class="close-modal ml-auto fill-current text-gray-700 w-6 h-6 cursor-pointer" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 18">
                <path d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z"/>
            </svg>
        </div>
        <hr>

        <div class="w-full mt-6">
            <form id="business-contact-form" method="POST" action="{{route('profile.business.contact.store')}}"  class="w-full">
                @csrf
                <div class='flex flex-wrap -mx-3 mb-6'>
                    <input type="hidden" name="business_id" id="business_id" value="{{$business_id}}">
                    <input type="hidden" name="contact_id" id="contact_id" value="">
                    <div class='w-full md:w-full px-3 mb-6'>
                        <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2' for='grid-text-1'>{{ __('Division') }}
                            <i class="fa fa-asterisk text-red-500"></i>
                        </label>
                        <select class="block appearance-none text-gray-600 w-full bg-white border border-gray-400 shadow-inner px-4 py-2 pr-8 rounded" name="division" id="division" required>
                            <option value="">Select Division</option>
                            @foreach ($division_types as $division)
                            <option value="{{ $division }}">{{ $division }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class='w-full md:w-full px-3 mb-6'>
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="contact person">
                            {{ __('Contact Person') }}
                            <i class="fa fa-asterisk text-red-500"></i>
                        </label>
                        <input class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none focus:border-gray-500" name="contact_person" id="contact_person" type="text" required>
                    </div>
                    <div class='w-full md:w-full px-3 mb-6'>
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="designation">
                            {{ __('Designation') }}
                            <i class="fa fa-asterisk text-red-500"></i>
                        </label>
                        <input class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500" name="designation" id="designation" type='text' required>
                    </div>
                    <div class='w-full md:w-full px-3 mb-6'>
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="designation">
                            {{ __('Location') }}
                            <i class="fa fa-asterisk text-red-500"></i>
                        </label>
                        <input class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500" name="location" id="location" type='text' required>
                    </div>
                    <div class='w-full md:w-full px-3 mb-6'>
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="designation">{{ __('Locality') }}</label>
                        <input class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500" name="locality" id="locality" type='text'>
                    </div>
                    <div class='w-full md:w-full px-3 mb-6'>
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="designation">
                            {{ __('Postal/Zip Code') }}
                            <i class="fa fa-asterisk text-red-500"></i>
                        </label>
                        <input class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500" name="postal_code" id="postal_code" type='text' required>
                    </div>
                    <div class='w-full md:w-full px-3 mb-6'>
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="designation">{{ __('Address') }}</label>
                        <input class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500" name="address" id="address" type='text'>
                    </div>
                    <div class='w-full md:w-full px-3 mb-6'>
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="designation">
                            {{ __('Mobile/Cell Phone') }}
                            <i class="fa fa-asterisk text-red-500"></i>
                        </label>
                        <input class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500" name="cell_no" id="cell_no" type="text" required>
                    </div>
                    <div class='w-full md:w-full px-3 mb-6'>
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="designation">{{ __('Telephone') }}</label>
                        <input class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500" name="telephone_no" id="telephone_no" type="text">
                    </div>
                    <div class='w-full md:w-full px-3 mb-6'>
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="designation">{{ __('Email') }}</label>
                        <input class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500" name="email" id="email" type="email">
                    </div>
                    <div class='w-full md:w-full px-3 mb-6'>
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="designation">{{ __('Toll Free No') }}</label>
                        <input class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500" name="toll_free_no" id="toll_free_no" type="text">
                    </div>
                </div>
                <div class="my-5 float-right">

                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" id="confirm-add-btn">
                        Save
                    </button>
                    <button type="button" class="bg-transparent hover:bg-gray-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded close-modal">
                        Cancel
                    </button>
                </div>

            </form>
        </div>
        <hr>


      </div>
    </div>
  </div>
