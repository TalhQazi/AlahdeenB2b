@foreach ($warehouse_bookings as $detail)

    <tr class="hover:bg-gray-100 border-b border-gray-200 py-10">
        <td class="px-4 py-4">{{$detail->id}}</td>
        <td class="px-4 py-4">{{$detail->warehouse->id}}</td>
        <td class="px-4 py-4">{{$detail->booked_by->name}}</td>
        <td class="px-4 py-4">{{$detail->item}}</td>
        <td class="px-4 py-4">{{$detail->start_time}}</td>
        <td class="px-4 py-4">{{$detail->end_time}}</td>
        <td class="px-4 py-4 {{$detail->booking_type == 'partial' ? 'text-red-500': 'text-green-500'}}">{{$detail->booking_type}}</td>
        <td class="px-4 py-4">{{$detail->booking_status}}</td>
        <td class="px-4 py-4 {{$detail->deleted_at ? 'text-red-500': 'text-green-500'}}">{{$detail->deleted_at ? 'Deleted': 'On'}}</td>
        <td class="px-4 py-4">{{$detail->created_at}}</td>
        <td class="px-4 py-4">
            @if (!$detail->deleted_at)
                @if($detail->booking_status == "approved")
                    @if(empty($detail->invoice_id))
                    <a href="{{route('admin.warehousebookings.create-agreement',['warehouse_booking' => $detail->id])}}" target="_blank" title="{{__('Create Booking Invoice')}}" class="mx-0.5">
                        <i class="fa fa-plus mx-0.5"></i>
                    </a>
                    <a href="#" data-url={{route('admin.warehousebookings.reject',['warehouse_booking' => $detail->id])}} title="{{__('Mark As Rejected')}}" class="mx-0.5 reject_details_btn">
                        <i class="fa fa-ban mx-0.5"></i>
                    </a>
                    @else
                    <a target="_blank" href="{{route('admin.warehousebookings.view-booking-invoice',['booking_agreement_term' => $detail->invoice->id])}}" title="{{__('View Booking Invoice')}}" class="mx-0.5">
                        <i class="fa fa-eye mx-0.5"></i>
                    </a>
                    @endif
                @elseif ($detail->booking_status == "pending")
                    <a href="#" data-url={{route('admin.warehousebookings.update',['warehouse_booking' => $detail->id])}} title="{{__('Update Booking Details')}}" class="mx-0.5 edit_details_btn">
                        <i class="fa fa-pencil mx-0.5"></i>
                    </a>
                    <a href="#" data-url={{route('admin.warehousebookings.reject',['warehouse_booking' => $detail->id])}} title="{{__('Mark As Rejected')}}" class="mx-0.5 reject_details_btn">
                        <i class="fa fa-ban mx-0.5"></i>
                    </a>
                @elseif ($detail->booking_status == "rejected")
                <a href="#" data-url={{route('admin.warehousebookings.update',['warehouse_booking' => $detail->id])}} title="{{__('Update Booking Details')}}" class="mx-0.5 edit_details_btn">
                    <i class="fa fa-pencil mx-0.5"></i>
                </a>
                @else
                    @if($detail->warehouse->user->id != Auth::user()->id && $detail->invoice)
                        <a target="_blank" href="{{route('admin.warehousebookings.view-booking-invoice',['booking_agreement_term' => $detail->invoice->id])}}" title="{{__('View Booking Invoice')}}" class="mx-0.5">
                            <i class="fa fa-eye mx-0.5"></i>
                        </a>
                    @endif
                @endif

            @endif


        </td>
    </tr>
@endforeach
