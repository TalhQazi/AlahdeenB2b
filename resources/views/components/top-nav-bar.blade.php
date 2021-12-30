<!-- start navbar -->
<div
    class="md:fixed md:w-full md:top-0 md:z-20 flex flex-row flex-wrap items-center bg-green-300 p-6 border-b border-gray-300">

    <!-- logo -->
    <div class="flex-none w-56 flex flex-row items-center">
        <img src="{{ asset('img/logo.png') }}" class="w-10 flex-none">
        <strong class="capitalize ml-1 flex-1">Emandii Dashboard</strong>

        <button id="sliderBtn" class="flex-none text-right text-gray-900 hidden md:block">
            <i class="fad fa-list-ul"></i>
        </button>
    </div>
    <!-- end logo -->

    <!-- navbar content toggle -->
    <button id="navbarToggle" class="hidden md:block md:fixed right-0 mr-6">
        <i class="fad fa-chevron-double-down"></i>
    </button>
    <!-- end navbar content toggle -->

    <!-- navbar content -->
    <div id="navbar"
        class="animated md:hidden md:fixed md:top-0 md:w-full md:left-0 md:mt-16 md:border-t md:border-b md:border-gray-200 md:p-10 md:bg-white flex-1 pl-3 flex flex-row flex-wrap justify-between items-center md:flex-col md:items-center">
        <!-- left -->
        <div
            class="text-gray-600 md:w-full md:flex md:flex-row md:justify-evenly md:pb-10 md:mb-10 md:border-b md:border-gray-200">
            {{-- <a class="mr-2 transition duration-500 ease-in-out hover:text-gray-900" href="#" title="email"><i class="fad fa-envelope-open-text"></i></a>
        <a class="mr-2 transition duration-500 ease-in-out hover:text-gray-900" href="#" title="email"><i class="fad fa-comments-alt"></i></a>
        <a class="mr-2 transition duration-500 ease-in-out hover:text-gray-900" href="#" title="email"><i class="fad fa-check-circle"></i></a>
        <a class="mr-2 transition duration-500 ease-in-out hover:text-gray-900" href="#" title="email"><i class="fad fa-calendar-exclamation"></i></a> --}}
        </div>
        <!-- end left -->

        <!-- right -->
        <div class="flex flex-row-reverse items-center">

            <!-- user -->
            <div class="dropdown relative md:static">

                <button class="menu-btn focus:outline-none focus:shadow-outline flex flex-wrap items-center">
                    <div class="w-8 h-8 overflow-hidden rounded-full">
                        @if (isset(Auth::user()->profile_photo_path))
                            <img class="w-full h-full object-cover" src="{{ asset(Auth::user()->profile_photo_path) }}"
                                alt="{{ Auth::user()->name }}">
                        @else
                            <img class="w-full h-full object-cover" src="{{ asset('common/images/user.svg') }}">
                        @endif
                    </div>

                    <div class="ml-2 capitalize flex ">
                        <h1 class="text-sm text-gray-800 font-semibold m-0 p-0 leading-none">{{ Auth::user()->name }}
                        </h1>
                        <i class="fad fa-chevron-down ml-2 text-xs leading-none"></i>
                    </div>
                </button>

                <button class="hidden fixed top-0 left-0 z-10 w-full h-full menu-overflow"></button>

                <div
                    class="text-gray-500 menu hidden md:mt-10 md:w-full rounded bg-white shadow-md absolute z-20 right-0 w-64 mt-5 py-2 animated faster">

                    <!-- item -->
                    <a class="px-4 py-2 block capitalize font-medium text-sm tracking-wide bg-white hover:bg-gray-200 hover:text-gray-900 transition-all duration-300 ease-in-out"
                        href="{{ route('user.profile.edit') }}">
                        <i class="fad fa-user-edit text-xs mr-1"></i>
                        {{ __('Edit my profile') }}
                    </a>
                    <!-- end item -->
                    @hasanyrole('business|corporate|warehouse-owner')
                    <a class="px-4 py-2 block capitalize font-medium text-sm tracking-wide bg-white hover:bg-gray-200 hover:text-gray-900 transition-all duration-300 ease-in-out"
                        href="{{ route('profile.business.home') }}">
                        <i class="fa fa-sticky-note text-xs mr-1"></i>
                        {{ __('Business Profile') }}
                    </a>
                    @endhasanyrole
                    @hasanyrole('corporate|business|individual')
                    <a class="px-4 py-2 block capitalize font-medium text-sm tracking-wide bg-white hover:bg-gray-200 hover:text-gray-900 transition-all duration-300 ease-in-out"
                        href="{{ route('product.interest.home') }}">
                        <i class="fa fa-cogs text-xs mr-1"></i>
                        {{ __('Products of Interest') }}
                    </a>
                    <a class="px-4 py-2 block capitalize font-medium text-sm tracking-wide bg-white hover:bg-gray-200 hover:text-gray-900 transition-all duration-300 ease-in-out"
                        href="{{ route('product.services.home') }}">
                        <i class="fa fa-cogs text-xs mr-1"></i>
                        {{ __('My Products & Services') }}
                    </a>
                    <a class="px-4 py-2 block capitalize font-medium text-sm tracking-wide bg-white hover:bg-gray-200 hover:text-gray-900 transition-all duration-300 ease-in-out"
                        href="{{ route('profile.business.create.director.profile') }}">
                        <i class="fad fa-user-edit text-xs mr-1"></i>
                        {{ __('Director Profile') }}
                    </a>
                    @endhasanyrole
                    <a class="px-4 py-2 block capitalize font-medium text-sm tracking-wide bg-white hover:bg-gray-200 hover:text-gray-900 transition-all duration-300 ease-in-out"
                        href="{{ route('profile.business.company.page') }}">
                        <i class="fad fa-user-edit text-xs mr-1"></i>
                        {{ __('Company Profile Settings') }}
                    </a>

                    <hr>

                    <!-- item -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a class="px-4 py-2 block capitalize font-medium text-sm tracking-wide bg-white hover:bg-gray-200 hover:text-gray-900 transition-all duration-300 ease-in-out"
                            href="{{ route('logout') }}" onclick="event.preventDefault();
            this.closest('form').submit();">
                            <i class="fad fa-user-times text-xs mr-1"></i>
                            log out
                        </a>
                    </form>
                    <!-- end item -->

                </div>
            </div>
            <!-- end user -->

            <!-- notifcation -->
            <div class="dropdown relative mr-5 md:static">

                {{-- <button class="text-gray-500 menu-btn p-0 m-0 hover:text-gray-900 focus:text-gray-900 focus:outline-none transition-all ease-in-out duration-300">
            <i class="fad fa-bells"></i>
          </button> --}}

                <button class="hidden fixed top-0 left-0 z-10 w-full h-full menu-overflow"></button>

                <div
                    class="menu hidden rounded bg-white md:right-0 md:w-full shadow-md absolute z-20 right-0 w-84 mt-5 py-2 animated faster">
                    <!-- top -->
                    <div class="px-4 py-2 flex flex-row justify-between items-center capitalize font-semibold text-sm">
                        <h1>notifications</h1>
                        <div class="bg-teal-100 border border-teal-200 text-teal-500 text-xs rounded px-1">
                            <strong>5</strong>
                        </div>
                    </div>
                    <hr>
                    <!-- end top -->

                    <!-- body -->

                    <!-- item -->
                    <a class="flex flex-row items-center justify-start px-4 py-4 block capitalize font-medium text-sm tracking-wide bg-white hover:bg-gray-200 transition-all duration-300 ease-in-out"
                        href="#">

                        <div class="px-3 py-2 rounded mr-3 bg-gray-100 border border-gray-300">
                            <i class="fad fa-birthday-cake text-sm"></i>
                        </div>

                        <div class="flex-1 flex flex-rowbg-green-100">
                            <div class="flex-1">
                                <h1 class="text-sm font-semibold">poll..</h1>
                                <p class="text-xs text-gray-500">text here also</p>
                            </div>
                            <div class="text-right text-xs text-gray-500">
                                <p>4 min ago</p>
                            </div>
                        </div>

                    </a>
                    <hr>
                    <!-- end item -->

                    <!-- item -->
                    <a class="flex flex-row items-center justify-start px-4 py-4 block capitalize font-medium text-sm tracking-wide bg-white hover:bg-gray-200 transition-all duration-300 ease-in-out"
                        href="#">

                        <div class="px-3 py-2 rounded mr-3 bg-gray-100 border border-gray-300">
                            <i class="fad fa-user-circle text-sm"></i>
                        </div>

                        <div class="flex-1 flex flex-rowbg-green-100">
                            <div class="flex-1">
                                <h1 class="text-sm font-semibold">mohamed..</h1>
                                <p class="text-xs text-gray-500">text here also</p>
                            </div>
                            <div class="text-right text-xs text-gray-500">
                                <p>78 min ago</p>
                            </div>
                        </div>

                    </a>
                    <hr>
                    <!-- end item -->

                    <!-- item -->
                    <a class="flex flex-row items-center justify-start px-4 py-4 block capitalize font-medium text-sm tracking-wide bg-white hover:bg-gray-200 transition-all duration-300 ease-in-out"
                        href="#">

                        <div class="px-3 py-2 rounded mr-3 bg-gray-100 border border-gray-300">
                            <i class="fad fa-images text-sm"></i>
                        </div>

                        <div class="flex-1 flex flex-rowbg-green-100">
                            <div class="flex-1">
                                <h1 class="text-sm font-semibold">new imag..</h1>
                                <p class="text-xs text-gray-500">text here also</p>
                            </div>
                            <div class="text-right text-xs text-gray-500">
                                <p>65 min ago</p>
                            </div>
                        </div>

                    </a>
                    <hr>
                    <!-- end item -->

                    <!-- item -->
                    <a class="flex flex-row items-center justify-start px-4 py-4 block capitalize font-medium text-sm tracking-wide bg-white hover:bg-gray-200 transition-all duration-300 ease-in-out"
                        href="#">

                        <div class="px-3 py-2 rounded mr-3 bg-gray-100 border border-gray-300">
                            <i class="fad fa-alarm-exclamation text-sm"></i>
                        </div>

                        <div class="flex-1 flex flex-rowbg-green-100">
                            <div class="flex-1">
                                <h1 class="text-sm font-semibold">time is up..</h1>
                                <p class="text-xs text-gray-500">text here also</p>
                            </div>
                            <div class="text-right text-xs text-gray-500">
                                <p>1 min ago</p>
                            </div>
                        </div>

                    </a>
                    <!-- end item -->


                    <!-- end body -->

                    <!-- bottom -->
                    <hr>
                    <div class="px-4 py-2 mt-2">
                        <a href="#"
                            class="border border-gray-300 block text-center text-xs uppercase rounded p-1 hover:text-teal-500 transition-all ease-in-out duration-500">
                            view all
                        </a>
                    </div>
                    <!-- end bottom -->
                </div>
            </div>
            <!-- end notifcation -->

            <!-- messages -->
            <div class="dropdown relative mr-5 md:static">

                <button
                    class="text-gray-500 menu-btn p-0 m-0 hover:text-gray-900 focus:text-gray-900 focus:outline-none transition-all ease-in-out duration-300">
                    <i class="fas fa-comments text-white"></i>
                    <span class="cntmsg Hd_pa">{{ $message_notifications['unread_count'] }}</span>
                </button>

                <button class="hidden fixed top-0 left-0 z-10 w-full h-full menu-overflow"></button>
                @if (!empty($message_notifications['message_info']))
                    <div class="menu hidden md:w-full md:right-0 rounded bg-white shadow-md absolute z-20 right-0 w-84 mt-5 py-2 animated faster h-48 max-h-96 overflow-y-scroll"
                        id="message_notifications">
                        <!-- body -->

                        <!-- item -->

                        @foreach ($message_notifications['message_info'] as $conversation_id => $message_info)

                            <a id="conversation_notification_{{ $conversation_id }}"
                                class="flex flex-row items-center justify-start px-4 py-4 block capitalize font-medium text-sm tracking-wide bg-white hover:bg-gray-200 transition-all duration-300 ease-in-out"
                                href="{{ route('chat.messages', ['conversation_id' => $conversation_id]) }}">

                                <div
                                    class="w-10 h-10 rounded-full overflow-hidden mr-3 bg-gray-100 border border-gray-300">
                                    <img class="w-full h-full object-cover"
                                        src="{{ $message_info['participant']['profile_photo_path'] }}">
                                </div>

                                <div class="flex-1 flex flex-rowbg-green-100">
                                    <div class="flex-1">
                                        {{-- <h1 class="text-sm font-semibold">{!! $message_info['participant']->business->company_name !!}</h1> --}}
                                        <h3 class="text-sm">{{ $message_info['participant']['name'] }}</h3>
                                        <p class="text-xs text-gray-500">{!! $message_info['last_message'] !!} </p>
                                    </div>
                                </div>

                            </a>
                            <hr>
                        @endforeach
                        <div class="px-4 py-2 mt-2">
                            <a href="{{ route('chat.messages') }}"
                                class="border border-gray-300 block text-center text-xs uppercase rounded p-1 hover:text-teal-500 transition-all ease-in-out duration-500">
                                {{ __('View All') }}
                            </a>
                        </div>
                        <!-- end bottom -->
                    </div>
                @endif

            </div>
            <!-- end messages -->
            @if (Session::has('user_type') && Session::get('user_type') != "driver")
            <div class="mr-2 capitalize flex">
              <a href="{{ Session::get('user_type') == "buyer" ? route('become.a.seller') : route('become.a.buyer') }}">
                <h1 class="text-sm text-gray-800 font-semibold m-0 p-0 leading-none">
                  {{ Session::get('user_type') == "buyer" ? __('Switch to Seller') : __('Switch to Buyer')  }}
                </h1>
              </a>
            </div>
            @endif
        </div>
        <!-- end right -->
    </div>
    <!-- end navbar content -->

</div>
<!-- end navbar -->
