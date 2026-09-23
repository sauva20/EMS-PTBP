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
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

        <!-- Scripts & Styles -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            braun: {
                                green: '#00997A', // Corporate Green
                                purple: '#8A3D8D', // Corporate Purple
                                sand: '#e6e3d8' // Supporting Sand
                            }
                        }
                    }
                }
            }
        </script>
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <!-- Background with subtle green to purple gradient -->
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-[#d9ede8] via-[#fcfcfc] to-[#ebe1ed]">
            
            <!-- Card with gradient top border -->
            <div class="w-full sm:max-w-md mt-6 px-8 py-10 bg-white shadow-xl overflow-hidden rounded-[25px] relative">
                <!-- Gradient Top Line -->
                <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-braun-green to-braun-purple"></div>
                
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
