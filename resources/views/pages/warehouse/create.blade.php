@extends('layouts.master')

@push('styles')
    <link href="https://api.mapbox.com/mapbox-gl-js/v2.0.1/mapbox-gl.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset(('css/pages/warehouse_add.css')) }}">
@endpush


@section('page')
    @include('components.warehouse.steps')
    <div class="min-h-screen pt-2 font-mono">
        <div class="container mx-auto">
            <div class="inputs w-full p-6">
                <h2 class="text-2xl text-gray-900">{{__('Add Warehouse')}}</h2>
                <form id="add_warehouse_form" class="mt-6 border-t border-gray-400 pt-4" enctype="multipart/form-data" method="POST" action="{{route('warehouse.store')}}">
                    @csrf

                        @include('components.warehouse.basic-info')
                        @include('components.warehouse.location-info')
                        @include('components.warehouse.features-info')
                        @include('components.warehouse.image-info')
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
    </div>

@endsection

@push('scripts')
    <script>
        var base_url = '{{ config('app.url') }}';
    </script>
    <script type="text/javascript" src="{{ asset(('js/pages/warehouse_add.js')) }}"></script>
@endpush

