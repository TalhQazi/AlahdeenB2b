<?php
    $old_city = old('city');
    $old_city_id = old('city_id');
    $lat = old('lat');
    $lng = old('lng');
    // $locality = old('locality');
    $locality_id = old('locality_id');

    if(empty($old_city)) {
        $old_city = Auth::user()->city->city;
        $old_city_id = Auth::user()->city->id;
        $lat = Auth::user()->city->lat;
        $lng = Auth::user()->city->lng;
    }

    if(!empty($warehouse)) {
        if(!empty($warehouse->city)) {
            $old_city = $warehouse->city_info->city;
            $old_city_id = $warehouse->city_info->id;
        }

        if(!empty($warehouse->locality)) {
            $locality = $warehouse->locality_info->name;
            $locality_id = $warehouse->locality_info->id;
        }

        $lat = $warehouse->lat;
        $lng = $warehouse->lng;

    }
?>
<div id='location_details_info' class='flex flex-wrap -mx-3 mb-6 form-step hidden'>
    <div class='w-full md:w-full px-3 mb-6'>
        <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2' for='grid-text-1'>{{__('City')}}</label>
        <input class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500' name="city" id='city' value="{{$old_city}}" type='text'>
        <input type="hidden" name="city_id" id="city_id" value="{{$old_city_id}}">
        <input type="hidden" name="lat" id="lat" value="{{$lat}}">
        <input type="hidden" name="lng" id="lng" value="{{$lng}}">
    </div>

    <div class='w-full md:w-full px-3 mb-6'>
        <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2' for='grid-text-1'>{{__('Locality')}}</label>
        
            <select class="block appearance-none text-gray-600 w-full bg-white border border-gray-400 shadow-inner px-4 py-2 pr-8 rounded" name="locality_id" id="locality_id">
                <option value="">{{__('Locality')}}</option>
                @foreach ($locality as $loc)
                    <option value="{{$loc->id}}">{{$loc->name}}</option>
                @endforeach
            </select>
    </div>

    <div class='w-full md:w-full px-3 mb-6'>
        <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2' for='grid-text-1'>{{__('Choose warehouse location on map')}}</label>
        <div id="map" class="w-full md:w-full px-3 mb-6" style="height:300px;"></div>
    </div>
    
</div>
