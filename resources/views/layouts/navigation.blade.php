<aside @mouseenter="sidebarOpen = true" @mouseleave="sidebarOpen = false" 
       :class="sidebarOpen ? 'w-[260px]' : 'w-[80px]'" 
       class="w-[80px] bg-white flex-col hidden md:flex border-r border-slate-200/60 z-20 transition-all duration-500 ease-[cubic-bezier(0.16,1,0.3,1)] relative flex-shrink-0 shadow-[4px_0_24px_rgba(0,0,0,0.01)] will-change-[width]">
    
    <!-- Sidebar Header (Logo) -->
    <div class="px-0 justify-center h-[72px] flex items-center border-b border-slate-100 transition-all duration-500 ease-[cubic-bezier(0.16,1,0.3,1)] w-full overflow-hidden" :class="sidebarOpen ? 'px-6 justify-start' : 'px-0 justify-center'">
        <a href="{{ route('dashboard') }}" class="flex items-center">
            <div class="w-[22px] relative h-6 transition-all duration-500 ease-[cubic-bezier(0.16,1,0.3,1)] overflow-hidden flex-shrink-0" :class="sidebarOpen ? 'w-[140px]' : 'w-[22px]'">
                <img src="{{ asset('images/logo.png') }}" alt="B. Braun Logo" class="absolute left-0 top-0 h-full max-w-none" onerror="this.src='https://upload.wikimedia.org/wikipedia/commons/4/4e/B_Braun_logo.svg'">
            </div>
        </a>
    </div>

    <!-- Sidebar Navigation Links -->
    <nav class="flex-1 overflow-y-auto flex flex-col transition-all duration-500 ease-[cubic-bezier(0.16,1,0.3,1)]">
        <div class="p-4 flex flex-col gap-1.5">
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}" 
               class="justify-center flex items-center p-3 overflow-hidden rounded-xl font-semibold transition-all {{ request()->routeIs('dashboard') ? 'bg-[#009B77] text-white shadow-[0_4px_12px_rgba(0,155,119,0.25)] hover:bg-[#008264]' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}" :class="sidebarOpen ? 'justify-start' : 'justify-center'" title="Dashboard">
                <i class="{{ request()->routeIs('dashboard') ? 'ph-fill' : 'ph-duotone' }} ph-squares-four text-xl flex-shrink-0"></i>
                <span class="opacity-0 w-0 ml-0 text-sm whitespace-nowrap transition-all duration-500 ease-[cubic-bezier(0.16,1,0.3,1)] overflow-hidden" :class="sidebarOpen ? 'opacity-100 w-32 ml-3' : 'opacity-0 w-0 ml-0'">{{ __('Dashboard') }}</span>
            </a>

            <!-- Data Entry -->
            <a href="{{ route('data-entry.index') }}" 
               class="justify-center flex items-center p-3 overflow-hidden rounded-xl font-semibold transition-all {{ request()->routeIs('data-entry.*') ? 'bg-[#009B77] text-white shadow-[0_4px_12px_rgba(0,155,119,0.25)] hover:bg-[#008264]' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}" :class="sidebarOpen ? 'justify-start' : 'justify-center'" title="Data Entry">
                <i class="{{ request()->routeIs('data-entry.*') ? 'ph-fill' : 'ph-duotone' }} ph-pencil-simple-line text-xl flex-shrink-0"></i>
                <span class="opacity-0 w-0 ml-0 text-sm whitespace-nowrap transition-all duration-500 ease-[cubic-bezier(0.16,1,0.3,1)] overflow-hidden" :class="sidebarOpen ? 'opacity-100 w-32 ml-3' : 'opacity-0 w-0 ml-0'">{{ __('Data Entry') }}</span>
            </a>

            <!-- Accumulative Data -->
            <a href="{{ route('reports.accumulative') }}" 
               class="justify-center flex items-center p-3 overflow-hidden rounded-xl font-semibold transition-all {{ request()->routeIs('reports.*') ? 'bg-[#009B77] text-white shadow-[0_4px_12px_rgba(0,155,119,0.25)] hover:bg-[#008264]' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}" :class="sidebarOpen ? 'justify-start' : 'justify-center'" title="Accumulative Data">
                <i class="{{ request()->routeIs('reports.*') ? 'ph-fill' : 'ph-duotone' }} ph-table text-xl flex-shrink-0"></i>
                <span class="opacity-0 w-0 ml-0 text-sm whitespace-nowrap transition-all duration-500 ease-[cubic-bezier(0.16,1,0.3,1)] overflow-hidden" :class="sidebarOpen ? 'opacity-100 w-32 ml-3' : 'opacity-0 w-0 ml-0'">{{ __('Accumulative Data') }}</span>
            </a>
            <!-- Master Campuses -->
            <a href="{{ route('master.campuses.index') }}" 
               class="justify-center flex items-center p-3 overflow-hidden rounded-xl font-semibold transition-all {{ request()->routeIs('master.campuses.*') ? 'bg-[#009B77] text-white shadow-[0_4px_12px_rgba(0,155,119,0.25)] hover:bg-[#008264]' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}" :class="sidebarOpen ? 'justify-start' : 'justify-center'" title="Master Campuses">
                <i class="{{ request()->routeIs('master.campuses.*') ? 'ph-fill' : 'ph-duotone' }} ph-map-pin text-xl flex-shrink-0"></i>
                <span class="opacity-0 w-0 ml-0 text-sm whitespace-nowrap transition-all duration-500 ease-[cubic-bezier(0.16,1,0.3,1)] overflow-hidden" :class="sidebarOpen ? 'opacity-100 w-32 ml-3' : 'opacity-0 w-0 ml-0'">{{ __('Master Campuses') }}</span>
            </a>
            <a href="{{ route('master.buildings.index') }}" 
               class="justify-center flex items-center p-3 overflow-hidden rounded-xl font-semibold transition-all {{ request()->routeIs('master.buildings.*') ? 'bg-[#009B77] text-white shadow-[0_4px_12px_rgba(0,155,119,0.25)] hover:bg-[#008264]' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}" :class="sidebarOpen ? 'justify-start' : 'justify-center'" title="Master Buildings">
                <i class="{{ request()->routeIs('master.buildings.*') ? 'ph-fill' : 'ph-duotone' }} ph-buildings text-xl flex-shrink-0"></i>
                <span class="opacity-0 w-0 ml-0 text-sm whitespace-nowrap transition-all duration-500 ease-[cubic-bezier(0.16,1,0.3,1)] overflow-hidden" :class="sidebarOpen ? 'opacity-100 w-32 ml-3' : 'opacity-0 w-0 ml-0'">{{ __('Master Buildings') }}</span>
            </a>
        </div>
        
        <!-- Bottom Actions (Logout) -->
        <div class="mt-auto p-4 border-t border-slate-200/60 flex flex-col gap-1.5 transition-all duration-500 ease-[cubic-bezier(0.16,1,0.3,1)]">
            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <button type="submit" 
                        class="w-full justify-center flex items-center p-3 overflow-hidden rounded-xl font-semibold text-slate-500 hover:bg-red-50 hover:text-red-500 transition-all" :class="sidebarOpen ? 'justify-start' : 'justify-center'" title="{{ __('Logout') }}">
                    <i class="ph-bold ph-sign-out text-xl flex-shrink-0"></i>
                    <span class="opacity-0 w-0 ml-0 text-sm whitespace-nowrap transition-all duration-500 ease-[cubic-bezier(0.16,1,0.3,1)] overflow-hidden" :class="sidebarOpen ? 'opacity-100 w-32 ml-3' : 'opacity-0 w-0 ml-0'">{{ __('Logout') }}</span>
                </button>
            </form>
        </div>
    </nav>
</aside>