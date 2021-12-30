<div class="flex items-center justify-center fixed left-0 bottom-0 w-full h-full bg-gray-800 bg-opacity-75 z-10 hidden" id="add-product-interests-modal">
    <div class="bg-white rounded-lg w-1/2 sm:w-3/4">
      <div class="flex flex-col items-start p-4">
        <div class="flex items-center w-full border-b-1">
          <div class="text-gray-900 font-medium text-lg" id="modal-title">Add Products of Interest</div>
          <svg class="close-modal ml-auto fill-current text-gray-700 w-6 h-6 cursor-pointer" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 18">
              <path d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z"/>
           </svg>
        </div>
        <hr>
        {{-- <div class="mt-6">Add Products of Interest</div> --}}
        <div class="w-full mt-6">
            <form id="add-product-interest-form" method="POST" action="{{route('product.interest.store')}}"  class="w-full">
                @csrf
                <div class='flex flex-wrap -mx-3 mb-6'>

                    <div class='w-full md:w-full px-3 mb-6'>
                        <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2' for='grid-text-1'>Requirement</label>
                        <input class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500' name="required_product" id='required_product' type='text' required>
                    </div>
                    <div class='w-full md:w-full px-3 mb-6'>
                        <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2' for='grid-text-1'>Frequency</label>
                        <select class="block appearance-none text-gray-600 w-full bg-white border border-gray-400 shadow-inner px-4 py-2 pr-8 rounded" name="frequency" id="frequency">
                            <option value="">Select Frequency</option>
                            <option value="immediate">Immediate</option>
                            <option value="after one month">After 1 Month</option>
                        </select>
                    </div>
                    <div class='w-full md:w-full px-3 mb-6'>
                        <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2' for='grid-text-1'>Quantity</label>
                        <input class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500' name="quantity" id='quantity' type='number' min="1" required>
                    </div>
                    <div class='w-full md:w-full px-3 mb-6'>
                        <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2' for='grid-text-1'>Quantity</label>
                        <select class="block appearance-none text-gray-600 w-full bg-white border border-gray-400 shadow-inner px-4 py-2 pr-8 rounded" name="quantity_unit" required>
                            <option value="">Select Unit</option>
                            @foreach ($quantity_units as $unit)
                                <option value="{{$unit}}">{{$unit}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>
        </div>
        <hr>

        <div class="ml-auto my-5">

            <button type="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" id="confirm-add-btn">
                Save
            </button>
            <button type="button" class="bg-transparent hover:bg-gray-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded close-modal">
                Cancel
            </button>
        </div>

      </div>
    </div>
  </div>
