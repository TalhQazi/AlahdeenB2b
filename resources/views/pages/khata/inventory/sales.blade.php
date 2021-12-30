@extends('layouts.master')

@push('styles')
<link rel="stylesheet" href="{{ asset(('css/auto_complete.css')) }}">
@endpush

@section('page')
    @parent
    <div class="card col-span-2 xl:col-span-1">
        <div class="card-header">{{ __('Products Sale') }}</div>
        <div class="bg-white pb-4 px-4 rounded-md w-full">

            <div class="w-full flex justify-end px-2 mt-2">
                {{-- <div class="sm:w-64 inline-block relative mr-2 sm:flex-1">
                    <form action="" method="GET">
                        <input type="text" name="q" id="q"
                            class="leading-snug border border-gray-300 block w-full appearance-none bg-gray-100 text-sm text-gray-600 py-2 px-4 pl-8 rounded-lg"
                            placeholder="Search" />
                    </form>
                    <div class="pointer-events-none absolute pl-3 inset-y-0 left-0 flex items-center px-2 text-gray-300">
                        <svg class="fill-current h-3 w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 511.999 511.999">
                            <path
                                d="M508.874 478.708L360.142 329.976c28.21-34.827 45.191-79.103 45.191-127.309C405.333 90.917 314.416 0 202.666 0S0 90.917 0 202.667s90.917 202.667 202.667 202.667c48.206 0 92.482-16.982 127.309-45.191l148.732 148.732c4.167 4.165 10.919 4.165 15.086 0l15.081-15.082c4.165-4.166 4.165-10.92-.001-15.085zM202.667 362.667c-88.229 0-160-71.771-160-160s71.771-160 160-160 160 71.771 160 160-71.771 160-160 160z" />
                        </svg>
                    </div>
                </div>
                <div>
                    <a href="{{ route('khata.inventory.product.definition') }}" class="btn btn-indigo"><i class="fas fa-plus mr-2 sm:hidden"></i>
                        <span class="">{{ __('Add Product Defination') }}</span>
                    </a>
                </div> --}}
            </div>

            <div class="w-full overflow-auto">
                <table class="table-auto border-collapse w-full mt-5">
                    <thead>
                        <tr class="rounded-lg text-sm font-medium text-gray-700 text-left" style="font-size: 0.9674rem">
                            <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('SR. #') }}</th>
                            <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Product Code') }}</th>
                            <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Product Name') }}</th>
                            <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Rate') }}</th>
                            <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Quantity') }}</th>
                            <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Total') }}</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm font-normal text-gray-700" id="search_results">
                        @if (count($products) > 0)
                            @foreach ($products as $key => $product)

                                <tr class="hover:bg-gray-100 border-b border-gray-200 py-10">
                                    <td class="px-4 py-4">{{ $loop->iteration }}</td>
                                    <td class="px-4 py-4">{{ $product->product_code }}</td>
                                    <td class="px-4 py-4">{{ $product->title }}</td>
                                    <td class="px-4 py-4">{{ $product->rate }}</td>
                                    <td class="px-4 py-4">{{ $product->quantity }}</td>
                                    <td class="px-4 py-4">{{ $product->total . ' PKR' }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr class="hover:bg-gray-100 border-b border-gray-200 py-10">
                                <td colspan="6" class="px-4 py-4 text-center">
                                    {{ __('No Inventory Products Definition found.') }}
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
                <div id="pagination" class="w-full flex justify-center border-t border-gray-100 pt-4 items-center">
                    {{ $products->links() }}
                </div>
            </div>

        </div>
    </div>


    @include('pages.khata.inventory.forms.purchase_return_form')

    @include('pages.khata.inventory.listing.purchase_return_listing')

@endsection

@push('scripts')
    <script>
        var base_url = '{{ config('app.url') }}';
    </script>
    <script type="text/javascript" src="{{ asset(('js/pages/purchase_return.js')) }}"></script>
@endpush
