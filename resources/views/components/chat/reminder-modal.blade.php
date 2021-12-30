<div class="flex items-center justify-center fixed left-0 bottom-0 w-full h-full bg-gray-800 bg-opacity-75 z-10 hidden modal"
    id="reminder-modal">
    <div class="bg-white rounded-lg w-1/2 sm:w-3/4">
        <div class="flex flex-col items-start p-4">
            <div class="flex items-center w-full border-b-1">
                <div class="text-gray-900 font-medium text-lg" id="modal-title">{{ __('Set Reminder For Call') }}
                </div>
                <svg class="close-modal ml-auto fill-current text-gray-700 w-6 h-6 cursor-pointer"
                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 18">
                    <path
                        d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z" />
                </svg>
            </div>
            <hr>

            <div class="w-full mt-6">
                <form id="reminder_form" method="POST" class="w-full">
                    @csrf
                    <div class='flex flex-wrap -mx-3 mb-6'>
                        <div class='w-full md:w-full px-3 mb-6'>
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                                for="reminder">
                                {{ __('Reminder date and time') }}
                            </label>
                            <input
                                class="appearance-none w-3/4 bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none focus:border-gray-500"
                                name="reminder_date_time" id="reminder_date_time" type="text" readonly
                                autocomplete="off">
                                <button type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded">
                                {{ __('Set Reminder') }} <i class="fa fa-clock"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <hr>
            <div id="set_reminders" class="w-full">
                @if (!empty($chat_reminders))
                    @foreach ($chat_reminders as $reminder)
                        <?php
                            $isInPastClass = strtotime(now()) > strtotime($reminder->reminder_date_time) ? 'text-red-500': 'text-green-500';
                        ?>
                        <div class="w-1/2 md:w-full px-3 mb-6" id="reminder_div_{{$reminder->id}}">
                            <label class="uppercase tracking-wide {{$isInPastClass}} text-xs font-bold mb-2" for="reminders">
                                {{$reminder->reminder_date_time}}
                            </label>
                            <span class="text-gray-700 text-xs font-bold"> {{__('Mark as done')}} </span>
                            <input class="bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500 reminder" name="reminder[{{$reminder->id}}]" id="reminder_{{$reminder->id}}" type="checkbox">
                        </div>
                    @endforeach
                @endif
            </div>

        </div>
    </div>
</div>
