@foreach ($warehouse_bookings as $detail)
    <?php
        $bookingType = $detail->invoice ? $detail->invoice->booking_type : $detail->booking_type;
        $bookingStatus = $detail->invoice ? $detail->invoice->booking_status : $detail->booking_status;
        $bookingFinalStatus = $detail->invoice && $bookingStatus == "pending" ? __('payment pending') : $bookingStatus;
    ?>
    <tr class="hover:bg-gray-100 border-b border-gray-200 py-10">
        <td class="px-4 py-4">{{$detail->id}}</td>
        <td class="px-4 py-4">{{$detail->warehouse->id}}</td>
        <td class="px-4 py-4">{{$detail->invoice ? $detail->invoice->item : $detail->item}}</td>
        <td class="px-4 py-4">{{$detail->invoice ? $detail->invoice->start_time : $detail->start_time}}</td>
        <td class="px-4 py-4">{{$detail->invoice ? $detail->invoice->end_time : $detail->end_time}}</td>
        <td class="px-4 py-4 {{$bookingType == 'partial' ? 'text-red-500': 'text-green-500'}}">{{$bookingType}}</td>
        <td class="px-4 py-4">{{$bookingFinalStatus}}</td>
        <td class="px-4 py-4 {{$detail->deleted_at ? 'text-red-500': 'text-green-500'}}">{{$detail->deleted_at ? 'Deleted': 'On'}}</td>
        <td class="px-4 py-4">{{$detail->created_at}}</td>
        <td class="px-4 py-4">
            @if (!$detail->deleted_at)
                @if($bookingStatus == "approved")
                    @if(!empty($detail->invoice->id))
                        <a target="_blank" href="{{route('admin.warehousebookings.view-booking-invoice',['booking_agreement_term' => $detail->invoice->id])}}" title="{{__('View Booking Invoice')}}" class="mx-0.5">
                            <i class="fa fa-receipt mx-0.5"></i>
                        </a>
                    @endif
                @elseif ($bookingStatus == "pending")
                    @if($detail->invoice && $bookingFinalStatus == "payment pending")
                        <a target="_blank" href="{{route('warehousebookings.invoice-payment',['booking_agreement_term' => $detail->invoice->id])}}" title="{{__('Make Payment')}}" class="mx-0.5">
                            <i class="fa fa-shopping-cart mx-0.5"></i>
                        </a>
                    @else
                        <a href="#" data-url="{{route('warehousebookings.edit',['warehouse_booking' => $detail->id])}}" title="{{__('Update Booking Details')}}" class="mx-0.5 edit_details_btn">
                            <i class="fa fa-pencil mx-0.5"></i>
                        </a>
                        <a href="#"
                            data-url="{{route('warehouse.schedule.delete',['warehouse' => $detail->warehouse->id, 'warehouse_booking' => $detail->id])}}"
                            data title="{{__('Delete Booking Request')}}" class="mx-0.5 delete_booking"
                            data-warehouse-id="{{$detail->warehouse->id}}"
                            data-booking-id="{{$detail->id}}"
                        >
                            <i class="fa fa-trash mx-0.5"></i>
                        </a>
                    @endif
                @elseif ($bookingStatus == "rejected")
                <a href="#" data-url="{{route('admin.warehousebookings.update',['warehouse_booking' => $detail->id])}}" title="{{__('Update Booking Details')}}" class="mx-0.5 edit_details_btn">
                    <i class="fa fa-pencil mx-0.5"></i>
                </a>
                @else
                <a target="_blank" href="{{route('warehousebookings.view-invoice',['booking_agreement_term' => $detail->invoice->id])}}" title="{{__('View Booking Invoice')}}" class="mx-0.5">
                    <i class="fa fa-receipt mx-0.5"></i>
                </a>
                @endif

            @endif


        </td>
    </tr>
@endforeach
