@extends('layouts.master')
@push('styles')
    <link rel="stylesheet" href="{{ asset(('css/pages/dashboard.css')) }}">
@endpush


@section('page')
    <div class="card col-span-2 xl:col-span-1">

        <div class="card-header">
            @if(!empty($trends))
                <div class="chart_container">
                    <canvas id="trending" data-trends="{{json_encode($trends)}}"></canvas>
                </div>
            @endif
            @if(!empty($total_division))
            <div class="chart_container">
                <canvas id="total_division" data-division="{{json_encode($total_division)}}"></canvas>
            </div>
            @endif
            @if(!empty($buying_trends))
            <div class="chart_container">
                <canvas id="buying_trends" data-buying-trends="{{json_encode($buying_trends)}}"></canvas>
            </div>
            @endif
            @if(!empty($req_for_quotation_trends))
            <div class="chart_container">
                <canvas id="req_for_quote_trends" data-trends="{{json_encode($req_for_quotation_trends)}}"></canvas>
            </div>
            @endif
            @if(!empty($quotation_trends))
            <div class="chart_container">
                <canvas id="quotation_trends" data-trends="{{json_encode($quotation_trends)}}"></canvas>
            </div>
            @endif
        </div>
    </div>

@endsection

@push('scripts')
    <script src="{{asset(('js/pages/dashboard.js'))}}"></script>
@endpush
