<div class="flex items-center justify-center fixed left-0 bottom-0 w-full h-full bg-gray-800 bg-opacity-75 z-10 hidden" id="product-image-modal">
    <div class="bg-white rounded-lg w-1/2 sm:w-3/4">
      <div class="flex flex-col items-start p-4">
        <div class="flex items-center w-full border-b-1">
          <div class="text-gray-900 font-medium text-lg" id="modal-title">Change Image</div>
          <svg class="close-modal ml-auto fill-current text-gray-700 w-6 h-6 cursor-pointer" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 18">
              <path d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z"/>
           </svg>
        </div>
        <hr>
        <div class="mt-6">Change Image</div>
        <div class="w-full mt-6">
            <form id="product-image-form" enctype="multipart/form-data" method="POST" action="{{route('product.update',['product' => $product->id])}}"  class="w-full">
                @csrf
                {{ method_field('PUT') }}
                <input type="hidden" name="orignal_image_id" id="orignal_image_id">
                <input accept="image/x-png,image/jpeg" class='my-5 appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500' name="product_image" id='grid-text-1' type='file'>
                <input type="checkbox" name="is_main" id="is_main"> Set as main image
            </form>
        </div>
        <hr>

        <div class="ml-auto my-5">

            <button type="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" id="confirm-btn">
                Confirm
            </button>
            <button type="button" class="bg-transparent hover:bg-gray-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded close-modal">
                Cancel
            </button>
        </div>

      </div>
    </div>
  </div>
