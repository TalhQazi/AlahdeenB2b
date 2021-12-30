<div class="overflow-x-auto mt-6" id="testimonials">
    <table class="table-auto border-collapse w-full">
        <thead>
            <tr class="rounded-lg text-sm font-medium text-gray-700 text-left" style="font-size: 0.9674rem">
            <th class="px-4 py-2 bg-gray-200 " style="background-color:#f8f8f8">{{ __('ID') }}</th>
            <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('User Name') }}</th>
            <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('User Designation') }}</th>
            <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Company Name') }}</th>
            <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Company Website') }}</th>
            <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Message') }}</th>
            <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Image') }}</th>
            <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Status') }}</th>
            <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Created At') }}</th>
            <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Controls') }}</th>
            </tr>
        </thead>
        <tbody class="text-sm font-normal text-gray-700 search_results">
            @foreach ($testimonials as $testimony)
            <tr class="hover:bg-gray-100 border-b border-gray-200 py-10">
                <td class="px-4 py-4">{{$testimony->id}}</td>
                <td class="px-4 py-4">{{$testimony->user_name}}</td>
                <td class="px-4 py-4">{{$testimony->designation}}</td>
                <td class="px-4 py-4">{{$testimony->company_name ? $testimony->company_name : 'NA'}}</td>
                <td class="px-4 py-4">{{$testimony->company_website ? $testimony->company_website : 'NA'}}</td>
                <td class="px-4 py-4">{{$testimony->message}}</td>
                <td class="px-4 py-4">
                    <img class="w-auto object-cover object-center h-10" id="certification_img" src="{{asset(str_replace('/storage/','',$testimony->image_path))}}" alt="Company Logo Upload" />
                </td>
                <td class="px-4 py-4 {{$testimony->deleted_at ? 'text-red-500': 'text-green-500'}}">{{$testimony->deleted_at ? 'Deleted': 'On'}}</td>
                <td class="px-4 py-4">{{$testimony->created_at}}</td>
                <td class="px-4 py-4">
                    <?php

                        $editRoute = route('admin.testimonials.edit',['testimonial' => $testimony->id]);
                        $editTitle = __('Edit Testimony Details');
                        $updateRoute = route('admin.testimonials.update',['testimonial' => $testimony->id]);
                        if($testimony->deleted_at) {
                            $editRoute = "#";
                            $editTitle = "Reactivate testimony to edit it";
                        }
                    ?>

                    <a href="#" title="{{$editTitle}}" data-url="{{$editRoute}}" class="mx-0.5 edit_testimony_btn">
                        <i class="fa fa-pencil mx-0.5"></i>
                    </a>
                    @if ($testimony->deleted_at)
                        <a  href="#" data-url={{route('admin.testimonials.restore',['testimonial_id' => $testimony->id])}} title="{{ __('Restore testimony') }}" class="ml-1 restore_testimony_btn">
                            <i class="fa fa-toggle-off"></i>
                        </a>
                    @else
                        <a href="#" data-url={{route('admin.testimonials.destroy',['testimonial' => $testimony->id])}} title="{{ __('Delete testimony') }}" class="ml-1 delete_testimony_btn">
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
