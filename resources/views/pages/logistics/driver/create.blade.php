@extends('layouts.master')

@push('styles')
    <link rel="stylesheet" href="{{ asset(('css/pages/driver.css')) }}">
@endpush

@section('page')
    @parent
    <div class="card col-span-2 xl:col-span-1">
        <div class="card-header">{{ __('About') }}</div>
        <div class="form-wrapper p-10">
                <form id="driver_info_form" method="POST" enctype="multipart/form-data" action="{{ !empty($driver) ? route('logistics.drivers.update', ['driver' => $driver->id]) : route('logistics.drivers.store') }}">
                    @csrf
                    @if (!empty($driver))
                        @method('PUT')
                    @endif
                    <input type="hidden" name="vehicle_id" id="vehicle_id" value="1">
                        @include('pages.logistics.driver.about')
                        @include('pages.logistics.driver.license')
                        @include('pages.logistics.driver.cnic')
                        @include('pages.logistics.driver.vehicle-info')
                        <div class="flex p-2 mt-4">
                            <div class="flex-auto flex flex-row-reverse">
                                <button id="next_step" class="text-base  ml-2  hover:scale-110 focus:outline-none flex justify-center px-4 py-2 rounded font-bold cursor-pointer
                                    hover:bg-teal-600
                                    bg-teal-600
                                    text-teal-100
                                    border duration-200 ease-in-out
                                    border-teal-600 transition">
                                    {{__('Next')}}
                                </button>
                                <button id="previous_step" class="text-base hover:scale-110 focus:outline-none flex justify-center px-4 py-2 rounded font-bold cursor-pointer
                                    hover:bg-teal-200
                                    bg-teal-100
                                    text-teal-700
                                    border duration-200 ease-in-out
                                    border-teal-600 transition hidden">
                                    {{__('Previous')}}
                                </button>
                            </button>
                            </div>
                        </div>

                </form>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    var base_url = '{{ config('app.url') }}';
</script>
<script type="text/javascript" src="{{ asset(('js/pages/driver_info.js')) }}"></script>
{{-- <script type="text/javascript" src="{{ asset(('js/components/image_reader.js')) }}"></script> --}}
@endpush
