with open('resources/views/data-entry/index.blade.php', 'r', encoding='utf-8') as f:
    content = f.read()

factors_html = """
        <!-- Global Electricity Emission Factors -->
        <div class="bg-white shadow-sm sm:rounded-2xl border border-slate-200 transition-all hover:shadow-md mb-6">
            <div class="p-5 bg-blue-500/10 border-b border-blue-500/20 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 rounded-t-2xl">
                <div>
                    <h3 class="text-lg font-bold text-blue-700 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Global Electricity Emission Factors
                    </h3>
                    <p class="text-sm text-blue-700/70 mt-1 ml-7">Configure the CO2e global multipliers for Electricity (Coal & Renewable/GET).</p>
                </div>
            </div>
            <div class="p-6">
                <form action="{{ route('data-entry.update-electricity-factors') }}" method="POST">
                    @csrf
                    <input type="hidden" name="month" value="{{ $selectedMonth }}">
                    <input type="hidden" name="year" value="{{ $selectedYear }}">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="coal_factor" :value="__('Coal Factor (tCO2e/kWh)')" class="text-gray-600 font-medium" />
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <x-text-input id="coal_factor" class="block w-full pr-12 transition-colors border-gray-400 focus:border-blue-500 focus:ring-blue-500" type="text" inputmode="decimal" name="coal_factor" value="{{ $coalFactor }}" required />
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">tCO2e</span>
                                </div>
                            </div>
                        </div>
                        <div>
                            <x-input-label for="renewable_factor" :value="__('Renewable Factor (tCO2e/kWh)')" class="text-gray-600 font-medium" />
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <x-text-input id="renewable_factor" class="block w-full pr-12 transition-colors border-gray-400 focus:border-blue-500 focus:ring-blue-500" type="text" inputmode="decimal" name="renewable_factor" value="{{ $renewableFactor }}" required />
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">tCO2e</span>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Source: REC for TNB GET</p>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                            Save Factors
                        </button>
                    </div>
                </form>
            </div>
        </div>
"""

target_string = '<!-- GET Quota Form -->'
if target_string in content:
    content = content.replace(target_string, target_string + '\n\n' + factors_html, 1) # ONLY REPLACE ONCE
    with open('resources/views/data-entry/index.blade.php', 'w', encoding='utf-8') as f:
        f.write(content)
    print('Inserted successfully')
else:
    print('Target string not found')
