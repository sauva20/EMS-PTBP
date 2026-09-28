<x-app-layout>
    <div class="space-y-6 max-w-[95%] mx-auto py-6">
        
        <!-- Page Title Card -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-[#009B77]/10 rounded-xl flex items-center justify-center border border-[#009B77]/20">
                    <svg class="w-6 h-6 text-[#009B77]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900">{{ __('Data Entry') }}</h2>
                    <p class="text-sm text-gray-500 mt-0.5">Manage and calculate CO₂ emissions data</p>
                </div>
            </div>
        </div>
        
        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-md shadow-sm">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Oops! There were some errors with your submission:</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if(session('success'))
            <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 mb-6 rounded-r-md shadow-sm">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-emerald-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-emerald-800">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Master Form Wrapping Both Panels -->
        <form action="{{ route('data-entry.store') }}" method="POST" id="matrixForm">
            @csrf

        <!-- Compact Configuration Panel -->
        <div class="bg-white shadow-sm sm:rounded-xl border border-slate-200 mb-6 overflow-hidden">
            <div class="px-4 py-3 bg-[#009B77] flex justify-between items-center">
                <h3 class="text-sm font-bold text-white flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Configuration & Emission Factors
                </h3>
            </div>
            
            <div class="p-4 grid grid-cols-1 lg:grid-cols-12 gap-6">
                <!-- Left: Campus Setup -->
                <div class="lg:col-span-7 border-r border-slate-100 pr-0 lg:pr-6">
                    <h4 class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3">Campus Monthly Setup</h4>
                        <div class="flex flex-wrap items-end gap-3">
                            <div class="w-[120px]">
                                <label class="block text-[11px] text-gray-500 font-medium mb-1">Month</label>
                                <x-custom-select name="period_month" id="period_month" :options="$months" :selected="$selectedMonth" :required="true" buttonClass="border-gray-300 py-1.5 text-sm h-8" onchange="reloadPage()" />
                            </div>
                            <div class="w-24">
                                <label class="block text-[11px] text-gray-500 font-medium mb-1">Year</label>
                                @php $yearOpts = array_combine($years, $years); @endphp
                                <x-custom-select name="period_year" id="period_year" :options="$yearOpts" :selected="$selectedYear" :required="true" buttonClass="border-gray-300 py-1.5 text-sm h-8" onchange="reloadPage()" />
                            </div>
                            <div class="w-[120px]">
                                <label class="block text-[11px] text-gray-500 font-medium mb-1">Campus</label>
                                @php $campusOpts = ['Campus 1' => 'Campus 1', 'Campus 2' => 'Campus 2']; @endphp
                                <x-custom-select name="campus" id="campus" :options="$campusOpts" selected="Campus 1" :required="true" buttonClass="border-gray-300 py-1.5 text-sm h-8" />
                            </div>
                            <div class="flex-1 min-w-[120px]">
                                <label class="block text-[11px] text-gray-500 font-medium mb-1">Main Meter (kWh)</label>
                                <input id="main_meter_kwh" class="block w-full h-8 py-1.5 px-2 text-sm border-gray-300 rounded focus:border-[#009B77] focus:ring-[#009B77]" type="text" inputmode="decimal" placeholder="0" oninput="let v = this.value.replace(/[^0-9.]/g, ''); let p = v.split('.'); p[0] = p[0].replace(/\B(?=(\d{3})+(?!\d))/g, ','); this.value = p.join('.'); updateHiddenCampusData();" />
                            </div>
                            <div class="flex-1 min-w-[120px]">
                                <label class="block text-[11px] text-gray-500 font-medium mb-1">GET Quota (kWh)</label>
                                <input id="quota_kwh" class="block w-full h-8 py-1.5 px-2 text-sm border-gray-300 rounded focus:border-[#009B77] focus:ring-[#009B77]" type="text" inputmode="decimal" required placeholder="0" oninput="let v = this.value.replace(/[^0-9.]/g, ''); let p = v.split('.'); p[0] = p[0].replace(/\B(?=(\d{3})+(?!\d))/g, ','); this.value = p.join('.'); updateHiddenCampusData();" />
                            </div>
                        </div>
                        
                        <!-- Hidden inputs to store data for all campuses -->
                        <div id="hidden_campus_data_container">
                            @foreach(['Campus 1', 'Campus 2'] as $cName)
                                <input type="hidden" name="campus_data[{{ $cName }}][main_meter_kwh]" id="hidden_main_meter_{{ Str::slug($cName) }}" value="{{ $campusData[$cName]['main_meter_kwh'] ?? '' }}">
                                <input type="hidden" name="campus_data[{{ $cName }}][get_kwh]" id="hidden_quota_{{ Str::slug($cName) }}" value="{{ $campusData[$cName]['quota_kwh'] ?? '' }}">
                            @endforeach
                        </div>
                </div>

                <!-- Right: Emission Factors -->
                <div class="lg:col-span-5">
                    <h4 class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3">Global Emission Factors</h4>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        <div>
                            <label class="block text-[11px] text-gray-500 font-medium mb-1">Coal (tCO2e)</label>
                            <input id="inp_coal_factor" class="block w-full h-8 py-1.5 px-2 text-sm border-gray-300 rounded focus:border-blue-500 focus:ring-blue-500" type="number" step="any" inputmode="decimal" value="{{ $coalFactor }}" />
                        </div>
                        <div>
                            <label class="block text-[11px] text-gray-500 font-medium mb-1" title="Source: REC for TNB GET">Renewable</label>
                            <input id="inp_renewable_factor" class="block w-full h-8 py-1.5 px-2 text-sm border-gray-300 rounded focus:border-blue-500 focus:ring-blue-500" type="number" step="any" inputmode="decimal" value="{{ $renewableFactor }}" />
                        </div>
                        <div>
                            <label class="block text-[11px] text-gray-500 font-medium mb-1">PV (tCO2e)</label>
                            <input id="inp_pv_factor" class="block w-full h-8 py-1.5 px-2 text-sm border-gray-300 rounded focus:border-blue-500 focus:ring-blue-500" type="number" step="any" inputmode="decimal" value="{{ $pvFactor }}" />
                        </div>
                        <div>
                            <label class="block text-[11px] text-gray-500 font-medium mb-1">Boiler (kgCO2e/L)</label>
                            <input id="inp_boiler_factor" class="block w-full h-8 py-1.5 px-2 text-sm border-gray-300 rounded focus:border-orange-500 focus:ring-orange-500" type="number" step="any" inputmode="decimal" value="{{ number_format($boilerFactor * 1000, 2, '.', '') }}" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Matrix Data Entry Form -->
        <div class="bg-white shadow-sm sm:rounded-2xl border border-slate-200 transition-all hover:shadow-md">

                
                <div class="p-5 bg-[#009B77] flex flex-col md:flex-row justify-between items-start md:items-center gap-4 rounded-t-2xl">
                    <div>
                        <h3 class="text-lg font-bold text-white flex items-center gap-2">
                            <svg class="w-5 h-5 text-emerald-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Emissions Data Matrix
                        </h3>
                        <p class="text-sm text-emerald-100/90 mt-1 ml-7">Enter usage data for each building. CO₂ emissions are calculated automatically.</p>
                    </div>
                    <input type="hidden" name="month" value="{{ $selectedMonth }}">
                    <input type="hidden" name="year" value="{{ $selectedYear }}">
                </div>

                <div class="overflow-x-auto w-full">
                    <table class="w-full text-sm text-left whitespace-nowrap">
                        <thead class="bg-[#009B77]/10 text-[#009B77]">
                            <tr>
                                <th class="sticky left-0 z-10 bg-[#e6f5f1] px-4 py-3 border-b border-r border-[#009B77]/20 text-xs font-bold uppercase tracking-wider shadow-[1px_0_0_0_rgba(0,155,119,0.2)]">Emission Source</th>
                                @foreach($buildings as $building)
                                    <th class="px-3 py-3 border-b border-r border-[#009B77]/20 text-center text-xs font-bold uppercase tracking-wider">{{ $building->name }}</th>
                                @endforeach
                                <th class="px-4 py-3 border-b border-l border-[#009B77]/20 text-right pr-6 text-xs font-bold uppercase tracking-wider">Total</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white" id="matrix-tbody">
                            @php
                                $co2Sources = $sources->filter(fn($s) => $s->name !== 'Water Consumption');
                                $waterSource = $sources->firstWhere('name', 'Water Consumption');
                            @endphp

                            @foreach($co2Sources as $source)
                                <tr class="hover:bg-[#009B77]/5 transition-colors group border-b border-gray-200">
                                    <td class="sticky left-0 z-10 bg-white group-hover:bg-[#009B77]/5 px-4 py-3 border-r border-gray-200 shadow-[1px_0_0_0_#e5e7eb] align-middle">
                                        @php
                                            $excelLabels = [
                                                'Stationary Energy (Diesel)' => 'Stationary energy (1) Diesel (L)',
                                                'Stationary Energy (LPG)' => 'Stationary energy (2) LPG (kg)',
                                                'Purchased Electricity' => 'Purchased electricity (kWh)',
                                                'Self-generated PV Electricity' => 'Solar generated electricity (kWh)',
                                                'Transportation Petrol' => 'Transportation Petrol (L)',
                                                'IPPU/Refrigerant' => 'IPPU/Refrigerant (kg)'
                                            ];
                                            $displayName = $excelLabels[$source->name] ?? $source->name;
                                        @endphp
                                        <div class="font-medium text-gray-900">{{ $displayName }}</div>
                                    </td>
                                    @foreach($buildings as $building)
                                        <td class="px-2 py-2 border-r border-gray-100 align-top">
                                            <div class="flex flex-col">
                                                <input type="text" inputmode="decimal"
                                                    name="emissions[{{ $building->id }}][{{ $source->id }}]" 
                                                    class="matrix-input w-full px-2 py-1.5 text-right text-sm border-gray-200 focus:border-[#009B77] focus:ring-[#009B77] rounded transition-colors shadow-sm bg-white"
                                                    value="{{ isset($existingEmissions[$building->id][$source->id]) ? number_format($existingEmissions[$building->id][$source->id], 0, '.', ',') : '' }}"
                                                    data-factor="{{ $factors[$source->id] ?? 0 }}"
                                                    data-source="{{ $source->name }}"
                                                    data-source-id="{{ $source->id }}"
                                                    data-building-id="{{ $building->id }}"
                                                    data-building-name="{{ $building->name }}"
                                                    placeholder="0"
                                                    oninput="let v = this.value.replace(/[^0-9.]/g, ''); let p = v.split('.'); p[0] = p[0].replace(/\B(?=(\d{3})+(?!\d))/g, ','); this.value = p.join('.'); if(typeof window.updateTotals === 'function') window.updateTotals();"
                                                >
                                                <div class="text-right text-[11px] text-[#009B77] font-medium h-4 mt-1 px-1">
                                                    @if($source->name === 'Water Consumption')
                                                        <!-- No CO2 display for water -->
                                                    @elseif($source->name === 'Purchased Electricity')
                                                        <span class="text-gray-400">CO₂: </span><span class="co2-preview font-semibold">{{ isset($existingCo2[$building->id][$source->id]) ? number_format($existingCo2[$building->id][$source->id], 2) : 'Auto' }}</span>
                                                    @else
                                                        <span class="text-gray-400">CO₂: </span><span class="co2-preview font-semibold">{{ isset($existingCo2[$building->id][$source->id]) ? number_format($existingCo2[$building->id][$source->id], 2) : '' }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                    @endforeach
                                    <td class="px-3 py-2 border-l border-b border-gray-200 bg-[#009B77]/5 align-top group-hover:bg-[#009B77]/10 transition-colors">
                                        <div class="flex items-center justify-end h-full pt-1.5 pr-2">
                                            @if($source->name === 'Purchased Electricity')
                                                <span id="total_co2_{{ $source->id }}" class="font-bold text-gray-900 text-sm">Auto</span>
                                            @else
                                                <span id="total_co2_{{ $source->id }}" class="font-bold text-gray-900 text-sm">0.00</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach

                            <!-- Grand Total Row for CO2 -->
                            <tr class="bg-yellow-50/30 font-bold border-t-[3px] !border-gray-400">
                                <td class="sticky left-0 z-10 bg-[#fffdf0] px-4 py-3 border-r border-gray-200 align-middle">
                                    <div class="text-gray-900 uppercase">Total CO₂ Emission</div>
                                </td>
                                @foreach($buildings as $building)
                                    <td class="px-2 py-3 border-r border-gray-100 text-right text-sm text-[#009B77] align-middle" id="grand_total_building_{{ $building->id }}">
                                        0.00
                                    </td>
                                @endforeach
                                <td class="px-3 py-3 border-l border-gray-200 text-right text-sm text-[#009B77] align-middle bg-[#009B77]/10" id="grand_total_all">
                                    0.00
                                </td>
                            </tr>

                            @if($waterSource)
                            <!-- Spacer Row to visually separate Water Consumption -->
                            <tr>
                                <td colspan="{{ count($buildings) + 2 }}" class="h-4 bg-slate-200 border-y border-slate-300"></td>
                            </tr>

                            <!-- Water Consumption Row -->
                            @php $source = $waterSource; @endphp
                            <tr class="hover:bg-[#009B77]/5 transition-colors group bg-white">
                                <td class="sticky left-0 z-10 bg-white group-hover:bg-[#009B77]/5 px-4 py-3 border-r border-gray-200 shadow-[1px_0_0_0_#e5e7eb] align-middle">
                                    <div class="font-medium text-gray-900">{{ $source->name === 'Water Consumption' ? 'Water Consumption (Liter)' : $source->name }}</div>
                                </td>
                                @foreach($buildings as $building)
                                    <td class="px-2 py-2 border-r border-gray-100 align-top relative group">
                                        <input type="text" inputmode="decimal"
                                            name="emissions[{{ $building->id }}][{{ $source->id }}]" 
                                            class="matrix-input w-full h-9 text-right text-sm font-medium border-0 focus:ring-2 focus:ring-inset focus:ring-[#009B77] bg-transparent hover:bg-slate-50 transition-colors {{ isset($existingEmissions[$building->id][$source->id]) && $existingEmissions[$building->id][$source->id] > 0 ? 'text-slate-900' : 'text-slate-400' }}"
                                            value="{{ isset($existingEmissions[$building->id][$source->id]) && $existingEmissions[$building->id][$source->id] > 0 ? number_format($existingEmissions[$building->id][$source->id], 0) : '' }}"
                                            data-source-id="{{ $source->id }}"
                                            data-source="{{ $source->name }}"
                                            data-building-id="{{ $building->id }}"
                                            data-building-name="{{ $building->name }}"
                                            data-factor="{{ $factors[$source->id] ?? 0 }}"
                                            placeholder="0">
                                        <div class="mt-1 text-right min-h-[16px] pr-2">
                                            <!-- No CO2 display for water -->
                                        </div>
                                    </td>
                                @endforeach
                                <td class="px-4 py-2 text-right align-middle bg-slate-50/50">
                                    <div class="flex items-center justify-end h-full">
                                        <span id="total_val_{{ $source->id }}" class="font-bold text-sm text-slate-700">0</span>
                                    </div>
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>


    
    <!-- ELECTRICITY BREAKDOWN TABLES -->
    @php
        // Manually group them to perfectly match the Excel 
        $campus1Buildings = $buildings->filter(fn($b) => in_array($b->name, ['B3', 'B5', 'B8', 'B9']));
        $campus2Buildings = $buildings->filter(fn($b) => in_array($b->name, ['B1', 'B10', 'B11', 'ASM', 'B12']));
        $otherBuildings = $buildings->filter(fn($b) => !in_array($b->name, ['B1', 'B3', 'B5', 'B8', 'B9', 'B10', 'B11', 'ASM', 'B12']));
        
        $orderedBuildings = collect();
        foreach(['B3', 'B5', 'B8', 'B9'] as $n) { if($b = $buildings->firstWhere('name', $n)) $orderedBuildings->push($b); }
        foreach(['B1', 'B10', 'B11', 'ASM', 'B12'] as $n) { if($b = $buildings->firstWhere('name', $n)) $orderedBuildings->push($b); }
        foreach($otherBuildings as $b) { $orderedBuildings->push($b); }
        
        $c1Count = $campus1Buildings->count();
        $c2Count = $campus2Buildings->count();
    @endphp
    
    <div class="overflow-x-auto w-full border border-gray-200 shadow-sm mt-6 mb-8">
        <table class="w-full text-sm text-left whitespace-nowrap border-collapse" id="electricity-matrix" data-coal-factor="{{ $coalFactor }}">
            <thead class="bg-white text-gray-900 border-b-2 border-gray-900">
                <tr>
                    <th rowspan="2" class="px-4 py-2 border border-gray-300 font-bold align-middle w-64 bg-gray-50 text-center">
                        CO2 Emission for Electricity<br>(Supply: TNB)
                    </th>
                    @if($c1Count > 0)
                        <th colspan="{{ $c1Count }}" class="px-4 py-2 border border-gray-300 font-bold text-center bg-gray-50">Campus 1</th>
                    @endif
                    @if($c2Count > 0)
                        <th colspan="{{ $c2Count }}" class="px-4 py-2 border border-gray-300 font-bold text-center bg-gray-50">Campus 2</th>
                    @endif
                    @if($otherBuildings->count() > 0)
                        <th colspan="{{ $otherBuildings->count() }}" class="px-4 py-2 border border-gray-300 font-bold text-center bg-gray-50">Other</th>
                    @endif
                    <th rowspan="2" class="px-4 py-2 border border-gray-300 font-bold text-center align-middle bg-gray-50">
                        Total GET<br>
                        <span id="header_total_get">0.00</span>
                    </th>
                </tr>
                <tr>
                    @if($c1Count > 0)
                        <!-- Campus 1 GET row -->
                        <th colspan="{{ ceil($c1Count / 2) }}" class="px-4 py-1 border border-gray-300 font-bold text-center bg-gray-50">GET (kWh)</th>
                        <th colspan="{{ floor($c1Count / 2) }}" class="px-4 py-1 border border-gray-300 font-bold text-center bg-gray-50">
                            <input type="text" id="inp_c1_get" class="matrix-input w-full text-center border-0 bg-transparent font-bold focus:ring-0 p-0" placeholder="0.00" value="5,280,000.00" oninput="calcElectricityMatrix()">
                        </th>
                    @endif
                    @if($c2Count > 0)
                        <!-- Campus 2 GET row -->
                        <th colspan="{{ ceil($c2Count / 2) }}" class="px-4 py-1 border border-gray-300 font-bold text-center bg-gray-50">GET (kWh)</th>
                        <th colspan="{{ floor($c2Count / 2) }}" class="px-4 py-1 border border-gray-300 font-bold text-center bg-gray-50">
                            <input type="text" id="inp_c2_get" class="matrix-input w-full text-center border-0 bg-transparent font-bold focus:ring-0 p-0" placeholder="0.00" value="4,137,000.00" oninput="calcElectricityMatrix()">
                        </th>
                    @endif
                    @if($otherBuildings->count() > 0)
                        <th colspan="{{ $otherBuildings->count() }}" class="px-4 py-1 border border-gray-300 bg-gray-50"></th>
                    @endif
                </tr>
                <tr>
                    <th class="px-4 py-2 border border-gray-300 font-bold bg-gray-50"></th>
                    @foreach($orderedBuildings as $building)
                        <th class="px-3 py-2 border border-gray-300 text-center font-bold bg-gray-50">{{ $building->name }}</th>
                    @endforeach
                    <th class="px-4 py-2 border border-gray-300 text-center font-bold bg-gray-50">Total</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                <tr>
                    <td class="px-4 py-2 border border-gray-300 font-medium text-gray-900">Purchased electricity (kWh)</td>
                    @php $elecSource = $sources->firstWhere('name', 'Purchased Electricity'); @endphp
                    @foreach($orderedBuildings as $building)
                        <td class="px-2 py-2 border border-gray-300 text-right">
                            @if($elecSource)
                            <input type="text" inputmode="decimal"
                                name="emissions[{{ $building->id }}][{{ $elecSource->id }}]" 
                                class="matrix-input w-full px-2 py-1 text-right text-sm border-gray-200 rounded input-purchased-elec"
                                value="{{ isset($existingEmissions[$building->id][$elecSource->id]) ? number_format($existingEmissions[$building->id][$elecSource->id], 2, '.', '') : '' }}"
                                placeholder="0"
                                data-campus="{{ $campus1Buildings->contains('id', $building->id) ? 'c1' : ($campus2Buildings->contains('id', $building->id) ? 'c2' : 'other') }}"
                                oninput="calcElectricityMatrix()"
                            >
                            @endif
                        </td>
                    @endforeach
                    <td class="px-4 py-2 text-right border border-gray-300 font-bold text-gray-800" id="total_purchased_elec">0.00</td>
                </tr>
                <tr class="bg-gray-50">
                    <td class="px-4 py-2 border border-gray-300 font-medium text-gray-900">Total purchased electricity (kWh)</td>
                    @if($c1Count > 0)
                        <td colspan="{{ $c1Count }}" class="px-4 py-1 border border-gray-300 text-center">
                            <input type="text" id="inp_c1_total_purchased" class="matrix-input w-full text-center border-0 bg-transparent font-bold focus:ring-0 p-0" placeholder="0.00" value="6,095,551.00" oninput="calcElectricityMatrix()">
                        </td>
                    @endif
                    @if($c2Count > 0)
                        <td colspan="{{ $c2Count }}" class="px-4 py-1 border border-gray-300 text-center">
                            <input type="text" id="inp_c2_total_purchased" class="matrix-input w-full text-center border-0 bg-transparent font-bold focus:ring-0 p-0" placeholder="0.00" value="4,395,110.00" oninput="calcElectricityMatrix()">
                        </td>
                    @endif
                    @if($otherBuildings->count() > 0)
                        <td colspan="{{ $otherBuildings->count() }}" class="px-4 py-2 border border-gray-300 text-center"></td>
                    @endif
                    <td class="px-4 py-2 text-right border border-gray-300 font-bold text-gray-800" id="grand_total_purchased_elec">0.00</td>
                </tr>
                <tr>
                    <td class="px-4 py-2 border border-gray-300 font-medium text-gray-900">Percentage by building (%)</td>
                    @foreach($orderedBuildings as $building)
                        <td class="px-2 py-2 border border-gray-300 text-right cell-percentage">0.00</td>
                    @endforeach
                    <td class="px-4 py-2 text-right border border-gray-300 font-bold text-gray-800"></td>
                </tr>
                <tr class="bg-gray-50">
                    <td class="px-4 py-2 border border-gray-300 font-medium text-gray-900">Adjusted (kWh)</td>
                    @foreach($orderedBuildings as $building)
                        <td class="px-2 py-2 border border-gray-300 text-right cell-adjusted">0.00</td>
                    @endforeach
                    <td class="px-4 py-2 text-right border border-gray-300 font-bold text-gray-800"></td>
                </tr>
                <tr>
                    <td class="px-4 py-2 border border-gray-300 font-medium text-gray-900">Purchased electricity- Coal (kWh)</td>
                    @if($c1Count > 0)
                        <td colspan="{{ $c1Count }}" class="px-4 py-2 border border-gray-300 text-center" id="c1_total_coal">0.00</td>
                    @endif
                    @if($c2Count > 0)
                        <td colspan="{{ $c2Count }}" class="px-4 py-2 border border-gray-300 text-center" id="c2_total_coal">0.00</td>
                    @endif
                    @if($otherBuildings->count() > 0)
                        <td colspan="{{ $otherBuildings->count() }}" class="px-4 py-2 border border-gray-300 text-center"></td>
                    @endif
                    <td class="px-4 py-2 text-right border border-gray-300 font-bold text-gray-800" id="grand_total_coal">0.00</td>
                </tr>
                <tr class="bg-gray-50">
                    <td class="px-4 py-2 border border-gray-300 font-medium text-gray-900">Purchased electricity- Coal (kWh)</td>
                    @foreach($orderedBuildings as $building)
                        <td class="px-2 py-2 border border-gray-300 text-right cell-purchased-coal">0.00</td>
                    @endforeach
                    <td class="px-4 py-2 text-right border border-gray-300 font-bold text-gray-800" id="total_purchased_coal">0.00</td>
                </tr>
                <tr class="bg-[#dcfce7]">
                    <td class="px-4 py-2 border border-gray-300 font-medium text-green-800">CO2 Emission (Coal)</td>
                    @foreach($orderedBuildings as $building)
                        <td class="px-2 py-2 border border-gray-300 text-right text-green-800 font-medium cell-co2-coal">0.00</td>
                    @endforeach
                    <td class="px-4 py-2 text-right border border-gray-300 font-bold text-green-800" id="total_co2_coal">0.00</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- SOLAR TABLE (Same styling) -->
    <div class="overflow-x-auto w-full border border-gray-200 shadow-sm mt-6 mb-8">
        <table class="w-full text-sm text-left whitespace-nowrap border-collapse" id="solar-matrix" data-factor="{{ $pvFactor }}">
            <thead class="bg-white text-gray-900 border-b-2 border-gray-900">
                <tr>
                    <th class="px-4 py-2 border border-gray-300 font-bold bg-gray-50 text-center align-middle w-64">CO2 Emission for Electricity<br>(Supply: Self-generated PV)</th>
                    @if($c1Count > 0)
                        <th colspan="{{ $c1Count }}" class="px-4 py-2 border border-gray-300 font-bold text-center bg-gray-50">Campus 1</th>
                    @endif
                    @if($c2Count > 0)
                        <th colspan="{{ $c2Count }}" class="px-4 py-2 border border-gray-300 font-bold text-center bg-gray-50">Campus 2</th>
                    @endif
                    @if($otherBuildings->count() > 0)
                        <th colspan="{{ $otherBuildings->count() }}" class="px-4 py-2 border border-gray-300 font-bold text-center bg-gray-50">Other</th>
                    @endif
                    <th class="px-4 py-2 border border-gray-300 text-center font-bold bg-gray-50">Total</th>
                </tr>
                <tr>
                    <th class="px-4 py-2 border border-gray-300 font-bold bg-gray-50"></th>
                    @foreach($orderedBuildings as $building)
                        <th class="px-3 py-2 border border-gray-300 text-center font-bold bg-gray-50">{{ $building->name }}</th>
                    @endforeach
                    <th class="px-4 py-2 border border-gray-300 text-center font-bold bg-gray-50">Total</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                <tr>
                    <td class="px-4 py-2 border border-gray-300 font-medium text-gray-900">Solar Generated Electricity (kWh)</td>
                    @php $solarSource = $sources->firstWhere('name', 'Self-generated PV Electricity'); @endphp
                    @foreach($orderedBuildings as $building)
                        <td class="px-2 py-2 border border-gray-300 text-right">
                            @if($solarSource)
                            <input type="text" inputmode="decimal"
                                name="emissions[{{ $building->id }}][{{ $solarSource->id }}]" 
                                class="matrix-input w-full px-2 py-1 text-right text-sm border-gray-200 rounded input-solar"
                                value="{{ isset($existingEmissions[$building->id][$solarSource->id]) ? number_format($existingEmissions[$building->id][$solarSource->id], 2, '.', '') : '' }}"
                                placeholder="0.00"
                                oninput="calcSolarMatrix()"
                            >
                            @endif
                        </td>
                    @endforeach
                    <td class="px-4 py-2 text-right border border-gray-300 font-bold text-gray-800" id="total_solar_elec">0.00</td>
                </tr>
                <tr class="bg-[#dcfce7]">
                    <td class="px-4 py-2 border border-gray-300 font-medium text-green-800">CO2 Emission for Self-PV</td>
                    @foreach($orderedBuildings as $building)
                        <td class="px-2 py-2 border border-gray-300 text-right text-green-800 font-medium cell-solar-co2">0.00</td>
                    @endforeach
                    <td class="px-4 py-2 text-right border border-gray-300 font-bold text-green-800" id="total_solar_co2">0.00</td>
                </tr>
            </tbody>
        </table>
    </div>

<!-- NEW SEPARATE TABLES -->
<div class="mt-8 space-y-8 mb-8">
    @php
        $dieselSource = $sources->firstWhere('name', 'Stationary Energy (Diesel)');
        $petrolSource = $sources->firstWhere('name', 'Transportation Petrol');
        $refrigerantSource = $sources->firstWhere('name', 'IPPU/Refrigerant');
        
        $boiler = $machineryCategories->get($dieselSource->id ?? 0)?->firstWhere('name', 'Boiler');
        $forklift = $machineryCategories->get($dieselSource->id ?? 0)?->firstWhere('name', 'Forklift Diesel');
        $genset = $machineryCategories->get($dieselSource->id ?? 0)?->firstWhere('name', 'GenSet Diesel');
        
        $os = $machineryCategories->get($petrolSource->id ?? 0)?->firstWhere('name', 'OS');
        $of = $machineryCategories->get($petrolSource->id ?? 0)?->firstWhere('name', 'OF');
        
        $r22 = $machineryCategories->get($refrigerantSource->id ?? 0)?->firstWhere('name', 'R22');
        $r407c = $machineryCategories->get($refrigerantSource->id ?? 0)?->firstWhere('name', 'R407C');
        $r134a = $machineryCategories->get($refrigerantSource->id ?? 0)?->firstWhere('name', 'R134A');
        
        // Single dummy/first building for facility-wide entries
        $mainBuilding = $buildings->first();
    @endphp

    @if($dieselSource)
    <div class="overflow-x-auto w-full border border-gray-200 rounded-xl shadow-sm">
        <h3 class="bg-gray-50 px-4 py-2 font-bold text-gray-700">Diesel Breakdown</h3>
        <table class="w-full text-sm text-left whitespace-nowrap">
            <thead class="bg-[#009B77] text-white">
                <tr>
                    <th class="px-4 py-3 border-b border-r border-[#008264] text-xs font-bold uppercase tracking-wider w-48">Category</th>
                    @foreach($buildings as $building)
                        <th class="px-3 py-3 border-b border-r border-[#008264] text-center text-xs font-bold uppercase tracking-wider">{{ $building->name }}</th>
                    @endforeach
                    <th class="px-4 py-3 border-b border-[#008264] text-right text-xs font-bold uppercase tracking-wider">Total</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @foreach([$boiler, $forklift, $genset] as $cat)
                @if($cat)
                <tr class="hover:bg-[#009B77]/5 transition-colors border-b border-gray-100">
                    <td class="px-4 py-3 border-r border-gray-200 font-medium text-gray-900">{{ $cat->name }} (L)</td>
                    
                    @foreach($buildings as $building)
                    <td class="px-2 py-2 border-r border-gray-100 align-top">
                        <input type="text" inputmode="decimal"
                            name="emissions[{{ $building->id }}][{{ $dieselSource->id }}][{{ $cat->id }}]" 
                            class="matrix-input w-full px-2 py-1.5 text-right text-sm border-gray-200 focus:border-[#009B77] focus:ring-[#009B77] rounded shadow-sm bg-white row-calc-diesel-{{ $cat->id }}"
                            value="{{ isset($existingEmissions[$building->id][$dieselSource->id . '_' . $cat->id]) ? number_format($existingEmissions[$building->id][$dieselSource->id . '_' . $cat->id], 0, '.', ',') : '' }}"
                            placeholder="0"
                            oninput="formatInputAndCalcRow(this, 'row-calc-diesel-{{ $cat->id }}', 'total-diesel-{{ $cat->id }}')"
                        >
                    </td>
                    @endforeach
                    
                    <td class="px-4 py-2 text-right bg-slate-50 font-bold text-gray-800" id="total-diesel-{{ $cat->id }}">0</td>
                </tr>
                @endif
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-6">
        @if($petrolSource)
        <div class="overflow-x-auto w-full border border-gray-200 rounded-xl shadow-sm">
            <h3 class="bg-gray-50 px-4 py-2 font-bold text-gray-700 border-b border-gray-200">Petrol Breakdown</h3>
            <table class="w-full text-sm text-left whitespace-nowrap">
                <thead class="bg-[#009B77] text-white">
                    <tr>
                        <th class="px-4 py-3 border-b border-r border-[#008264] text-xs font-bold uppercase tracking-wider w-32">Source</th>
                        <th class="px-4 py-3 border-b border-r border-[#008264] text-center text-xs font-bold uppercase tracking-wider">OS (L)</th>
                        <th class="px-4 py-3 border-b border-r border-[#008264] text-center text-xs font-bold uppercase tracking-wider">OF (L)</th>
                        <th class="px-4 py-3 border-b border-[#008264] text-right text-xs font-bold uppercase tracking-wider">Total</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    <tr class="hover:bg-[#009B77]/5 transition-colors border-b border-gray-100">
                        <td class="px-4 py-3 border-r border-gray-200 font-medium text-gray-900">Petrol</td>
                        
                        @foreach([$os, $of] as $cat)
                        <td class="px-2 py-2 border-r border-gray-100 align-top">
                            @if($cat && $mainBuilding)
                            <input type="text" inputmode="decimal"
                                name="emissions[{{ $mainBuilding->id }}][{{ $petrolSource->id }}][{{ $cat->id }}]" 
                                class="matrix-input w-full px-2 py-1.5 text-right text-sm border-gray-200 focus:border-[#009B77] focus:ring-[#009B77] rounded shadow-sm bg-white row-calc-petrol-single"
                                value="{{ isset($existingEmissions[$mainBuilding->id][$petrolSource->id . '_' . $cat->id]) ? number_format($existingEmissions[$mainBuilding->id][$petrolSource->id . '_' . $cat->id], 2, '.', ',') : '' }}"
                                placeholder="0.00"
                                oninput="formatInputAndCalcRow(this, 'row-calc-petrol-single', 'total-petrol-single')"
                            >
                            @endif
                        </td>
                        @endforeach
                        
                        <td class="px-4 py-2 text-right bg-slate-50 font-bold text-gray-800" id="total-petrol-single">0.00</td>
                    </tr>
                </tbody>
            </table>
        </div>
        @endif
        
        @if($refrigerantSource)
        <div class="overflow-x-auto w-full border border-gray-200 rounded-xl shadow-sm">
            <h3 class="bg-gray-50 px-4 py-2 font-bold text-gray-700 border-b border-gray-200">Refrigerant Breakdown</h3>
            <table class="w-full text-sm text-left whitespace-nowrap">
                <thead class="bg-[#009B77] text-white">
                    <tr>
                        <th class="px-4 py-3 border-b border-r border-[#008264] text-xs font-bold uppercase tracking-wider w-32">Source</th>
                        <th class="px-4 py-3 border-b border-[#008264] text-left text-xs font-bold uppercase tracking-wider">Usage (KG)</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @foreach([$r22, $r134a, $r407c] as $index => $cat)
                    @if($cat)
                    <tr class="hover:bg-[#009B77]/5 transition-colors border-b border-gray-100">
                        @if($index === 0)
                        <td class="px-4 py-3 border-r border-gray-200 font-medium text-gray-900 align-top" rowspan="3">Refrigerant</td>
                        @endif
                        <td class="px-2 py-2 border-r border-gray-100 align-middle flex items-center gap-2">
                            <input type="text" inputmode="decimal"
                                name="emissions[{{ $mainBuilding->id }}][{{ $refrigerantSource->id }}][{{ $cat->id }}]" 
                                class="matrix-input w-32 px-2 py-1.5 text-right text-sm border-gray-200 focus:border-[#009B77] focus:ring-[#009B77] rounded shadow-sm bg-white"
                                value="{{ isset($existingEmissions[$mainBuilding->id][$refrigerantSource->id . '_' . $cat->id]) ? number_format($existingEmissions[$mainBuilding->id][$refrigerantSource->id . '_' . $cat->id], 3, '.', ',') : '' }}"
                                placeholder="0.000"
                                oninput="let v = this.value.replace(/[^0-9.]/g, ''); let p = v.split('.'); p[0] = p[0].replace(/\B(?=(\d{3})+(?!\d))/g, ','); this.value = p.join('.');"
                            >
                            <span class="font-semibold text-gray-700">{{ $cat->name }}</span>
                        </td>
                    </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>

                <div class="p-4 border-t border-slate-200 bg-slate-50 flex justify-end items-center gap-4 rounded-b-2xl">
                    <span class="text-sm text-gray-500 italic hidden sm:inline-block">Unsaved changes will be lost</span>
                    <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-[#009B77] border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#008264] focus:bg-[#008264] active:bg-[#006e54] focus:outline-none focus:ring-2 focus:ring-[#009B77] focus:ring-offset-2 transition ease-in-out duration-150 shadow-md hover:shadow-lg">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                        Save All Data
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    @push('scripts')
    <script>
        const campusData = @json($campusData);
        
        function formatNumber(val) {
            if (val === null || val === undefined) return '';
            let cleaned = val.toString().replace(/[^0-9.]/g, '');
            if (!cleaned) return '';
            let parts = cleaned.split('.');
            parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            if (parts.length > 2) {
                return parts[0] + '.' + parts.slice(1).join('');
            }
            return parts.join('.');
        }

        document.addEventListener('DOMContentLoaded', function() {
            try {
                const campusSelect = document.getElementById('campus');
                const mainMeterInput = document.getElementById('main_meter_kwh');
                const quotaInput = document.getElementById('quota_kwh');

                // Update hidden inputs when user types
                window.updateHiddenCampusData = function() {
                    if (!campusSelect) return;
                    const selected = campusSelect.value.toLowerCase().replace(/[^a-z0-9]/g, '-');
                    const hiddenMain = document.getElementById('hidden_main_meter_' + selected);
                    const hiddenQuota = document.getElementById('hidden_quota_' + selected);
                    
                    if (hiddenMain && mainMeterInput) hiddenMain.value = mainMeterInput.value.replace(/,/g, '');
                    if (hiddenQuota && quotaInput) hiddenQuota.value = quotaInput.value.replace(/,/g, '');
                };

                function updateCampusFields() {
                    if (!campusSelect) return;
                    const selected = campusSelect.value.toLowerCase().replace(/[^a-z0-9]/g, '-');
                    const hiddenMain = document.getElementById('hidden_main_meter_' + selected);
                    const hiddenQuota = document.getElementById('hidden_quota_' + selected);
                    
                    mainMeterInput.value = (hiddenMain && hiddenMain.value) ? formatNumber(hiddenMain.value) : '';
                    quotaInput.value = (hiddenQuota && hiddenQuota.value) ? formatNumber(hiddenQuota.value) : '';
                }

                if (campusSelect) {
                    campusSelect.addEventListener('change', updateCampusFields);
                    updateCampusFields();
                }

                [mainMeterInput, quotaInput].forEach(input => {
                    if (input) {
                        input.addEventListener('input', function(e) {
                            let originalValue = this.value;
                            let formatted = formatNumber(originalValue);
                            if (this.value !== formatted) {
                                this.value = formatted;
                            }
                        });
                    }
                });
            } catch (e) {
                console.error("Campus logic error:", e);
            }

            // Campus Logic
            const inputs = document.querySelectorAll('.matrix-input');
            
            window.updateTotals = function() {
                try {
                    const sourceIds = [...new Set(Array.from(inputs).map(i => i.dataset.sourceId))];
                    
                    sourceIds.forEach(sourceId => {
                        let totalVal = 0;
                        let totalCo2 = 0;
                        
                        const firstInput = document.querySelector(`.matrix-input[data-source-id="${sourceId}"]`);
                        if (!firstInput) return;
                        
                        const isElec = firstInput.dataset.source === 'Purchased Electricity';
                        
                        const rowInputs = document.querySelectorAll(`.matrix-input[data-source-id="${sourceId}"]`);
                        rowInputs.forEach(input => {
                            const valStr = input.value.toString().replace(/,/g, '');
                            const val = parseFloat(valStr) || 0;
                            totalVal += val;
                            
                            let factor = parseFloat(input.dataset.factor) || 0;
                            const sourceName = input.dataset.source;
                            const buildingName = input.dataset.buildingName;

                            if (sourceName === 'Stationary Energy (Diesel)') {
                                factor = (buildingName === 'B1') ? 0.00317 : 0.00268;
                            } else if (sourceName === 'Stationary Energy (LPG)') {
                                factor = 0.0014;
                            }

                            const co2 = val * factor;
                            // Round each building's CO2 to 2 decimal places BEFORE summing to match Excel's behavior
                            const roundedCo2 = Math.round(co2 * 100) / 100;
                            
                            if (input.parentElement) {
                                const previewCell = input.parentElement.querySelector('.co2-preview');
                                if (previewCell && !isElec && sourceName !== 'Water Consumption') {
                                    totalCo2 += roundedCo2;
                                    if (val > 0) {
                                        previewCell.textContent = roundedCo2.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                                    } else {
                                        previewCell.textContent = '';
                                    }
                                }
                            }
                        });
                        
                        const totalValEl = document.getElementById(`total_val_${sourceId}`);
                        if (totalValEl) {
                            totalValEl.textContent = totalVal > 0 ? totalVal.toLocaleString('en-US', { minimumFractionDigits: 0, maximumFractionDigits: 2 }) : '0';
                        }
                        
                        const totalCo2El = document.getElementById(`total_co2_${sourceId}`);
                        if (totalCo2El) {
                            if (!isElec) {
                                totalCo2El.textContent = totalCo2 > 0 ? totalCo2.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : '0';
                            } else {
                                totalCo2El.textContent = 'Auto';
                            }
                        }
                    });

                    // Calculate Grand Total CO2 per building
                    let grandTotalAll = 0;
                    const buildingIds = [...new Set(Array.from(inputs).map(i => i.dataset.buildingId))];
                    
                    buildingIds.forEach(bId => {
                        let buildingTotalCo2 = 0;
                        const colInputs = document.querySelectorAll(`.matrix-input[data-building-id="${bId}"]`);
                        
                        colInputs.forEach(input => {
                            if (input.dataset.source === 'Water Consumption') return;
                            
                            const previewCell = input.parentElement.querySelector('.co2-preview');
                            if (previewCell) {
                                const co2Text = previewCell.textContent.replace(/,/g, '').trim();
                                if (co2Text !== 'Auto' && co2Text !== '') {
                                    buildingTotalCo2 += parseFloat(co2Text) || 0;
                                }
                            }
                        });

                        const grandTotalEl = document.getElementById(`grand_total_building_${bId}`);
                        if (grandTotalEl) {
                            grandTotalEl.textContent = buildingTotalCo2 > 0 ? buildingTotalCo2.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : '0.00';
                        }
                        
                        grandTotalAll += buildingTotalCo2;
                    });

                    const grandTotalAllEl = document.getElementById('grand_total_all');
                    if (grandTotalAllEl) {
                        grandTotalAllEl.textContent = grandTotalAll > 0 ? grandTotalAll.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : '0.00';
                    }

                } catch (e) {
                    console.error("Totals calculation error:", e);
                }
            };

            try {
                inputs.forEach(input => {
                    input.addEventListener('input', function(e) {
                        let originalValue = this.value;
                        let formatted = formatNumber(originalValue);
                        if (this.value !== formatted) {
                            this.value = formatted;
                        }
                        window.updateTotals();
                    });
                });

                window.updateTotals();
            } catch (e) {
                console.error("Matrix logic error:", e);
            }
        });
        

            // Matrix Logic for extra tables
            window.formatInputAndCalcRow = function(input, rowClass, totalId) {
                let v = input.value.replace(/[^0-9.]/g, '');
                if (v === '') {
                    input.value = '';
                } else {
                    let p = v.split('.');
                    p[0] = p[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                    input.value = p.join('.');
                }

                let total = 0;
                document.querySelectorAll('.' + rowClass).forEach(el => {
                    let val = parseFloat(el.value.replace(/,/g, ''));
                    if (!isNaN(val)) total += val;
                });

                const totalEl = document.getElementById(totalId);
                if (totalEl) {
                    if (input.placeholder && input.placeholder.includes('.')) {
                        let decimals = input.placeholder.split('.')[1].length;
                        totalEl.textContent = total.toLocaleString('en-US', { minimumFractionDigits: decimals, maximumFractionDigits: decimals });
                    } else {
                        totalEl.textContent = total.toLocaleString('en-US');
                    }
                }
            };
            
            document.querySelectorAll('[class*="row-calc-"]').forEach(input => {
                if (input.value) {
                    const event = new Event('input', { bubbles: true });
                    input.dispatchEvent(event);
                }
            });

        window.reloadPage = function() {
            const month = document.getElementById('period_month').value;
            const year = document.getElementById('period_year').value;
            window.location.href = `{{ route('data-entry.index') }}?month=${month}&year=${year}`;
        }
    </script>

    <!-- Standalone Keyboard Navigation Script (Bulletproof) -->
    <script>
        (function() {
            // 1. Global Enter Key Prevention
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    // Let buttons and textareas work normally
                    if (e.target.tagName === 'BUTTON' || e.target.type === 'submit' || e.target.tagName === 'TEXTAREA') {
                        return;
                    }
                    e.preventDefault();
                }
            }, true); // Use capture phase to guarantee interception

            // 2. Matrix Arrow & Enter Navigation
            document.addEventListener('keydown', function(e) {
                if (!e.target.classList.contains('matrix-input')) return;
                
                const input = e.target;
                let targetInput = null;

                // Helper to find input at grid coordinates
                const findInput = (rowOffset, colOffset) => {
                    const td = input.closest('td');
                    const tr = input.closest('tr');
                    const tbody = input.closest('tbody');
                    if (!td || !tr || !tbody) return null;

                    const rows = Array.from(tbody.querySelectorAll('tr'));
                    const currentRowIdx = rows.indexOf(tr);
                    
                    const cells = Array.from(tr.querySelectorAll('td'));
                    const currentColIdx = cells.indexOf(td);

                    const targetRow = rows[currentRowIdx + rowOffset];
                    if (targetRow) {
                        const targetCells = Array.from(targetRow.querySelectorAll('td'));
                        const targetCell = targetCells[currentColIdx + colOffset];
                        if (targetCell) {
                            return targetCell.querySelector('.matrix-input');
                        }
                    }
                    return null;
                };

                switch (e.key) {
                    case 'ArrowRight':
                        if (input.selectionStart === input.value.length) {
                            targetInput = findInput(0, 1);
                        }
                        break;
                    case 'ArrowLeft':
                        if (input.selectionEnd === 0) {
                            targetInput = findInput(0, -1);
                        }
                        break;
                    case 'ArrowDown':
                        targetInput = findInput(1, 0);
                        break;
                    case 'ArrowUp':
                        targetInput = findInput(-1, 0);
                        break;
                    case 'Enter':
                        if (e.shiftKey) {
                            targetInput = findInput(-1, 0); // Up
                        } else {
                            targetInput = findInput(1, 0); // Down
                        }
                        break;
                }

                if (targetInput) {
                    e.preventDefault();
                    targetInput.focus();
                    setTimeout(() => targetInput.select(), 10);
                }
            });

            // 3. Excel-like Paste Handler (Auto-fill columns/rows)
            document.addEventListener('paste', function(e) {
                if (!e.target.classList.contains('matrix-input')) return;
                
                const clipboardData = e.clipboardData || window.clipboardData;
                if (!clipboardData) return;
                
                const pastedText = clipboardData.getData('Text');
                if (!pastedText) return;
                
                // If it contains tabs or newlines, it's a tabular paste from Excel
                if (pastedText.includes('\t') || pastedText.includes('\n')) {
                    e.preventDefault(); // Stop standard single-input paste
                    
                    const rows = pastedText.split(/\r?\n/);
                    const startInput = e.target;
                    const startTd = startInput.closest('td');
                    const startTr = startInput.closest('tr');
                    const tbody = startInput.closest('tbody');
                    if (!startTd || !startTr || !tbody) return;
                    
                    const trs = Array.from(tbody.querySelectorAll('tr'));
                    const startRowIdx = trs.indexOf(startTr);
                    
                    const tds = Array.from(startTr.querySelectorAll('td'));
                    const startColIdx = tds.indexOf(startTd);
                    
                    rows.forEach((rowStr, rOffset) => {
                        // Skip empty trailing newline typical in Excel copies
                        if (!rowStr.trim() && rOffset === rows.length - 1) return; 
                        
                        const cols = rowStr.split('\t');
                        const targetTr = trs[startRowIdx + rOffset];
                        
                        if (targetTr) {
                            const targetTds = Array.from(targetTr.querySelectorAll('td'));
                            cols.forEach((colStr, cOffset) => {
                                const targetTd = targetTds[startColIdx + cOffset];
                                if (targetTd) {
                                    const input = targetTd.querySelector('.matrix-input');
                                    if (input) {
                                        // Clean formatting (keep digits and decimal point)
                                        let cleanVal = colStr.replace(/[^0-9.]/g, '');
                                        if (cleanVal) {
                                            // Apply thousand separators
                                            let parts = cleanVal.split('.');
                                            parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                                            input.value = parts.join('.');
                                        } else {
                                            input.value = '';
                                        }
                                    }
                                }
                            });
                        }
                    });
                    
                    // Force total recalculation after massive paste
                    if (typeof window.updateTotals === 'function') {
                        window.updateTotals();
                    }
                }
            });
        })();
        // Setup AJAX for factors
        function setupAjaxFactors() {
            const elCoal = document.getElementById('inp_coal_factor');
            const elRen = document.getElementById('inp_renewable_factor');
            const elPv = document.getElementById('inp_pv_factor');
            const elBoiler = document.getElementById('inp_boiler_factor');
            
            function saveElectricity() {
                if(!elCoal || !elRen || !elPv) return;
                const formData = new FormData();
                formData.append('_token', '{{ csrf_token() }}');
                formData.append('month', '{{ $selectedMonth }}');
                formData.append('year', '{{ $selectedYear }}');
                formData.append('coal_factor', elCoal.value);
                formData.append('renewable_factor', elRen.value);
                formData.append('pv_factor', elPv.value);
                
                fetch('{{ route("data-entry.update-electricity-factors") }}', {
                    method: 'POST',
                    body: formData,
                    headers: { 'Accept': 'application/json' }
                }).then(() => {
                    [elCoal, elRen, elPv].forEach(el => {
                        el.style.backgroundColor = '#e6fffa';
                        setTimeout(() => el.style.backgroundColor = '', 1000);
                    });
                    
                    const elecMatrix = document.getElementById('electricity-matrix');
                    if (elecMatrix) {
                        elecMatrix.dataset.coalFactor = elCoal.value;
                        if (typeof calcElectricityMatrix === 'function') calcElectricityMatrix();
                    }
                    const solarMatrix = document.getElementById('solar-matrix');
                    if (solarMatrix) {
                        solarMatrix.dataset.factor = elPv.value;
                        if (typeof calcSolarMatrix === 'function') calcSolarMatrix();
                    }
                });
            }
            
            if(elCoal) elCoal.addEventListener('change', saveElectricity);
            if(elRen) elRen.addEventListener('change', saveElectricity);
            if(elPv) elPv.addEventListener('change', saveElectricity);
            
            if(elBoiler) {
                elBoiler.addEventListener('change', function() {
                    const val = parseFloat(elBoiler.value) || 0;
                    const tco2ePerLiter = val / 1000;
                    
                    const formData = new FormData();
                    formData.append('_token', '{{ csrf_token() }}');
                    formData.append('month', '{{ $selectedMonth }}');
                    formData.append('year', '{{ $selectedYear }}');
                    formData.append('boiler_factor', tco2ePerLiter);
                    
                    fetch('{{ route("data-entry.update-diesel-factors") }}', {
                        method: 'POST',
                        body: formData,
                        headers: { 'Accept': 'application/json' }
                    }).then(() => {
                        elBoiler.style.backgroundColor = '#e6fffa';
                        setTimeout(() => elBoiler.style.backgroundColor = '', 1000);
                        
                        const dieselMatrix = document.getElementById('diesel-matrix');
                        if (dieselMatrix) {
                            dieselMatrix.dataset.boilerFactor = tco2ePerLiter;
                            if (typeof calcDieselMatrix === 'function') calcDieselMatrix();
                        }
                    });
                });
            }
        }
        setupAjaxFactors();
        
        function calcElectricityMatrix() {
            const matrix = document.getElementById('electricity-matrix');
            if (!matrix) return;
            const coalFactor = parseFloat(matrix.dataset.coalFactor) || 0;
            
            const inpC1Get = document.getElementById('inp_c1_get');
            const inpC2Get = document.getElementById('inp_c2_get');
            const getC1 = inpC1Get ? parseFloat(inpC1Get.value.replace(/,/g, '')) || 0 : 0;
            const getC2 = inpC2Get ? parseFloat(inpC2Get.value.replace(/,/g, '')) || 0 : 0;
            const getMap = { 'c1': getC1, 'c2': getC2, 'other': 0 };
            
            const headerTotalGet = document.getElementById('header_total_get');
            if(headerTotalGet) headerTotalGet.textContent = (getC1 + getC2).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            
            const inputs = Array.from(matrix.querySelectorAll('.input-purchased-elec'));
            const percentCell = matrix.querySelectorAll('.cell-percentage');
            const adjCell = matrix.querySelectorAll('.cell-adjusted');
            const coalCell = matrix.querySelectorAll('.cell-purchased-coal');
            const co2Cell = matrix.querySelectorAll('.cell-co2-coal');
            
            const inpC1TotPurchased = document.getElementById('inp_c1_total_purchased');
            const inpC2TotPurchased = document.getElementById('inp_c2_total_purchased');
            const c1TotPurchased = inpC1TotPurchased ? parseFloat(inpC1TotPurchased.value.replace(/,/g, '')) || 0 : 0;
            const c2TotPurchased = inpC2TotPurchased ? parseFloat(inpC2TotPurchased.value.replace(/,/g, '')) || 0 : 0;
            const campusPurchased = { 'c1': c1TotPurchased, 'c2': c2TotPurchased, 'other': 0 };
            
            let total = 0;
            inputs.forEach(input => {
                const val = parseFloat(input.value.replace(/,/g, '')) || 0;
                total += val;
            });
            
            const elTotal = document.getElementById('total_purchased_elec');
            if(elTotal) elTotal.textContent = total.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            
            const elGrandTotal = document.getElementById('grand_total_purchased_elec');
            if(elGrandTotal) elGrandTotal.textContent = (c1TotPurchased + c2TotPurchased).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            
            let totalCoal = 0;
            let totalCo2 = 0;
            let campusCoal = { 'c1': 0, 'c2': 0, 'other': 0 };
            
            inputs.forEach((input, index) => {
                const val = parseFloat(input.value.replace(/,/g, '')) || 0;
                const campus = input.dataset.campus;
                const campusTot = campusPurchased[campus] || 0;
                
                let pct = 0;
                if(campusTot > 0) {
                    pct = val / campusTot;
                }
                
                if (percentCell[index]) percentCell[index].textContent = pct.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                
                const adjusted = val - (pct * getMap[campus]);
                if (adjCell[index]) adjCell[index].textContent = adjusted.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                
                let campTotalCoal = campusTot - getMap[campus];
                if(campTotalCoal < 0) campTotalCoal = 0; 
                
                const coal = pct * campTotalCoal;
                
                if (coalCell[index]) coalCell[index].textContent = coal.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                
                const co2 = coal * coalFactor;
                if (co2Cell[index]) co2Cell[index].textContent = co2.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                
                totalCoal += coal;
                totalCo2 += co2;
                if(campus) campusCoal[campus] += coal;
            });
            
            const elC1TotCoal = document.getElementById('c1_total_coal');
            if (elC1TotCoal) elC1TotCoal.textContent = campusCoal['c1'].toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            
            const elC2TotCoal = document.getElementById('c2_total_coal');
            if (elC2TotCoal) elC2TotCoal.textContent = campusCoal['c2'].toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            
            const elGrandTotalCoal = document.getElementById('grand_total_coal');
            if(elGrandTotalCoal) elGrandTotalCoal.textContent = totalCoal.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            
            const totCoalEl = document.getElementById('total_purchased_coal');
            if(totCoalEl) totCoalEl.textContent = totalCoal.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            
            const totCo2El = document.getElementById('total_co2_coal');
            if(totCo2El) totCo2El.textContent = totalCo2.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        }
    
        function calcSolarMatrix() {
            const matrix = document.getElementById('solar-matrix');
            if (!matrix) return;
            const factor = parseFloat(matrix.dataset.factor) || 0;
            
            const inputs = matrix.querySelectorAll('.input-solar');
            const co2Cells = matrix.querySelectorAll('.cell-solar-co2');
            
            let total = 0;
            let totalCo2 = 0;
            
            inputs.forEach((input, index) => {
                const val = parseFloat(input.value) || 0;
                total += val;
                
                const co2 = val * factor;
                totalCo2 += co2;
                
                if (co2Cells[index]) co2Cells[index].textContent = co2.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            });
            
            const elTotal = document.getElementById('total_solar_elec');
            if(elTotal) elTotal.textContent = total.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            
            const elCo2 = document.getElementById('total_solar_co2');
            if(elCo2) elCo2.textContent = totalCo2.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        }
        
        // Initial calc
        setTimeout(() => {
            calcElectricityMatrix();
            calcSolarMatrix();
        }, 500);
    </script>
    @endpush
</x-app-layout>
