<div class="overflow-x-auto mt-6" id="products">

    <table class="table-auto border-collapse w-full">
        <thead>
            <tr class="rounded-lg text-sm font-medium text-gray-700 text-left" style="font-size: 0.9674rem">
                <th class="px-4 py-2 bg-gray-200 " style="background-color:#f8f8f8">{{ __('ID') }}</th>
                <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Image') }}</th>
                <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Title') }}</th>
                <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Price') }}</th>
                <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Status') }}</th>
                <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Category') }}</th>
                <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('CPA') }}</th>
                <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Created At') }}</th>
                <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Controls') }}</th>
            </tr>
        </thead>
        <tbody class="text-sm font-normal text-gray-700 search_results">
            @if (!empty($products))
                @foreach ($products as $key => $product)
                    <tr class="hover:bg-gray-100 border-b border-gray-200 py-10">
                        <td class="px-4 py-4">{{ $product->id }}</td>
                        <td class="px-4 py-4">
                            @if (!empty($product->image_path))
                                <img class="object-cover object-center" width="100" height="100"
                                    src="{{ url(Storage::url($product->image_path)) }}"
                                    alt="{{ $product->title . ' Image' }}" />
                            @else
                                {{ __('Not Provided') }}
                            @endif
                        </td>
                        <td class="px-4 py-4">{{ $product->title }}</td>
                        <td class="px-4 py-4">{{ $product->price }}</td>
                        <td class="px-4 py-4 {{ $product->deleted_at ? 'text-red-500' : 'text-green-500' }}">
                            {{ $product->deleted_at ? 'Deleted' : 'On' }}</td>
                        <td class="px-4 py-4">{{ $product->category }}</td>
                        <td class="px-4 py-4">
                            @if ($product->is_cpa_approved == 0)
                                <p class="text-blue-800">{{ __('Pending') }}</p>
                            @else
                                <p class="text-green-800">{{ __('Approved') }}</p>
                            @endif
                        </td>
                        <td class="px-4 py-4">{{ $product->created_at }}</td>
                        <td class="px-4 py-4">
                            <?php
                            $editRoute = route('matter-sheet.matter_sheet_product.edit', ['matter_sheet_product' => $product->id]);
                            $editTitle = __('Edit Matter Sheet Product');
                            ?>

                            @if ($product->is_cpa_approved == 0)

                                <a href="{{ $editRoute }}" title="{{ $editTitle }}" class="mx-0.5">
                                    <i class="fa fa-pencil mx-0.5"></i>
                                </a>

                            @endif

                        </td>
                    </tr>
                @endforeach
            @else
                <tr class="hover:bg-gray-100 border-b border-gray-200 py-10">
                    <td class="px-4 py-4">
                        {{ __('No Products Found') }}
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
    <div id="pagination" class="w-full flex justify-center border-t border-gray-100 pt-4 items-center">
        {{ $paginator }}
    </div>
</div>
