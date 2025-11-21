<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>SARTU</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=montserrat:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-sartu-rojo to-sartu-marron">
            <!-- Logo SARTU -->
            <div class="absolute top-8 left-8">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center shadow-lg">
                        <span class="text-xl font-bold text-sartu-rojo">S</span>
                    </div>
                    <span class="text-white font-bold text-xl">SARTU</span>
                </div>
            </div>

            <!-- Card Container -->
            <div class="w-full max-w-md mx-4">
                <div class="bg-white rounded-2xl shadow-xl p-8 border border-sartu-gris-oscuro/10">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
