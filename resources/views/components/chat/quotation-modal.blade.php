<div class="flex items-center justify-center fixed left-0 bottom-0 w-full h-full bg-gray-800 bg-opacity-75 z-10 modal hidden quotation_modal_main" id="quotation-modal" role="dialog">
    <div class="bg-white rounded-lg w-1/2 sm:w-3/4 overflow-y-scroll quotation_modal_white">
        <div class="flex flex-col items-start p-4">
            <div class="flex items-center w-full border-b-1">
                <div class="text-gray-900 font-medium text-lg" id="modal-title">{{ __('Send Quotation') }}
                </div>
                <svg class="close-modal ml-auto fill-current text-gray-700 w-6 h-6 cursor-pointer"
                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 18">
                    <path
                        d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z" />
                </svg>
            </div>
            <hr>

            <div class="alert alert-default w-full hidden" id="quotation_request_details"></div>

            <div class="w-full flex flex-row mt-4" id="quotation_details">
                <div class="w-64 p-6 bg-gray-100 overflow-y-auto">
                    <nav>
                        <div>
                            <div class="mt-3">
                                <a href="#" class="-mx-3  py-1 px-3 text-xs font-medium flex items-center justify-between hover:bg-gray-300 rounded-lg uppercase quotation_tab active" data-target="#products_info">
                                    <span class=" text-gray-900">{{__('Select Product')}}</span>
                                </a>
                            </div>
                            <div class="mt-3">
                                <a href="" class="-mx-3  py-1 px-3 text-xs font-medium flex items-center justify-between hover:bg-gray-300 rounded-lg uppercase quotation_tab" data-target="#terms_conditions">
                                    <span class=" text-gray-900">{{__('Terms and Conditions')}}</span>
                                </a>
                            </div>
                            <div class="mt-3">
                                <a href="#" class="-mx-3  py-1 px-3 text-xs font-medium flex items-center justify-between hover:bg-gray-300 rounded-lg uppercase quotation_tab" data-target="#verify_details">
                                    <span class=" text-gray-900">{{__('Verify Details')}}</span>
                                </a>
                            </div>
                            <div class="mt-3">
                                <a href="#" class="-mx-3  py-1 px-3 text-xs font-medium flex items-center justify-between hover:bg-gray-300 rounded-lg uppercase quotation_tab" data-target="#generate_pdf">
                                    <span class=" text-gray-900">{{__('Generate PDF')}}</span>
                                </a>
                            </div>
                        </div>

                    </nav>
                </div>
                <div class="px-4 w-full" id="quotation_form_div">
                    <input type="hidden" id="product_counter" value="1">
                    <form id="quotation_form" method="POST" action={{route('quotation.create-pdf')}}>
                        @csrf
                        <input type="hidden" name="buyer_id" id="buyer_id">
                        <input type="hidden" name="post_buy_req_id" id="post_buy_req_id">
                        @include('components.quotation.product-details')
                        @include('components.quotation.terms-and-conditions')
                        @include('components.quotation.verify-details')
                        @include('components.quotation.generate-pdf')
                        <div class="flex p-2 mt-4">
                            <div class="flex-auto flex flex-row-reverse">
                                <button id="next_step" class="text-base  ml-2  hover:scale-110 focus:outline-none flex justify-center px-4 py-2 rounded font-bold cursor-pointer
                                    hover:bg-teal-600
                                    bg-teal-600
                                    text-teal-100
                                    border duration-200 ease-in-out
                                    border-teal-600 transition">
                                    Next
                                </button>
                                <button id="previous_step" class="text-base hover:scale-110 focus:outline-none flex justify-center px-4 py-2 rounded font-bold cursor-pointer
                                    hover:bg-teal-200
                                    bg-teal-100
                                    text-teal-700
                                    border duration-200 ease-in-out
                                    border-teal-600 transition hidden">
                                    Previous
                                </button>

                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
