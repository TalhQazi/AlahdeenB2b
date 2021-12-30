@extends('layouts.master')

@push('styles')
    <link rel="stylesheet" href="{{ asset(('css/pages/stock_index.css')) }}">
@endpush

@section('page')
    <div class="card col-span-2 xl:col-span-1">
        <div class="card-header">{{ __('Inventory Manager Stock Levels') }}</div>
        <div class="bg-white pb-4 px-4 rounded-md w-full">
            <div class="w-full flex justify-end px-2 mt-2">
                <div class="sm:w-64 inline-block relative mr-2 sm:flex-1">
                    <form action="" method="GET">
                        <input type="text" name="q" id="q"
                            class="leading-snug border border-gray-300 block w-full appearance-none bg-gray-100 text-sm text-gray-600 py-2 px-4 pl-8 rounded-lg"
                            placeholder="{{ __('Search by product title') }}" />
                    </form>
                    <div class="pointer-events-none absolute pl-3 inset-y-0 left-0 flex items-center px-2 text-gray-300">
                        <svg class="fill-current h-3 w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 511.999 511.999">
                            <path
                                d="M508.874 478.708L360.142 329.976c28.21-34.827 45.191-79.103 45.191-127.309C405.333 90.917 314.416 0 202.666 0S0 90.917 0 202.667s90.917 202.667 202.667 202.667c48.206 0 92.482-16.982 127.309-45.191l148.732 148.732c4.167 4.165 10.919 4.165 15.086 0l15.081-15.082c4.165-4.166 4.165-10.92-.001-15.085zM202.667 362.667c-88.229 0-160-71.771-160-160s71.771-160 160-160 160 71.771 160 160-71.771 160-160 160z" />
                        </svg>
                    </div>
                </div>
            </div>

            <table class="table-auto border-collapse w-full mt-5">
                <thead>
                    <tr class="rounded-lg text-sm font-medium text-gray-700 text-left" style="font-size: 0.9674rem">
                        <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Product ID') }}</th>
                        <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Product Name') }}</th>
                        <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Product Count') }}</th>
                        <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Average Interval Sale') }}</th>
                        <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Purchase/Production Interval') }}</th>
                        <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Remaining Products') }}</th>
                        <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Quantity Status') }}</th>
                        <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Controls') }}</th>
                    </tr>
                </thead>
                <tbody class="text-sm font-normal text-gray-700 search_results">
                    @if (count($products) > 0)
                        @foreach ($products as $key => $product)

                                <tr class="hover:bg-gray-100 border-b border-gray-200 py-10">
                                    @if($product->total_units)
                                    <td class="px-4 py-4">{{ $product->id }}</td>
                                    <td class="px-4 py-4">{{ $product->title }}</td>
                                    <td class="px-4 py-4">{{ $product->total_units }}</td>
                                    <td class="px-4 py-4">{{ $product->avg_interval_sale }}</td>
                                    <td class="px-4 py-4">{{ $product->purchase_production_interval . " " . $product->purchase_production_unit }}</td>
                                    <td class="px-4 py-4">{{ $product->remaining_products }}</td>
                                    <td class="px-4 py-4 {{ $product->quantity_status_class }}">{{ $product->quantity_status }}</td>
                                    <td class="px-4 py-4 controls">
                                        <span>
                                            <button class="add_stock" type="button" title="{{ __('Add Stock') }}" data-product-id="{{ $product->id }}">
                                                <i class="far fa-plus-circle fa-lg"></i>
                                            </button>
                                        </span>
                                    </td>
                                    @else
                                        <td colspan="8" class="px-4 py-4 text-center">
                                            {{ __('Quantity info is missing for ').$product->title.'.' }}
                                            <a target="_blank" class="underline" href="{{route('khata.inventory.product.pricing.create')}}">{{__(' Please add missing info')}}</a>
                                        </td>
                                    @endif

                                </tr>

                        @endforeach
                    @else
                        <tr class="hover:bg-gray-100 border-b border-gray-200 py-10">
                            <td colspan="8" class="px-4 py-4 text-center">
                                {{ __('No Stock found in Inventory.') }}
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
            <div id="pagination" class="w-full flex justify-center border-t border-gray-100 pt-4 items-center">
                {!! $paginator !!}
            </div>
        </div>
    </div>

    <div class="grid grid-cols-12 col-gap-2 mt-10">
       <div class="col-span-4 row-span-full card">
           <div class="card-header">{{ __('Sales Records') }}</div>

            <div class="p-5">
                <p>
                    {{ __('From') }}
                    <input id="date_timepicker_start" type="text" class="appearance-none block w-1/2 bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500">
                    {{ __('To') }}
                    <input id="date_timepicker_end" type="text" class="appearance-none block w-1/2 bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500">
                    <button class="btn btn-teal mt-2" id="refresh_records_btn">{{__('Refresh Records')}}</button>
                </p>
            </div>

            <div class="mt-5">
                <h2 class="card-header">{{ __('Total Quantity of Sales Made of Individual Product') }}</h2>
                <table class="table-auto border-collapse w-full mt-5">
                    <thead>
                        <tr class="rounded-lg text-sm font-medium text-gray-700 text-left" style="font-size: 0.9674rem">
                            <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Product Name') }}</th>
                            <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Quantity') }}</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm font-normal text-gray-700" id="qty_sales_record">

                    </tbody>
                </table>
            </div>
            <div class="mt-5">
                <h2 class="card-header">{{ __('Total Value of Sales Made of Individual Product') }}</h2>
                <table class="table-auto border-collapse w-full mt-5">
                    <thead>
                        <tr class="rounded-lg text-sm font-medium text-gray-700 text-left" style="font-size: 0.9674rem">
                            <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Product Name') }}</th>
                            <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Sales') }}</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm font-normal text-gray-700" id="value_sales_record"></tbody>
                </table>
            </div>
            <div class="mt-5">
                <h2 class="card-header">{{ __('Most Sold Products') }}</h2>
                <table class="table-auto border-collapse w-full mt-5">
                    <thead>
                        <tr class="rounded-lg text-sm font-medium text-gray-700 text-left" style="font-size: 0.9674rem">
                            <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Product Name') }}</th>
                            <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Total Sale Amount') }}</th>
                            <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Total Sold Units') }}</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm font-normal text-gray-700" id="most_sold_products_record"></tbody>
                </table>
            </div>
            <div class="mt-5">
                <h2 class="card-header">{{ __('Rising Products') }}</h2>
                <table class="table-auto border-collapse w-full mt-5">
                    <thead>
                        <tr class="rounded-lg text-sm font-medium text-gray-700 text-left" style="font-size: 0.9674rem">
                            <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Product Name') }}</th>
                            <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Total Sale Amount') }}</th>
                            <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Total Sold Units') }}</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm font-normal text-gray-700" id="rising_products_record"></tbody>
                </table>
            </div>
            <div class="mt-5">
                <h2 class="card-header">{{ __('Under Performing Product') }}</h2>
                <table class="table-auto border-collapse w-full mt-5">
                    <thead>
                        <tr class="rounded-lg text-sm font-medium text-gray-700 text-left" style="font-size: 0.9674rem">
                            <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Product Name') }}</th>
                            <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Total Sale Amount') }}</th>
                            <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Total Sold Units') }}</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm font-normal text-gray-700" id="under_performing_record"></tbody>
                </table>
            </div>

       </div>
       <div class="col-span-8 card">
            <div class="card-header">{{ __('Stock Reports') }}</div>
            <div class="p-5">
                <p>
                    {{ __('From') }}
                    <input id="stock_report_start" type="text" class="appearance-none block w-1/2 bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500">
                    {{ __('To') }}
                    <input id="stock_report_end" type="text" class="appearance-none block w-1/2 bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500">
                    <button class="btn btn-teal mt-2" id="refresh_stock_report_btn">{{__('Refresh Records')}}</button>
                </p>
            </div>
            <div class="mt-5 hidden" id="revenue_contribution_div">
                <h2 class="card-header">{{ __('Top 3 Revenue Generating Products') }}</h2>
                <table class="table-auto border-collapse w-full mt-5">
                    <thead>
                        <tr class="rounded-lg text-sm font-medium text-gray-700 text-left" style="font-size: 0.9674rem">
                            <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Total Revenue Generated') }}</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm font-normal text-gray-700" id="revenue_contribution"></tbody>
                </table>
            </div>
            <div class="mt-5">
                <h2 class="card-header">{{ __('Largest Deals Done') }}</h2>
                <table class="table-auto border-collapse w-full">
                    <thead>
                        <tr class="rounded-lg text-sm font-medium text-gray-700 text-left" style="font-size: 0.9674rem">
                            <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Buying Party Name') }}</th>
                            <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Product') }}</th>
                            <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Total Quantity Sold') }}</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm font-normal text-gray-700 largest_deals_done">
                    </tbody>
                </table>
            </div>
            <div class="mt-5">
                <h2 class="card-header">{{ __('Stock Levels') }}</h2>
                <table class="table-auto border-collapse w-full">
                    <thead>
                        <tr class="rounded-lg text-sm font-medium text-gray-700 text-left" style="font-size: 0.9674rem">
                            <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Product Name') }}</th>
                            <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Total Quantity Available') }}</th>
                            <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Total Quantity Sold') }}</th>
                            <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Average Production Interval Sale') }}</th>
                            <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Purchase/Production Interval') }}</th>
                            <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Total Revenue') }}</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm font-normal text-gray-700 stock_level_results">
                    </tbody>
                </table>
                <div id="stock_results_pagination" class="w-full flex justify-center border-t border-gray-100 pt-4 items-center">

                </div>
            </div>
        </div>

    </div>


    @include('components.khata.add-stock')

@endsection

@push('scripts')
    <script src="{{ asset(('/js/pages/stock_index.js')) }}"></script>
@endpush
