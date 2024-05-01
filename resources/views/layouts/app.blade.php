<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Utopia') }}</title>

        <link href="{{ asset('css/styles.css') }}" rel="stylesheet" />

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <link rel="stylesheet" href="css/style.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>

    <body class="h-screen font-sans antialiased">

        <div class="flex flex-row bg-gray-100 h-screen">

            @include('layouts.navigation')

            <!-- Page Content -->
            <main class="bg-[#18B7BE] ml-24 w-screen p-4">

                <div class="m-8">
                    @include('messageFlash')
                </div>

                {{ $slot }}

            </main>


        </div>
    </body>
</html>
