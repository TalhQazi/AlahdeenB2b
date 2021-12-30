@extends('layouts.master')

@push('styles')
    <link rel="stylesheet" href="{{ asset(('css/auto_complete.css')) }}">
@endpush

@section('page')
    <div class="card col-span-2 xl:col-span-1 py-5">
        <div class="card-header">{{ __('Edit Profile') }}</div>
        <form method="POST" enctype="multipart/form-data" action="{{ route('user.profile.update') }}">
            @csrf

            <div>
                <div class="px-3 py-3 center mx-auto">
                    <div class="bg-white px-4 py-5 rounded-lg shadow-lg text-center w-full">
                        <div class="col-span-full mx-auto">
                            <p class="leading-10 text-center">
                                {{ __('3MB max. JPEG or PNG format only.') }}
                            </p>
                        </div>
                        <div class="mb-4">
                            @if (isset(auth()->user()->profile_photo_path))
                                <img class="w-auto mx-auto object-cover object-center h-40 profile_image" id="company_banner_preview"
                                    src="{{ url(auth()->user()->profile_photo_path) }}" alt="Profile picture upload" />
                            @else
                                <img class="w-auto mx-auto object-cover object-center profile_image" id="company_banner_preview"
                                    src="{{ asset('common/images/user.svg') }}" alt="Profile picture upload" />
                            @endif

                        </div>
                        <label class="cursor-pointer mt-6">
                            <span
                                class="mt-2 text-base leading-normal px-4 py-2 bg-blue-500 text-white text-sm rounded-full">{{ __('Profile Picture') }}</span>
                            <input type='file' name="profile_photo_path" id="company_banner" class="hidden" :accept="accept" />
                        </label>
                        
                        <div class="w-full md:w-full px-3 mb-6 text-left">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-text-1">Name</label>
                            <input class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none focus:border-gray-500 keywords" data-target-div="#main_products" name="name" id="main_keywords_input" type="text" autocomplete="off" value="{{ auth()->user()->name }}">
                        </div>
                        <div class="w-full md:w-full px-3 mb-6 text-left">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-text-1">Email</label>
                            <input class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none focus:border-gray-500 keywords" data-target-div="#main_products" name="email" id="main_keywords_input" type="text" autocomplete="off" value="{{ auth()->user()->email }}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex justify-center mt-5">
                <button class="btn btn-teal">{{ __('Update') }}</button>
            </div>
        </form>
    </div>

@endsection

@push('scripts')
    <script type="text/javascript" src="{{ asset(('js/pages/company_profile_settings.js')) }}"></script>
@endpush
