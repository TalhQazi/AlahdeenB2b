<?php
    $old_features = old('feature_name');

    $stored_features = '';
    if(!empty($warehouse) && !empty($warehouse->features->data)) {
        $stored_features = $warehouse->features->data;
    }

?>

<div id='features_info' class='flex flex-wrap -mx-3 mb-6 form-step hidden'>
@if (empty($old_features) && empty($stored_features))
        @foreach ($features as $feature)
            @if ($feature->key_type == 'boolean')
                <div class='w-full md:w-full px-3 mb-6'>
                    <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2' for='grid-text-1'>{{__("$feature->key")}}</label>
                    <input class='block bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500' name='feature_name[{{$feature->id}}]' id='feature_name_{{$feature->id}}' type='checkbox'>
                </div>
            @else
                <div class='w-full md:w-full px-3 mb-6'>
                    <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2' for='grid-text-1'>{{__("$feature->key")}}</label>
                    <input class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500' name='feature_name[{{$feature->id}}]' id='feature_name_{{$feature->id}}' type='number'>
                </div>
            @endif

        @endforeach

@elseif (!empty($stored_features))
    @foreach($features as $feature)
        @if ($feature->key_type == 'boolean')
            <div class='w-full md:w-full px-3 mb-6'>
                <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2' for='grid-text-1'>{{__("$feature->key")}}</label>
                <input class='block bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500' name='feature_name[{{$feature->id}}]' id='feature_name_{{$feature->id}}' type='checkbox' <?php if(!empty($stored_features->{$feature->id})) {echo 'checked';} ?> >
            </div>
        @else
            <div class='w-full md:w-full px-3 mb-6'>
                <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2' for='grid-text-1'>{{__("$feature->key")}}</label>
                <input class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500' name='feature_name[{{$feature->id}}]' id='feature_name_{{$feature->id}}' type='number' value='{{ !empty($stored_features->{$feature->id}) ? $stored_features->{$feature->id}->feature : ''}}'>
            </div>
        @endif
    @endforeach

@else {{dd(1)}}
    @foreach($features as $feature)
        @if ($feature->key_type == 'boolean')
                <div class='w-full md:w-full px-3 mb-6'>
                    <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2' for='grid-text-1'>{{__("$feature->key")}}</label>
                    <input class='block bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500' name='feature_name[{{$feature->id}}]' id='feature_name_{{$feature->id}}' type='checkbox' <?php if(!empty($old_features[$feature->id])) {echo 'checked';} ?> >
                </div>
            @else
                <div class='w-full md:w-full px-3 mb-6'>
                    <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2' for='grid-text-1'>{{__("$feature->key")}}</label>
                    <input class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500' name='feature_name[{{$feature->id}}]' id='feature_name_{{$feature->id}}' type='number' value='{{$old_features[$feature->id]}}'>
                </div>
            @endif
    @endforeach
@endif
</div>
