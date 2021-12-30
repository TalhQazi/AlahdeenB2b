<div class="flex items-center justify-center fixed left-0 bottom-0 w-full h-full bg-gray-800 bg-opacity-75 z-10 modal hidden"
    id="request-quotation-detail-modal">
    <div class="bg-white rounded-lg w-1/2 sm:w-3/4 overflow-y-scroll">
        <div class="flex flex-col items-start p-4">
            <div class="flex items-center w-full border-b-1">
                <div class="text-gray-900 font-medium text-lg" id="modal-title">{{ __('Quotation Request Details') }}
                </div>
                <svg class="close-modal ml-auto fill-current text-gray-700 w-6 h-6 cursor-pointer"
                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 18">
                    <path
                        d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z" />
                </svg>
            </div>
            <hr>

            <div class="w-full flex flex-col mt-4">
                <div class="alert alert-default w-full" id="quotation_request_details"></div>

                <div class="my-5 flex justify-end">

                    <button type="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                        id="create_quote_ref_btn">
                        {{ __('Create Quotation') }} <i class="fa fa-paper-plane ml-1"></i>
                    </button>
                </div>



            </div>
        </div>
    </div>
</div>
