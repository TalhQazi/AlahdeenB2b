<?php
    $property_type_id = old('property_type_id');
    $area = old('area');
    $price = old('price');
    $can_be_shared = old('can_be_shared');

    if(!empty($warehouse)) {
        $property_type_id = $warehouse->property_type->id;
        $area = $warehouse->area;
        $price = $warehouse->price;
        $can_be_shared = $warehouse->can_be_shared;
    }
?>
<div id='basic_info' class='flex flex-wrap -mx-3 mb-6 form-step'>
    <div class='w-full md:w-full px-3 mb-6'>
        <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'>{{__('Select Warehouse Type')}}</label>
        <div class="flex-shrink w-full inline-block relative">
            <select class="block appearance-none text-gray-600 w-full bg-white border border-gray-400 shadow-inner px-4 py-2 pr-8 rounded" name="property_type_id" id="property_type_id">
                <option value="">{{__('Select Warehouse Type')}}</option>
                @foreach ($property_types as $property_type)
                    <option <?php echo $property_type_id == $property_type->id ? 'selected' : ''; ?>  value="{{$property_type->id}}">{{$property_type->title}}</option>
                @endforeach
            </select>
            <div class="pointer-events-none absolute top-0 mt-3  right-0 flex items-center px-2 text-gray-600">
                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
            </div>
        </div>
    </div>
    <div class='w-full md:w-full px-3 mb-6'>
        <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2' for='grid-text-1'>{{__('Area in sq.ft.')}}</label>
        <input class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500' name='area' id='area' type='text' value='{{$area}}'>
    </div>
    <div class='w-full md:w-full px-3 mb-6'>
        <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2' for='grid-text-1'>{{__('Rent per month')}}</label>
        <input class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500' name='price' id='price' type='text' value='{{$price}}'>
    </div>
    <div class='w-full md:w-full px-3 mb-6'>
        <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2' for='grid-text-1'>{{__('Can be shared')}}</label>
        <input class='block bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500' name='can_be_shared' id='can_be_shared' type='checkbox' <?php if(!empty($can_be_shared)) { echo "checked"; } ?>>
    </div>
</div>
