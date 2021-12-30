@extends('layouts.master')

@section('page')
    <div class="card col-span-2 xl:col-span-1">
        <div class="card-header">Thank you for your subscription</div>
        <div class="bg-white pb-4 px-4 rounded-md w-full">
            <h2>Please proceed with your payment method and update us at billing@emandii.com</h2>
            <h5>Your Order Reference# <span class=" text-blue-400">{{ $invoice->id }}</span></h5>
        </div>
        <div class=" align-middle">
            <h2>Show Offline payment Options (Bank Details, etc)</h2>
        </div>
    </div>
@endsection
