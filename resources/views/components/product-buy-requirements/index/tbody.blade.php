@if (!empty($products_buy_requirements))
    @foreach ($products_buy_requirements as $key => $requirement)
        <tr class="buy-request hover:bg-gray-100 border-b border-gray-200 py-10">
            <td class="px-4 py-4">{{ $loop->iteration }}</td>
            <td class="px-4 py-4">{{ $requirement->required_product }}</td>
            <td class="px-4 py-4">{{ $requirement->buyer->name }}</td>
            <td class="px-4 py-4">{{ $requirement->quantity }}</td>
            <td class="px-4 py-4">{{ $requirement->unit }}</td>
            <td class="px-4 py-4">{{ $requirement->budget }}</td>
            <td class="px-4 py-4">{{ $requirement->requirement_urgency }}</td>
            <td class="px-4 py-4">{{ $requirement->requirement_frequency }}</td>
            <td class="px-4 py-4">{{ $requirement->added_at }}</td>
            @if(Session::get('user_type') != "buyer")
            <td class="px-4 py-4 flex flex-row">
                <button class="btn contact_buyer" data-req-id="{{$requirement->id}}" data-buyer-id="{{$requirement->buyer->id}}">{{__('Contact Buyer')}}</button>
                <div class="sll pl-2 pt-3" id="sll-{{ $requirement->id }}">
                    <i class="@if(in_array($requirement->id, $user_shortlisted_leads)) fas @else far @endif fa-star"></i>
                </div>
            </td>
            @endif
        </tr>
    @endforeach
@else
    <tr class="hover:bg-gray-100 border-b border-gray-200 py-10">
        <td class="px-4 py-4">
            {{ __('No requirements Found') }}
        </td>
    </tr>
@endif
