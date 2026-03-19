<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Azurecly') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
        }
    </style>
    <body class="font-sans text-gray-900 antialiased overflow-hidden h-screen w-screen">
        <div class="h-screen w-screen flex flex-col justify-center items-center overflow-hidden">
            {{-- Form dengan max-w-sm --}}
            <div class="w-full sm:max-w-sm px-6 py-8 bg-white shadow-xl rounded-2xl max-h-[90vh] overflow-y-auto scrollbar-hide">
                {{ $slot }}
            </div>

            <div class="absolute bottom-4 left-0 right-0 text-center text-sm text-gray-500">
                &copy; {{ date('Y') }} Azurecly. All rights reserved.
            </div>
        </div>
    </body>
</html>