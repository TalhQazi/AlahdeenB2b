<div id="images_info" class="sm:px-8 md:px-16 sm:py-8 hidden">
    @if (!empty($warehouse->images->data))
        @foreach ($warehouse->images->data as $index => $image)
            <div class="inline-block bg-white px-4 py-5 rounded-lg shadow-lg text-center w-48 warehouse-img-div">
                <div class="mb-4">
                    <img class="w-auto mx-auto object-cover object-center h-40" id="{{'photo_'.$index.'_preview'}}" src="{{ $image->image_path }}" alt="Warehouse Image Upload" />
                    <i class="fa fa-times text-red-500 relative remove_image" style="top: -180px; left: 88px;" data-target="{{$index}}"></i>
                </div>
                <label class="cursor-pointer mt-6">
                    <span class="mt-2 text-base leading-normal px-2 py-2 bg-blue-500 text-white text-sm rounded-full" >{{ __('Warehouse Image') }}</span>
                    <input type="hidden" name="warehouse_image_id[{{$index}}]" id="warehouse_image_id_{{$index}}" value="{{$image->id}}">
                    <input type='file' name="warehouse_images[{{$index}}]" id="photo_{{$index}}" class="hidden warehouse_image" :accept="accept" />
                </label>
                <label class="cursor-pointer mt-6">
                    @if ($image->is_main)
                        <div class="my-4">
                            {{ __('Main Image') }}
                            <input type="checkbox" class="set_main_image" checked disabled>
                        </div>
                    @else
                        <div class="my-4">
                            {{ __('Main Image') }}
                            <input type="checkbox" class="set_main_image" data-warehouse_id="{{$warehouse->id}}" data-image_id="{{$image->id}}">
                        </div>
                    @endif
                </label>

            </div>
        @endforeach
    @endif
    <main class="container mx-auto max-w-screen-lg h-full my-4">
      <!-- file upload modal -->
      <article aria-label="File Upload Modal" class="relative h-full flex flex-col bg-white shadow-xl rounded-md">
        <!-- overlay -->
        <div id="image-overlay" class="w-full h-full absolute top-0 left-0 pointer-events-none z-50 flex flex-col items-center justify-center rounded-md">
          <i>
            <svg class="fill-current w-12 h-12 mb-3 text-blue-700" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
              <path d="M19.479 10.092c-.212-3.951-3.473-7.092-7.479-7.092-4.005 0-7.267 3.141-7.479 7.092-2.57.463-4.521 2.706-4.521 5.408 0 3.037 2.463 5.5 5.5 5.5h13c3.037 0 5.5-2.463 5.5-5.5 0-2.702-1.951-4.945-4.521-5.408zm-7.479-1.092l4 4h-3v4h-2v-4h-3l4-4z" />
            </svg>
          </i>
        </div>

        <!-- scroll area -->
        <section class="overflow-auto p-8 w-full h-full flex flex-col">
          <header class="border-dashed border-2 border-gray-400 py-12 flex flex-col justify-center items-center">
            <input id="hidden-input" name="warehouse_images[]" type="file" multiple class="hidden" accept="image/x-png,image/jpeg"/>
            <button type="button" id="button" class="mt-2 rounded-sm px-3 py-1 bg-gray-200 hover:bg-gray-300 focus:shadow-outline focus:outline-none">
              {{ __('Upload a file') }}
            </button>
          </header>

          <h1 class="pt-8 pb-3 font-semibold sm:text-lg text-gray-900">
            {{ __('To Upload') }}
          </h1>

          <ul id="gallery" class="flex flex-1 flex-wrap -m-1">
            <li id="empty" class="h-full w-full text-center flex flex-col items-center justify-center items-center">
              <img class="mx-auto w-32" src="https://user-images.githubusercontent.com/507615/54591670-ac0a0180-4a65-11e9-846c-e55ffce0fe7b.png" alt="no data" />
              <span class="text-small text-gray-500">{{ __('No files selected') }}</span>
            </li>
          </ul>
        </section>



      </article>
    </main>
</div>

<template id="image-template">
    <li class="block p-1 w-1/2 sm:w-1/3 md:w-1/4 lg:w-1/6 xl:w-1/8 h-24">
        <article tabindex="0" class="group hasImage w-full h-full rounded-md focus:outline-none focus:shadow-outline bg-gray-100 cursor-pointer relative text-transparent hover:text-white shadow-sm">
            <img alt="upload preview" class="img-preview w-full h-full sticky object-cover rounded-md bg-fixed" />
            <section class="flex flex-col rounded-md text-xs break-words w-full h-full z-20 absolute top-0 py-2 px-3">
                <h1 class="flex-1"></h1>
                <div class="flex">
                    <span class="p-1">
                        <i>
                        <svg class="fill-current w-4 h-4 ml-auto pt-" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                            <path d="M5 8.5c0-.828.672-1.5 1.5-1.5s1.5.672 1.5 1.5c0 .829-.672 1.5-1.5 1.5s-1.5-.671-1.5-1.5zm9 .5l-2.519 4-2.481-1.96-4 5.96h14l-5-8zm8-4v14h-20v-14h20zm2-2h-24v18h24v-18z" />
                        </svg>
                        </i>
                    </span>

                    <p class="p-1 size text-xs"></p>
                    <button class="delete ml-auto focus:outline-none hover:bg-gray-300 p-1 rounded-md">
                        <svg class="pointer-events-none fill-current w-4 h-4 ml-auto" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <path class="pointer-events-none" d="M3 6l3 18h12l3-18h-18zm19-4v2h-20v-2h5.711c.9 0 1.631-1.099 1.631-2h5.316c0 .901.73 2 1.631 2h5.711z" />
                        </svg>
                    </button>
                    <input type="radio" name="main_image" class="main-image"> {{__('Main Image')}}
                </div>
            </section>
        </article>
    </li>
</template>
