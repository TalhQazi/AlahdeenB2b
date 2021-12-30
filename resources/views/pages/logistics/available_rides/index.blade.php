@extends('layouts.master')

@push('styles')
<link rel="stylesheet" href="{{ asset(('css/auto_complete.css')) }}">
@endpush

@section('page')
    @parent
    <div class="card col-span-2 xl:col-span-1">
        <div class="card-header">{{ __('Bookings') }}</div>
        <div class="bg-white pb-4 px-4 rounded-md w-full">

            <div class="w-full overflow-auto">
                <table class="table-auto border-collapse w-full mt-5">
                    <thead>
                        <tr class="rounded-lg text-sm font-medium text-gray-700 text-left" style="font-size: 0.9674rem">
                            <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('SR. #') }}</th>
                            <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Delivery Type') }}</th>
                            <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Shipper Name') }}</th>
                            <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Shipper Address') }}</th>
                            <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Receiver Name') }}</th>
                            <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Receiver Address') }}</th>
                            <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Departure Date') }}</th>
                            <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Create Date') }}</th>
                            <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Controls') }}</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm font-normal text-gray-700" id="search_results">
                        @if (count($bookingReqs) > 0)
                            @foreach ($bookingReqs as $key => $book_req)

                                <tr class="hover:bg-gray-100 border-b border-gray-200 py-10">
                                    <td class="px-4 py-4">{{ $loop->iteration }}</td>
                                    <td class="px-4 py-4">{{ $book_req->delivery_type }}</td>
                                    <td class="px-4 py-4">{{ $book_req->shipper_name }}</td>
                                    <td class="px-4 py-4">{{ $book_req->shipper_address }}</td>
                                    <td class="px-4 py-4">{{ $book_req->receiver_name }}</td>
                                    <td class="px-4 py-4">{{ $book_req->receiver_address }}</td>
                                    <td class="px-4 py-4">{{ $book_req->departure_date }}</td>
                                    <td class="px-4 py-4">{{ $book_req->created_at }}</td>
                                    <td class="px-4 py-4">
                                        <a href="{{route('logistics.booking_request.edit',['booking_request' => $book_req->id])}}" title="{{__('Edit Booking')}}" class="mx-0.5 edit_warehouse_btn">
                                            <i class="fa fa-pencil mx-0.5"></i>
                                        </a>

                                        <a href="{{route('logistics.booking_request.destroy',['booking_request' => $book_req->id])}}" title="{{__('Delete Booking')}}" class="mx-0.5 edit_warehouse_btn">
                                            <i class="fa fa-trash mx-0.5"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr class="hover:bg-gray-100 border-b border-gray-200 py-10">
                                <td colspan="6" class="px-4 py-4 text-center">
                                    {{ __('No Inventory Products Definition found.') }}
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
                <div id="pagination" class="w-full flex justify-center border-t border-gray-100 pt-4 items-center">
                    {{ $bookingReqs->links() }}
                </div>
            </div>

        </div>
    </div>

@endsection

@push('scripts')
    <script>
        var base_url = '{{ config('app.url') }}';
    </script>
    <script type="text/javascript" src="{{ asset(('js/pages/booking_request.js')) }}"></script>
@endpush
