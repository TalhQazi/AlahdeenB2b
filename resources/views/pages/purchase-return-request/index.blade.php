@extends('layouts.master')

@push('styles')
    <link rel="stylesheet" href="{{ asset(('css/auto_complete.css')) }}">
@endpush

@section('page')
    @parent
    <div>
            @include('pages.khata.inventory.forms.purchase_return_form')

            @include('pages.khata.inventory.listing.purchase_return_listing')
    </div>

@endsection

@push('scripts')
    <script>
        var base_url = '{{ config('app.url') }}';
    </script>
    <script type="text/javascript" src="{{ asset(('js/pages/purchase_return.js')) }}"></script>
@endpush
