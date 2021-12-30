@extends('layouts.master')

@push('styles')
    <link rel="stylesheet" type="text/css" href="{{asset(('css/add_products.css'))}}">
@endpush


@section('page')
    @parent
    <div class="min-h-screen pt-2 font-mono">
        <div class="container mx-auto">
            <div class="inputs w-full p-6">
                <h2 class="text-2xl text-gray-900">{{ __('Add Product') }}</h2>
                <form id="add_product_form" class="mt-6 border-t border-gray-400 pt-4" enctype="multipart/form-data" method="POST" action="{{route('product.store')}}">
                    @csrf
                    <div class="bg-gray-500 sm:px-8 md:px-16 sm:py-8">
                        <main class="container mx-auto max-w-screen-lg h-full">
                          <!-- file upload modal -->
                          <article aria-label="File Upload Modal" class="relative h-full flex flex-col bg-white shadow-xl rounded-md" ondrop="dropHandler(event);" ondragover="dragOverHandler(event);" ondragleave="dragLeaveHandler(event);" ondragenter="dragEnterHandler(event);">
                            <!-- overlay -->
                            <div id="image-overlay" class="w-full h-full absolute top-0 left-0 pointer-events-none z-50 flex flex-col items-center justify-center rounded-md">
                              <i>
                                <svg class="fill-current w-12 h-12 mb-3 text-blue-700" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                  <path d="M19.479 10.092c-.212-3.951-3.473-7.092-7.479-7.092-4.005 0-7.267 3.141-7.479 7.092-2.57.463-4.521 2.706-4.521 5.408 0 3.037 2.463 5.5 5.5 5.5h13c3.037 0 5.5-2.463 5.5-5.5 0-2.702-1.951-4.945-4.521-5.408zm-7.479-1.092l4 4h-3v4h-2v-4h-3l4-4z" />
                                </svg>
                              </i>
                              <p class="text-lg text-blue-700">{{ __('Drop files to upload') }}</p>
                            </div>

                            <!-- scroll area -->
                            <section class="h-full overflow-auto p-8 w-full h-full flex flex-col">
                              <header class="border-dashed border-2 border-gray-400 py-12 flex flex-col justify-center items-center">
                                <p class="mb-3 font-semibold text-gray-900 flex flex-wrap justify-center">
                                  <span>{{ __('Drag and drop your') }}</span>&nbsp;<span>{{ __('files anywhere or') }}</span>
                                </p>
                                <input id="hidden-input" name="product_images[]" type="file" multiple class="hidden" accept="image/x-png,image/jpeg"/>
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

                    <!-- using two similar templates for simplicity in js code -->
                    <template id="file-template">
                        <li class="block p-1 w-1/2 sm:w-1/3 md:w-1/4 lg:w-1/6 xl:w-1/8 h-24">
                            <article tabindex="0" class="group w-full h-full rounded-md focus:outline-none focus:shadow-outline elative bg-gray-100 cursor-pointer relative shadow-sm">
                            <img alt="upload preview" class="img-preview hidden w-full h-full sticky object-cover rounded-md bg-fixed" />

                            <section class="flex flex-col rounded-md text-xs break-words w-full h-full z-20 absolute top-0 py-2 px-3">
                                <h1 class="flex-1 group-hover:text-blue-800"></h1>
                                <div class="flex">
                                <span class="p-1 text-blue-800">
                                    <i>
                                    <svg class="fill-current w-4 h-4 ml-auto pt-1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                        <path d="M15 2v5h5v15h-16v-20h11zm1-2h-14v24h20v-18l-6-6z" />
                                    </svg>
                                    </i>
                                </span>
                                <p class="p-1 size text-xs text-gray-700"></p>
                                <button class="delete ml-auto focus:outline-none hover:bg-gray-300 p-1 rounded-md text-gray-800">
                                    <svg class="pointer-events-none fill-current w-4 h-4 ml-auto" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                    <path class="pointer-events-none" d="M3 6l3 18h12l3-18h-18zm19-4v2h-20v-2h5.711c.9 0 1.631-1.099 1.631-2h5.316c0 .901.73 2 1.631 2h5.711z" />
                                    </svg>
                                </button>
                                </div>
                            </section>
                            </article>
                        </li>
                    </template>

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
                                <input type="radio" name="main_image" class="main-image">
                                </div>
                            </section>
                            </article>
                        </li>
                    </template>



                    <div class="flex flex-wrap -mx-3 mb-6 mt-10">

                        <div class="w-full md:w-full px-3 mb-6">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-text-1">{{ __('Product Video Link') }}</label>
                            <input class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500" name="product_video_link" id="product_video_link" type="text" value="{{ old('product_video_link') }}">
                        </div>
                        <div class="w-full md:w-full px-3 mb-6">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="product document">{{ __('Product Document') }}</label>
                            <input class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500" name="product_document" id="product_document" type="file">
                        </div>
                        <div class="w-full md:w-full px-3 mb-6">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="product title">
                                {{ __('Product Title') }}
                                <i class="fas fa-asterisk text-red-500"></i>
                            </label>
                            <input class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500" name="title" id="title" type="text" value="{{ old('title') }}" required>
                        </div>
                        <div class="w-full md:w-full px-3 mb-6">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="description">
                                {{ __('Product Description') }}
                                <i class="fas fa-asterisk text-red-500"></i>
                            </label>
                            <textarea name="description" id="description" cols="30" rows="10" class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500">
                                {{ old('description') }}
                            </textarea>
                        </div>
                        <div class="w-full md:w-full px-3 mb-6">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="price">
                                {{ __('Price') }}
                                <i class="fas fa-asterisk text-red-500"></i>
                            </label>
                            <input class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500" name="price" id="product_price" type="text" value="{{ old('price') }}" min="1">
                        </div>
                        <div class='w-full md:w-full px-3 mb-6' id="level_1_div">
                            <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'>
                                {{ __('Select Category') }}
                                <i class="fas fa-asterisk text-red-500"></i>
                            </label>
                            <div class="flex-shrink w-full inline-block relative">
                                <select class="block appearance-none text-gray-600 w-full bg-white border border-gray-400 shadow-inner px-4 py-2 pr-8 rounded category" name="category[1]" id="category_level_1" data-level="1">
                                    <option value="">{{ __('Select 1st Category Level') }}</option>
                                    @foreach ($categories as $category)
                                        <option {{ !empty(old('category.1')) && old('category.1') == $category->id ? "selected" : "" }} value="{{$category->id}}">{{$category->title}}</option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute top-0 mt-3  right-0 flex items-center px-2 text-gray-600">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                                </div>
                            </div>
                        </div>
                        <div class="w-full md:w-full px-3 mb-6">
                          <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="is featured">{{__('Is Featured')}}</label>
                          <input class="block bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500" name="is_featured" id="is_featured" type="checkbox" {{ $can_be_featured == 0 ? 'disabled' : '' }}>
                          @if ($can_be_featured == 0)<span class="text-gray-700">{{ __('It is disabled since the limit for max featured products has already been reached') }}</span>@endif
                        </div>
                        <div class="w-full" id="prod_specs_div">
                            <div class="w-full md:w-full px-3 mb-6">
                                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="detail key">
                                    {{ __('Product Detail Key') }}
                                    <i class="fas fa-asterisk text-red-500"></i>
                                </label>
                                <input class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500" name="product_details_key[]" id="product_details_key_1" type="text" value="{{ old('product_details_key.0') }}" required>
                            </div>
                            <div class="w-full md:w-full px-3 mb-6">
                                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-text-1">
                                    {{ __("Product Detail Value") }}
                                    <i class="fas fa-asterisk text-red-500"></i>
                                </label>
                                <input class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500" name="product_details_value[]" id="product_details_value_1" type="text" value="{{ old('product_details_value.0') }}" required>
                            </div>
                        </div>
                        <span class="w-full md:w-full px-3 mb-6 text-blue-500 underline" id="add_product_details">
                            {{ __('Add More Product Specifications') }}
                        </span>
                        <div class="personal w-full border-t border-gray-400 pt-4">
                            <div class="flex justify-end">
                                <button class="border border-gray-700 bg-gray-700 text-white rounded-md px-4 py-2 m-2 transition duration-500 ease select-none hover:bg-gray-800 focus:outline-none focus:shadow-outline" type="submit">{{ __('Save Product') }}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        const fileTempl = document.getElementById("file-template"),
        imageTempl = document.getElementById("image-template"),
        empty = document.getElementById("empty");

        // use to store pre selected files
        let FILES = {};

        // check if file is of type image and prepend the initialied
        // template to the target element
        function addFile(target, file) {
            const isImage = file.type.match("image.*"),
            objectURL = URL.createObjectURL(file);

            const clone = isImage
                ? imageTempl.content.cloneNode(true)
                : fileTempl.content.cloneNode(true);

            clone.querySelector("h1").textContent = file.name;
            clone.querySelector("li").id = objectURL;
            clone.querySelector(".delete").dataset.target = objectURL;
            clone.querySelector(".main-image").dataset.target = objectURL;
            clone.querySelector(".size").textContent =
            file.size > 1024
                ? file.size > 1048576
                ? Math.round(file.size / 1048576) + "mb"
                : Math.round(file.size / 1024) + "kb"
                : file.size + "b";

            isImage &&
                Object.assign(clone.querySelector("img"), {
                src: objectURL,
                alt: file.name
            });

            empty.classList.add("hidden");
            target.prepend(clone);

            FILES[objectURL] = file;
        }

        const gallery = document.getElementById("gallery"),
        overlay = document.getElementById("image-overlay");

        // click the hidden input of type file if the visible button is clicked
        // and capture the selected files
        const hidden = document.getElementById("hidden-input");
        document.getElementById("button").onclick = () => hidden.click();
            hidden.onchange = (e) => {
            for (const file of e.target.files) {
            addFile(gallery, file);
            }
        };

        // use to check if a file is being dragged
        const hasFiles = ({ dataTransfer: { types = [] } }) =>
            types.indexOf("Files") > -1;

            // use to drag dragenter and dragleave events.
            // this is to know if the outermost parent is dragged over
            // without issues due to drag events on its children
            let counter = 0;

        // reset counter and append file to gallery when file is dropped
        function dropHandler(ev) {
            ev.preventDefault();
            for (const file of ev.dataTransfer.files) {
                addFile(gallery, file);
                overlay.classList.remove("draggedover");
                counter = 0;
            }
        }

        // only react to actual files being dragged
        function dragEnterHandler(e) {
            e.preventDefault();
            if (!hasFiles(e)) {
                return;
            }
            ++counter && overlay.classList.add("draggedover");
        }

        function dragLeaveHandler(e) {
            1 > --counter && overlay.classList.remove("draggedover");
        }

        function dragOverHandler(e) {
            if (hasFiles(e)) {
                e.preventDefault();
            }
        }

        // event delegation to caputre delete events
        // fron the waste buckets in the file preview cards
        gallery.onclick = ({ target }) => {
            if (target.classList.contains("delete")) {
                const ou = target.dataset.target;
                document.getElementById(ou).remove(ou);
                gallery.children.length === 1 && empty.classList.remove("hidden");
                delete FILES[ou];
            } else if(target.classList.contains('main-image')) {
                const ou = target.dataset.target;
                let mainImageIndex = index(document.getElementById(ou));
                document.getElementById(ou).getElementsByClassName('main-image')[0].value = mainImageIndex;
            }
        };

        // print all selected files
        // document.getElementById("submit").onclick = () => {
        //   alert(`Submitted Files:\n${JSON.stringify(FILES)}`);
        //   console.log(FILES);
        // };

        // clear entire selection
        // document.getElementById("cancel").onclick = () => {
        //   while (gallery.children.length > 0) {
        //     gallery.lastChild.remove();
        //   }
        //   FILES = {};
        //   empty.classList.remove("hidden");
        //   gallery.append(empty);
        // };


        function index(element){
            var sib = element.parentNode.childNodes;
            var n = 0;
            for (var i=0; i<sib.length; i++) {
                if (sib[i]==element) return n;
                if (sib[i].nodeType==1) n++;
            }
            return -1;
        }
    </script>
    <script type="text/javascript" src="{{ asset(('js/add_products.js')) }}"></script>
    <script src="https://cdn.tiny.cloud/1/nkcpprlcvgg1ldeqgx3dn4mhqmutceszm1yqqf73vsyqhoq9/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: '#description'
        });
    </script>
@endpush

