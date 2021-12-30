<div class="form_step" id="products_info">
    <div class="grid grid-rows-3 grid-flow-col gap-4 alert alert-light alert-close mb-5" id="quotation_div_0">

        <div class="bg-white px-4 py-5 rounded-lg shadow-lg text-center w-48 row-span-3 col-span-3 img_div">
            <div class="mb-4">
                <img class="w-auto mx-auto object-cover object-center" id="q_image_0_preview" src="{{asset('img/camera_icon.png')}}" alt="Upload File" />
                <i class="fa fa-times text-red-500 relative hidden remove_image" style="top: -180px; left: 88px;"></i>
            </div>
            <label class="cursor-pointer mt-6">
                <span class="mt-2 text-base leading-normal px-4 py-2 bg-blue-500 text-white rounded-full" >{{ __('Attachment') }}</span>
                <input type="hidden" name="q_image_path[]" id="q_image_path_0">
                <input type='file' name="q_image[]" id="q_image_0" class="hidden photo" accept="image/x-png,image/jpeg" />
            </label>
        </div>
        <div class="row-span-3 col-span-9">

            <div class="col-span-12">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="product">
                    {{ __('Product') }}
                </label>
                <input class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500 product_autocomplete" name="product[]" id='product_0' type='text'>
            </div>
            <div class="grid grid-cols-12 gap-2 mt-3">
                <div class="col-span-12">

                    <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2' for='grid-text-1'>{{ __('Quantity') }}</label>
                    <input class='appearance-none block bg-white text-gray-700 border px-4 w-32 border-gray-400 shadow-inner rounded-md py-3 leading-tight focus:outline-none  focus:border-gray-500' name="quantity[]" id="quantity_0" type="text">
                </div>
                <div class="col-span-12">
                    <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2' for='grid-text-1'>{{ __('Price') }}</label>
                    <input class='appearance-none block bg-white text-gray-700 border px-4 w-32 border-gray-400 shadow-inner rounded-md py-3 leading-tight focus:outline-none  focus:border-gray-500' name="price[]" id="price_0" type="text">
                </div>
                <div class="col-span-12">

                    <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'>{{ __('Unit') }}</label>
                    <div class="flex-shrink w-full inline-block relative">
                        <select class="block appearance-none text-gray-700 w-full bg-white border border-gray-400 shadow-inner px-4 py-3 pr-8 rounded leading-tight" name="unit[]" id="unit_0">
                            <option value="">{{ __('/ Unit') }}</option>
                            @foreach ($quantity_units as $unit)
                                <option value="{{$unit}}">{{$unit}}</option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute top-0 mt-3  right-0 flex items-center px-2 text-gray-600">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-span-12 mt-3">
                <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2' for='grid-text-1'>{{__('Product Description')}}</label>
                <textarea name="description[]" id="description_0" cols="30" rows="3" class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500'></textarea>
            </div>
        </div>
    </div>



<div class="flex p-2 mt-4" id="add_more_div">
    <button id="add_more_products" class="text-base  ml-2  hover:scale-110 focus:outline-none flex justify-center px-4 py-2 rounded font-bold cursor-pointer
            hover:bg-teal-600
            bg-teal-600
            text-teal-100
            border duration-200 ease-in-out
            border-teal-600 transition">
            {{__('Add More Products')}}
        </button>
</div>
</div>
