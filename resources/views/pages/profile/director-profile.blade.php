@extends('layouts.master')

@push('styles')
    <link rel="stylesheet" href="{{ asset(('css/pages/director_profile.css')) }}">
@endpush

@section('page')

    @if(!isset($hide_steps_bar))
        @include('components.business_profile.business-details-steps')
    @endif

    <div class="card col-span-2 xl:col-span-1 mt-10">
        <div class="card-header">{{ __('Directors List') }}</div>
        <div class="bg-white pb-4 px-4 rounded-md w-full">
            <div class="w-full flex justify-end px-2 mt-2 mb-5">
                <div>
                    <a href="#" class="btn btn-indigo add_director">
                        <i class="fas fa-user mr-2 sm:hidden"></i>
                        <span>{{ __('Add Director') }}</span>
                    </a>
                </div>
            </div>

            <table class="table-auto border-collapse w-full">
                <thead>
                <tr class="rounded-lg text-sm font-medium text-gray-700 text-left" style="font-size: 0.9674rem">
                    <th class="px-4 py-2 bg-gray-200 " style="background-color:#f8f8f8">{{ __('ID') }}</th>
                    <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Photo') }}</th>
                    <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Name') }}</th>
                    <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Designation') }}</th>
                    <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Description') }}</th>
                    <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Controls') }}</th>
                </tr>
                </thead>
                <tbody class="text-sm font-normal text-gray-700">
                @if (!empty($directors_profiles))
                    @foreach ($directors_profiles as $director_profile)
                        <tr class="hover:bg-gray-100 border-b border-gray-200 py-10" id="row_{{$director_profile->id}}">
                            <td class="px-4 py-4">{{$director_profile->id}}</td>
                            <td class="px-4 py-4" id="director_img_col"><img
                                    class="w-auto object-cover object-center h-10" id="director_img"
                                    src="{{url($director_profile->director_photo)}}" alt="{{$director_profile->name}}"/>
                            </td>
                            <td class="px-4 py-4 capitalize">{{$director_profile->name}}</td>
                            <td class="px-4 py-4">{{$director_profile->designation}}</td>
                            <td class="px-4 py-4 capitalize">{!! $director_profile->description !!}</td>
                            <td class="px-4 py-4">
                                <form
                                    action="{{ route('profile.business.delete.director.profile', ['business_director' => $director_profile->id]) }}"
                                    method="POST">
                                    @csrf
                                    @method("DELETE")
                                    <span class="edit_director" title="{{ __('Edit') }}"
                                          data-url="{{route('profile.business.update.director.profile', ['business_director' => $director_profile->id])}}">
                                            <i class="fas fa-pencil"></i>
                                        </span>
                                    <span>
                                            <button class="remove_director" type="submit"
                                                    title="{{ __('Remove Director') }}">
                                                <i class="far fa-trash fa-lg"></i>
                                            </button>
                                        </span>
                                </form>

                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr class="hover:bg-gray-100 border-b border-gray-200 py-10">
                        <td class="px-4 py-4">
                            {{ __('No directors found')  }}
                        </td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>

    </div>
@endsection

@section('modals')
    @include('components.business_profile.director-profile-modal')
@endsection

@push('scripts')
    <script src="https://cdn.tiny.cloud/1/nkcpprlcvgg1ldeqgx3dn4mhqmutceszm1yqqf73vsyqhoq9/tinymce/5/tinymce.min.js"
            referrerpolicy="origin"></script>
    <script type="text/javascript" src="{{ asset(('js/pages/director_profile.js')) }}"></script>
@endpush
