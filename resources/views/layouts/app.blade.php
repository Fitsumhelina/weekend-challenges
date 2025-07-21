<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- jQuery from CDN -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <!-- Vite Assets (CSS and JS bundle) -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @include('layouts.navigation')

            <div class="flex">
                {{-- Sidebar --}}
                @include('layouts.sidebar')

                {{-- Main content wrapper --}}
                <div class="flex-1 flex flex-col">
                    {{-- Header --}}
                    @isset($header)
                        <header class="bg-white dark:bg-gray-800 shadow">
                            <div class="px-4 sm:px-6 lg:px-8">
                                {{ $header }}
                            </div>
                        </header>
                    @endisset

                    {{-- Main Content --}}
                    <main class="flex-1 p-4 sm:p-6 lg:p-8">
                        @yield('content')
                    </main>
                </div>
            </div>
        </div>

        {{-- Scripts section for specific page scripts --}}
        @yield('scripts')
    </body>
</html>
