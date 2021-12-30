<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name') }}</title>
        <link rel="shortcut icon" href="./img/fav.png" type="image/x-icon">
        <link rel="stylesheet" href="https://kit-pro.fontawesome.com/releases/v5.12.1/css/pro.min.css">
        <link rel="stylesheet" type="text/css" href="{{asset(('css/admin_dashboard.css'))}}">
        @stack('styles')

    </head>
    <body class="bg-gray-100">
        <div class="overlay h-screen w-full left-0 top-0 bg-black bg-opacity-50 z-10 hidden"></div>

        <!-- strat wrapper -->
        <div class="h-screen flex flex-row flex-wrap">

            <!-- strat content -->
            <div class="bg-gray-100 flex-1 p-6 md:mt-16">

                @yield('page')

            </div>
            <!-- end content -->
        </div>
        <!-- end wrapper -->
        <script type="text/javascript">
            // global variables
            const base_url = '{{ config('app.url ') }}';
            const LANG = "{{ app()->getLocale() }}";
        </script>
        <script>

        <script type="text/javascript" src="{{ asset(('/js/app.js')) }}"></script>

        </script>
        @stack('scripts')

    </body>
</html>
