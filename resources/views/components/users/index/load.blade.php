<div class="overflow-x-auto mt-6 search_results" id="users">
    <table class="table-auto border-collapse w-full">
        <thead>
            <tr class="rounded-lg text-sm font-medium text-gray-700 text-left" style="font-size: 0.9674rem">
            <th class="px-4 py-2 bg-gray-200 " style="background-color:#f8f8f8">{{ __('ID') }}</th>
            <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Name') }}</th>
            <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('User Type') }}</th>
            <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Status') }}</th>
            <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Created At') }}</th>
            <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Controls') }}</th>
            </tr>
        </thead>
        <tbody class="text-sm font-normal text-gray-700">
            @foreach ($users->data as $user)
            @if ($user->id != Auth::user()->id)
            <tr class="hover:bg-gray-100 border-b border-gray-200 py-10">
                <td class="px-4 py-4">{{$user->id}}</td>
                <td class="px-4 py-4">{{$user->name}}</td>
                <td class="px-4 py-4">{{ ucfirst($user->user_type) }}</td>
                <td class="px-4 py-4 {{$user->deleted_at ? 'text-red-500': 'text-green-500'}}">{{$user->deleted_at ? 'Deleted': 'On'}}</td>
                <td class="px-4 py-4">{{$user->created_at}}</td>
                <td class="px-4 py-4">
                    <?php
                        $editRoute = route('admin.users.role.update', ['user' => $user->id]);
                        $editTitle = __("Edit User Role");
                    ?>

                    <span title="{{$editTitle}}" data-target-url="{{$editRoute}}" class="mx-0.5 edit_role" data-role="{{$user->user_type}}">
                        <i class="fa fa-pencil mx-0.5"></i>
                    </span>
                </td>
            </tr>

            @endif
            @endforeach
        </tbody>
    </table>
    <div id="" class="w-full flex justify-center border-t border-gray-100 pt-4 items-center">
        {{$paginator}}
    </div>
</div>