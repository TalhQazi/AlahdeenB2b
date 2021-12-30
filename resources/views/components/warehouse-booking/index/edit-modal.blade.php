<div class="flex items-center justify-center fixed left-0 bottom-0 w-full h-full bg-gray-800 bg-opacity-75 z-10 hidden modal" id="edit_booking_modal">
    <div class="bg-white rounded-lg w-1/2 sm:w-3/4" style="height: 500px; overflow-y:scroll;">
      <div class="flex flex-col items-start p-4">
        <div class="flex items-center w-full border-b-1">
            <div class="text-gray-900 font-medium text-lg" id="modal-title">{{ __('Booking Details') }}</div>
            <svg class="close-modal ml-auto fill-current text-gray-700 w-6 h-6 cursor-pointer" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 18">
                <path d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z"/>
            </svg>
        </div>
        <hr>

        <div class="w-full mt-6">
            <form id="booking_form" method="POST" class="w-full">
                @csrf
                @method('PUT')
                <div class='flex flex-wrap -mx-3 mb-6'>
                    <div class='w-full md:w-full px-3 mb-6'>
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="title">
                            {{ __('Item') }}
                            <i class="fa fa-asterisk text-red-500"></i>
                        </label>
                        <input class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none focus:border-gray-500" name="item" id="item" type="text">
                    </div>
                    <div class='w-full md:w-full px-3 mb-6'>
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="description">
                            {{__('Details')}}
                            <i class="fa fa-asterisk text-red-500"></i>
                        </label>
                        <textarea name="description" id="description" cols="30" rows="10" class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500'></textarea>
                    </div>
                    <div class='w-full md:w-full px-3 mb-6'>
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="start date">
                            {{ __('Start Date') }}
                            <i class="fa fa-asterisk text-red-500"></i>
                        </label>
                        <input class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500" name="start_date" id="start_date" type="text">
                    </div>
                    <div class='w-full md:w-full px-3 mb-6'>
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="start time">
                            {{ __('Start Time') }}
                            <i class="fa fa-asterisk text-red-500"></i>
                        </label>
                        <input class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500" name="start_time" id="start_time" type="text">
                    </div>
                    <div class='w-full md:w-full px-3 mb-6'>
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="end date">
                            {{ __('End Date') }}
                            <i class="fa fa-asterisk text-red-500"></i>
                        </label>
                        <input class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500" name="end_date" id="end_date" type="text">
                    </div>
                    <div class='w-full md:w-full px-3 mb-6'>
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="start time">
                            {{ __('End Time') }}
                            <i class="fa fa-asterisk text-red-500"></i>
                        </label>
                        <input class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500" name="end_time" id="end_time" type="text">
                    </div>
                    <div class='w-full md:w-full px-3 mb-6'>
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="quantity">
                            {{ __('Quantity') }}
                        </label>
                        <input class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none focus:border-gray-500" name="quantity" id="quantity" type="text">
                    </div>
                    <div class='w-full md:w-full px-3 mb-6'>
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="unit">
                            {{ __('Unit') }}
                        </label>
                        <select class="block appearance-none text-gray-600 w-full bg-white border border-gray-400 shadow-inner px-4 py-2 pr-8 rounded" name="unit" id="unit">
                            <option value="">{{__('Quantity')}}</option>
                            @foreach ($quantity_units as $unit)
                                <option value="{{$unit}}">{{$unit}}</option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute top-0 mt-3  right-0 flex items-center px-2 text-gray-600">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                        </div>
                    </div>
                    <div class='w-full md:w-full px-3 mb-6'>
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="goods value">
                            {{ __('Goods Value') }}
                        </label>
                        <input class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none focus:border-gray-500" name="goods_value" id="goods_value" type="text">
                    </div>
                    <div class='w-full md:w-full px-3 mb-6'>
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="booking type">
                            {{ __('Booking Type') }}
                        </label>
                        <select class="block appearance-none text-gray-600 w-full bg-white border border-gray-400 shadow-inner px-4 py-2 pr-8 rounded" name="type" id="type">
                            <option value="fully">{{__('Fully')}}</option>
                            <option value="partial">{{__('Partial')}}</option>
                        </select>
                        <div class="pointer-events-none absolute top-0 mt-3  right-0 flex items-center px-2 text-gray-600">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                        </div>
                    </div>
                    <div class='w-full md:w-full px-3 mb-6 area hidden'>
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="area">
                            {{ __('Area Required') }}
                        </label>
                        <input class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none focus:border-gray-500" name="area" id="area" type="text">
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
