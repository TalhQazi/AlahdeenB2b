@extends('layouts.master')
@push('styles')
    <link rel="stylesheet" href="{{ asset(('css/pages/inventory_management.css')) }}">
@endpush


@section('page')

    <div class="card col-span-2 xl:col-span-1">
        <div class="card-header">{{ __('Inventory Management') }}</div>
            <div class="grid grid-flow-row grid-cols-3 grid-rows-2 gap-20 md:grid-rows-3 md:grid-cols-2 sm:grid-rows-6 sm:grid-cols-1 justify-items-center py-10">
                <div class="chart_container">
                    <a href="{{ route('khata.inventory.product.definitions.list') }}">
                        <img src="{{url('img/icons8-product-100.png')}}" alt="">
                        {{ __('Product Defination') }}
                    </a>
                </div>

                <div class="chart_container">
                    <a href="{{ route('khata.inventory.product.pricing.list') }}">
                        <img src="{{url('img/icons8-pricing-100.png')}}" alt="">
                        {{ __('Product Pricing') }}
                    </a>
                </div>
                <div class="chart_container">
                    <a href="{{ route('khata.inventory.product.stock') }}">
                        <img src="{{url('img/icons8-stocks-100.png')}}" alt="">
                        {{ __('Stock Levels') }}
                    </a>
                </div>
                <div class="chart_container">
                    <a href="{{ route('khata.inventory.products.list') }}" data-href="{{ route('khata.inventory.products.list') }}">
                        <img src="{{url('img/icons8-bulleted-list-100.png')}}" alt="">
                        {{ __('Products List') }}
                    </a>
                </div>
                <div class="chart_container">
                    <a href="{{ route('khata.inventory.product.sales') }}" data-href="{{ route('khata.inventory.product.sales') }}">
                        <img src="{{url('img/icons8-total-sales-100.png')}}" alt="">
                        {{ __('Sales and Returns') }}
                    </a>
                </div>
                <div class="chart_container">
                    <a href="#">
                        <img src="{{url('img/icons8-combo-chart-100.png')}}" alt="">
                        {{ __('Reports') }}
                    </a>
                </div>


            </div>



    </div>
@endsection

@push('scripts')
    {{-- <script src="{{asset(('js/pages/dashboard.js'))}}"></script> --}}
@endpush
