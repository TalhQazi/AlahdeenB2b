<div class="flex items-center justify-center fixed left-0 bottom-0 w-full h-full bg-gray-800 bg-opacity-75 z-10 hidden modal" id="delete-modal">
  <div class="bg-white rounded-lg w-1/2">
    <div class="flex flex-col items-start p-4">
      <div class="flex items-center w-full border-b-1">
        <div class="text-gray-900 font-medium text-lg" id="modal-title"></div>
		<svg class="close-modal ml-auto fill-current text-gray-700 w-6 h-6 cursor-pointer" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 18">
			<path d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z"/>
     	</svg>
      </div>
      <hr>
      <div class="confirmation_msg_div">{{ __('Are you sure you want to delete?') }}</div>
      <hr>
      <div class="ml-auto">
        <form method="post">
            @csrf
            @method('DELETE')
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Confirm
            </button>
            <button type="button" class="bg-transparent hover:bg-gray-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded close-modal">
            Cancel
            </button>
        </form>
      </div>
    </div>
  </div>
</div>
