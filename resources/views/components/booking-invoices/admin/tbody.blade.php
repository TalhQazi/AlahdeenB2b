@foreach ($invoices as $detail)
    <tr class="hover:bg-gray-100 border-b border-gray-200 py-10">
        <td class="px-4 py-4">{{$detail->id}}</td>
        <td class="px-4 py-4">{{$detail->warehouse_booking->warehouse->id}}</td>
        <td class="px-4 py-4">{{$detail->warehouse_booking->warehouse->user->name}}</td>
        <td class="px-4 py-4">{{$detail->warehouse_booking->booked_by->name}}</td>
        <td class="px-4 py-4">{{$detail->start_time}}</td>
        <td class="px-4 py-4">{{$detail->end_time}}</td>
        <td class="px-4 py-4">{{$detail->price}}</td>
        <td class="px-4 py-4 {{$detail->requestor_payment_status ? 'text-green-500' : 'text-red-500'}}">{{$detail->requestor_payment_status ? __('Received') : __('Pending')}}</td>
        <td class="px-4 py-4 {{$detail->owner_paid_status ? 'text-green-500' : 'text-red-500'}}">{{$detail->owner_paid_status ? __('Paid') : __('Pending')}}</td>
        <td class="px-4 py-4">{{$detail->created_at}}</td>
        <td class="px-4 py-4">
            @if(!$detail->requestor_payment_status)
                <a href="#" class="mx-0.5 payment_received" title="{{__('Requestor Payment Received')}}"
                    data-invoice-id="{{$detail->id}}"
                    data-price="{{$detail->price}}"
                    data-status="{{$detail->requestor_payment_status ? __('received') : __('pending')}}"
                >
                    <i class="fa fa-wallet"></i>
                </a>
            @endif
            @if($detail->requestor_payment_status && !$detail->owner_paid_status)
                <a href="#" class="mx-0.5 payment_made" title="{{__('Money Transfered to Warehouse Owner')}}"
                    data-invoice-id="{{$detail->id}}"
                    data-commission-percentage={{$detail->commission_percentage ?? '0'}}
                    data-commission-collected={{$detail->commission_paid ?? '0'}}
                    data-tax-percentage={{$detail->tax_percentage ?? '0'}}
                    data-tax-amount={{$detail->tax_amount ?? '0'}}
                    data-price="{{$detail->price}}"
                    data-status="{{$detail->owner_paid_status ? __('paid') : __('pending')}}"">
                    <i class="mx-0.5 fa fa-handshake"></i>
                </a>
            @endif
        </td>
    </tr>
@endforeach
