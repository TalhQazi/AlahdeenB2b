@extends('layouts.master')

@push('styles')

@endpush


@section('page')
    <div class="min-h-screen pt-2 font-mono">
        <div class="container mx-auto">
            <div class="inputs w-full p-6">
                <h2 class="text-2xl text-gray-900">{{__('Warehouse Agreement')}}</h2>
                <form id="booking_agreement_form" class="mt-6 border-t border-gray-400 pt-4" method="POST" action="{{route('admin.warehousebookings.store-agreement',['warehouse_booking' => $warehouse_booking->id])}}">
                    @csrf
                    <div class='flex flex-wrap -mx-3 mb-6'>
                        <div class='w-full md:w-full px-3 mb-6'>
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="title">
                                {{ __('Item') }}
                                <i class="fa fa-asterisk text-red-500"></i>
                            </label>
                            <input class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none focus:border-gray-500" name="item" id="item" type="text" value="{{$warehouse_booking->item}}">
                        </div>
                        <div class='w-full md:w-full px-3 mb-6'>
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="description">
                                {{__('Details')}}
                                <i class="fa fa-asterisk text-red-500"></i>
                            </label>
                            <textarea name="description" id="description" cols="30" rows="10" class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500'>{{$warehouse_booking->description}}</textarea>
                        </div>
                        <div class='w-full md:w-full px-3 mb-6'>
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="start date">
                                {{ __('Start Date') }}
                                <i class="fa fa-asterisk text-red-500"></i>
                            </label>
                            <input class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500" name="start_date" id="start_date" type="text" value="{{$warehouse_booking->start_date}}">
                        </div>
                        <div class='w-full md:w-full px-3 mb-6'>
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="start time">
                                {{ __('Start Time') }}
                                <i class="fa fa-asterisk text-red-500"></i>
                            </label>
                            <input class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500" name="start_time" id="start_time" type="text" value="{{$warehouse_booking->time_start}}">
                        </div>
                        <div class='w-full md:w-full px-3 mb-6'>
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="end date">
                                {{ __('End Date') }}
                                <i class="fa fa-asterisk text-red-500"></i>
                            </label>
                            <input class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500" name="end_date" id="end_date" type="text" value="{{$warehouse_booking->end_date}}">
                        </div>
                        <div class='w-full md:w-full px-3 mb-6'>
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="start time">
                                {{ __('End Time') }}
                                <i class="fa fa-asterisk text-red-500"></i>
                            </label>
                            <input class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500" name="end_time" id="end_time" type="text" value="{{$warehouse_booking->time_end}}">
                        </div>
                        <div class='w-full md:w-full px-3 mb-6'>
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="quantity">
                                {{ __('Quantity') }}
                            </label>
                            <input class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none focus:border-gray-500" name="quantity" id="quantity" type="text" value="{{$warehouse_booking->quantity}}">
                        </div>
                        <div class='w-full md:w-full px-3 mb-6'>
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="unit">
                                {{ __('Unit') }}
                            </label>
                            <select class="block appearance-none text-gray-600 w-full bg-white border border-gray-400 shadow-inner px-4 py-2 pr-8 rounded" name="unit" id="unit">
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
                            <input class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none focus:border-gray-500" name="goods_value" id="goods_value" type="text" value="{{$warehouse_booking->goods_value}}">
                        </div>
                        <div class='w-full md:w-full px-3 mb-6'>
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="booking type">
                                {{ __('Booking Type') }}
                            </label>
                            <select class="block appearance-none text-gray-600 w-full bg-white border border-gray-400 shadow-inner px-4 py-2 pr-8 rounded" name="type" id="type">
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
                            <input class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none focus:border-gray-500" name="area" id="area" type="text" value="{{$warehouse_booking->area}}">
                        </div>
                        <div class='w-full md:w-full px-3 mb-6'>
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="goods value">
                                {{ __('Price') }}
                            </label>
                            <input class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none focus:border-gray-500" name="price" id="price" type="text" value="{{$price}}">
                        </div>
                        <div class='w-full md:w-full px-3 mb-6'>
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="description">
                                {{__('Terms for user')}}
                                <i class="fa fa-asterisk text-red-500"></i>
                            </label>
                            <textarea name="user_terms" id="user_terms" cols="30" rows="10" class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500'></textarea>
                        </div>
                        <div class='w-full md:w-full px-3 mb-6'>
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="description">
                                {{__('Terms for warehouse owner')}}
                                <i class="fa fa-asterisk text-red-500"></i>
                            </label>
                            <textarea name="owner_terms" id="owner_terms" cols="30" rows="10" class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500'></textarea>
                        </div>
                    </div>
                    <div class="my-5 float-right">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" id="confirm-add-btn">
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        var base_url = '{{ config('app.url ') }}';
    </script>
    <script type="text/javascript" src="{{ asset(('js/pages/warehouse_booking_agreement.js')) }}"></script>
@endpush

