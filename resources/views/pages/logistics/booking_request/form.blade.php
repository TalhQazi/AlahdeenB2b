@extends('layouts.master')

@push('styles')
    <link rel="stylesheet" href="{{ asset(('css/auto_complete.css')) }}">

    <style>
        /* HIDE RADIO */
        [type=radio] {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }

        /* IMAGE STYLES */
        [type=radio] + img {
            cursor: pointer;
        }

        /* CHECKED STYLES */
        [type=radio]:checked + img {
            outline: 2px solid #9ae6b4;
        }
        #pac-input_two
        {
            width: 200px;
            padding: 10px;
        }

    </style>
@endpush

@section('page')

    <div class="card col-span-2 xl:col-span-1 margion mt-5">
        <div class="bg-white rounded-md w-full">
            <div class="pt-2 font-mono">
                <div class="container mx-auto">
                    <div class="inputs w-full p-6">
                        <h2 class="text-2xl text-gray-900">{{ __('Booking Request Form') }}</h2>
                        <form id="add_catalog_form" class="mt-6 border-t border-gray-400 pt-4"
                              enctype="multipart/form-data"
                              method="POST" action="
                                           @if (!isset($booking_request->id))
                        {{ route('logistics.booking_request.store') }}
                        @else
                        {{ route('logistics.booking_request.update', ['booking_request' => $booking_request->id]) }}
                        @endif
                            ">

                            @csrf
                            @if (isset($booking_request->id))
                                @method('PUT')
                            @endif

                            <input id="pick_up_city_id" name="pick_up_city_id" type='hidden'
                                   value="{{ $booking_request->pick_up_city_id ?? old('pick_up_city_id') }}">

                            <input id="drop_off_city_id" name="drop_off_city_id" type='hidden'
                                   value="{{ $booking_request->drop_off_city_id ?? old('drop_off_city_id') }}">

                            <div class='flex flex-wrap -mx-3 mb-6'>

                                {{-- ! Select for delivery type --}}
                                <div class="grid grid-cols-1 w-full">
                                    <div class='w-full md:w-full px-3 mb-6'>
                                        <label
                                            class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'
                                            for='delivery_type'>{{ __('Delivery Type') }}</label>

                                        <select
                                            class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500'
                                            name="delivery_type" id="delivery_type"
                                        >
                                            <option value="null">{{ __('Select an option') }}</option>
                                            @foreach ($delivery_types as $key => $delivery_type)
                                                <option value="{{ $key }}"
                                                    {{ isset($booking_request->delivery_type) && $booking_request->delivery_type == $key ? 'selected' : '' }}
                                                >
                                                    {{ $delivery_type }}
                                                </option>
                                            @endforeach

                                        </select>
                                    </div>
                                </div>

                                {{-- ! Select for vehicle --}}
                                @include('components.booking_request.vechile_radio')

                                <div class="grid md:grid-cols-1 grid-cols-2 w-full">
                                    <div class='w-full md:w-full px-3 mb-6'>
                                        <label
                                            class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'
                                            for='vehicle_type'>{{ __('Pick Up City') }}</label>
                                        <select
                                            class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500'
                                            name="pick_up_city_id" id='pick_up_city_id'
                                            value="{{ $booking_request->pick_up_city_id ?? old('pick_up_city_id') }}">
                                            <option value="">Select pick up city</option>
                                            @foreach(city_dropdown() as $item)
                                                    <option value="{{ $item->id }}">{{ __($item->city) }}</option>
                                                @endforeach
                                        </select>
                                    </div>

                                    <div class='w-full md:w-full px-3 mb-6'>
                                        <label
                                            class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'
                                            for='drop_off_city_id'>{{ __('Drop Off City') }}</label>
                                        <select
                                            class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500'
                                            name="drop_off_city_id" id='drop_off_city_id'
                                            value="{{ $booking_request->drop_off_city_id ?? old('drop_off_city_id') }}">
                                            <option value="">Select Drop Off City</option>
                                            @foreach(city_dropdown() as $item)
                                                <option value="{{ $item->id }}">{{ __($item->city) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div id="country_intl" class="grid md:grid-cols-1 grid-cols-2 w-full hidden">
                                    <div class='w-full md:w-full px-3 mb-6'>
                                        <label
                                            class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'
                                            for='pick_up_country'>{{ __('Pick Up Country') }}</label>
                                        <input
                                            class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500'
                                            name="pick_up_country" id='pick_up_country' type='text'
                                            value="{{ $booking_request->pick_up_country ?? old('pick_up_country') }}">
                                    </div>

                                    <div class='w-full md:w-full px-3 mb-6'>
                                        <label
                                            class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'
                                            for='drop_off_country'>{{ __('Drop Off Country') }}</label>
                                        <input
                                            class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500'
                                            name="drop_off_country" id='drop_off_country' type='text'
                                            value="{{ $booking_request->drop_off_country ?? old('drop_off_country') }}">
                                    </div>
                                </div>

                                <div class="grid md:grid-cols-1 grid-cols-2 w-full">
                                    <div class='w-full md:w-full px-3 mb-6'>
                                        <label
                                            class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'
                                            for='shipper_name'>{{ __('Shipper Name') }}</label>
                                        <input
                                            class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500'
                                            name="shipper_name" id='shipper_name' type='text'
                                            value="{{ $booking_request->shipper_name ?? old('shipper_name') }}">
                                    </div>

                                    <div class='w-full md:w-full px-3 mb-6'>
                                        <label
                                            class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'
                                            for='receiver_name'>{{ __('Receiver Name') }}</label>
                                        <input
                                            class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500'
                                            name="receiver_name" id='receiver_name' type='text'
                                            value="{{ $booking_request->receiver_name ?? old('receiver_name') }}">
                                    </div>
                                </div>

                                <div class="grid md:grid-cols-1 grid-cols-2 w-full">
                                    <div class='w-full md:w-full px-3 mb-6'>
                                        <label
                                            class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'
                                            for='shipper_contact_number'>{{ __('Shipper Contact Number') }}</label>
                                        <input
                                            class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500'
                                            name="shipper_contact_number" id='shipper_contact_number' type='text'
                                            value="{{ $booking_request->shipper_contact_number ?? old('shipper_contact_number') }}">
                                    </div>

                                    <div class='w-full md:w-full px-3 mb-6'>
                                        <label
                                            class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'
                                            for='receiver_contact_number'>{{ __('Receiver Contact Number') }}</label>
                                        <input
                                            class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500'
                                            name="receiver_contact_number" id='receiver_contact_number' type='text'
                                            value="{{ $booking_request->receiver_contact_number ?? old('receiver_contact_number') }}">
                                    </div>
                                </div>

                                <div class="grid md:grid-cols-1 grid-cols-2 w-full">
                                    <div class='w-full md:w-full px-3 mb-6'>
                                        <label
                                            class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'
                                            for='shipper_address'>{{ __('Shipper Address') }}</label>
                                        <input
                                            class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500'
                                            name="shipper_address" id='shipper_address' type='text'
                                            value="{{ $booking_request->shipper_address ?? old('shipper_address') }}">
                                    </div>

                                    <div class='w-full md:w-full px-3 mb-6'>
                                        <label
                                            class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'
                                            for='receiver_address'>{{ __('Receiver Address') }}</label>
                                        <input
                                            class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500'
                                            name="receiver_address" id='receiver_address' type='text'
                                            value="{{ $booking_request->receiver_address ?? old('receiver_address') }}">
                                    </div>
                                </div>

                                <div class="grid md:grid-cols-1 w-full">
                                    <div class='w-full md:w-full px-3 mb-6'>
                                        @include('components.mapsSearchContainer.map_two')
                                    </div>
                                </div>

                                <div class="grid md:grid-cols-1 grid-cols-2 w-full">
                                    <div class='w-full md:w-full px-3 mb-6'>
                                        <label
                                            class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'
                                            for='vehicle_type'>{{ __('Vehicle Type') }}</label>
                                        <select
                                            class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500'
                                            name="vehicle_type" id='vehicle_type'
                                            value="{{ $booking_request->vehicle_type ?? old('vehicle_type') }}">

                                            @foreach ($vehicle_types as $vehicle_type)
                                                <option value="{{ $vehicle_type }}">{{ $vehicle_type }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class='w-full md:w-full px-3 mb-6'>
                                        <label
                                            class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'
                                            for='departure_date'>{{ __('Departure Date') }}</label>
                                        <input
                                            class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500'
                                            name="departure_date" id='departure_date' type='date'
                                            value="{{ $booking_request->departure_date ?? old('departure_date') }}">
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 w-full">


                                    <div class='w-full md:w-full px-3 mb-6'>
                                        <label
                                            class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'
                                            for='departure_time'>{{ __('Departure Time') }}</label>
                                        <input
                                            class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500'
                                            name="departure_time" id='departure_time' type='time'
                                            value="{{ !empty($booking_request->departure_time) ? str_replace(':00', '', $booking_request->departure_time) : old('departure_time') }}">
                                    </div>
                                </div>

                                <div class="grid md:grid-cols-1 grid-cols-2 w-full">
                                    <div class="grid grid-cols-2 w-full">
                                        <div class='w-full md:w-full px-3 mb-6'>
                                            <label
                                                class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'
                                                for='weight'>{{ __('Weight') }}</label>
                                            <input
                                                class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500'
                                                name="weight" id='weight' type='number'
                                                value="{{ $booking_request->weight ?? old('weight') }}">
                                        </div>

                                        <div class='w-full md:w-full px-3 mb-6'>
                                            <label
                                                class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'
                                                for='weight_unit'>{{ __('Weight Unit') }}</label>

                                            <select
                                                class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500'
                                                name="weight_unit"
                                                value="{{ $booking_request->weight_unit ?? old('weight_unit') }}">

                                                @foreach ($weight_units as $key => $weight_unit)
                                                    <option value="{{ $key }}">{{ $weight_unit }}</option>
                                                @endforeach
                                            </select>

                                        </div>
                                    </div>

                                    <div class='w-full md:w-full px-3 mb-6'>
                                        <label
                                            class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'
                                            for='bid_offer'>{{ __('Bid Offer') }}</label>
                                        <input
                                            class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500'
                                            name="bid_offer" id='bid_offer' type='text'
                                            value="{{ $booking_request->bid_offer ?? old('bid_offer') }}">
                                    </div>
                                </div>

                                <div class="grid md:grid-cols-1 grid-cols-2 w-full">
                                    <div class="grid grid-cols-2 w-full">
                                        <div class='w-full md:w-full px-3 mb-6'>
                                            <label
                                                class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'
                                                for='volume'>{{ __('Volume') }}</label>
                                            <input
                                                class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500'
                                                name="volume" id='volume' type='number'
                                                value="{{ $booking_request->volume ?? old('volume') }}">
                                        </div>

                                        <div class='w-full md:w-full px-3 mb-6'>
                                            <label
                                                class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'
                                                for='volume_unit'>{{ __('Volume Unit') }}</label>
                                            <select
                                                class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500'
                                                name="volume_unit"
                                                value="{{ $booking_request->volume_unit ?? old('volume_unit') }}">

                                                @foreach ($volume_units as $key => $volume_unit)
                                                    <option value="{{ $key }}">{{ $volume_unit }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class='w-full md:w-full px-3 mb-6'>
                                        <label
                                            class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'
                                            for='comments_and_wishes'>{{ __('Comments And Wishes') }}</label>
                                        <input
                                            class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500'
                                            name="comments_and_wishes" id='comments_and_wishes' type='text'
                                            value="{{ $booking_request->comments_and_wishes ?? old('comments_and_wishes') }}">
                                    </div>
                                </div>

                                <div class="grid md:grid-cols-1 grid-cols-2 w-full">
                                    <div class="grid grid-cols-2 w-full">

                                        <div class='w-full md:w-full px-3 mb-6'>
                                            <label for='terms_agreed'>{{ __('Agree to terms and conditions') }}</label>
                                            <input name="terms_agreed" id='terms_agreed' type='checkbox'
                                                {{ isset($booking_request->terms_agreed) ? 'checked' : ' ' }}>
                                        </div>
                                    </div>
                                </div>

                                @include('pages.logistics.booking_request.consignment_comp')

                                <div class="personal w-full border-t border-gray-400 pt-4">
                                    <div class="flex justify-end">
                                        <button
                                            class="border border-gray-700 bg-gray-700 text-white rounded-md px-4 py-2 m-2 transition duration-500 ease select-none hover:bg-gray-800 focus:outline-none focus:shadow-outline"
                                            type="button">{{ __('Save Purchase Return') }}</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        var base_url = '{{ config('app.url') }}';
        var vehicles = {!! json_encode($vehicles->toArray(), JSON_HEX_TAG) !!};
    </script>
    <script type="text/javascript" src="{{ asset(('js/pages/booking_request.js')) }}"></script>
@endpush
