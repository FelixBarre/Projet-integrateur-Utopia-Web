<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Utopia') }}</title>

        <link href="{{ asset('css/style.css') }}" rel="stylesheet" />

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />



        <script src="{{asset('js/script.js')}}" defer></script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

    </head>

    <body class="h-screen bg-[#18B7BE] font-sans antialiased ">

        <div class="flex flex-rowbg-gray-100">

            @include('layouts.navigation')

            <!-- Page Content -->
            <main class="w-full p-4 ml-24 ">

                <div class="m-8">
                    @include('messageFlash')
                </div>

                {{ $slot }}

            </main>


        </div>
    </body>
</html>
