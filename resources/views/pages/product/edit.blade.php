@extends('layouts.master')

@push('styles')
    <link rel="stylesheet" type="text/css" href="{{asset(('css/add_products.css'))}}">
@endpush

@section('page')
@parent
<div id="image-gallery">
    <div class="grid grid-cols-6 sm:grid-cols-1 md:grid-cols-1 lg:grid-cols-6 xl:grid-cols-4 gap-2">
        <?php
            $imagesCount = 0;
            $productImages = $product->images->data;
        ?>
        @foreach ($productImages as $image)
            @if ($imagesCount < 1)
                <div class="product-images flex flex-col items-center justify-center bg-white p-4 shadow rounded-lg">
            @else
                <div class="product-images hidden flex flex-col items-center justify-center bg-white p-4 shadow rounded-lg">
            @endif

                <div class="inline-flex shadow-lg border border-gray-200 rounded-full overflow-hidden h-40 w-40">
                    <img src="{{asset(str_replace('/storage/', '', $image->path))}}"
                        alt=""
                        class="h-full w-full">
                </div>


                <div>
                    @if ($image->is_main)
                        {{ __('Main Image') }} <input type="checkbox" class="set_main_image" checked disabled>
                    @else
                        {{ __('Main Image') }} <input type="checkbox" class="set_main_image" data-product_id="{{$product->id}}" data-image_id={{$image->id}} name="" id="">
                    @endif

                </div>

                <ul class="flex flex-row mt-4 space-x-2">
                    <li>
                    <span data-main-image="{{$image->is_main == 1 ? 1 : 0}}" data-image_id="{{$image->id}}" class="change_product_pic flex items-center justify-center h-8 w-8 border rounded-full text-gray-800 border-gray-800">
                            <i class="fa fa-pencil"></i>
                        </span>
                    </li>
                    <li>
                        <a href="{{route('product.delete-image',['product' => $product->id, 'product_image' => $image->id])}}" class="flex items-center justify-center h-8 w-8 border rounded-full text-gray-800 border-gray-800">
                            <i class="fa fa-trash"></i>
                        </a>
                    </li>
                </ul>
            </div>
            <?php $imagesCount++; ?>
        @endforeach

    </div>



    <div class="m-5 flex">
        @if ($imagesCount > 1 )
            <button class="view-more-images btn btn-danger more">
                {{ __('Show More') }}
                <i class="fa fa-angle-down align-middle"></i>
            </button>
        @endif
            <button id="product-add-images" class="add-more-images btn btn-success ml-5">
                {{ __('Add More Images') }}
                <i class="fa fa-plus align-middle"></i>
            </button>
            <a href="{{$view_docs_link}}" id="product-view-documents" class="btn btn-indigo ml-5">
                {{ __('View Documents') }}
                <i class="fa fa-eye align-middle"></i>
            </a>
        </div>

</div>

<div class="mt-6">
    <form method="post" action="{{route('product.update',['product' => $product->id])}}">
        @csrf
        {{ method_field('PUT') }}
        @if (!empty($product->videos))
            @foreach ($product->videos as $video)
                <div class="w-full md:w-full px-3 mb-6">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-text-1">{{ __('Product Video Link') }}</label>
                    <input type="hidden" name="product_videos_id[]" value="{{$video->id}}">
                    <input class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500" name="product_videos_link[]" id="product_videos_link[]" type="text" value="{{$video->link}}">
                </div>
            @endforeach

        @else
            <div class="w-full md:w-full px-3 mb-6">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-text-1">{{ __('Product Video Link') }}</label>
                <input type="hidden" name="product_videos_id[]">
                <input class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500" name="product_videos_link[]" id="product_videos_link[]" type="text">
            </div>

        @endif
        <div class="w-full md:w-full px-3 mb-6">
            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="title">
                {{ __('Title') }}
                <i class="fas fa-asterisk text-red-500"></i>
            </label>
            <input class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500" name="title" id="title" type="text" value="{{ old('title') ?? $product->title}}">
        </div>
        <div class="w-full md:w-full px-3 mb-6">
            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="description">
                {{ __('Description') }}
                <i class="fas fa-asterisk text-red-500"></i>
            </label>
            <textarea id="description" name="description" id="" cols="30" rows="10" class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500" value="{{ old('description') ?? $product->description }}">
                {{$product->description}}
            </textarea>
        </div>
        <div class="w-full md:w-full px-3 mb-6">
            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-text-1">
                {{ __('Price') }}
                <i class="fas fa-asterisk text-red-500"></i>
            </label>
            <input class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500" name="price" id="price" type="text" value="{{ old('price') ?? $product->price}}">
        </div>
        <div class="w-full md:w-full px-3 mb-6" id="level_1_div">
            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">
              {{ __('Select Category') }}
              <i class="fas fa-asterisk text-red-500"></i>
            </label>
          <div class="flex-shrink w-full inline-block relative">
              <input type="hidden" name="product_categories" id="product_categories" value={{ $product->category_id }}>
              <select class="block appearance-none text-gray-600 w-full bg-white border border-gray-400 shadow-inner px-4 py-2 pr-8 rounded category" name="category[1]" id="category_level_1" data-level="1">
                  <option value="">{{ __('Select Category') }}</option>
                  @foreach ($categories as $category)
                    @if (!empty($product_parent_categories[0]) && $category->id == $product_parent_categories[0])
                    <option selected value="{{$category->id}}">{{$category->title}}</option>
                    @else
                        <option value="{{$category->id}}">{{$category->title}}</option>
                    @endif
                  @endforeach
              </select>
              <div class="pointer-events-none absolute top-0 mt-3  right-0 flex items-center px-2 text-gray-600">
                  <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
              </div>
          </div>
        </div>
        <div class='w-full md:w-full px-3 mb-6'>
          <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="is featured">{{__('Is Featured')}}</label>
          <input class="block bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500" name="is_featured" id="is_featured" type="checkbox" {{ $product->is_featured ?  'checked' : '' }} {{ $product->is_featured == 0 && $can_be_featured == 0 ? 'disabled' : '' }}>
          @if ($product->is_featured == 0 && $can_be_featured == 0) <span class="text-gray-700">{{ __('It is disabled since the limit for max featured products has already been reached') }} </span> @endif
        </div>

        @if (!empty($product->details->data))
            <div id="specifications_div" class="mb-6" data-spec-counter={{count($product->details->data)}}>
            @foreach ($product->details->data as $detail)
                <div id="specification_{{$detail->id}}">

                    <input type="hidden" name="product_details_id[]" value="{{$detail->id}}">
                    <div class="w-full md:w-full px-3 mb-6">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-text-1">
                            {{__('Key') }}
                            <i class="fas fa-asterisk text-red-500"></i>
                        </label>
                        <input class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500" name="product_details_key[]" id="grid-text-1" type="text" value="{{$detail->key}}">
                    </div>
                    <div class="w-full md:w-full px-3 mb-6">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-text-1">
                            {{__('Value')}}
                            <i class="fas fa-asterisk text-red-500"></i>
                        </label>
                        <input class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500" name="product_details_value[]" id="grid-text-1" type="text" value="{{$detail->value}}">
                    </div>
                    <span class="w-full md:w-full px-3 mb-6 text-blue-500 underline remove-product-details">{{__('Remove Specification')}}</span>
                </div>
            @endforeach
            </div>
        @endif

        <span class="w-full md:w-full px-3 mb-6 text-blue-500 underline" id="add_product_details">{{__('Add More Product Specifications')}}</span>
        <div class="personal w-full border-t border-gray-400 pt-4" id="save-btn-div">
            <div class="flex justify-end">
                <button class="btn-bs-secondary" type="submit">{{__('Save Product')}}</button>
            </div>
        </div>
    </form>
</div>

@endsection

@section('modals')
    @parent
    @include('components.edit-product-image')
    @include('components.add-product-images')
@endsection

@push('scripts')
    <script>
        var base_url = '{{ config('app.url') }}';
    </script>
    <script type="text/javascript" src="{{ asset(('js/show_more_images.js')) }}"></script>
    <script type="text/javascript" src="{{ asset(('js/product_edit.js')) }}"></script>
    <script src="https://cdn.tiny.cloud/1/nkcpprlcvgg1ldeqgx3dn4mhqmutceszm1yqqf73vsyqhoq9/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: '#description'
        });
    </script>
@endpush


