@extends('layouts.master')

@push('styles')
    <link rel="stylesheet" href="{{ asset(('css/pages/chat.css')) }}">
@endpush

@section('page')
    <input type="hidden" name="page" id="page" value="1">
    <input type="hidden" name="total" id="total" value="{{ $total_participants }}">
    <input type="hidden" name="user_id" id="user_id" value="{{ Auth::user()->id }}">
    <!-- This is an example component -->
    <div class="container mx-auto shadow-lg rounded-lg">
        <!-- headaer -->

            <?php
                $firstKey = array_key_first($participants);
                $business = !empty($participants[$firstKey]->business) ? $participants[$firstKey]->business : '';
                $businessUrl = !empty($business) && !empty($business->url) ? $business->url : '';
            ?>
        <!-- end header -->
        <!-- Chatting -->
        <div class="flex flex-row justify-between bg-white">
            <!-- chat list -->
            <div class="flex flex-col w-2/5 border-r-2 overflow-y-auto">
                <!-- search compt -->
                <div class="border-b-2 py-4 px-2 mt-5 message_header">
                    <input type="text" id="chat_search" placeholder="{{__('Search by company name, contact person, mobile, city')}}"
                        class="py-2 px-2 border-2 border-gray-200 rounded-2xl w-full" />
                    {{-- <span class="flex justify-end mt-3"><i class="fas fa-filter"></i></span> --}}
                </div>
                <!-- end search compt -->
                <!-- user list -->
                <div id="participants_list">
                    <div class="scrollable">
                        <?php $count = 0; ?>
                        @foreach ($participants as $key => $conversation)
                            <?php
                                $conversationId = array_key_first($conversation);
                                $borderClass = $count == 0 ? 'active' : '';
                                $business = !empty($conversation[$conversationId]['participant']->business) ? $conversation[$conversationId]['participant']->business : '';
                            ?>
                            <div class="flex flex-row py-4 px-2 items-center border-b-2 border-l-4 {{ $borderClass }} participants"
                                data-conversation-id="{{ $conversationId }}"
                                data-buyer-id="{{$conversation[$conversationId]['participant']->id}}"
                                >
                                <div class="w-1/4">
                                    @if (!empty($conversation[$conversationId]['participant']->company_logo))
                                        <img src="{{url($conversation[$conversationId]['participant']->company_logo)}}" class="object-cover h-12 w-12 rounded-full" alt="" />
                                    @else
                                        <img class="w-12 h-12 rounded-full object-cover" src="{{asset('img/user.svg')}}" >
                                    @endif
                                </div>
                                <div class="w-full">
                                    <div class="text-lg font-semibold">{{ !empty($business->company_name) ? $business->company_name : 'NA' }}</div>
                                    <div class="font-semibold">{{ $conversation[$conversationId]['participant']->name }}</div>
                                    <span class="text-gray-500 last_message">{!! $conversation[$conversationId]['last_message']->body !!}</span>
                                    <span class="text-gray-500 unread_count">{{ '( ' . $conversation[$conversationId]['unread_count'] . ' )' }}</span>
                                </div>
                            </div>
                            <?php $count++; ?>
                        @endforeach
                    </div>
                </div>

                <!-- end user list -->
            </div>
            <!-- end chat list -->
            <!-- message -->
            <div class="w-full flex flex-col justify-between" id="messages_list">
                <div class="flex flex-col mt-5 border-b-2 message_header">
                    <?php
                        $firstKey = array_key_first($participants[0]);
                        // ddd($participants[0][$firstKey]);
                        $business = !empty($participants[0][$firstKey]['participant']->business) ? $participants[0][$firstKey]['participant']->business : '';
                        $businessUrl = !empty($business) && !empty($business->alternate_website) ? $business->alternate_website : '';
                        $businessPhone = !empty($business) && !empty($business->phone_number) ? $business->phone_number : $participants[0][$firstKey]['participant']->phone;
                    ?>
                    <div class="font-semibold text-2xl flex justify-center" id="company_name">{{ !empty($business) ? $business->company_name : __('Messages') }}</div>
                    <a href="{{$businessUrl}}" target="_blank" class="font-semibold flex justify-center mt-2" id="website_url">{{ !empty($businessUrl) ? $businessUrl : '' }}</a>
                    <div class="flex justify-between px-10">
                        <span id="phone_number"><i class="far fa-phone"></i>&nbsp;&nbsp;{{$businessPhone}}</span>
                        <span id="contact_person" class="ml-5"><i class="far fa-user"></i>&nbsp;&nbsp;{{$participants[0][$firstKey]['participant']->name}}</span>
                        @if (Session::get('user_type') == "seller")
                        <span id="reminder"><i class="far fa-clock"></i>{{__('Set Reminders')}}</span>
                        <span id="notes"><i class="far fa-sticky-note"></i>{{__('Add Notes')}}</span>
                        <span id="labels"><i class="far fa-tags"></i>{{__('Add Lable')}}</span>
                        @endif
                    </div>

                </div>
                <div class="flex px-5 flex-col mt-5 messages">
                    @foreach ($messages as $key => $message)
                        <?php
                            if($message->sender->id != Auth::user()->id) {
                                if($message->type == "reminder" || $message->type == "notes") {
                                    continue;
                                } else {
                                    $justifyClass = 'justify-start';
                                }
                            } else {
                                if($message->type == "reminder" || $message->type == "notes") {
                                    $justifyClass = 'justify-center';
                                } else {
                                    $justifyClass = 'justify-end';
                                }
                            }

                        ?>

                        <div class="flex {{ $justifyClass }} mb-4">
                            <div class="mr-2 py-3 px-4 bg-blue-400 rounded-bl-3xl rounded-tl-3xl rounded-tr-xl text-white max-w-md break-words">
                                @if ($message->type == "document")
                                    <div class="bg-white p-2"><a href="{{asset(str_replace('/storage/', '', $message->body))}}" target="_blank"><img class="attachment" src="{{asset('/img/docs.png')}}"/></a></div>
                                @elseif ($message->type == "image")
                                    <div class="bg-white p-2"><a href="{{url(Storage::url($message->body))}}" target="_blank"><img class="attachment" src="{{Storage::url($message->body)}}"/></a></div>
                                @elseif ($message->type == "reminder")
                                    <div class="p-2"><span><i class="far fa-clock"></i> {{ '| ' . __('Call Reminder Set For: ') . $message->body}}</span></div>
                                @elseif ($message->type == "notes")
                                    <div class="p-2"><span><i class="far fa-sticky-note"></i>{{' | '.$message->body}}</span></div>
                                @elseif($message->type == "quotation")
                                    <div class="bg-white p-2"><a href="{{ url(Storage::url($message->body))}}" target="_blank"><img class="attachment" src="{{asset('/img/docs.png')}}"/></a></div>
                                @else
                                    {!! $message->body !!}
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="w-full py-3 px-3 border-t border-gray-300">

                    <div class="w-full bg-gray-100 flex flex-row items-center">
                        {{-- <div class="w-2/3 flex justify-start ml-2 items-center">
                            <i class="far fa-bolt"></i>

                            <div class="flex justify-between overflow-hidden">
                                <a class="prev ml-2">❮</a>
                                <span class="w-32 ml-2 rounded-full text-white badge bg-teal-400 text-sm truncate quick_replies">
                                    {{__('Will get back to you')}}
                                </span>
                                <span class="w-32 ml-2 rounded-full text-white badge bg-teal-400 text-sm truncate quick_replies">
                                    {{__('I need some samples before placing the order')}}
                                </span>
                                <span class="w-32 ml-2 rounded-full text-white badge bg-teal-400 text-sm truncate quick_replies">
                                    {{__('Price is too high, my budget price:')}}
                                </span>
                                <span class="w-32 ml-2 rounded-full text-white badge bg-teal-400 text-sm truncate quick_replies">
                                    {{__('Do you deliver in:')}}
                                </span>
                                <span class="w-32 ml-2 rounded-full text-white badge bg-teal-400 text-sm truncate quick_replies">
                                    {{__('How soon can you deliver?')}}
                                </span>
                                <span class="w-32 ml-2 rounded-full text-white badge bg-teal-400 text-sm truncate quick_replies">
                                    {{__('Is the product currently in stock?')}}
                                </span>

                                <a class="next ml-2">❯</a>

                            </div>
                        </div> --}}
                    </div>
                    <div class="w-full items-center">
                        <div class="flex flex-row justify-between items-center">
                            <button class="outline-none focus:outline-none ml-2" id="attachment_btn" title="{{__('Send Attachment')}}">
                                <i class="far fa-paperclip"></i>
                            </button>
                            @if (Session::get('user_type') == "seller")
                            <button class="outline-none focus:outline-none ml-2" id="quote_btn" title="{{__('Send Quotation')}}">
                                <i class="far fa-comment"></i>
                            </button>
                            <button class="outline-none focus:outline-none ml-2" id="attach_catalog" type="button" title="{{__('Send Catalog')}}">
                                <i class="far fa-album-collection"></i>
                            </button>
                            @else
                            <button class="outline-none focus:outline-none ml-2" id="request_quote_btn" title="{{__('Request Quotation')}}">
                                <i class="far fa-quote-left"></i>
                            </button>
                            @endif

                            <input aria-placeholder="{{ __('Type your message here') }}" placeholder="{{ __('Type your message here') }}"
                                class="py-2 mx-3 pl-5 block w-full rounded-full bg-gray-200 outline-none focus:text-gray-700"
                                type="text" name="message" id="message">

                            <button class="outline-none focus:outline-none" id="send_msg_btn" type="button">
                                <i class="far fa-paper-plane"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end message -->
        </div>
    </div>
@endsection

@section('modals')
    @include('components.chat.attachment-modal')
    @include('components.chat.reminder-modal')
    @include('components.chat.notes-modal')
    @include('components.chat.label-modal')
    @include('components.chat.catalog-modal')
    @include('components.chat.quotation-modal')
    @include('components.chat.request-quotation-modal')
@endsection

@push('scripts')
    <script>
        var storage_url = '{!! storage_path() !!}';
    </script>
    <script src="{{ asset(('/js/pages/conversation.js')) }}"></script>
    <script src="{{ asset(('/js/components/quotation.js')) }}"></script>
@endpush
