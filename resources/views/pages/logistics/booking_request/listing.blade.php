<div class="card col-span-2 xl:col-span-1 mt-5">

    <div class="card-header">{{ __('Products Return Records') }}</div>

    <div class="bg-white pb-4 px-4 rounded-md w-full">
        <div class="w-full flex justify-end px-2 mt-2">
            <div class="sm:w-64 inline-block relative ">
                {{-- <input type="text" id="search_keywords"
                    class="leading-snug border border-gray-300 block w-full appearance-none bg-gray-100 text-sm text-gray-600 py-1 px-4 pl-8 rounded-lg"
                    placeholder="Search" /> --}}

                <div class="pointer-events-none absolute pl-3 inset-y-0 left-0 flex items-center px-2 text-gray-300">
                    <svg class="fill-current h-3 w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 511.999 511.999">
                        <path
                            d="M508.874 478.708L360.142 329.976c28.21-34.827 45.191-79.103 45.191-127.309C405.333 90.917 314.416 0 202.666 0S0 90.917 0 202.667s90.917 202.667 202.667 202.667c48.206 0 92.482-16.982 127.309-45.191l148.732 148.732c4.167 4.165 10.919 4.165 15.086 0l15.081-15.082c4.165-4.166 4.165-10.92-.001-15.085zM202.667 362.667c-88.229 0-160-71.771-160-160s71.771-160 160-160 160 71.771 160 160-71.771 160-160 160z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto mt-6" id="products">

            <table class="table-auto border-collapse w-full">
                <thead>

                    <tr class="rounded-lg text-sm font-medium text-gray-700 text-left" style="font-size: 0.9674rem">
                        <th class="px-4 py-2 bg-gray-200 " style="background-color:#f8f8f8">{{ __('ID') }}</th>
                        <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Product code') }}</th>
                        <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Product Name') }}</th>
                        <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Status') }}</th>
                        <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Return Quantity') }}</th>
                        <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Return Amount') }}</th>
                        <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Purchase Order No.') }}
                        </th>
                        <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Invoice No') }}</th>
                        @if (Auth::user()->hasRole(['super-admin', 'admin']) || Session::get('user_type') == 'seller')
                                <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Controls') }}</th>
                        @endif
                    </tr>
                </thead>

                <tbody class="text-sm font-normal text-gray-700 search_results">
                    @if (!empty($purchase_returns))
                        @foreach ($purchase_returns as $key => $purchase_return)
                            <tr class="hover:bg-gray-100 border-b border-gray-200 py-10">
                                <td class="px-4 py-4">{{ $purchase_return->id }}</td>
                                <td class="px-4 py-4">{{ $purchase_return->product_code }}</td>
                                <td class="px-4 py-4">{{ $purchase_return->product_name }}</td>
                                <td
                                    class="px-4 py-4

                                @if ($purchase_return->status == 'Approved')
                                    text-green-600
                                @elseif ($purchase_return->status == 'Declined')
                                    text-red-600
                                    @else
                                    text-blue-600
                                    @endif
                                ">
                                    {{ $purchase_return->status }}
                                </td>

                                <td class="px-4 py-4">{{ $purchase_return->return_quantity }}</td>
                                <td class="px-4 py-4">{{ $purchase_return->return_amount }}</td>
                                <td class="px-4 py-4">{{ $purchase_return->purchase_order_no }}</td>
                                <td class="px-4 py-4">{{ $purchase_return->invoice_no }}</td>
                                @if (Auth::user()->hasRole(['super-admin', 'admin']) || Session::get('user_type') == 'seller')
                                    @if ($purchase_return->status == 'Pending')

                                        <td class="px-4 py-4">
                                            <a href="{{ route('khata.purchase_return.change-status', ['purchase_return' => $purchase_return->id, 'status' => 'Approved']) }}"
                                                title="{{ __('Approve purchase return') }}">
                                                <i class="fa fa-check"></i>
                                            </a>

                                            <a href="{{ route('khata.purchase_return.change-status', ['purchase_return' => $purchase_return->id, 'status' => 'Declined']) }}"
                                                title="{{ __('Decline purchase return') }}" class="mx-0.5">
                                                <i class="fa fa-times mx-2"></i>
                                            </a>
                                        </td>
                                    @endif
                                @endif
                            </tr>
                        @endforeach
                    @else
                        <tr class="hover:bg-gray-100 border-b border-gray-200 py-10">
                            <td class="px-4 py-4">
                                {{ __('No Record Found') }}
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>

            <div id="pagination" class="w-full flex justify-center border-t border-gray-100 pt-4 items-center">
                {{ $paginator }}
            </div>
        </div>
    </div>
</div>
