<!-- NEW SEPARATE TABLES -->
<div class="mt-8 space-y-8">
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
    @endphp

    @if($dieselSource)
    <div class="overflow-x-auto w-full border border-gray-200 rounded-xl shadow-sm">
        <h3 class="bg-gray-100 px-4 py-2 font-bold text-gray-700">Diesel Breakdown</h3>
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
    
    @if($petrolSource)
    <div class="overflow-x-auto w-full border border-gray-200 rounded-xl shadow-sm mt-6">
        <h3 class="bg-gray-100 px-4 py-2 font-bold text-gray-700">Petrol Breakdown</h3>
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
                @foreach([$os, $of] as $cat)
                @if($cat)
                <tr class="hover:bg-[#009B77]/5 transition-colors border-b border-gray-100">
                    <td class="px-4 py-3 border-r border-gray-200 font-medium text-gray-900">{{ $cat->name }} (L)</td>
                    
                    @foreach($buildings as $building)
                    <td class="px-2 py-2 border-r border-gray-100 align-top">
                        <input type="text" inputmode="decimal"
                            name="emissions[{{ $building->id }}][{{ $petrolSource->id }}][{{ $cat->id }}]" 
                            class="matrix-input w-full px-2 py-1.5 text-right text-sm border-gray-200 focus:border-[#009B77] focus:ring-[#009B77] rounded shadow-sm bg-white row-calc-petrol-{{ $cat->id }}"
                            value="{{ isset($existingEmissions[$building->id][$petrolSource->id . '_' . $cat->id]) ? number_format($existingEmissions[$building->id][$petrolSource->id . '_' . $cat->id], 2, '.', ',') : '' }}"
                            placeholder="0.00"
                            oninput="formatInputAndCalcRow(this, 'row-calc-petrol-{{ $cat->id }}', 'total-petrol-{{ $cat->id }}')"
                        >
                    </td>
                    @endforeach
                    
                    <td class="px-4 py-2 text-right bg-slate-50 font-bold text-gray-800" id="total-petrol-{{ $cat->id }}">0.00</td>
                </tr>
                @endif
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
    
    @if($refrigerantSource)
    <div class="overflow-x-auto w-full border border-gray-200 rounded-xl shadow-sm mt-6">
        <h3 class="bg-gray-100 px-4 py-2 font-bold text-gray-700">Refrigerant Breakdown</h3>
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
                @foreach([$r22, $r407c] as $cat)
                @if($cat)
                <tr class="hover:bg-[#009B77]/5 transition-colors border-b border-gray-100">
                    <td class="px-4 py-3 border-r border-gray-200 font-medium text-gray-900">{{ $cat->name }}</td>
                    
                    @foreach($buildings as $building)
                    <td class="px-2 py-2 border-r border-gray-100 align-top">
                        <input type="text" inputmode="decimal"
                            name="emissions[{{ $building->id }}][{{ $refrigerantSource->id }}][{{ $cat->id }}]" 
                            class="matrix-input w-full px-2 py-1.5 text-right text-sm border-gray-200 focus:border-[#009B77] focus:ring-[#009B77] rounded shadow-sm bg-white row-calc-refrig-{{ $cat->id }}"
                            value="{{ isset($existingEmissions[$building->id][$refrigerantSource->id . '_' . $cat->id]) ? number_format($existingEmissions[$building->id][$refrigerantSource->id . '_' . $cat->id], 3, '.', ',') : '' }}"
                            placeholder="0.000"
                            oninput="formatInputAndCalcRow(this, 'row-calc-refrig-{{ $cat->id }}', 'total-refrig-{{ $cat->id }}')"
                        >
                    </td>
                    @endforeach
                    
                    <td class="px-4 py-2 text-right bg-slate-50 font-bold text-gray-800" id="total-refrig-{{ $cat->id }}">0.000</td>
                </tr>
                @endif
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>
