@push('styles')
    <link rel="stylesheet" href="{{ asset(('css/pages/registration.css')) }}">
    <link rel="stylesheet" href="{{ asset(('css/auto_complete.css')) }}">
@endpush

<x-guest-layout>
    <x-jet-authentication-card>
        <x-slot name="logo">
            <x-jet-authentication-card-logo />
        </x-slot>

        <x-jet-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('register') }}" id="registrationForm">
            @csrf

            <div>
                <x-jet-label for="account_type" value="{{ __('Account Type') }}" />
                <select
                    class="block appearance-none w-full bg-white border border-grey-lighter text-grey-darker py-3 px-4 pr-8 rounded"
                    id="account_type" name="account_type">
                    <option value="1">{{ __('Business') }}</option>
                    <option value="2">{{ __('Corporate') }}</option>
                    <option value="3">{{ __('Individual') }}</option>
                    <option value="4">{{ __('Warehouse Owner') }}</option>
                    <option value="6">{{ __('Driver') }}</option>
                </select>
            </div>

            <div class="mt-4">
                <x-jet-label for="name" value="{{ __('Full Name') }}" />
                <x-jet-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" autofocus
                    autocomplete="name" />
            </div>

            <div class="mt-4">
                <x-jet-label for="email" value="{{ __('Email') }}" />
                <x-jet-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" />
            </div>

            <div class="mt-4">
                <x-jet-label for="phone" value="{{ __('Phone') }}" />
                <x-jet-input id="phone" class="block mt-1 w-full" type="tel" name="phone" :value="old('phone_full')"
                    autocomplete="off" />
            </div>

            <div class="mt-4">
                <x-jet-label for="password" value="{{ __('Password') }}" />
                <x-jet-input id="password" class="block mt-1 w-full" type="password" name="password"
                    autocomplete="new-password" />
            </div>

            <div class="mt-4">
                <x-jet-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                <x-jet-input id="password_confirmation" class="block mt-1 w-full" type="password"
                    name="password_confirmation" autocomplete="new-password" />
            </div>

            <div class="mt-4">
                <x-jet-label for="city" value="{{ __('City') }}" />
                <select
                    class="block appearance-none w-full bg-white border border-grey-lighter text-grey-darker py-3 px-4 pr-8 rounded"
                    id="city_id" name="city_id">
                    <option value="">Select city</option>
                    @foreach(city_dropdown() as $item)
                        <option value="{{ $item->id }}">{{ __($item->city) }}</option>
                    @endforeach
                </select>
            </div>

            <div id="corporate" style="display: none">
                <div class="mt-4">
                    <x-jet-label for="industry" value="{{ __('Industry') }}" />
                    <x-jet-input id="industry" class="block mt-1 w-full" type="text" name="industry"
                        :value="old('industry')" autocomplete="industry" disabled />
                </div>
            </div>

            <div id="individual" style="display: none">
                <div class="mt-4">
                    <x-jet-label for="address" value="{{ __('Street Address') }}" />
                    <x-jet-input id="address" class="block mt-1 w-full" type="text" name="address"
                        :value="old('address')" autocomplete="address" disabled />
                </div>
                <div class="mt-4">
                    <span> {{ __('Do you want to provide your skillsets to get Job/Freelance oppurtunities?') }}</span>
                    <div class="inline-flex items-center ml-2">
                        <label class="inline-flex items-center">
                            <input type="radio" class="form-radio" name="job_freelance" value="1" disabled checked />
                            <span class="ml-2">{{ __('Yes') }}</span>
                        </label>
                        <label class="inline-flex items-center ml-2">
                            <input type="radio" class="form-radio" name="job_freelance" value="0" disabled />
                            <span class="ml-2">{{ __('No') }}</span>
                        </label>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="inline-flex">
                        <fieldset>
                            <legend class="text-base font-medium text-gray-900">{{ __('Start As') }}:</legend>
                            <div class="mt-4 space-y-4">
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input id="product_service" name="start_as[]" type="checkbox" value="product/service"
                                            class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded"
                                            {{ old('product_service') == 'on' ? 'checked' : '' }} disabled />
                                        <label for="product_service"
                                            class="font-medium text-gray-700 ml-2">{{ __('Product/Service') }}</label>
                                    </div>
                                    <div class="flex items-center h-5 ml-4">
                                        <input id="broker_agent" name="start_as[]" type="checkbox" value="broker/agent"
                                            class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded"
                                            {{ old('broker_agent') == 'on' ? 'checked' : '' }} disabled />
                                        <label for="broker_agent"
                                            class="font-medium text-gray-700 ml-2">{{ __('Broker/Agent') }}</label>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input id="rentals" name="start_as[]" type="checkbox" value="rentals"
                                            class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded"
                                            {{ old('rentals') == 'on' ? 'checked' : '' }} disabled />
                                        <label for="rentals"
                                            class="font-medium text-gray-700 ml-2">{{ __('Rentals') }}</label>
                                    </div>
                                    <div class="flex items-center h-5 ml-4">
                                        <input id="skillset" name="start_as[]" type="checkbox" value="skillset"
                                            class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded"
                                            {{ old('skillset') == 'on' ? 'checked' : '' }} disabled />
                                        <label for="skillset"
                                            class="font-medium text-gray-700 ml-2">{{ __('Skillset') }}</label>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <p>By clicking register you agree to our <a class="text-blue-600" href="/terms">terms & conditions</a>.</p>
            </div>

            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>

                <x-jet-button class="ml-4">
                    {{ __('Register') }}
                </x-jet-button>
            </div>
        </form>
    </x-jet-authentication-card>
    @push('scripts')
        <script>
            var base_url = '{{ config('app.url') }}';
        </script>
        <script src="{{ asset(('/js/pages/registration.js')) }}"></script>
        {{-- <script src="https://cdn.jsdelivr.net/npm/places.js@1.19.0"></script> --}}
    @endpush
</x-guest-layout>
