<x-popup-wrapper>
    @section('popup-id', 'add_stock')
    @section('popup-title', __('Add Stock'))
    @section('popup-body')
        @if (count($products) > 0)
            <div class="existing-clients flex flex-row w-full">
                <form method="POST" action="{{ route('khata.inventory.product.stock.store') }}" id="add_product_stock">
                    @csrf
                    <div>
                        <label for="existing-product">{{ __('Choose Product from Existing List') }}</label>
                        <select class="text-gray-600 w-full bg-white border border-gray-400 shadow-inner px-4 py-2 pr-8 rounded" name="existing_product" id="existing_product" required>
                            <option value="">{{ __('Select Product') }}</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}">{{ $product->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mt-5">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold" for="Quantity">
                            {{ __('Quantity') }}
                            <i class="fa fa-asterisk text-red-500"></i>
                        </label>
                        <input class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none focus:border-gray-500" name="quantity" id="quantity" type="text">
                    </div>

                </form>
            </div>
        @endif
    @endsection
</x-popup-wrapper>
