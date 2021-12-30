@push('styles')
    <link rel="stylesheet" href="{{ asset(('css/pages/manage_clients.css')) }}">
    <link rel="stylesheet" href="{{ asset(('css/auto_complete.css')) }}">
@endpush

<x-popup-wrapper>
    @section('popup-id', 'add-client')
    @section('popup-title', __('Add Client'))
    @section('popup-body')
        @if (count($existingClients) > 0)
            <div class="existing-clients flex flex-row w-full">
                <form method="POST" action="{{ route('khata.client.store') }}" id="addExistingClient">
                    @csrf
                    <label for="existing-client">{{ __('Choose Client from Existing List') }}</label>
                    <select class="text-gray-600 w-full bg-white border border-gray-400 shadow-inner px-4 py-2 pr-8 rounded" name="existingClient" id="existing-client" required>
                        <option value="">{{ __('Select Client') }}</option>
                        @foreach ($existingClients as $ec)
                            <option value="{{ $ec->id }}">{{ $ec->name }}</option>
                        @endforeach
                    </select>
                    <div class="flex flex-row-reverse w-full mt-1">
                        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" type="submit">{{ __('Add to Clients') }}</button>
                    </div>
                </form>
            </div>
            <div class="w-full text-center">{{ __('OR') }}</div>
        @endif
        <div class="add-new-client">{{ __('Invite New Client') }}</div>
        <form method="POST" action="{{ route('khata.client.store') }}" id="addClientForm">
            <div class="alert alert-error hidden">
            </div>
            @csrf

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
                <x-jet-label for="company_name" value="{{ __('Business Name') }}" />
                <x-jet-input id="company_name" class="block mt-1 w-full" type="text" name="company_name" :value="old('company_name')" />
            </div>

            <div class="mt-4">
                <x-jet-label for="phone" value="{{ __('Phone') }}" />
                <x-jet-input id="phone" class="block mt-1 w-full" type="tel" name="phone" :value="old('phone_full')"
                    autocomplete="off" />
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

        </form>
    @endsection
</x-popup-wrapper>
