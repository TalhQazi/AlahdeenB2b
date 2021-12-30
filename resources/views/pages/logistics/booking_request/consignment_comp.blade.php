<div class="grid grid-cols-1 w-full">
    <div class="bg-white rounded-lg">
        <div class="flex flex-col items-start p-4">
            <div class="w-full">
                <div class="product-details w-full py-10 overflow-x-scroll">
                    <div class="grid grid-cols-12">
                        <table class="table-auto border-collapse w-full col-span-12">
                            <thead>
                            <tr class="rounded-lg text-sm font-medium text-gray-700 text-left"
                                style="font-size: 0.9674rem">
                                <th class="px-4 py-2 bg-gray-200 " style="background-color:#f8f8f8">
                                    {{ __('Sr.No') }}</th>
                                <th class="px-4 py-2 " style="background-color:#f8f8f8">
                                    {{ __('Product Name') }}</th>
                                <th class="px-4 py-2 " style="background-color:#f8f8f8">
                                    {{ __('Type of Packing') }}</th>
                                <th class="px-4 py-2 " style="background-color:#f8f8f8">
                                    {{ __('No. of Packets ') }}</th>
                                <th class="px-4 py-2 " style="background-color:#f8f8f8">
                                    {{ __('Description') }}</th>
                                <th class="px-4 py-2 " style="background-color:#f8f8f8">
                                    {{ __('Controls') }}</th>

                            </tr>
                            </thead>

                            <tbody class="text-sm font-normal text-gray-700" id="products_tbody">

                            @if ( isset($booking_consignments) && count($booking_consignments) > 0)
                                @foreach ($booking_consignments as $consignment)
                                    <tr class="hover:bg-gray-100 border-b border-gray-200 py-10">
                                        <td class="px-4 py-4">
                                            {{ $loop->iteration }}
                                        </td>

                                        <td class="px-4 py-4">
                                            <select name="item[{{ $loop->iteration }}][product_id]"
                                                    id="item_product_id_{{ $loop->iteration }}"
                                                    class="text-base border-b border-gray-300 focus:outline-none focus:border-gray-500 w-40">
                                                <option value="null">{{ __('Select product') }}
                                                </option>
                                                @foreach ($product_list as $item)
                                                    <option value="{{ $item->id }}"
                                                        {{ isset($consignment->product_id) && $consignment->product_id == $item->id ? 'selected' : '' }}
                                                    >
                                                        {{ $item->title }}</option>
                                                @endforeach
                                            </select>
                                        </td>

                                        <td class="px-4 py-4">
                                            <select name="item[{{ $loop->iteration }}][type_of_packing]"
                                                    id="item_type_of_packing_{{ $loop->iteration }}"
                                                    class="text-base border-b border-gray-300 focus:outline-none focus:border-gray-500 w-40">
                                                <option value="null">{{ __('Select packing') }}
                                                </option>
                                                @foreach ($package_types as $key=>$package_type)
                                                    <option value="{{ $key }}"
                                                        {{ isset($consignment->type_of_packing) && $consignment->type_of_packing == $key ? 'selected' : '' }}
                                                    >
                                                        {{ $package_type }}</option>
                                                @endforeach
                                            </select>
                                        </td>

                                        <td class="px-4 py-4">
                                            <input
                                                class="text-base border-b border-gray-300 focus:outline-none focus:border-gray-500 w-40 qty"
                                                value="{{ $consignment->no_of_packs }}"
                                                type="number" name="item[{{ $loop->iteration }}][no_of_packs]" id="item_no_of_packs_{{ $loop->iteration }}">
                                        </td>

                                        <td class="px-4 py-4">
                                            <input
                                                class="text-base border-b border-gray-300 focus:outline-none focus:border-gray-500 w-40"
                                                value="{{ $consignment->description }}"
                                                type="text" name="item[{{ $loop->iteration }}][description]" id="item_description_{{ $loop->iteration }}">
                                        </td>

                                        <td class="px-4 py-4">
                                                <span>
                                                    <button role="button" type="button"
                                                            class="btn btn-sm btn-danger remove_line" data-lineid="1">Remove</button>
                                                </span>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr class="hover:bg-gray-100 border-b border-gray-200 py-10">
                                    <td class="px-4 py-4">
                                        1
                                    </td>

                                    <td class="px-4 py-4">
                                           <select name="item[1][product_id]" id="item_product_id_1"
                                                class="text-base border-b border-gray-300 focus:outline-none focus:border-gray-500 w-40">
                                                <option value="null">{{ __('Select product') }}
                                                </option>
                                                @foreach ($product_list as $item)
                                                    <option value="{{ $item->id }}"
                                                        {{ isset($consignment->product_id) && $consignment->product_id == $item->id ? 'selected' : '' }}
                                                    >
                                                        {{ $item->title }}</option>
                                                @endforeach
                                            </select>
                                    </td>

                                    <td class="px-4 py-4">
                                        <select name="item[1][type_of_packing]" id="item_type_of_packing_1"
                                                class="text-base border-b border-gray-300 focus:outline-none focus:border-gray-500 w-40">
                                            <option value="">{{ __('Select packing') }}
                                            </option>
                                            @foreach ($package_types as $key=>$package_type)
                                                <option value="{{ $key }}"
                                                    {{ isset($consignment->type_of_packing) && $consignment->type_of_packing == $key ? 'selected' : '' }}
                                                >
                                                    {{ $package_type }}</option>
                                            @endforeach
                                        </select>
                                    </td>

                                    <td class="px-4 py-4">
                                        <input
                                            class="text-base border-b border-gray-300 focus:outline-none focus:border-gray-500 w-40 qty"
                                            type="number" name="item[1][no_of_packs]" id="item_no_of_packs_1">
                                    </td>

                                    <td class="px-4 py-4">
                                        <input
                                            class="text-base border-b border-gray-300 focus:outline-none focus:border-gray-500 w-40"
                                            type="text" name="item[1][description]" id="item_description_1">
                                    </td>

                                    <td class="px-4 py-4">
                                            <span>
                                                <button role="button" type="button"
                                                        class="btn btn-sm btn-danger remove_line"
                                                        data-lineid="1">Remove</button>
                                            </span>
                                    </td>
                                </tr>
                            @endif


                            </tbody>
                        </table>
                        <div class="flex col-span-12 justify-center mt-5">
                            {{-- <button class="btn btn-teal ml-3" id="add_more_btn"><i
                                    class="fas fa-plus-circle" type="button"></i>{{ __(' Add More Products') }}</button> --}}

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
