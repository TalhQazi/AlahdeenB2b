@extends('layouts.master')

@section('page')
    @if ($errors->any())
        <div class="alert alert-error alert-close mb-5">
            @foreach ($errors->all() as $error)
            <button class="alert-btn-close">
                <i class="fad fa-times"></i>
            </button>
            <span>{{ $error }}</span>
            @endforeach
        </div>
    @endif
    <div class="card col-span-2 xl:col-span-1">
        <div class="card-header">{{ __('Warehouse Booking Invoice') }}</div>
        <div class="bg-white pb-4 px-4 rounded-md w-full">
                <div class="mt-10">
                    <table class="w-full border-solid table">
                        <tr class=" text-left">
                            <th>{{ __('ID') }}</th>
                            <th>{{ __('Quantity') }}</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Price') }}</th>
                            <th>{{ __('Controls') }}</th>
                        </tr>
                        @if (count($cart->content()) > 0)
                        @foreach ($cart->content() as $item)
                         <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->qty }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->price }}</td>
                            <td>
                                <a href="{{route('subscription.removeCartItem', ['rowId' => $item->rowId])}}">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                        <tr>
                            <td colspan="3">{{ __('Tax') }}: ({{ $item->taxRate }}%)</td>
                            <td>{{ $cart->tax() }}</td>
                        </tr>
                        {{-- <tr>
                            <td colspan="3">{{ __('Discount') }}: ({{ $item->discountRate }}%)</td>
                            <td>{{ $cart->discount() }}</td>
                        </tr> --}}
                        <tr>
                            <td colspan="3" class="pt-5">{{ __('Total') }}:</td>
                            <td class="pt-5">{{ $cart->total() }}</td>
                        </tr>
                        @else
                        <tr>
                            <td colspan="4" class="text-center pt-5">{{ __('No Items in your cart') }}</td>
                        </tr>
                        @endif
                    </table>
                    {{-- @if (Session::has('subscription'))
                        <div class="alert alert-success alert-close mb-5">
                            <button class="alert-btn-close">
                                <i class="fad fa-times"></i>
                            </button>
                            <span>{{ __('Promocode Applied') }}: {{ Session::get('subscription.promocode.code') }}</span>
                        </div>
                    @else
                        <form action="{{ route('subscription.applyCoupon') }}" method="post" class="mt-10 flex justify-end">
                            @csrf
                            <input class="border rounded-md p-2 text-base mr-2" type="text" name="promocode" id="promocode" required placeholder="Coupon Code">
                            <button class="bg-gray-300 hover:bg-gray-600 hover:text-white p-2 rounded-md" type="submit">{{ __('Apply Coupon') }}</button>
                        </form>
                    @endif --}}
                </div>
                @if (count($cart->content()) > 0)
                <div class="mt-5 clearfix inline-block w-full">
                    <form action="{{ route('warehousebookings.invoice-proceedPayment', ['booking_agreement_term' => $invoice_id]) }}" method="post">
                        @csrf
                        <div class="relative border-none w-3/12 md:w-2/3 sm:w-full inline-flex mr-2">
                            <select name="payment_method" id="payment_method" class="block appearance-none w-full bg-white border border-grey-lighter text-grey-darker py-3 px-4 rounded-md" required>
                                <option value="">{{ __('Select Payment Method') }}</option>
                                @foreach ($paymentMethods as $pm)
                                    <option value="{{ $pm->id }}" {{ old('payment_method') == $pm->id ? 'selected' : '' }}>{{ $pm->name }}</option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3">
                                <i class="fas fa-chevron-down"></i>
                            </div>
                        </div>
                        <div class="block w-full">
                            <input type="checkbox" name="agree_subscription_terms" id="agree_subscription_terms" {{ old('agree_subscription_terms') == 'on' ? 'checked' : '' }} required>
                            <label for="agree_subscription_terms">{{ __('I Agree to ') }} <a class=" text-blue-400" href="{{ __('route to subscription payment terms') }}" target="_blank" rel="noopener noreferrer">{{ __('Terms and Conditions') }}</a> </label>
                        </div>
                        <button class="mt-5 bg-green-300 hover:bg-green-600 hover:text-white py-3 px-4 rounded-md">{{ __('Proceed To Payment') }}</button>
                    </form>
                </div>
                @endif
        </div>
    </div>
@endsection
