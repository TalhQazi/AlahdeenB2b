<select class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500 select2'
    name="sub_category_id" id="sub_category_id"
>   
    <option value="">{{ __('Select Sub Category') }}</option>
    @foreach($list as $item)
        <option value="{{ $item->id }}">{{ $item->title }}</option>
    @endforeach

</select>