@if (!empty($products))
    @foreach ($products->data as $key => $product)
        <tr class="hover:bg-gray-100 border-b border-gray-200 py-10">
            <td class="px-4 py-4">{{$product->id}}</td>
            <td class="px-4 py-4"><input class="shadow-inner rounded-md py-3 px-4 leading-tight is_featured" id="is_featured_{{$product->id}}" type="checkbox" {{ $product->is_featured ?  'checked' : '' }} data-product-id="{{$product->id}}"></td>
            <td class="px-4 py-4">
                @if (!empty($product->images->data))
                    <img class="object-cover object-center" width="100" height="100" src="{{asset(str_replace('/storage/','',$product->images->data[0]->path))}}" alt="{{$product->title . ' Image'}}" />
                @else
                    {{__('Not Provided')}}
                @endif
            </td>
            <td class="px-4 py-4">{{$product->title}}</td>
            <td class="px-4 py-4">{{$product->price}}</td>
            <td class="px-4 py-4 {{$product->deleted_at ? 'text-red-500': 'text-green-500'}}">{{$product->deleted_at ? 'Deleted': 'On'}}</td>
            <td class="px-4 py-4">{{$product->category->title}}</td>
            <td class="px-4 py-4">{{$product->created_by->name}}</td>
            <td class="px-4 py-4">{{$product->created_at}}</td>
            <td class="px-4 py-4">
                <?php
                    $showRoute = route('admin.product.show',['product' => $product->id]);
                    $showTitle = __('View Product Details');
                    $editRoute = route('admin.product.edit',['product' => $product->id]);
                    $editTitle = __('Edit Product');
                    if($product->deleted_at) {
                        $showRoute = route('admin.product.show-deleted',['product_id' => $product->id]);
                        $editRoute = "#";
                        $editTitle = __('Reactivate product to edit it');
                    }
                ?>
                <a href="{{$showRoute}}" title="{{$showTitle}}">
                    <i class="fa fa-eye"></i>
                </a>
                <a href="{{$editRoute}}" title="{{$editTitle}}" class="mx-0.5">
                    <i class="fa fa-pencil mx-0.5"></i>
                </a>
                @if ($product->deleted_at)
                    <a href="{{route('product.restore',['product_id' => $product->id])}}" title="{{ __('Enable Product') }}" class="ml-1 restore-product">
                        <i class="fa fa-toggle-off"></i>
                    </a>
                @else
                    <a href="{{route('product.destroy',['product' => $product->id])}}" title="{{ __('Deactivate Product') }}" class="ml-1 delete-product">
                        <i class="fa fa-toggle-on"></i>
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
