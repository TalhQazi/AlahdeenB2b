@extends('layouts.master')

@push('styles')
    <link rel="stylesheet" href="{{ asset(('css/common/validation_error.css')) }}">
@endpush


@section('page')
    <div class="min-h-screen pt-2 font-mono">
        <div class="container mx-auto">
            <div class="inputs w-full p-6">
                <h2 class="text-2xl text-gray-900">{{ __('Add Details') }}</h2>
                <form id="become_seller_form" class="mt-6 border-t border-gray-400 pt-4" method="POST"
                    action="{{ route('become.a.seller.store') }}">
                    @csrf
                    <div>
                        <label class="block font-medium text-sm text-gray-700" for="account_type">
                            {{ __('Account Type') }}
                        </label>
                        <select
                            class="block appearance-none text-gray-600 w-full bg-white border border-gray-400 shadow-inner px-4 py-2 pr-8 rounded"
                            id="account_type" name="account_type">
                            <option value="1">{{ __('Business') }}</option>
                            <option value="2">{{ __('Corporate') }}</option>
                            <option value="3">{{ __('Individual') }}</option>
                            <option value="4">{{ __('Warehouse Owner') }}</option>
                        </select>
                    </div>
                    <div id="corporate" style="display: none">
                        <div class="mt-4">
                            <label class="block font-medium text-sm text-gray-700" for="industry">
                                {{ __('Industry') }}
                            </label>
                            <input
                                class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500"
                                name="industry" id="industry" type="text" value="{{ old('industry') }}">
                        </div>
                    </div>

                    <div id="individual" style="display: none">

                        <div class="mt-4">
                            <label class="block font-medium text-sm text-gray-700" for="address">
                                {{ __('Street Address') }}
                            </label>
                            <input
                                class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500"
                                name="address" id="address" type="text" value="{{ old('address') }}">
                        </div>
                        <div class="mt-4">
                            <span>{{ __('Do you want to provide your skillsets to get Job/Freelance oppurtunities?') }}</span>
                            <div class="inline-flex items-center ml-2">
                                <label class="inline-flex items-center">
                                    <input type="radio" class="form-radio" name="job_freelance" value="1" checked="">
                                    <span class="ml-2">{{ __('Yes') }}</span>
                                </label>
                                <label class="inline-flex items-center ml-2">
                                    <input type="radio" class="form-radio" name="job_freelance" value="0">
                                    <span class="ml-2">{{ __('No') }}</span>
                                </label>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="inline-flex">
                                <fieldset>
                                    <legend class="text-base font-medium text-gray-900">{{ __('Start As:') }}</legend>
                                    <div class="mt-4 space-y-4">
                                        <div class="flex items-start">
                                            <div class="flex items-center h-5">
                                                <input id="product_service" name="start_as[]" type="checkbox"
                                                    value="product/service"
                                                    class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                                <label for="product_service"
                                                    class="font-medium text-gray-700 ml-2">{{ __('Product/Service') }}</label>
                                            </div>
                                            <div class="flex items-center h-5 ml-4">
                                                <input id="broker_agent" name="start_as[]" type="checkbox"
                                                    value="broker/agent"
                                                    class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                                <label for="broker_agent"
                                                    class="font-medium text-gray-700 ml-2">{{ __('Broker/Agent') }}</label>
                                            </div>
                                        </div>
                                        <div class="flex items-start">
                                            <div class="flex items-center h-5">
                                                <input id="rentals" name="start_as[]" type="checkbox" value="rentals"
                                                    class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                                <label for="rentals"
                                                    class="font-medium text-gray-700 ml-2">{{ __('Rentals') }}</label>
                                            </div>
                                            <div class="flex items-center h-5 ml-4">
                                                <input id="skillset" name="start_as[]" type="checkbox" value="skillset"
                                                    class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                                <label for="skillset"
                                                    class="font-medium text-gray-700 ml-2">{{ __('Skillset') }}</label>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>

                    </div>
                    <div class="flex p-2 mt-4">
                        <div class="flex-auto flex flex-row justify-center">
                            <button type="submit" class="btn btn-teal">
                                {{ __('Become a Seller') }}
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
        var base_url = '{{ config('app.url ') }}';

    </script>
    <script src="{{ asset(('/js/pages/become_a_seller.js')) }}"></script>
@endpush
