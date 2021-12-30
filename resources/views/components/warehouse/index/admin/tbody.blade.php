@foreach ($warehouses as $warehouse)
    <tr class="hover:bg-gray-100 border-b border-gray-200 py-10">
        <td class="px-4 py-4">{{$warehouse->id}}</td>
        <td class="px-4 py-4">{{$warehouse->locality}}</td>
        <td class="px-4 py-4">{{$warehouse->city}}</td>
        <td class="px-4 py-4">{{$warehouse->area}}</td>
        <td class="px-4 py-4">{{$warehouse->price}}</td>
        <td class="px-4 py-4">{{$warehouse->property_type->title}}</td>
        <td class="px-4 py-4">{{!empty($warehouse->can_be_shared) ? __('Yes') : __('No')}}</td>
        <td class="px-4 py-4 {{$warehouse->is_approved == 0 ? 'text-red-500': 'text-green-500'}}">{{$warehouse->is_approved == 0 ? 'No': 'Yes'}}</td>
        <td class="px-4 py-4 {{$warehouse->deleted_at ? 'text-red-500': 'text-green-500'}}">{{$warehouse->deleted_at ? 'Deleted': 'On'}}</td>
        <td class="px-4 py-4">{{$warehouse->created_at}}</td>
        <td class="px-4 py-4">
            @if ($warehouse->deleted_at)
                <a  href="#" data-url={{route('admin.warehouse.restore',['warehouse_id' => $warehouse->id])}} title="{{ __('Restore warehouse') }}" class="ml-1 restore_warehouse_btn">
                    <i class="fa fa-trash-restore mx-0.5"></i>
                </a>

            @else
                <a href="{{route('admin.warehouse.edit',['warehouse' => $warehouse->id])}}" title="{{__('Edit Warehouse')}}" class="mx-0.5 edit_warehouse_btn">
                    <i class="fa fa-pencil mx-0.5"></i>
                </a>
                @if($warehouse->is_approved == 0)
                    <a href="#" data-url={{route('admin.warehouse.approve',['warehouse' => $warehouse->id])}} title="{{__('Approve Warehouse')}}" class="mx-0.5 approve_warehouse_btn">
                        <i class="fa fa-toggle-off mx-0.5"></i>
                    </a>
                @else
                    <a href="#" data-url={{route('admin.warehouse.disapprove',['warehouse' => $warehouse->id])}} title="{{__('Disapprove Warehouse')}}" class="mx-0.5 disapprove_warehouse_btn">
                        <i class="fa fa-toggle-on mx-0.5"></i>
                    </a>
                @endif
                <a href="#" data-url={{route('admin.warehouse.destroy',['warehouse' => $warehouse->id])}} title="{{ __('Delete warehouse') }}" class="ml-1 delete_warehouse_btn">
                    <i class="fa fa-trash mx-0.5"></i>
                </a>
            @endif
        </td>
    </tr>
@endforeach
