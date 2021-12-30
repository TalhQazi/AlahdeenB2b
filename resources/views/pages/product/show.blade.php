@extends('layouts.master')

@section('page')
@parent
<div id="image-gallery">
    <div class="grid grid-cols-6 sm:grid-cols-1 md:grid-cols-1 lg:grid-cols-6 xl:grid-cols-4 gap-2">
        <?php
            $imagesCount = 0;
            $productImages = $product->images->data;
        ?>
        @foreach ($productImages as $image)
            @if ($imagesCount <= 1)
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
                        {{__('Main Image')}} <input type="checkbox" class="set_main_image" checked disabled>
                    @else
                        {{__('Main image')}} <input type="checkbox" class="set_main_image" data-image_id={{$image->id}} name="" id="">
                    @endif

                </div>

            </div>
            <?php $imagesCount++; ?>
        @endforeach

    </div>
    @if ($imagesCount > 1 )
        <div class="m-5">
            <span class="view-more-images text-blue-500 cursor-pointer align-middle hover:underline more">
                {{__('Show More')}}
                <i class="fa fa-angle-down align-middle"></i>
            </span>
        </div>
    @endif
</div>

<div class="mt-6">
    <div class='w-full md:w-full px-3 mb-6'>
        <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2' for='grid-text-1'>{{__('Title')}}</label>
        <input class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500' name="title" id='grid-text-1' type='text' value="{{$product->title}}" disabled>
    </div>
    <div class='w-full md:w-full px-3 mb-6'>
        <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2' for='grid-text-1'>{{__('Description')}}</label>
        <textarea name="description" id="" cols="30" rows="10" class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500' value="{{$product->description}}" disabled>
            {{$product->description}}
        </textarea>
    </div>
    <div class='w-full md:w-full px-3 mb-6'>
        <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2' for='grid-text-1'>{{__('Price')}}</label>
        <input class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500' name="title" id='grid-text-1' type='text' value="{{$product->price}}" disabled>
    </div>
    <div class='w-full md:w-full px-3 mb-6'>
        <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2' for='grid-text-1'>{{__('Category')}}</label>
        <input class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500' name="title" id='grid-text-1' type='text' value="{{$product->category->title}}" disabled>
    </div>
    <div class='w-full md:w-full px-3 mb-6'>
      <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="is featured">{{__('Is Featured')}}</label>
      <input class="block bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500" name="is_featured" id="is_featured" type="checkbox" {{ $product->is_featured ?  'checked' : '' }} disabled>
    </div>
    @if (!empty($product->details->data))
    @foreach ($product->details->data as $detail)
    <div class='w-full md:w-full px-3 mb-6'>
        <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2' for='grid-text-1'>{{__('Key')}}</label>
        <input class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500' name="title" id='grid-text-1' type='text' value="{{$detail->key}}" disabled>
    </div>
    <div class='w-full md:w-full px-3 mb-6'>
        <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2' for='grid-text-1'>{{__('Value')}}</label>
        <input class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500' name="title" id='grid-text-1' type='text' value="{{$detail->value}}" disabled>
    </div>
    @endforeach
    @endif
</div>

@endsection

@push('scripts')
    <script type="text/javascript" src="{{ asset(('js/show_more_images.js')) }}"></script>
@endpush


