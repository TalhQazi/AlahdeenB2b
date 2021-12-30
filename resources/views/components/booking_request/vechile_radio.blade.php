<div class="grid grid-cols-1 w-full">
    <div class='w-full md:w-full px-3 mb-6'>
        <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'
               for='vehicle_id'>{{ __('Vehicle') }} <span class="text-blue-400">@if(!empty($booking_request->id)){{ "(Selected: $selected_vehicle->name)" }} @endif</span> </label>

        <div class="flex flex-wrap">
            @foreach ($vehicles as $vehicle)
                @if ($vehicle->parent_id == 0)
                    <label class="mx-5">
                        <input class="parent_vehicle" data-index-number="{{ $vehicle->id }}" type="radio"
                               name="vehicle_id"
                               value="{{ $vehicle->id }}" checked>
                        <img style="height: 150px; width: 150px;" src="{{ asset($vehicle->image_path) }}">

                        <h5 style="margin-top: 20px; text-align: center;">{{ $vehicle->name }}</h5>
                    </label>
                @endif
            @endforeach

        </div>

        <div class="my-5">
            <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'
                   for='vehicle_id'>{{ __('Vehicle Specification') }}</label>
            <div id="child_vehicles" class="flex flex-1">
            </div>
        </div>
    </div>
</div>
