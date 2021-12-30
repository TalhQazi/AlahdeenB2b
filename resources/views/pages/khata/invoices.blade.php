@extends('layouts.master')

@section('page')
    @parent
    <div class="card col-span-2 xl:col-span-1">
        <div class="card-header">{{ __('Manage Invoices') }}</div>
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
                  @if(Session::get('user_type') == "seller")
                    <a href="{{ route('khata.invoice.create') }}" class="btn btn-indigo"><i class="fas fa-file-invoice-dollar mr-2 sm:hidden"></i>
                        <span class="">{{ __('Add Invoice') }}</span>
                    </a>
                  @endif
                </div>
            </div>

            <table class="table-auto border-collapse w-full mt-5">
                <thead>
                    <tr class="rounded-lg text-sm font-medium text-gray-700 text-left" style="font-size: 0.9674rem">
                        <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Date') }}</th>
                        <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Invoice No') }}</th>
                        <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Billed To') }}</th>
                        <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Created By') }}</th>
                        <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Status') }}</th>
                        <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Invoice Email') }}</th>
                        <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Controls') }}</th>
                    </tr>
                </thead>
                <tbody class="text-sm font-normal text-gray-700">
                    @if (count($invoices) > 0)
                        @foreach ($invoices as $key => $invoice)

                                <tr class="hover:bg-gray-100 border-b border-gray-200 py-10">
                                    <td class="px-4 py-4">{{ $invoice['invoice_date'] }}</td>
                                    <td class="px-4 py-4">{{ $invoice['number'] }}</td>
                                    <td class="px-4 py-4">{{ $invoice['buyer_details']->name }} -- {{ $invoice['buyer_details']->company_name  }}</td>
                                    <td class="px-4 py-4">{{ $invoice['created_by']->name }}</td>
                                    <td class="px-4 py-4">{{ $invoice['status'] }}</td>
                                    <td class="px-4 py-4"><input class="mail_invoice" data-invoice-id="{{$invoice['id']}}" type="checkbox" name="is_shared" id="is_shared_{$key}" @if($invoice['is_shared']) checked @endif></td>
                                    <td class="px-4 py-4 controls">
                                      <span>
                                        <a download href="{{route('khata.invoice.download', ['invoice' => $invoice['id']])}}" title="{{ __('Download Invoice') }}">
                                            <i class="far fa-download fa-lg"></i>
                                        </a>
                                      </span>
                                      {{-- <form action="{{ route('khata.invoice.destroy', ['client' => $client->id]) }}"
                                            method="POST">
                                            @csrf
                                            @method("DELETE")
                                            <span>
                                                <a href="" onclick="alert('Add invoice create link here');" title="{{ __('Create Invoice') }}">
                                                    <i class="far fa-file-invoice-dollar fa-lg"></i>
                                                </a>
                                            </span>
                                            <span>
                                                <button class="remove-client" type="submit" title="{{ __('Remove Client') }}">
                                                    <i class="far fa-trash fa-lg"></i>
                                                </button>
                                            </span>
                                        </form> --}}
                                    </td>
                                </tr>

                        @endforeach
                    @else
                        <tr class="hover:bg-gray-100 border-b border-gray-200 py-10">
                            <td colspan="15" class="px-4 py-4 text-center">
                                {{ __('No Invoices to Manage.') }}
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    @push('scripts')
        <script src="{{ asset(('/js/pages/manage_invoices.js')) }}"></script>
    @endpush

@endsection
