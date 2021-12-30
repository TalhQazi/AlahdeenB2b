@extends('layouts.master')

@push('styles')
    <link rel="stylesheet" href="{{ asset(('css/product_of_interest.css')) }}">
@endpush


@section('page')
    @include('components.business_profile.business-details-steps')
    <div class="card col-span-2 xl:col-span-1 mt-20">
        <div class="card-header">Products of Interest</div>
        <div class="bg-white pb-4 px-4 rounded-md w-full">
            <div class="w-full flex justify-end px-2 mt-2">
                <div class="sm:w-64 relative mr-5">
                    <button class="btn btn-success" id="add-documents-btn">
                        Add Products of Interest
                        <i class="fa fa-plus"></i>
                    </button>
                </div>
                <div class="sm:w-64 relative ">
                    <input type="" name="" class="leading-snug border border-gray-300 block w-full appearance-none bg-gray-100 text-sm text-gray-600 py-1 px-4 pl-8 rounded-lg h-10" placeholder="Search" />

                    <div class="pointer-events-none absolute pl-3 inset-y-0 left-0 flex items-center px-2 text-gray-300">
                        <svg class="fill-current h-3 w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 511.999 511.999">
                            <path d="M508.874 478.708L360.142 329.976c28.21-34.827 45.191-79.103 45.191-127.309C405.333 90.917 314.416 0 202.666 0S0 90.917 0 202.667s90.917 202.667 202.667 202.667c48.206 0 92.482-16.982 127.309-45.191l148.732 148.732c4.167 4.165 10.919 4.165 15.086 0l15.081-15.082c4.165-4.166 4.165-10.92-.001-15.085zM202.667 362.667c-88.229 0-160-71.771-160-160s71.771-160 160-160 160 71.771 160 160-71.771 160-160 160z" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="overflow-x-auto mt-6">

                <table class="table-auto border-collapse w-full">
                    <thead>
                        <tr class="rounded-lg text-sm font-medium text-gray-700 text-left" style="font-size: 0.9674rem">
                            <th class="px-4 py-2 bg-gray-200 " style="background-color:#f8f8f8">ID</th>
                            <th class="px-4 py-2 " style="background-color:#f8f8f8">Product</th>
                            <th class="px-4 py-2 " style="background-color:#f8f8f8">Frequency</th>
                            <th class="px-4 py-2 " style="background-color:#f8f8f8">Quantity</th>
                            <th class="px-4 py-2 " style="background-color:#f8f8f8">Quantity Unit</th>
                            <th class="px-4 py-2 " style="background-color:#f8f8f8">Controls</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm font-normal text-gray-700">
                        @if (!empty($products_of_interests))
                            @foreach ($products_of_interests as $interest_details)
                                <tr class="hover:bg-gray-100 border-b border-gray-200 py-10">
                                    <td class="px-4 py-4">{{$interest_details->id}}</td>
                                    <td class="px-4 py-4">{{$interest_details->required_product}}</td>
                                    <td class="px-4 py-4 capitalize">{{$interest_details->frequency ? $interest_details->frequency : "NA"}}</td>
                                    <td class="px-4 py-4">{{$interest_details->quantity}}</td>
                                    <td class="px-4 py-4 capitalize">{{$interest_details->quantity_unit}}</td>
                                    <td class="px-4 py-4">
                                        <?php
                                            $deleteTitle = "Delete Product of Interest";
                                            $deleteRoute = route('product.interest.delete',['user_product_interest' => $interest_details->id]);
                                        ?>
                                        <a href="#" data-href="{{$deleteRoute}}" data-interest-id="{{$interest_details->id}}" title="{{$deleteTitle}}" class="mx-0.5 delete-product-interest">
                                            <i class="fa fa-trash mx-0.5"></i>
                                        </a>

                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr class="hover:bg-gray-100 border-b border-gray-200 py-10">
                                <td class="px-4 py-4">
                                    No Products of Interest Found
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <div id="pagination" class="w-full flex justify-center border-t border-gray-100 pt-4 items-center">
                {{$products_of_interests->links('')}}


            </div>

        </div>

        <style>

        thead tr th:first-child { border-top-left-radius: 10px; border-bottom-left-radius: 10px;}
        thead tr th:last-child { border-top-right-radius: 10px; border-bottom-right-radius: 10px;}

        tbody tr td:first-child { border-top-left-radius: 5px; border-bottom-left-radius: 0px;}
        tbody tr td:last-child { border-top-right-radius: 5px; border-bottom-right-radius: 0px;}


        </style>
    </div>

@endsection

@section('modals')
    @parent
    @include('components.product_interests.add')
    @include('components.delete-modal')
@endsection

@push('scripts')
    <script>
        var base_url = '{{ config('app.url') }}';
    </script>
    <script type="text/javascript" src="{{ asset(('js/product_interests.js')) }}"></script>
@endpush
