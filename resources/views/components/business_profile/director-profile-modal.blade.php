<div class="flex items-center justify-center fixed left-0 bottom-0 w-full h-full bg-gray-800 bg-opacity-75 z-10 modal hidden" id="business-director-modal">
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
            <form id="director_profile_form" method="POST" enctype="multipart/form-data" class="w-full">
                @csrf
                <div class='flex flex-wrap -mx-3 mb-6'>
                    <div class="px-3 py-3 center mx-auto">
                        <div class="bg-white px-4 py-5 rounded-lg shadow-lg text-center w-48">
                          <div class="mb-4">
                            <img class="w-auto mx-auto object-cover object-center h-40" id="director_photo_preview" src="{{asset('img/camera_icon.png')}}"" />
                          </div>
                          <label class="cursor-pointer mt-6">
                            <span class="mt-2 text-base leading-normal px-4 py-2 bg-blue-500 text-white text-sm rounded-full" >{{ __('Director Photo') }}</span>
                            <input type="file" name="director_photo" id="director_photo" class="hidden" :accept="accept" />
                          </label>
                        </div>
                    </div>
                    <div class="w-full md:w-full px-3 mb-6">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2' for='grid-text-1">{{__('Name')}}</label>
                        <input class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500" name="name" id="name" type="text">
                    </div>
                    <div class="w-full md:w-full px-3 mb-6">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2' for='grid-text-1">{{__('Designation')}}</label>
                        <input class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500" name="designation" id="designation" type="text">
                    </div>
                    <div class='w-full md:w-full px-3 mb-6'>
                        <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2' for='grid-text-1'>{{__('Description')}}</label>
                        <textarea name="description" id="description" cols="30" rows="10" class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none focus:border-gray-500 valid"></textarea>
                    </div>
                </div>
                <div class="my-5 float-right">

                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" id="confirm-add-btn">
                        {{ __('Save') }}
                    </button>
                    <button type="button" class="bg-transparent hover:bg-gray-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded close-modal">
                        {{ __('Cancel') }}
                    </button>
                </div>

            </form>
        </div>
        <hr>


      </div>
    </div>
  </div>
