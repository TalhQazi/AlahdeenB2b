<div class="form_step hidden" id="terms_conditions">
    <div class="grid grid-rows-none gap-4 alert alert-light alert-close mb-5">

        <h2 class="text-lg text-black font-extrabold">{{__('Terms and Conditions')}}</h2>

            <div class="col-span-12">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="shipping tax">
                    {{ __('Discount') }}
                </label>
                <input class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500" name="discount" id='discount' type='text'>
            </div>
            <div class="col-span-12">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="applicable tax">
                    {{ __('Applicable Taxes') }}
                </label>
                <input class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500" name="applicable_tax" id='applicable_tax' type='text'>
            </div>
            <div class="col-span-12">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="shipping tax">
                    {{ __('Shipping Taxes') }}
                </label>
                <input class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500" name="shipping_tax" id='shipping_tax' type='text'>
            </div>

            <div class="col-span-12">
                <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2' for='grid-text-1'>{{ __('Delivery Period') }}</label>
                <input class='appearance-none bg-white text-gray-700 border px-4 w-64 border-gray-400 shadow-inner rounded-md py-3 leading-tight focus:outline-none  focus:border-gray-500' name="period" id="period" type="text">
                <div class="flex-shrink w-1/2 inline-block relative">
                    <select class="block appearance-none text-gray-700 w-full bg-white border border-gray-400 shadow-inner px-4 py-3 pr-8 rounded leading-tight" name="period_unit" id="period_unit">
                        <option value="days">{{ __('Days') }}</option>
                        <option value="weeks">{{ __('Weeks') }}</option>
                        <option value="months">{{ __('Months') }}</option>
                    </select>
                    <div class="pointer-events-none absolute top-0 mt-3  right-0 flex items-center px-2 text-gray-600">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                    </div>
                </div>
            </div>

            <div class="col-span-12">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="shipping tax">
                    {{ __('Payment Terms') }}
                </label>
                <input class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500" name="payment_terms" id='payment_terms' type='text'>
            </div>
            <div class="col-span-12 mt-3">
                <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2' for='grid-text-1'>{{__('Additional Information')}}</label>
                <textarea name="additional_info" id="additional_info" cols="30" rows="3" class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500'></textarea>
            </div>

    </div>

</div>
