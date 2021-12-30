@extends('layouts.master')

@push('styles')
    <link rel="stylesheet" href="{{ asset(('css/common/validation_error.css')) }}">
@endpush

@section('page')
    <div class="p-5">

        @if(!isset($hide_steps_bar))
            @include('components.business_profile.business-details-steps')
        @endif

        <div class="mt-8 p-4">
            <div class="card col-span-2 xl:col-span-1 mt-10">
                <div class="card-header">
                    {{__('Certifications')}}

                    @if($certificateAwardsIsAvailable)
                        <button id="add_certification_btn"
                                class="btn btn-gray xs:float-none sm:float-none md:float-none float-right sm:mt-2">{{__('Add Certifications')}}
                            <i class="fa fa-plus ml-2"></i></button>
                    @endif
                </div>
                <div class="bg-white pb-4 px-4 rounded-md w-full">
                    <div class="w-full flex justify-end px-2 mt-2">
                        <div class="sm:w-64 inline-block relative ">
                            <input type="" name=""
                                   class="leading-snug border border-gray-300 block w-full appearance-none bg-gray-100 text-sm text-gray-600 py-1 px-4 pl-8 rounded-lg"
                                   placeholder="Search"/>

                            <div
                                class="pointer-events-none absolute pl-3 inset-y-0 left-0 flex items-center px-2 text-gray-300">
                                <svg class="fill-current h-3 w-3" xmlns="http://www.w3.org/2000/svg"
                                     viewBox="0 0 511.999 511.999">
                                    <path
                                        d="M508.874 478.708L360.142 329.976c28.21-34.827 45.191-79.103 45.191-127.309C405.333 90.917 314.416 0 202.666 0S0 90.917 0 202.667s90.917 202.667 202.667 202.667c48.206 0 92.482-16.982 127.309-45.191l148.732 148.732c4.167 4.165 10.919 4.165 15.086 0l15.081-15.082c4.165-4.166 4.165-10.92-.001-15.085zM202.667 362.667c-88.229 0-160-71.771-160-160s71.771-160 160-160 160 71.771 160 160-71.771 160-160 160z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="overflow-x-auto mt-6 px-3">
                        <table class="table-auto border-collapse w-full">
                            <thead>
                            <tr class="rounded-lg text-sm font-medium text-gray-700 text-left"
                                style="font-size: 0.9674rem">
                                <th class="px-4 py-2 bg-gray-200 "
                                    style="background-color:#f8f8f8">{{ __('Certification') }}</th>
                                <th class="px-4 py-2 "
                                    style="background-color:#f8f8f8">{{ __('Standard Certification') }}</th>
                                <th class="px-4 py-2 "
                                    style="background-color:#f8f8f8">{{ __('Membership & Affliations') }}</th>
                                <th class="px-4 py-2 " style="background-color:#f8f8f8">Controls</th>
                            </tr>
                            </thead>
                            <tbody class="text-sm font-normal text-gray-700">
                            @if (!empty($business_certificates))

                                @foreach ($business_certificates as $certificate)
                                    <tr class="hover:bg-gray-100 border-b border-gray-200 py-10">
                                        <td class="px-4 py-4">
                                            @if (!empty($certificate['image_path']))
                                                <?php $photo = url(Storage::url($certificate['image_path'])); ?>
                                                <img class="w-auto object-cover object-center h-10"
                                                     id="certification_img" src="{{ $photo }}"
                                                     alt="Company Logo Upload"/>
                                            @else
                                                {{__('NA')}}
                                            @endif

                                        </td>
                                        <td class="px-4 py-4">{{$certificate['certification']}}</td>
                                        <td class="px-4 py-4">{{$certificate['membership']}}</td>
                                        <td class="px-4 py-4">
                                            <?php
                                            $editRoute = route('profile.business.certification.edit', ['business_certification' => $certificate['id']]);
                                            $deleteRoute = route('profile.business.certification.delete', ['business_certification' => $certificate['id']]);
                                            ?>

                                            <a href="#" data-url="{{$editRoute}}"
                                               title="{{ __('Edit Certification Details') }}"
                                               class="mx-0.5 edit_certification_btn">
                                                <i class="fa fa-pencil mx-0.5"></i>
                                            </a>

                                            <a data-url="{{$deleteRoute}}"
                                               title="{{ __('Delete Certification Details') }}"
                                               class="ml-1 delete_certificate_award_btn">
                                                <i class="fa fa-trash"></i>
                                            </a>

                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr class="hover:bg-gray-100 border-b border-gray-200 py-10">
                                    <td class="px-4 py-4" colspan="100">
                                        No Certifications Found
                                    </td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
            <div id="pagination" class="w-full flex justify-center border-t border-gray-100 pt-4 items-center">
                {{$business_certificates->links('')}}


            </div>
            <div class="card col-span-2 xl:col-span-1 mt-10">
                <div class="card-header">
                    {{__('Awards')}}

                    @if($certificateAwardsIsAvailable)
                        <button id="add_award_btn"
                                class="btn btn-gray xs:float-none sm:float-none md:float-none float-right sm:mt-2">{{__('Add Awards & Recognition')}}
                            <i class="fa fa-plus ml-2"></i></button>
                    @endif
                </div>
                <div class="bg-white pb-4 px-4 rounded-md w-full">
                    <div class="w-full flex justify-end px-2 mt-2">
                        <div class="sm:w-64 inline-block relative ">
                            <input type="" name=""
                                   class="leading-snug border border-gray-300 block w-full appearance-none bg-gray-100 text-sm text-gray-600 py-1 px-4 pl-8 rounded-lg"
                                   placeholder="Search"/>

                            <div
                                class="pointer-events-none absolute pl-3 inset-y-0 left-0 flex items-center px-2 text-gray-300">
                                <svg class="fill-current h-3 w-3" xmlns="http://www.w3.org/2000/svg"
                                     viewBox="0 0 511.999 511.999">
                                    <path
                                        d="M508.874 478.708L360.142 329.976c28.21-34.827 45.191-79.103 45.191-127.309C405.333 90.917 314.416 0 202.666 0S0 90.917 0 202.667s90.917 202.667 202.667 202.667c48.206 0 92.482-16.982 127.309-45.191l148.732 148.732c4.167 4.165 10.919 4.165 15.086 0l15.081-15.082c4.165-4.166 4.165-10.92-.001-15.085zM202.667 362.667c-88.229 0-160-71.771-160-160s71.771-160 160-160 160 71.771 160 160-71.771 160-160 160z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="overflow-x-auto mt-6 px-3">
                        <table class="table-auto border-collapse w-full">
                            <thead>
                            <tr class="rounded-lg text-sm font-medium text-gray-700 text-left"
                                style="font-size: 0.9674rem">
                                <th class="px-4 py-2 bg-gray-200 "
                                    style="background-color:#f8f8f8">{{ __('Award') }}</th>
                                <th class="px-4 py-2 "
                                    style="background-color:#f8f8f8">{{ __('Award & Recogination') }}</th>
                                <th class="px-4 py-2 " style="background-color:#f8f8f8">Controls</th>
                            </tr>
                            </thead>
                            <tbody class="text-sm font-normal text-gray-700">
                            @if (!empty($business_awards))

                                @foreach ($business_awards as $award)
                                    <tr class="hover:bg-gray-100 border-b border-gray-200 py-10">
                                        <td class="px-4 py-4">
                                            @if (!empty($award['award_image']))
                                                <?php $photo = url(Storage::url($award['award_image'])); ?>
                                                <img class="w-auto object-cover object-center h-10"
                                                     id="certification_img" src="{{ $photo }}"
                                                     alt="Company Logo Upload"/>
                                            @else
                                                {{__('NA')}}
                                            @endif

                                        </td>
                                        <td class="px-4 py-4">{{$award['title']}}</td>
                                        <td class="px-4 py-4">
                                            <?php
                                            $editRoute = route('profile.business.award.edit', ['business_award' => $award['id']]);
                                            $deleteRoute = route('profile.business.award.delete', ['business_award' => $award['id']]);
                                            ?>

                                            <a href="#" data-url="{{$editRoute}}" title="{{ __('Edit Award Details') }}"
                                               class="mx-0.5 edit_award_btn">
                                                <i class="fa fa-pencil mx-0.5"></i>
                                            </a>

                                            <a data-url="{{$deleteRoute}}" title="{{ __('Delete Award Details') }}"
                                               class="ml-1 delete_certificate_award_btn">
                                                <i class="fa fa-trash"></i>
                                            </a>

                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr class="hover:bg-gray-100 border-b border-gray-200 py-10">
                                    <td class="px-4 py-4" colspan="100">
                                        {{ __('No Awards Found') }}
                                    </td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                    <div id="pagination" class="w-full flex justify-center border-t border-gray-100 pt-4 items-center">
                        {{$business_awards->links('')}}


                    </div>
                </div>

            </div>

        </div>

    </div>
@endsection

@section('modals')
    @include('components.business_profile.certification-modal')
    @include('components.business_profile.delete-certification-modal')
    @include('components.business_profile.award-modal')
@endsection


@push('scripts')
    <script>
        var base_url = '{{ config('app.url') }}';
    </script>
    <script type="text/javascript" src="{{ asset(('js/business_certification.js')) }}"></script>
    <script type="text/javascript" src="{{ asset(('js/components/image_reader.js')) }}"></script>
@endpush
