<div class="card col-span-2 xl:col-span-1 margion mt-5">
    <div class="bg-white rounded-md w-full">
        <div class="pt-2 font-mono">
            <div class="container mx-auto">
                <div class="inputs w-full p-6">
                    <h2 class="text-2xl text-gray-900">{{ __('Request for Purchase Return') }}</h2>
                    <form id="add_catalog_form" class="mt-6 border-t border-gray-400 pt-4" enctype="multipart/form-data"
                        method="POST" action="{{ route('khata.purchase_return.store') }}">
                        @csrf
                        <input type="hidden" name="product_id" id="product_id" />
                        <div class='flex flex-wrap -mx-3 mb-6'>
                            <div class="grid md:grid-cols-1 grid-cols-2 w-full">
                                <div class='w-full md:w-full px-3 mb-6'>
                                    <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'
                                        for='product_code'>{{ __('Product Code') }}</label>
                                    <input
                                        class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500'
                                        name="product_code" id='product_code' type='text'
                                        value="{{ $purchase_return->product_code ?? old('product_code') }}">
                                </div>

                                <div class='w-full md:w-full px-3 mb-6'>
                                    <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'
                                        for='product_name'>{{ __('Product Name') }}</label>
                                    <input
                                        class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500 keywords'
                                        name="product_name" id='product_name' type="text"
                                        data-target-div='#product_name_div'
                                        value="{{ $purchase_return->product_name ?? old('product_name') }}">
                                </div>
                            </div>

                            <div class="grid md:grid-cols-1 grid-cols-2 w-full">
                                <div class='w-full md:w-full px-3 mb-6'>
                                    <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'
                                        for='return_quantity'>{{ __('Return Quantity') }}</label>
                                    <input
                                        class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500'
                                        name="return_quantity" id='return_quantity' type='number'
                                        value="{{ $purchase_return->return_quantity ?? old('return_quantity') }}">
                                </div>

                                <div class='w-full md:w-full px-3 mb-6'>
                                    <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'
                                        for='return_amount'>{{ __('Return Amount') }}</label>
                                    <input
                                        class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500'
                                        name="return_amount" id='return_amount' type="number"
                                        value="{{ $purchase_return->return_amount ?? old('return_amount') }}">
                                </div>
                            </div>

                            <div class="grid md:grid-cols-1 grid-cols-2 w-full">
                                <div class='w-full md:w-full px-3 mb-6'>
                                    <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'
                                        for='purchase_order_no'>{{ __('Purchase Order #') }}</label>
                                    <input
                                        class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500'
                                        name="purchase_order_no" id='purchase_order_no' type='text'
                                        value="{{ $purchase_return->purchase_order_no ?? old('purchase_order_no') }}">
                                </div>

                                <div class='w-full md:w-full px-3 mb-6'>
                                    <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'
                                        for='invoice_no'>{{ __('Invoice #') }}</label>
                                    <input
                                        class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500'
                                        name="invoice_no" id='invoice_no' type="text"
                                        value="{{ $purchase_return->invoice_no ?? old('invoice_no') }}">
                                </div>
                            </div>

                            <div class="grid md:grid-cols-1 grid-cols-2 w-full">
                                <div class='w-full md:w-full px-3 mb-6'>
                                    <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'
                                        for='comments'>{{ __('Add Comment') }}</label>
                                    <textarea rows="5"
                                        class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500'
                                        name="comments"
                                        id='comments'>{{ $purchase_return->comments ?? old('comments') }}</textarea>
                                </div>

                                <div class='w-full md:w-full px-3 mb-6'>
                                    <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'
                                        for='is_return_product'>{{ __('Return Product') }} <small>Check if you
                                            want
                                            to return the product</small> </label>
                                    <input style="height: 20px; width: 20px" name="is_return_product"
                                        id='is_return_product' type="checkbox"
                                        onclick="$(this).val(this.checked ? 1 : 0)" >
                                </div>
                            </div>

                            <div class="personal w-full border-t border-gray-400 pt-4">
                                <div class="flex justify-end">
                                    <button
                                        class="border border-gray-700 bg-gray-700 text-white rounded-md px-4 py-2 m-2 transition duration-500 ease select-none hover:bg-gray-800 focus:outline-none focus:shadow-outline"
                                        type="submit">{{ __('Save Purchase Return') }}</button>
                                </div>
                            </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
