@extends('layouts.master')


@section('page')
    <div class="min-h-screen pt-2 font-mono">
        <div class="container mx-auto">
            <div class="inputs w-full p-6">
                <h2 class="text-2xl text-gray-900">{{__('Invoice')}}</h2>
                    <div class='flex flex-wrap -mx-3 mb-6 mt-6 border-t border-gray-400 pt-4'>
                        <div class='w-full md:w-full px-3 mb-6'>
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="title">
                                {{ __('Item') }}

                            </label>
                            <input class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none focus:border-gray-500" name="item" id="item" type="text" value="{{$warehouse_booking->item}}" disabled>
                        </div>
                        <div class='w-full md:w-full px-3 mb-6'>
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="description">
                                {{__('Details')}}

                            </label>
                            <textarea name="description" id="description" cols="30" rows="10" class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500' disabled>{{$warehouse_booking->description}}</textarea>
                        </div>
                        <div class='w-full md:w-full px-3 mb-6'>
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="start date">
                                {{ __('Start Date') }}

                            </label>
                            <input class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500" name="start_date" id="start_date" type="text" value="{{$warehouse_booking->start_date}}" disabled>
                        </div>
                        <div class='w-full md:w-full px-3 mb-6'>
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="start time">
                                {{ __('Start Time') }}

                            </label>
                            <input class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500" name="start_time" id="start_time" type="text" value="{{$warehouse_booking->time_start}}" disabled>
                        </div>
                        <div class='w-full md:w-full px-3 mb-6'>
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="end date">
                                {{ __('End Date') }}

                            </label>
                            <input class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500" name="end_date" id="end_date" type="text" value="{{$warehouse_booking->end_date}}" disabled>
                        </div>
                        <div class='w-full md:w-full px-3 mb-6'>
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="start time">
                                {{ __('End Time') }}

                            </label>
                            <input class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500" name="end_time" id="end_time" type="text" value="{{$warehouse_booking->time_end}}" disabled>
                        </div>
                        <div class='w-full md:w-full px-3 mb-6'>
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="quantity">
                                {{ __('Quantity') }}
                            </label>
                            <input class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none focus:border-gray-500" name="quantity" id="quantity" type="text" value="{{$warehouse_booking->quantity}}" disabled>
                        </div>
                        <div class='w-full md:w-full px-3 mb-6'>
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="unit">
                                {{ __('Unit') }}
                            </label>
                            <select class="block appearance-none text-gray-600 w-full bg-white border border-gray-400 shadow-inner px-4 py-2 pr-8 rounded" name="unit" id="unit" disabled>
                                <option value="">{{__('Quantity')}}</option>
                                @foreach ($quantity_units as $unit)
                                    <option value="{{$unit}}" <?php echo $unit == $warehouse_booking->unit ? 'selected' : '';?>>{{$unit}}</option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute top-0 mt-3  right-0 flex items-center px-2 text-gray-600">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                            </div>
                        </div>
                        <div class='w-full md:w-full px-3 mb-6'>
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="goods value">
                                {{ __('Goods Value') }}
                            </label>
                            <input class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none focus:border-gray-500" name="goods_value" id="goods_value" type="text" value="{{$warehouse_booking->goods_value}}" disabled>
                        </div>
                        <div class='w-full md:w-full px-3 mb-6'>
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="booking type">
                                {{ __('Booking Type') }}
                            </label>
                            <select class="block appearance-none text-gray-600 w-full bg-white border border-gray-400 shadow-inner px-4 py-2 pr-8 rounded" name="type" id="type" disabled>
                                <option value="fully" <?php echo $warehouse_booking->booking_type == 'fully' ? 'selected' : ''; ?> >{{__('Fully')}}</option>
                                <option value="partial" <?php echo $warehouse_booking->booking_type == 'partial' ? 'selected' : ''; ?> >{{__('Partial')}}</option>
                            </select>
                            <div class="pointer-events-none absolute top-0 mt-3  right-0 flex items-center px-2 text-gray-600">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                            </div>
                        </div>
                        @if ($warehouse_booking->booking_type == 'fully')
                        <div class='w-full md:w-full px-3 mb-6 area hidden'>
                        @else
                        <div class='w-full md:w-full px-3 mb-6 area'>
                        @endif
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="area">
                                {{ __('Area Required') }}
                            </label>
                            <input class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none focus:border-gray-500" name="area" id="area" type="text" value="{{$warehouse_booking->area}}" disabled>
                        </div>
                        @if($is_owner)
                        <div class='w-full md:w-full px-3 mb-6'>
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="description">
                                {{__('Terms and Conditions')}}

                            </label>
                            <textarea name="owner_terms" id="owner_terms" cols="30" rows="10" class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500' disabled>{{$warehouse_booking->owner_terms}}</textarea>
                        </div>
                        @else
                        <div class='w-full md:w-full px-3 mb-6'>
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="goods value">
                                {{ __('Total Amount to be paid') }}
                            </label>
                            <input class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none focus:border-gray-500" name="price" id="price" type="text" value="{{$warehouse_booking->price}}" disabled>
                        </div>
                        <div class='w-full md:w-full px-3 mb-6'>
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="description">
                                {{__('Terms and Conditions')}}

                            </label>
                            <textarea name="user_terms" id="user_terms" cols="30" rows="10" class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500' disabled>{{$warehouse_booking->user_terms}}</textarea>
                        </div>
                        @endif
                    </div>


            </div>
        </div>
    </div>

@endsection


