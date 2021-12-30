@extends('layouts.master')

@section('page')
    <div class="card col-span-2 xl:col-span-1">
        <div class="card-header">Subscriptions / Packages</div>
        <div class="bg-white pb-4 px-4 rounded-md w-full">
                <form action="{{ route('subscription.selectPackage') }}" method="post">
                    @csrf
                    <div class="overflow-x-auto mt-6 flex justify-center">
                        <div class="relative border-none w-4/12 md:w-2/3 inline-flex mr-2">
                            <label for="plan" class="block lg:inline-flex"> {{ __('Select Package') }}: </label>
                            <select name="plan" id="plan" class="block appearance-none w-full bg-white border border-grey-lighter text-grey-darker py-3 px-4 pr-8 rounded">
                                @foreach ($plans as $plan)
                                <option value="{{ $plan->id }}">{{ $plan->name }}</option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2">
                                <i class="fas fa-chevron-down"></i>
                            </div>
                        </div>
                        <div class="inline-flex w-2/12 md:w-1/3">
                            <button class="btn" type="submit">{{ __('Submit') }}</button>
                        </div>
                    </div>
                </form>
        </div>
    </div>

    {{-- Subscriptions History --}}
    <div class="card col-span-2 xl:col-span-1 mt-10">
        <div class="card-header">{{ __('Subscriptions') }}</div>
        <div class="divide-y mt-5 divide-cool-gray-300">
            <div class="bg-white pb-4 px-4 rounded-md w-full">
                <div class="subscription-list">
                    <div class="card">
                        <div class="card-body">
                        <!-- start a table -->
                        <table class="table-fixed w-full">
                            <!-- table head -->
                            <thead class="text-left">
                                <tr>
                                    <th class="w-1/6 pb-10 text-sm font-extrabold tracking-wide">Id</th>
                                    <th class="w-1/4 pb-10 text-sm font-extrabold tracking-wide text-right">Plan</th>
                                    <th class="w-1/4 pb-10 text-sm font-extrabold tracking-wide text-right">Trial Ends At</th>
                                    <th class="w-1/4 pb-10 text-sm font-extrabold tracking-wide text-right">Starts At</th>
                                    <th class="w-1/4 pb-10 text-sm font-extrabold tracking-wide text-right">Ends At</th>
                                </tr>
                            </thead>
                            <!-- end table head -->
                            <!-- table body -->
                            <tbody class="text-left text-gray-600">
                                @if (count($subscriptions) > 0)
                                    @foreach ($subscriptions as $sub)
                                    <tr>
                                        <th class="w-1/6 mb-4 text-xs font-extrabold tracking-wider">{{ $sub->id }}</th>
                                        <th class="w-1/4 mb-4 text-xs font-extrabold tracking-wider text-right">{{ $sub->plan_id }}</th>
                                        <th class="w-1/4 mb-4 text-xs font-extrabold tracking-wider text-right">{{ $sub->trial_ends_at }}</th>
                                        <th class="w-1/4 mb-4 text-xs font-extrabold tracking-wider text-right">{{ $sub->starts_at }}</th>
                                        <th class="w-1/4 mb-4 text-xs font-extrabold tracking-wider text-right">{{ $sub->ends_at }}</th>
                                    </tr>
                                    @endforeach

                                <!-- item -->
                                @else
                                    <tr>
                                        <td colspan="5" class="text-center">{{ __('Seems like you never subscribed to any of our plan, please subscribe to our value added plans and grow your business exponentially') }}</td>
                                    </tr>
                                @endif

                            </tbody>
                            <!-- end table body -->
                        </table>
                        <!-- end a table -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Transactions History --}}
    <div class="card col-span-2 xl:col-span-1 mt-10">
        <div class="card-header">{{ __('Subscription Orders History') }}</div>
        <div class="divide-y mt-5 divide-cool-gray-300">
            <div class="bg-white pb-4 px-4 rounded-md w-full">
                <div class="bg-white pb-4 px-4 rounded-md w-full">
                    <div class="search-filters h-10 p-5 text-center">
                        show transaction search filters here
                    </div>
                </div>
                {{-- Subscription Orders List --}}
                <div class="subscription-orders-list">
                    <div class="card">
                        <div class="card-body">
                        <!-- start a table -->
                        <table class="table-fixed w-full">
                            <!-- table head -->
                            <thead class="text-left">
                                <tr>
                                    <th class="w-1/2 pb-10 text-sm font-extrabold tracking-wide">Order id</th>
                                    <th class="w-1/4 pb-10 text-sm font-extrabold tracking-wide text-right">Plan</th>
                                    <th class="w-1/4 pb-10 text-sm font-extrabold tracking-wide text-right">Payment Method</th>
                                    <th class="w-1/4 pb-10 text-sm font-extrabold tracking-wide text-right">Promocode</th>
                                    <th class="w-1/4 pb-10 text-sm font-extrabold tracking-wide text-right">Discount</th>
                                    <th class="w-1/4 pb-10 text-sm font-extrabold tracking-wide text-right">Tax</th>
                                    <th class="w-1/4 pb-10 text-sm font-extrabold tracking-wide text-right">Total Amount</th>
                                    <th class="w-1/4 pb-10 text-sm font-extrabold tracking-wide text-right">status</th>
                                    <th class="w-1/4 pb-10 text-sm font-extrabold tracking-wide text-right">Placed At</th>
                                    <th class="w-1/4 pb-10 text-sm font-extrabold tracking-wide text-right">Last Updated</th>
                                </tr>
                            </thead>
                            <!-- end table head -->
                            <!-- table body -->
                            <tbody class="text-left text-gray-600">
                                @if (count($subscriptionOrders) > 0)
                                    @foreach ($subscriptionOrders as $order)
                                        <!-- item -->
                                        <tr>
                                            <th class="w-1/4 mb-4 text-xs font-extrabold tracking-wider">{{ $order->id }}</th>
                                            <th class="w-1/4 mb-4 text-xs font-extrabold tracking-wider text-right">{{ $order->plan_id }}</th>
                                            <th class="w-1/4 mb-4 text-xs font-extrabold tracking-wider text-right">{{ $order->payment_method_id }}</th>
                                            <th class="w-1/4 mb-4 text-xs font-extrabold tracking-wider text-right">{{ $order->promo_code }}</th>
                                            <th class="w-1/4 mb-4 text-xs font-extrabold tracking-wider text-right">{{ $order->total_discount }}</th>
                                            <th class="w-1/4 mb-4 text-xs font-extrabold tracking-wider text-right">{{ $order->total_tax }}</th>
                                            <th class="w-1/4 mb-4 text-xs font-extrabold tracking-wider text-right">{{ $order->total_amount }}</th>
                                            <th class="w-1/4 mb-4 text-xs font-extrabold tracking-wider text-right">{{ $order->status }}</th>
                                            <th class="w-1/4 mb-4 text-xs font-extrabold tracking-wider text-right">{{ $order->created_at }}</th>
                                            <th class="w-1/4 mb-4 text-xs font-extrabold tracking-wider text-right">{{ $order->updated_at }}</th>
                                        </tr>
                                        <!-- item -->
                                    @endforeach
                                @else
                                    <tr>
                                        <th colspan="10" class="w-1/4 mb-4 text-xs font-extrabold tracking-wider text-center">{{ __('No Records Found.') }}</th>
                                    </tr>
                                @endif
                            </tbody>
                            <!-- end table body -->
                        </table>
                        <!-- end a table -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
