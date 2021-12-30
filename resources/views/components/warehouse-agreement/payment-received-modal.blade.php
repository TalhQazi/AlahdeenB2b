<div class="flex items-center justify-center fixed left-0 bottom-0 w-full h-full bg-gray-800 bg-opacity-75 z-10 hidden modal" id="payment-received-modal">
    <div class="bg-white rounded-lg w-1/2 sm:w-3/4" style="height: 600px; overflow-y:scroll;">
      <div class="flex flex-col items-start p-4">
        <div class="flex items-center w-full border-b-1">
          <div class="text-gray-900 font-medium text-lg" id="modal-title">{{ __('Payment Received/Pay') }}</div>
          <svg class="close-modal ml-auto fill-current text-gray-700 w-6 h-6 cursor-pointer" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 18">
              <path d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z"/>
           </svg>
        </div>
        <hr>
        <div class="w-full mt-6">
            <form id="payment_form" enctype="multipart/form-data" method="POST" class="w-full">
                @csrf
                @method('put')
                <div class='-mx-3 mb-6'>
                    <div class="px-3 py-3 center mx-auto">
                        <div class="bg-white px-4 py-5 rounded-lg shadow-lg text-center w-48">
                            <div class="mb-4">
                                <img class="w-auto mx-auto object-cover object-center" id="ref_image_preview" src="{{asset('img/camera_icon.png')}}" alt="User Image Upload" />
                                <i class="fa fa-times text-red-500 relative hidden remove_image" style="top: -180px; left: 88px;"></i>
                            </div>
                            <label class="cursor-pointer mt-6">
                                <span class="mt-2 text-base leading-normal px-4 py-2 bg-blue-500 text-white text-sm rounded-full" >{{ __('Reference Image') }}</span>
                                <input type='file' name="ref_image" id="ref_image" class="hidden photo" :accept="accept" />
                            </label>
                        </div>
                    </div>
                    <div class='w-full md:w-full px-3 my-6'>
                        <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2' for='grid-text-1'>{{__('Description')}}</label>
                        <textarea id="ref_text" name="ref_text" cols="30" rows="10" class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500"></textarea>
                    </div>
                    <div class='w-full md:w-full px-3 mb-6'>
                        <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2' for='amount'>{{__('Transaction Date')}}</label>
                        <input class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500' name='transaction_date' id='transaction_date' type='text'>
                    </div>
                    <div class='w-full md:w-full px-3 mb-6'>
                        <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2' for='grid-text-1'>{{__('Select Payment Method')}}</label>
                        <select class="block appearance-none text-gray-600 w-full bg-white border border-gray-400 shadow-inner px-4 py-2 pr-8 rounded" name="payment_method_id" id="payment_method_id">
                            <option value="">{{__('Select Payment Method')}}</option>
                            @foreach ($payment_methods as $method)
                            <option value="{{$method->id}}">{{$method->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class='w-full md:w-full px-3 mb-6'>
                        <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2' for='grid-text-1'>{{__('Select Bank Account')}}</label>
                        <select class="block appearance-none text-gray-600 w-full bg-white border border-gray-400 shadow-inner px-4 py-2 pr-8 rounded" name="bank_account_id" id="bank_account_id">
                            <option value="">{{__('Select Bank Account')}}</option>
                            <option value="1">Habib Bank Limited</option>
                            <option value="2">Standard Chartered</option>
                        </select>
                    </div>
                    <div class='w-full md:w-full px-3 mb-6'>
                        <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2' for='grid-text-1'>{{__('Select Payment Status')}}</label>
                        <select class="block appearance-none text-gray-600 w-full bg-white border border-gray-400 shadow-inner px-4 py-2 pr-8 rounded" name="status" id="status">
                            <option value="">{{__('Select Payment Status')}}</option>
                            @foreach ($payment_statuses as $status)
                            <option value="{{$status}}">{{$status}}</option>
                            @endforeach
                        </select>
                    </div>
                    <?php

                    ?>
                    <div class='w-full md:w-full px-3 mb-6 hidden owner_paid'>
                        <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2' for='commission percentage'>{{__('Commission Percentage')}}</label>
                        <input class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500' name='commission_percentage' id='commission_percentage' type='text' disabled>
                    </div>
                    <div class='w-full md:w-full px-3 mb-6 hidden owner_paid'>
                        <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2' for='commission collected'>{{__('Commission Collected')}}</label>
                        <input class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500' name='commission_paid' id='commission_paid' type='text' disabled>
                    </div>
                    <div class='w-full md:w-full px-3 mb-6 hidden owner_paid'>
                        <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2' for='tax percentage'>{{__('Tax Percentage')}}</label>
                        <input class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500' name='tax_percentage' id='tax_percentage' type='text' disabled>
                    </div>
                    <div class='w-full md:w-full px-3 mb-6 hidden owner_paid'>
                        <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2' for='tax amount'>{{__('Tax Amount')}}</label>
                        <input class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500' name='tax_amount' id='tax_amount' type='text' disabled>
                    </div>
                    <div class='w-full md:w-full px-3 mb-6 hidden owner_paid'>
                        <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2' for='commission collected'>{{__('Total Amount Paid to Warehouse Owner')}}</label>
                        <input class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500' name='total_paid_to_owner' id='total_paid_to_owner' type='text' disabled>
                    </div>
                    <div class='w-full md:w-full px-3 mb-6 hidden' id='amount_div'>
                        <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2' for='amount'>{{__('Booking Price')}}</label>
                        <input class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500' name='amount' id='amount' type='text'>
                    </div>
                    <div class='w-full md:w-full px-3 mb-6 hidden' id='cancelled_div'>
                        <label class='uppercase tracking-wide text-gray-700 text-xs font-bold mb-2' for='closed'>{{__('Cancelled and closed')}}</label>
                        <input class='bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md focus:outline-none  focus:border-gray-500' name='close_invoice' id='close_invoice' type='checkbox'>
                    </div>
                </div>
            </form>
        </div>
        <hr>

        <div class="ml-auto my-5">

            <button type="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" id="update_status_btn">
                Save
            </button>
            <button type="button" class="bg-transparent hover:bg-gray-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded close-modal">
                Cancel
            </button>
        </div>

      </div>
    </div>
  </div>
