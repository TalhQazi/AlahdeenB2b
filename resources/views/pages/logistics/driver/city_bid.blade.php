@extends('layouts.master')

@push('styles')
    <link rel="stylesheet" href="{{ asset(('css/auto_complete.css')) }}">

    <style>
        .card-main {
            border: 1px solid black;
            border-radius: 5px;
            padding: 20px;
            margin-bottom: 20px;
            margin-top: 20px;
        }

        .list-card-section {
            padding: 10px;
            border: 1px solid black;
            width: fit-content;
            border-radius: 10px;
            background-color: #dadada70;
            margin-bottom: 10px;
        }

        .disable_anchor {
            pointer-events: none;
            cursor: default;
            background-color: #dadada70;
            color: black;
        }

    </style>
@endpush

@section('page')
    @parent
    <div class="card col-span-2 xl:col-span-1">
        <div class="card-header">{{ __('Bookings In Your City') }}</div>
        <div class="bg-white pb-4 px-4 rounded-md w-full">
            <div class="w-full flex justify-end px-2 mt-2">
                <div class="sm:w-64 inline-block relative ">
                    <input type="text" id="search_city"
                        class="leading-snug border border-gray-300 block w-full appearance-none bg-gray-100 text-sm text-gray-600 py-1 px-4 pl-8 rounded-lg"
                        placeholder="Search" />

                    <div class="pointer-events-none absolute pl-3 inset-y-0 left-0 flex items-center px-2 text-gray-300">
                        {{-- <svg class="fill-current h-3 w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 511.999 511.999">
                            <path
                                d="M508.874 478.708L360.142 329.976c28.21-34.827 45.191-79.103 45.191-127.309C405.333 90.917 314.416 0 202.666 0S0 90.917 0 202.667s90.917 202.667 202.667 202.667c48.206 0 92.482-16.982 127.309-45.191l148.732 148.732c4.167 4.165 10.919 4.165 15.086 0l15.081-15.082c4.165-4.166 4.165-10.92-.001-15.085zM202.667 362.667c-88.229 0-160-71.771-160-160s71.771-160 160-160 160 71.771 160 160-71.771 160-160 160z" />
                        </svg> --}}
                    </div>
                </div>
            </div>
            @if (count($bookingReqs) > 0)
                @foreach ($bookingReqs as $booking_request)
                    <div class="card-main">
                        <div class="grid grid-cols-4">

                            @if (!empty($booking_request->accepted_ride))
                                <section class="row-span-1">
                                    <div class="border-separate">
                                        <img src="{{ asset($booking_request->accepted_ride->driver->image) }}"
                                            alt="car_img" class="w-full">
                                    </div>

                                    <p>Driver Name: <span
                                            class="text-black">
                                            {{ $booking_request->accepted_ride->driver->user->name }}
                                            ({{ $booking_request->accepted_ride->driver->license_number }})
                                        </span></p>
                                    <p>Created at: {{ $booking_request->created_at }}
                                    </p>
                                </section>

                            @endif

                            <section class="row-span-2">
                                <div class="list-card-section">
                                    {{ $booking_request->pick_up_city->city }} - to -
                                    {{ $booking_request->drop_off_city->city }}
                                </div>
                                <div class="list-card-section">
                                    <span class="px-3"
                                        style="border-right: 2px dashed black;">{{ $booking_request->vehicle->name }}</span>
                                    <span class="px-3"
                                        style="border-right: 2px dashed black;">{{ $booking_request->weight }}
                                        {{ $booking_request->weight_unit }}</span>
                                    <span class="px-3">{{ $booking_request->vehicle_type }}</span>
                                </div>
                            </section>

                            <section class="row-span-1">
                                <div class="list-card-section">
                                    <p>PKR {{ $booking_request->bid_offer }}</p>
                                </div>
                                <div class="list-card-section">
                                    <p>1.1 Km</p>
                                    <small>5 mins away</small>
                                </div>
                            </section>

                            <section class="row-span-1">
                                <div class="text-center">
                                    @if ($booking_request->accepted_ride)

                                        <a href="{{ route('logistics.avaiable_ride.start_ride', ['booking_request' => $booking_request->id]) }}"
                                            class="btn btn-info mb-4 {{ !empty($booking_request->accepted_ride->start_time) ? 'disable_anchor' : '' }}">Start
                                            Ride</a>

                                        <a href="{{ route('logistics.avaiable_ride.end_ride', ['booking_request' => $booking_request->id]) }}"
                                            class="btn btn-danger {{ !empty($booking_request->accepted_ride->end_time) ? 'disable_anchor' : '' }}">End
                                            Ride</a>

                                    @else
                                        <a href="{{ route('logistics.avaiable_ride.accept', ['booking_request' => $booking_request->id]) }}"
                                            class="btn btn-success">Accept</a>
                                    @endif
                                </div>
                            </section>
                        </div>
                    </div>
                @endforeach

            @else

                <div class="my-4">
                    <h2 class="text-black text-center">No bookings in your city</h2>
                </div>

            @endif

            <div id="pagination" class="w-full flex justify-center border-t border-gray-100 pt-4 items-center">
                {{ $paginator }}
            </div>

        </div>
    </div>

@endsection

@push('scripts')
    <script>
        var base_url = '{{ config('app.url') }}';
    </script>
    <script type="text/javascript" src="{{ asset(('js/pages/city_bid.js')) }}"></script>
@endpush
