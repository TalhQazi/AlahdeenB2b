<div class="flex items-center justify-center fixed left-0 bottom-0 w-full h-full bg-gray-800 bg-opacity-75 z-10 modal hidden" id="request-quotation-modal">
    <div class="bg-white rounded-lg w-1/2 sm:w-3/4 overflow-y-scroll" style="height: 500px;">
        <div class="flex flex-col items-start p-4">
            <div class="flex items-center w-full border-b-1">
                <div class="text-gray-900 font-medium text-lg" id="modal-title">{{ __('Request Quotation') }}
                </div>
                <svg class="close-modal ml-auto fill-current text-gray-700 w-6 h-6 cursor-pointer"
                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 18">
                    <path
                        d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z" />
                </svg>
            </div>
            <hr>

            <div class="w-full flex flex-col mt-4">
                <div class="w-full bg-gray-100">
                    <input type="text" class="appearance-none block bg-white text-gray-700 border px-4 w-full border-gray-400 shadow-inner rounded-md py-3 leading-tight focus:outline-none  focus:border-gray-500" id="products_search_bar" placeholder="{{__('Search For Products Offered By This Seller')}}">
                </div>
                <div class="mt-5 w-full" id="quotation_form_div">
                    <form id="request_quotation_form" method="POST">
                        <input type="hidden" name="req_seller_id" id="req_seller_id">
                        <input type="hidden" name="req_conversation_id" id="req_conversation_id">
                        <div id="seller_products">

                        </div>
                        <div class="my-5 float-right">

                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" id="req_quote_btn">
                                {{__('Request Quotation')}} <i class="fa fa-paper-plane ml-1"></i>
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
