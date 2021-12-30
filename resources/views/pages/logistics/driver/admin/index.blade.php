@extends('layouts.master')

@section('page')
    @parent
    <!-- Start Recent Sales -->
    <div class="card col-span-2 xl:col-span-1">
        <div class="card-header">{{ __('Drivers List') }}</div>
        <div class="bg-white pb-4 px-4 rounded-md w-full">
            <div class="w-full flex justify-end px-2 mt-2">
                <div class="sm:w-64 inline-block relative ">
                    <div class="pointer-events-none absolute pl-3 inset-y-0 left-0 flex items-center px-2 text-gray-300">
                        <svg class="fill-current h-3 w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 511.999 511.999">
                            <path d="M508.874 478.708L360.142 329.976c28.21-34.827 45.191-79.103 45.191-127.309C405.333 90.917 314.416 0 202.666 0S0 90.917 0 202.667s90.917 202.667 202.667 202.667c48.206 0 92.482-16.982 127.309-45.191l148.732 148.732c4.167 4.165 10.919 4.165 15.086 0l15.081-15.082c4.165-4.166 4.165-10.92-.001-15.085zM202.667 362.667c-88.229 0-160-71.771-160-160s71.771-160 160-160 160 71.771 160 160-71.771 160-160 160z" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="overflow-x-auto mt-6 search_results" id="advertisments">
                <table class="table-auto border-collapse w-full">
                    <thead>
                        <tr class="rounded-lg text-sm font-medium text-gray-700 text-left" style="font-size: 0.9674rem">
                        <th class="px-4 py-2 bg-gray-200 " style="background-color:#f8f8f8">{{ __('ID') }}</th>
                        <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Image') }}</th>
                        <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Name') }}</th>
                        <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Date of Birth') }}</th>
                        <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('License Number') }}</th>
                        <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Expiry Date') }}</th>
                        <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Company Name') }}</th>
                        <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Number Plate') }}</th>
                        <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Is Verified') }}</th>
                        <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Controls') }}</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm font-normal text-gray-700">
                        @foreach ($drivers as $driver)
                        <tr class="hover:bg-gray-100 border-b border-gray-200 py-10">
                            <td class="px-4 py-4">{{ $driver->id }}</td>
                            <td class="px-4 py-4">
                                <img class="w-auto object-cover object-center h-10" id="certification_img" src="{{ url(Storage::url($driver->image)) }}" alt="{{ __('Driver Image') }}" />
                            </td>
                            <td class="px-4 py-4">{{ $driver->user->name }}</td>
                            <td class="px-4 py-4">{{ $driver->dob }}</td>
                            <td class="px-4 py-4">{{ $driver->license_number }}</td>
                            <td class="px-4 py-4">{{ $driver->license_expiry_date }}</td>
                            <td class="px-4 py-4">{{ $driver->vehicle->company_name }}</td>
                            <td class="px-4 py-4">{{ $driver->vehicle->number_plate_no }}</td>
                            <td class="px-4 py-4 {{$driver->is_verified ? 'text-green-500': 'text-red-500'}}">{{$driver->is_verified ? __('Yes') : __('No') }}</td>
                            <td class="px-4 py-4">
                                <a href="{{ route('admin.logistics.driver.show', ['driver' => $driver->id])}}" title="{{__('Show Driver Details')}}" class="mx-0.5">
                                    <i class="fas fa-eye mx-0.5"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div id="pagination" class="w-full flex justify-center border-t border-gray-100 pt-4 items-center">
                    {{$drivers->links()}}
                </div>
            </div>

        </div>

        <style>

        thead tr th:first-child { border-top-left-radius: 10px; border-bottom-left-radius: 10px;}
        thead tr th:last-child { border-top-right-radius: 10px; border-bottom-right-radius: 10px;}

        tbody tr td:first-child { border-top-left-radius: 5px; border-bottom-left-radius: 0px;}
        tbody tr td:last-child { border-top-right-radius: 5px; border-bottom-right-radius: 0px;}


        </style>
    </div>

@endsection

@push('scripts')
    <script>
        var base_url = '{{ config('app.url') }}';
    </script>
@endpush
