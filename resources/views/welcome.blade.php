<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="{{asset(('css/admin_dashboard.css'))}}">
        <title>Emandii</title>

    </head>
    <body class="antialiased">
      <div class="flex h-screen">
        <div class="m-auto">
          @if (Route::has('login'))

              @auth
                  <a class="border border-teal-500 bg-teal-500 text-white rounded-md px-4 py-2 m-2 transition duration-500 ease select-none hover:bg-teal-600 focus:outline-none focus:shadow-outline text-2xl" href="{{ url('/dashboard') }}">Dashboard</a>
              @else
                  <a class="border border-indigo-500 bg-indigo-500 text-white rounded-md px-4 py-2 m-2 transition duration-500 ease select-none hover:bg-indigo-600 focus:outline-none focus:shadow-outline text-2xl" href="{{ route('login') }}">Login</a>

                  @if (Route::has('register'))
                      <a class="border border-green-500 bg-green-500 text-white rounded-md px-4 py-2 m-2 transition duration-500 ease select-none hover:bg-green-600 focus:outline-none focus:shadow-outline text-2xl" href="{{ route('register') }}">Register</a>
                  @endif
              @endif

           @endif
        </div>
      </div>
    </body>
</html>
