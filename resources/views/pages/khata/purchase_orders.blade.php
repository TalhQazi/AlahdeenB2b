@extends('layouts.master')

@section('page')
    @parent
    <div class="card col-span-2 xl:col-span-1">
        <div class="card-header">{{ __('Manage Purchase Orders') }}</div>
        <div class="bg-white pb-4 px-4 rounded-md w-full">
            <div class="w-full flex justify-end px-2 mt-2">
                <div class="sm:w-64 inline-block relative mr-2 sm:flex-1">
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
                  <a href="{{ route('khata.purchase-order.create') }}" class="btn btn-indigo"><i class="fas fa-plus mr-2 sm:hidden"></i>
                      <span class="">{{ __('Add Purchase Order') }}</span>
                  </a>
              </div>
            </div>

            <table class="table-auto border-collapse w-full mt-5">
                <thead>
                    <tr class="rounded-lg text-sm font-medium text-gray-700 text-left" style="font-size: 0.9674rem">
                        <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('PO Date') }}</th>
                        <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('PO No') }}</th>
                        <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Delivery Date') }}</th>
                        <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('PO Details') }}</th>
                        <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Payment Details') }}</th>
                        <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Created By') }}</th>
                        <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Controls') }}</th>
                    </tr>
                </thead>
                <tbody class="text-sm font-normal text-gray-700" id="search_results">
                    @if (!empty($purchase_orders) > 0)
                        @foreach ($purchase_orders as $purchase_order)

                          <tr class="hover:bg-gray-100 border-b border-gray-200 py-10">
                              <td class="px-4 py-4">{{ $purchase_order->po_date }}</td>
                              <td class="px-4 py-4">{{ $purchase_order->number }}</td>
                              <td class="px-4 py-4">{{ $purchase_order->po_delivery_date }}</td>
                              <td class="px-4 py-4">{{ $purchase_order->order_description }}</td>
                              <td class="px-4 py-4">{{ $purchase_order->payment_details }}</td>
                              <td class="px-4 py-4">{{ $purchase_order->createdBy->name }}</td>
                              <td class="px-4 py-4 controls">
                                  <form action="{{ route('khata.purchase-order.destroy', ['purchase_order' => $purchase_order->id]) }}"
                                      method="POST">
                                      @csrf
                                      @method("DELETE")
                                      <span>
                                          <a href="{{route('khata.purchase-order.edit', ['purchase_order' => $purchase_order->id])}}" title="{{ __('Edit Purchase Order') }}">
                                              <i class="far fa-pencil fa-lg"></i>
                                          </a>
                                      </span>
                                      <span>
                                        <a href="{{route('khata.purchase-order.download', ['purchase_order' => $purchase_order->id])}}" title="{{ __('Download Purchase Order') }}">
                                            <i class="far fa-download fa-lg"></i>
                                        </a>
                                      </span>
                                      <span>
                                          <button class="remove-purchase-order" type="submit" title="{{ __('Remove Purchase Order') }}">
                                              <i class="far fa-trash fa-lg"></i>
                                          </button>
                                      </span>
                                  </form>
                              </td>
                          </tr>

                        @endforeach
                    @else
                        <tr class="hover:bg-gray-100 border-b border-gray-200 py-10">
                            <td colspan="5" class="px-4 py-4 text-center">
                                {{ __('No Clients to Manage.') }}
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    @push('scripts')
        <script src="{{ asset(('/js/pages/manage_purchase_order.js')) }}"></script>
    @endpush

@endsection
