<x-popup-wrapper>
    @section('popup-id', 'promotional_product')
    @section('popup-title', __('Add Promotion'))
    @section('popup-body')
        <form action="{{ route('promotions.product.store') }}" id="promotion_form" method="POST">
            @csrf
            <input type="hidden" name="promotion_against_id" id="promotion_against_id">
            <div class="col-span-1 row-span-6 mt-6">
                <div class="w-full md:w-full px-3 mb-6">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="add product promotion">{{ __('Add Product Promotion') }}</label>
                    <input class="appearance-none inline w-96 bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500" name="promotional_product" id="promotional_product"
                        type="text">
                    <input name="promotional_product_id" id="promotional_product_id" type="hidden">
                    <input class="appearance-none inline w-96 bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500" name="promotional_product_code" id="promotional_product_code" type="text" placeholder="{{ __('Product Code') }}" readonly>
                </div>
                <div class="w-full md:w-full px-3 mb-6">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="promotional-discount">{{ __('Price Per Unit') }}</label>
                    <input class="appearance-none inline w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500" name="promotional_product_price" id="promotional_product_price"
                        type="text" readonly>
                </div>
                <div class="w-full md:w-full px-3 mb-6">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="promotional-discount">{{ __('Promotional discount') }}</label>
                    <input class="appearance-none inline w-96 bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500" name="promotional_discount_percentage" id="promotional_discount_percentage"
                        type="text" placeholder="{{ __('Discount In Percentage') }}">
                    <input class="appearance-none inline w-96 bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500" name="promotional_discount_value" id="promotional_discount_value" type="text"
                        placeholder="{{ __('Discount Value') }}"
                    >
                </div>
                <div class="w-full md:w-full px-3 mb-6">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="by-date">{{ __('By Date') }}</label>
                    <input class="inline bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500" name="by_date" id="by_date" type="checkbox">
                    <div class="inline ml-1 by_date_fields hidden">

                        <input class="appearance-none inline w-72 bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500" name="start_date" id="start_date" type="text"
                            placeholder="{{ __('Start Date') }}"
                        >
                        <input class="appearance-none inline w-72 bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500" name="end_date" id="end_date" type="text"
                            placeholder="{{ __('End Date') }}"
                        >
                    </div>
                </div>
                <div class="w-full md:w-full px-3 mb-6">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="by-date">{{ __('by number of products') }}</label>
                    <input class="inline bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500" name="by_no_of_units" id="by_no_of_units" type="checkbox">
                    <div class="inline ml-1 by_no_of_units_field hidden">

                        <input class="appearance-none inline w-96 bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500" name="no_of_units" id="no_of_units" type="text"
                            placeholder="{{ __('No of Products') }}"
                        >

                    </div>
                </div>
                <div class="w-full md:w-full px-3 mb-6">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="add product promotion">{{ __('Promotion Description') }}</label>
                    <textarea
                        name="promotion_description" id="promotion_description"
                        cols="30" rows="5"
                        class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner
                        rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500"
                        placeholder="{{ __('Add relevant product promotion description') }}"
                    >{{ old('promotion_description') }}</textarea>
                </div>
            </div>
        </form>
    @endsection
</x-popup-wrapper>
