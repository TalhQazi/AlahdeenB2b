<div class="flex items-center justify-center fixed left-0 bottom-0 w-full h-full bg-gray-800 bg-opacity-75 z-10 hidden" id="add-product-documents-modal">
    <div class="bg-white rounded-lg w-1/2 sm:w-3/4">
      <div class="flex flex-col items-start p-4">
        <div class="flex items-center w-full border-b-1">
          <div class="text-gray-900 font-medium text-lg" id="modal-title">Product</div>
          <svg class="close-modal ml-auto fill-current text-gray-700 w-6 h-6 cursor-pointer" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 18">
              <path d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z"/>
           </svg>
        </div>
        <hr>
        <div class="mt-6">Add Documents</div>
        <div class="w-full mt-6">
            <form id="add-product-doc-form" enctype="multipart/form-data" method="POST" action="{{route('product.document-store',['product' => $product->id])}}"  class="w-full">
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
                          <p class="text-lg text-blue-700">Drop files to upload</p>
                        </div>

                        <!-- scroll area -->
                        <section class="h-full overflow-auto p-8 w-full h-full flex flex-col">
                          <header class="border-dashed border-2 border-gray-400 py-12 flex flex-col justify-center items-center">
                            <p class="mb-3 font-semibold text-gray-900 flex flex-wrap justify-center">
                              <span>Drag and drop your</span>&nbsp;<span>files anywhere or</span>
                            </p>
                            <input id="hidden-input" name="product_documents[]" type="file" multiple class="hidden">
                            <button type="button" id="button" class="mt-2 rounded-sm px-3 py-1 bg-gray-200 hover:bg-gray-300 focus:shadow-outline focus:outline-none">
                              Upload a file
                            </button>
                          </header>

                          <h1 class="pt-8 pb-3 font-semibold sm:text-lg text-gray-900">
                            To Upload
                          </h1>

                          <ul id="gallery" class="flex flex-1 flex-wrap -m-1">
                            <li id="empty" class="h-full w-full text-center flex flex-col items-center justify-center items-center">
                              <img class="mx-auto w-32" src="https://user-images.githubusercontent.com/507615/54591670-ac0a0180-4a65-11e9-846c-e55ffce0fe7b.png" alt="no data" />
                              <span class="text-small text-gray-500">No files selected</span>
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
                            </div>
                        </section>
                        </article>
                    </li>
                </template>
            </form>
        </div>
        <hr>

        <div class="ml-auto my-5">

            <button type="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" id="confirm-add-btn">
                Confirm
            </button>
            <button type="button" class="bg-transparent hover:bg-gray-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded close-modal">
                Cancel
            </button>
        </div>

      </div>
    </div>
  </div>
