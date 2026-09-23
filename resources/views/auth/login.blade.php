<x-guest-layout>
    <!-- Top right language/options mock -->
    <div class="absolute top-4 right-6 flex items-center text-xs text-gray-500 font-medium">
        <i class="bi bi-translate mr-1"></i> EN <i class="bi bi-chevron-down ml-1"></i>
    </div>

    <!-- Header Logo & Title -->
    <div class="text-center mt-2 mb-8">
        <div
            class="inline-flex items-center justify-center w-14 h-14 rounded-[12px] border-2 border-[#b3ded4] bg-[#e6f4f1] text-braun-green mb-4">
            <i class="bi bi-lightning-charge text-2xl"></i>
        </div>
        <h2 class="text-lg font-bold text-gray-800 tracking-tight">EMS MONITORING CONTROL SYSTEM</h2>
        <p class="text-[11px] font-semibold text-braun-green mt-1 uppercase tracking-widest"
            style="font-family: 'Rotis Sans Serif', Arial, sans-serif;">PT B | BRAUN PHARMACEUTICAL INDONESIA</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-7">
            <label for="pin"
                class="block text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-2">{{ __('Enter your 4-digit PIN') }}</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-gray-300">
                    <i class="bi bi-lock-fill text-[15px]"></i>
                </div>
                <input id="pin" type="password" name="pin"
                    class="block w-full pl-10 pr-10 py-3 bg-white border border-gray-200 text-gray-800 text-lg tracking-[0.25em] rounded-[10px] focus:ring-braun-green focus:border-braun-green outline-none shadow-sm transition-all"
                    placeholder="••••" required autofocus maxlength="4" autocomplete="off"
                    style="border-color: #e5e7eb;">
                <button type="button" onclick="togglePinVisibility()"
                    class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 focus:outline-none">
                    <i class="bi bi-eye-fill" id="togglePinIcon"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->get('pin')" class="mt-2" />
        </div>

        <button type="submit"
            class="w-full text-white bg-braun-green hover:bg-[#008266] focus:ring-4 focus:outline-none focus:ring-[#00997A]/50 font-bold rounded-[10px] text-sm px-5 py-3 text-center transition-colors shadow-lg shadow-[#00997A]/30">
            {{ __('LOGIN') }}
        </button>

        <div class="mt-6 text-center">
            <a href="#" class="text-xs text-gray-500 hover:text-braun-green font-medium inline-flex items-center">
                <i class="bi bi-display mr-2"></i> Open Display Report (Kiosk Mode)
            </a>
        </div>
    </form>

    <div class="mt-12 text-center text-[10px] text-gray-400">
        <p class="flex items-center justify-center mb-1">
            <i class="bi bi-shield-check text-braun-green mr-1"></i> EMS Monitoring Control System v1.0 | PTBP
        </p>
        <p>&copy; {{ date('Y') }} PT B. Braun Pharmaceutical Indonesia</p>
    </div>

    <script>
        function togglePinVisibility() {
            const pinInput = document.getElementById('pin');
            const toggleIcon = document.getElementById('togglePinIcon');

            if (pinInput.type === 'password') {
                pinInput.type = 'text';
                toggleIcon.classList.remove('bi-eye-fill');
                toggleIcon.classList.add('bi-eye-slash-fill');
            } else {
                pinInput.type = 'password';
                toggleIcon.classList.remove('bi-eye-slash-fill');
                toggleIcon.classList.add('bi-eye-fill');
            }
        }
    </script>
</x-guest-layout>
toggleIcon.classList.remove('bi-eye-slash-fill');
toggleIcon.classList.add('bi-eye-fill');
}
}
</script>
</x-guest-layout>