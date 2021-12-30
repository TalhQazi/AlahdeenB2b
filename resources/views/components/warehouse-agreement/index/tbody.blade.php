@foreach ($invoices as $detail)
    <?php
        $bookingType = $detail->booking_type;
        $bookingStatus = $detail->booking_status;
        $bookingFinalStatus = $bookingStatus;
        $isOwner = $detail->warehouse_booking->booked_by->id == Auth::user()->id ? false : true;
    ?>
    @if(!$isOwner || ($isOwner && ($bookingStatus == "confirmed" || $bookingStatus == "paid")))

        <tr class="hover:bg-gray-100 border-b border-gray-200 py-10">
            <td class="px-4 py-4">{{$detail->id}}</td>
            <td class="px-4 py-4">{{$detail->warehouse_booking->warehouse->id}}</td>
            <td class="px-4 py-4">{{$detail->item}}</td>
            <td class="px-4 py-4">{{$detail->start_time}}</td>
            <td class="px-4 py-4">{{$detail->end_time}}</td>
            <td class="px-4 py-4 {{$bookingType == 'partial' ? 'text-red-500': 'text-green-500'}}">{{$bookingType}}</td>
            <td class="px-4 py-4">{{$bookingFinalStatus}}</td>
            <td class="px-4 py-4 {{$detail->deleted_at ? 'text-red-500': 'text-green-500'}}">{{$detail->deleted_at ? 'Deleted': 'On'}}</td>
            <td class="px-4 py-4">{{$detail->created_at}}</td>
            <td class="px-4 py-4">
                @if (!$detail->deleted_at)
                    @if ($bookingStatus == "pending")
                        @if(!$isOwner)
                            <a target="_blank" href="{{route('warehousebookings.invoice-payment',['booking_agreement_term' => $detail->id])}}" title="{{__('Make Payment')}}" class="mx-0.5">
                                <i class="fa fa-shopping-cart mx-0.5"></i>
                            </a>
                        @endif
                    @else
                        <a target="_blank" href="{{route('warehousebookings.view-invoice',['booking_agreement_term' => $detail->id])}}" title="{{__('View Booking Invoice')}}" class="mx-0.5">
                            <i class="fa fa-receipt mx-0.5"></i>
                        </a>
                    @endif

                @endif


            </td>
        </tr>
    @endif
@endforeach
