<div class="overflow-x-auto mt-6" id="products">

    <table class="table-auto border-collapse w-full">
        <thead>
            <tr class="rounded-lg text-sm font-medium text-gray-700 text-left" style="font-size: 0.9674rem">
                <th class="px-4 py-2 bg-gray-200 " style="background-color:#f8f8f8">{{ __('ID') }}</th>
                <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('File') }}</th>
                <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Category') }}</th>
                <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('User') }}</th>
                <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Created At') }}</th>
                <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Controls') }}</th>
            </tr>
        </thead>
        <tbody class="text-sm font-normal text-gray-700 search_results">
            @if (!empty($products))
                @foreach ($products as $key => $product)
                    <tr class="hover:bg-gray-100 border-b border-gray-200 py-10">
                        <td class="px-4 py-4">{{ $product->id }}</td>
                        <td class="px-4 py-4">{{ $product->file_path }}</td>
                        <td class="px-4 py-4">{{ $product->category->title }}</td>
                        <td class="px-4 py-4">{{ $product->user->name }}</td>
                        <td class="px-4 py-4">{{ $product->created_at }}</td>
                        <td class="px-4 py-4">
                            <?php
                            $deleteTitle = __('Delete Matter Sheet');

                            $approveTitle = __('Approve Matter Sheet');

                            if ($product->deleted_at) {
                                $deleteRoute = route('product.show-deleted', ['product_id' => $product->id]);
                                $editRoute = '#';
                                $editTitle = __('Reactivate product to edit it');
                            }
                            ?>

                            <a href="{{ route('matter-sheet.matter_sheet_product.listing', ['matter_sheet_id' => $product->id]) }}"
                                title="{{ __('Show Products') }}">
                                <i class="fas fa-eye mx-2"></i>
                            </a>

                            @if ($product->is_cpa_approved == 0)
                                <a href="{{ route('matter-sheet.matter_sheet.destroy', ['matter_sheet' => $product->id]) }}"
                                    title="{{ $deleteTitle }}">
                                    <i class="fas fa-trash mx-2"></i>
                                </a>

                                @hasanyrole('admin|super-admin')
                                <a title="{{ $approveTitle }}"
                                    href="{{ route('matter-sheet.matter_sheet.approve', ['matter_sheet' => $product->id]) }}">
                                    <i class="fas fa-check mx-2"></i>
                                </a>
                                @endhasanyrole
                                @endif

                        </td>
                    </tr>
                @endforeach
            @else
                <tr class="hover:bg-gray-100 border-b border-gray-200 py-10">
                    <td class="px-4 py-4">
                        {{ __('No Matter Sheet Found') }}
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
    <div id="pagination" class="w-full flex justify-center border-t border-gray-100 pt-4 items-center">
        {{ $paginator }}
    </div>
</div>
