@push('styles')
    <link rel="stylesheet" href="{{ asset(('css/pages/registration.css')) }}">
    <link rel="stylesheet" href="{{ asset(('css/auto_complete.css')) }}">
@endpush

<x-guest-layout>
    <x-jet-authentication-card>
        <x-slot name="logo">
            <x-jet-authentication-card-logo />
        </x-slot>

        @if ($code == 201)
            <div class="mb-4 text-sm text-gray-600">
                {{ __('Thanks for signing up! Before getting started, could you verify your phone number by entering the OTP, we have just sent you a text with OTP on your phone.') }}
            </div>

            @if (Session::has('message'))
                <div class="alert {{ Session::get('alertClass', 'alert-info') }} alert-close mb-5  w-full xl:w-2/4">
                    <button class="alert-btn-close">
                        <i class="fad fa-times"></i>
                    </button>
                    <span>{{ Session::get('message') }}</span>
                </div>
            @else
                <div class="mb-4 text-sm text-gray-600">
                    {{ __('If you haven\'t received an OTP yet, You can retry sending OTP by clicking this link.') }}
                    <form method="POST" action="{{ route('phone.resend.otp') }}" style="display: inline">
                        @csrf

                        <button type="submit" class="underline text-sm hover:text-gray-900 text-blue-500">
                            {{ __('Resend OTP') }}
                        </button>
                    </form>
                    {{ __('Or verify you have entered the correct phone number.') }}
                </div>
            @endif
        @else
            <div class="mb-4 text-sm text-gray-600">
                {{ __('Oops! Something went wrong.') }}
            </div>
        @endif

        <x-jet-validation-errors class="mb-4" />

        <div class="mt-4 flex items-center justify-between">
            <p>You can click <span><a href="javascript:void(0)" class="text-blue-500 inline underline" onclick="$(this).closest('p').hide();$('#phone-form').removeClass('hidden');$('#verification-form').addClass('hidden');">here</a></span> to change number.</p>
            <div id="phone-form" class=" hidden">
                <form method="POST" action="{{ route('phone.update.number') }}">
                    @csrf
                    <div class="mt-4">
                        <x-jet-label for="phone" value="{{ __('Phone') }}" />
                        <x-jet-input id="phone" class="block mt-1 w-full" type="tel" name="phone" :value="old('phone_full')"
                            autocomplete="off" />
                    </div>
                    <div>
                        <x-jet-button type="submit" class="mt-3">
                            {{ __('Update Phone') }}
                        </x-jet-button>
                    </div>
                </form>
            </div>
        </div>

        <div class="mt-4 flex items-center justify-between">
            @if ($code == 201)
                <form method="POST" action="{{ route('phone.verify.otp') }}" id="verification-form">
                    @csrf
                    <div class="mt-4">
                        <x-jet-label for="otp" value="{{ __('OTP') }}" />
                        <x-jet-input id="otp" class="block mt-1 w-full" type="text" name="otp" autocomplete="off" />
                    </div>
                    <div>
                        <input type="hidden" name="uniqueId" value="{{ $uniqueId }}">
                        <x-jet-button type="submit" class="mt-3">
                            {{ __('Verify OTP') }}
                        </x-jet-button>
                    </div>
                </form>
            @else
                <div class="alert-error">
                    {{ $message }}
                </div>
            @endif
            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button type="submit" class="underline text-sm text-gray-600 hover:text-gray-900">
                    {{ __('Logout') }}
                </button>
            </form>
        </div>
    </x-jet-authentication-card>
    @push('scripts')
        <script>
            var base_url = '{{ config('
            app.url ') }}';

        </script>
        <script src="{{ asset(('/js/pages/registration.js')) }}"></script>
    @endpush
</x-guest-layout>
