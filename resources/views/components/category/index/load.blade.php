<div class="overflow-x-auto mt-6" id="categories">
    <table class="table-auto border-collapse w-full">
        <thead>
            <tr class="rounded-lg text-sm font-medium text-gray-700 text-left" style="font-size: 0.9674rem">
            <th class="px-4 py-2 bg-gray-200 " style="background-color:#f8f8f8">{{ __('ID') }}</th>
            <th class="px-4 py-2" style="background-color:#f8f8f8">{{ __('Image') }}</th>
            <th class="px-4 py-2" style="background-color:#f8f8f8">{{ __('Title') }}</th>
            <th class="px-4 py-2" style="background-color:#f8f8f8">{{ __('Parent Category') }}</th>
            <th class="px-4 py-2" style="background-color:#f8f8f8">{{ __('Status') }}</th>
            <th class="px-4 py-2" style="background-color:#f8f8f8">{{ __('Created At') }}</th>
            <th class="px-4 py-2" style="background-color:#f8f8f8">{{ __('Display Section') }}</th>
            <th class="px-4 py-2" style="background-color:#f8f8f8">{{ __('Controls') }}</th>
            </tr>
        </thead>
        <tbody class="text-sm font-normal text-gray-700 search_results">
            @foreach ($categories->data as $category)
            <tr class="hover:bg-gray-100 border-b border-gray-200 py-10">
                <td class="px-4 py-4">{{$category->id}}</td>
                <td class="px-4 py-4">
                    @if (!empty($category->image_path))
                        <img class="object-cover object-center" width="100" height="100" src="{{ asset(str_replace('/storage/', '', $category->image_path)) }}" alt="{{$category->title . ' Image'}}" />
                    @else
                        {{__('Not Provided')}}
                    @endif
                </td>
                <td class="px-4 py-4">{{$category->title}}</td>
                <td class="px-4 py-4">{{!empty($category->parent_category) ? $category->parent_category->title : 'NA'}}</td>
                <td class="px-4 py-4 {{$category->deleted_at ? 'text-red-500': 'text-green-500'}}">{{$category->deleted_at ? 'Deleted': 'On'}}</td>
                <td class="px-4 py-4">{{$category->created_at}}</td>
                <td class="px-4 py-4">{{!empty($category->home_page_category) ? $category->home_page_category->display_section : 'NA' }}</td>
                <td class="px-4 py-4">
                    <?php
                        $showRoute = route('admin.category.show',['category' => $category->id]);
                        $showTitle = __('View Category Details');
                        $editRoute = route('admin.category.edit',['category' => $category->id]);
                        $editTitle = __('Edit Category Details');
                        if($category->deleted_at) {
                            $showRoute = route('admin.category.show-deleted',['category_id' => $category->id]);
                            $editRoute = "#";
                            $editTitle = "Reactivate category to edit it";
                        }
                    ?>
                    <a href="{{$showRoute}}" title="{{$showTitle}}">
                        <i class="fa fa-eye"></i>
                    </a>
                    <a href="{{$editRoute}}" title="{{$editTitle}}" class="mx-0.5">
                        <i class="fa fa-pencil mx-0.5"></i>
                    </a>
                    @if ($category->deleted_at)
                        <a href="{{route('admin.category.restore',['category_id' => $category->id])}}" title="{{ __('Enable Category') }}" class="ml-1 restore-category">
                            <i class="fa fa-toggle-off"></i>
                        </a>
                    @else
                        <a href="{{route('admin.category.destroy',['category' => $category->id])}}" title="{{ __('Deactivate Category') }}" class="ml-1 delete-category">
                            <i class="fa fa-toggle-on"></i>
                        </a>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div id="pagination" class="w-full flex justify-center border-t border-gray-100 pt-4 items-center">
        {{$paginator}}
    </div>
</div>
