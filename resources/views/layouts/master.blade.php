<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name') }}</title>
        <link rel="shortcut icon" href="./img/fav.png" type="image/x-icon">
        <link rel="stylesheet" href="https://kit-pro.fontawesome.com/releases/v5.12.1/css/pro.min.css">
        <link rel="stylesheet" type="text/css" href="{{asset(('css/admin_dashboard.css'))}}">
        <link rel="stylesheet" type="text/css" href="{{asset(('css/common/message_notifications.css'))}}">
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css">
        <style>
            .quotation_modal_main
            {
                overflow: scroll;
            }
            .quotation_modal_white
            {
                padding-top:  300px;
                padding-bottom: 100px;
            }
            .profile_image
            {
                width: 150px;
            }
        </style>
        <link rel="stylesheet" type="text/css" href="{{ asset(('css/common/message_notifications.css')) }}">

        
        @stack('styles')

        <link rel="stylesheet" type="text/css" href="{{ asset(('common')) }}/css/custom.css">
        @yield('css')

    </head>
    <body class="bg-gray-100">
        <div class="overlay h-screen w-full left-0 top-0 bg-black bg-opacity-50 z-10 hidden"></div>

        @include('components.top-nav-bar')

        <!-- strat wrapper -->
        <div class="h-screen flex flex-row flex-wrap">

            @include('components.side-bar')

            <!-- strat content -->
            <div class="bg-gray-100 flex-1 p-6 md:mt-16 large-table">
                @if (Session::has('message'))
                    <div class="alert {{ Session::get('alert-class', 'alert-info') }} alert-close mb-5  w-full xl:w-2/4">
                        <button class="alert-btn-close">
                            <i class="fad fa-times"></i>
                        </button>
                        <span>{{Session::get('message')}}</span>
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-error alert-close mb-5 grid grid-rows-2">
                        <div class="col-span-12">
                            <button class="alert-btn-close float-right">
                                <i class="fad fa-times"></i>
                            </button>

                        </div>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="alert alert-error alert-close mb-5  w-full xl:w-2/4 mr-3 hidden">
                  <button class="error-btn-close">
                      <i class="far fa-times"></i>
                  </button>
                  <span id="error_message"></span>
              </div>
              <div class="alert alert-success alert-close mb-5  w-full xl:w-2/4 mr-3 hidden">
                  <button class="error-btn-close">
                      <i class="far fa-times"></i>
                  </button>
                  <span id="success_message"></span>
              </div>

                @yield('page')

                @yield('modals')

            </div>
            <!-- end content -->
        </div>
        <!-- end wrapper -->
        <script>
            var user_role = '{{Auth::user()->getRoleNames()}}';
            var base_url = '{{ config('app.url') }}';
            var user_id = '{{Auth::user()->id}}';
            var user_type = '{{Session::get('user_type')}}';
        </script>
        <script type="text/javascript" src="{{ asset(('/js/app.js')) }}"></script>
        <script type="text/javascript" src="{{ asset(('js/admin_dashboard.js')) }}"></script>
        <script type="text/javascript" src="{{ asset(('js/components/chat_notifications.js')) }}"></script>
        
        @stack('scripts')
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <!-- Dropify -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"></script>
        <script src="{{ asset(('common')) }}/js/custom.js"></script>
        <script>
            
            var data = <?php echo json_encode(session()->getOldInput()) ?>;
            mapDataToFields(data);

            startSelect2();
            startDropify();
        </script>
        
        @yield('js')
    </body>
</html>
