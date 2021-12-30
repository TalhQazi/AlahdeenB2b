<div class="flex items-center justify-center fixed left-0 bottom-0 w-full h-full bg-gray-800 bg-opacity-75 z-10 hidden modal" id="catalog-modal">
    <div class="bg-white rounded-lg w-1/2 sm:w-3/4">
      <div class="flex flex-col items-start p-4">
        <div class="flex items-center w-full border-b-1">
            <div class="text-gray-900 font-medium text-lg" id="modal-title">{{ __('Catalog') }}</div>
            <svg class="close-modal ml-auto fill-current text-gray-700 w-6 h-6 cursor-pointer" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 18">
                <path d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z"/>
            </svg>
        </div>
        <hr>

        <div class="w-full mt-6">
            <form id="catalog_form" method="POST" class="w-full">
                @csrf
                <div class='flex flex-wrap -mx-3 mb-6'>
                    <div class='w-full md:w-full px-3 mb-6'>
                        <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2' for='grid-text-1'>{{__('Select Catalog')}}</label>
                        <select class="block appearance-none text-gray-600 w-full bg-white border border-gray-400 shadow-inner px-4 py-2 pr-8 rounded" name="catalog_path" id="catalog_path">
                            <option value="">{{__('Select Catalog')}}</option>
                            @foreach ($catalogs as $catalog)
                                <option value="{{$catalog->path}}">{{$catalog->title}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class='w-full md:w-full px-3 mb-6'>
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="message">
                            {{ __('Message') }}
                        </label>
                        <input class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none focus:border-gray-500" name="message" id="message" type="text">
                    </div>
                </div>
                <div class="my-5 float-right">

                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" id="send_attachement_btn">
                        {{__('Send')}} <i class="fa fa-paper-plane ml-1"></i>
                    </button>
                </div>

            </form>
        </div>
        <hr>


      </div>
    </div>
  </div>
