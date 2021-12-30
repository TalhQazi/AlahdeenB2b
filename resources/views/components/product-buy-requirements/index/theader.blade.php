<tr class="rounded-lg text-sm font-medium text-gray-700 text-left" style="font-size: 0.9674rem">
    <th class="px-4 py-2 bg-gray-200" style="background-color:#f8f8f8">{{__('#') }}</th>
    <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Product Required') }}</th>
    <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Buyer') }}</th>
    <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Quantity') }}</th>
    <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Unit') }}</th>
    <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Budget') }}</th>
    <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Urgency') }}</th>
    <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Frequency') }}</th>
    <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Created At') }}</th>
    @if (Session::get('user_type') != "buyer")
    <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Controls') }}</th>
    @endif

</tr>

