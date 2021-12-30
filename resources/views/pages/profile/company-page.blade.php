@extends('layouts.master')

@push('styles')
    <link rel="stylesheet" href="{{ asset(('css/auto_complete.css')) }}">
@endpush

@section('page')
    <div class="card col-span-2 xl:col-span-1 py-5">
        <div class="card-header">{{ __('Company Banner') }}</div>
        <form method="POST" enctype="multipart/form-data" action="{{ route('profile.business.company.page.banner.store') }}">
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
                            @if (!empty($banner))
                                <img class="w-auto mx-auto object-cover object-center h-40" id="company_banner_preview"
                                    src="{{asset(str_replace('/storage/','', $banner->banner_image_path))}}" alt="Company Logo Upload" />
                            @else
                                <img class="w-auto mx-auto object-cover object-center" id="company_banner_preview"
                                    src="{{ asset('img/camera_icon.png') }}" alt="Company Banner Upload" />
                            @endif

                        </div>
                        <label class="cursor-pointer mt-6">
                            <span
                                class="mt-2 text-base leading-normal px-4 py-2 bg-blue-500 text-white text-sm rounded-full">{{ __('Website Banner') }}</span>
                            <input type='file' name="company_banner" id="company_banner" class="hidden" :accept="accept" />
                        </label>
                    </div>
                </div>
            </div>
            <div class="flex justify-center mt-5">
                <button class="btn btn-teal">{{ __('Save') }}</button>
            </div>
        </form>
    </div>
    <div>
        <form method="POST" action="{{ route('profile.business.company.page.products.store') }}">
            @csrf
            <div class="card col-span-2 xl:col-span-1 mt-5">
                <div class="card-header">{{ __('Banner Products') }}</div>
                @if ($banner_products_limit > 0)
                    <div class="w-full md:w-full p-3 mb-6 grid-rows-2 gap-4">
                        {{-- <div class="col-span-full mx-auto">
                            <p class="leading-10 text-center">
                                {{ __('200KB max. JPEG or PNG format only. Suggested photo width
                                        and height for the new version Minisite: 270*270px') }}
                            </p>
                        </div> --}}
                        <div class="col-span-12 text-center">
                            @if (!empty($banner_products))
                                @for ($i = 0; $i < $banner_products_limit; $i++)
                                    @if (!empty($banner_products[$i]))
                                        <?php $photo = asset($banner_products[$i]->product->images[0]->path) ?>
                                        <div class="inline-block bg-white px-4 py-5 rounded-lg shadow-lg text-center w-32">
                                            <div class="mb-4">
                                                <img class="w-auto mx-auto object-cover object-center h-32"
                                                    id="{{ 'banner_products_' . $i . '_preview' }}" src="{{ asset(str_replace('/storage/', '', $photo)) }}"
                                                    alt="{{ $banner_products[$i]->product->title . ' image' }}" />
                                            </div>
                                            <label class="cursor-pointer mt-6">
                                                <input
                                                class="mt-3 appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-1 px-4 leading-tight focus:outline-none  focus:border-gray-500 product_autocomplete"
                                                name="banner_products[{{ $i }}][product_name]"
                                                id="banner_products_name_{{ $i }}"
                                                value="{{ $banner_products[$i]->product->title }}"
                                                type="text">
                                                <input type="hidden" name="banner_products[{{$i}}][product_id]" id="banner_products_{{$i}}" value="{{ $banner_products[$i]->product->id }}">
                                            </label>
                                        </div>
                                    @else
                                        <div class="inline-block bg-white px-4 py-5 rounded-lg shadow-lg text-center w-32">
                                            <div class="mb-4">
                                                <img class="w-auto mx-auto object-cover object-center h-32"
                                                    id="{{ 'banner_products_' . $i . '_preview' }}"
                                                    src="{{ asset('img/camera_icon.png') }}"/>
                                            </div>
                                            <label class="cursor-pointer mt-6">
                                                <input
                                                class="mt-3 appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-1 px-4 leading-tight focus:outline-none  focus:border-gray-500 product_autocomplete"
                                                name="banner_products[{{ $i }}][product_name]"
                                                id="banner_products_name_{{ $i }}"
                                                type="text">
                                                <input type="hidden" name="banner_products[{{$i}}][product_id]" id="banner_products_{{$i}}">
                                            </label>
                                        </div>
                                    @endif
                                @endfor
                            @endif
                        </div>
                    </div>
                @endif
            </div>
            <div class="card col-span-2 xl:col-span-1 mt-5">
                <div class="card-header">{{ __('Top Products') }}</div>
                @if ($top_products_limit > 0)
                    <div class="w-full md:w-full p-3 mb-6 grid-rows-2 gap-4">
                        {{-- <div class="col-span-full mx-auto">
                            <p class="leading-10 text-center">
                                {{ __('200KB max. JPEG or PNG format only. Suggested photo width
                                        and height for the new version Minisite: 270*270px') }}
                            </p>
                        </div> --}}
                        <div class="col-span-12 text-center">
                            @if (!empty($top_products))
                                @for ($i = 0; $i < $top_products_limit; $i++)
                                    @if (!empty($top_products[$i]))
                                        <?php $photo = asset($top_products[$i]->product->images[0]->path) ?>
                                        <div class="inline-block bg-white px-4 py-5 rounded-lg shadow-lg text-center w-32">
                                            <div class="mb-4">
                                                <img class="w-auto mx-auto object-cover object-center h-32"
                                                    id="{{ 'top_products_' . $i . '_preview' }}" src="{{ $photo }}"
                                                    alt="{{ $top_products[$i]->product->title . ' image' }}" />
                                            </div>
                                            <label class="cursor-pointer mt-6">
                                                <input
                                                class="mt-3 appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-1 px-4 leading-tight focus:outline-none  focus:border-gray-500 product_autocomplete"
                                                name="top_products[{{ $i }}][product_name]"
                                                id="top_products_name_{{ $i }}"
                                                value="{{ $top_products[$i]->product->title }}"
                                                type="text">
                                                <input type="hidden" name="top_products[{{$i}}][product_id]" id="top_products_{{$i}}" value="{{$top_products[$i]->product->id}}">
                                            </label>
                                        </div>
                                    @else
                                        <div class="inline-block bg-white px-4 py-5 rounded-lg shadow-lg text-center w-32">
                                            <div class="mb-4">
                                                <img class="w-auto mx-auto object-cover object-center h-32"
                                                    id="{{ 'top_products_' . $i . '_preview' }}"
                                                    src="{{ asset('img/camera_icon.png') }}"/>
                                            </div>
                                            <label class="cursor-pointer mt-6">
                                                <input
                                                class="mt-3 appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-1 px-4 leading-tight focus:outline-none  focus:border-gray-500 product_autocomplete"
                                                name="top_products[{{ $i }}][product_name]"
                                                id="top_products_name_{{ $i }}"
                                                type="text">
                                                <input type="hidden" name="top_products[{{$i}}][product_id]" id="top_products_{{$i}}">
                                            </label>
                                        </div>
                                    @endif
                                @endfor
                            @endif
                        </div>
                    </div>
                @endif
            </div>
            <div class="flex justify-center mt-5">
                <button class="btn btn-teal">{{ __('Save') }}</button>
            </div>
        </form>
    </div>

@endsection

@push('scripts')
    <script type="text/javascript" src="{{ asset(('js/pages/company_profile_settings.js')) }}"></script>
@endpush
