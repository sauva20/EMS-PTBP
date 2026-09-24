<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Tailwind CSS (via CDN) -->
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>

    <!-- Phosphor Icons -->
    <script src="https://cdn.jsdelivr.net/npm/@phosphor-icons/web"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Alpine.js (via CDN instead of Vite) -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/anchor@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        @font-face {
            font-family: 'Rotis Sans Serif';
            src: local('Rotis Sans Serif'), local('Arial');
        }
        
        body {
            font-family: 'Rotis Sans Serif', ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }
    </style>
</head>

<body class="bg-[#F8FAFC] text-slate-800 antialiased min-h-screen flex" x-data="{ sidebarOpen: false }">
    
    <!-- Sidebar Navigation -->
    @include('layouts.navigation')

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0 h-screen overflow-hidden relative">
        
        <!-- Top Header Bar -->
        <header class="h-[72px] bg-white border-b border-slate-200/60 flex items-center justify-between px-8 z-[60] relative flex-shrink-0 shadow-[0_2px_10px_rgba(0,0,0,0.01)]">
            <!-- Left Side Topbar: App Title -->
            <div class="flex-1 flex items-center gap-3">
                <div class="flex items-center justify-center w-9 h-9 rounded-xl bg-gradient-to-br from-[#009B77] to-[#007A5E] shadow-sm shadow-[#009B77]/30 text-white">
                    <i class="ph-fill ph-lightning text-lg"></i>
                </div>
                <div class="flex flex-col justify-center">
                    <h1 class="text-[15px] font-extrabold text-slate-800 tracking-tight leading-none uppercase">
                        EMS CO2 <span class="text-[#009B77]">CALCULATION</span>
                    </h1>
                    <span class="text-[10px] font-bold text-slate-400 tracking-[0.2em] uppercase mt-0.5">Control System</span>
                </div>
            </div>

            <!-- Right Side Topbar: Profile / Title block -->
            <div class="flex-1 flex items-center justify-end gap-3">
                <!-- Profile Info -->
                <div class="flex items-center gap-3 p-1.5 rounded-xl transition-colors cursor-pointer hover:bg-slate-50">
                    <div class="text-right hidden sm:block">
                        <h3 class="font-bold text-sm text-[#009B77] uppercase">EHSS, SM, OE & LPMO</h3>
                    </div>
                    <!-- Avatar Circle -->
                    <div class="w-10 h-10 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center flex-shrink-0">
                        <span class="text-sm font-bold text-[#009B77] uppercase">AD</span>
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="p-4 overflow-y-auto flex-1 print:overflow-visible print:h-auto print:p-0">
            <div class="w-full max-w-[1400px] mx-auto">
                <!-- Dynamic Page Heading (if any) -->
                @isset($header)
                    <div class="mb-4">
                        {{ $header }}
                    </div>
                @endisset

                <!-- Actual Page Slot -->
                {{ $slot }}
            </div>
        </main>
    </div>
    
    @stack('scripts')
</body>

</html>