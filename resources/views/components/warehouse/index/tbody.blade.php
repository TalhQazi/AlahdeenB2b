@foreach ($warehouses as $warehouse)
    <tr class="hover:bg-gray-100 border-b border-gray-200 py-10">
        <td class="px-4 py-4">{{$warehouse->id}}</td>
        <td class="px-4 py-4">{{$warehouse->locality}}</td>
        <td class="px-4 py-4">{{$warehouse->city}}</td>
        <td class="px-4 py-4">{{$warehouse->area}}</td>
        <td class="px-4 py-4">{{$warehouse->price}}</td>
        <td class="px-4 py-4">{{$warehouse->property_type->title}}</td>
        <td class="px-4 py-4">{{!empty($warehouse->can_be_shared) ? __('Yes') : __('No')}}</td>
        <td class="px-4 py-4 {{$warehouse->is_active ?  'text-green-500' : 'text-red-500'}}">{{$warehouse->is_active ? __('Active'): __('In Active')}}</td>
        <td class="px-4 py-4">{{$warehouse->created_at}}</td>
        <td class="px-4 py-4">
            <a href="{{route('warehouse.edit',['warehouse' => $warehouse->id])}}" title="{{__('Edit Warehouse')}}" class="mx-0.5 edit_warehouse_btn">
                <i class="fa fa-pencil mx-0.5"></i>
            </a>
            <a href="{{route('warehouse.view-schedule',['warehouse' => $warehouse->id])}}" title="{{__('View Schedule')}}" class="mx-0.5">
                <i class="fa fa-eye mx-0.5"></i>
            </a>

            @if ($warehouse->is_active)
                <a  href="#" data-url={{route('warehouse.deactivate',['warehouse' => $warehouse->id])}} title="{{ __('Deactivate warehouse') }}" class="ml-1 deactivate_warehouse_btn">
                    <i class="fa fa-toggle-on"></i>
                </a>
            @else
                <a href="#" data-url={{route('warehouse.activate',['warehouse' => $warehouse->id])}} title="{{ __('Reactivate warehouse') }}" class="ml-1 activate_warehouse_btn">
                    <i class="fa fa-toggle-off"></i>
                </a>
            @endif
        </td>
    </tr>
@endforeach
