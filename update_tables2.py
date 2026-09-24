with open('resources/views/data-entry/index.blade.php', 'r', encoding='utf-8') as f:
    content = f.read()

start_marker = '<!-- NEW SEPARATE TABLES -->'
end_marker = '<div class="p-4 border-t border-slate-200 bg-slate-50 flex justify-end items-center gap-4 rounded-b-2xl">'

new_tables = r'''<!-- NEW SEPARATE TABLES -->
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
        
        // Single dummy/first building for facility-wide entries
        $mainBuilding = $buildings->first();
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
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-6">
        @if($petrolSource)
        <div class="overflow-x-auto w-full border border-gray-200 rounded-xl shadow-sm">
            <h3 class="bg-gray-100 px-4 py-2 font-bold text-gray-700 border-b border-gray-200">Petrol Breakdown</h3>
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
            <h3 class="bg-gray-100 px-4 py-2 font-bold text-gray-700 border-b border-gray-200">Refrigerant Breakdown</h3>
            <table class="w-full text-sm text-left whitespace-nowrap">
                <thead class="bg-[#009B77] text-white">
                    <tr>
                        <th class="px-4 py-3 border-b border-r border-[#008264] text-xs font-bold uppercase tracking-wider w-32">Source</th>
                        <th class="px-4 py-3 border-b border-[#008264] text-left text-xs font-bold uppercase tracking-wider">Usage (KG)</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @foreach([$r22, $r407c] as $index => $cat)
                    @if($cat)
                    <tr class="hover:bg-[#009B77]/5 transition-colors border-b border-gray-100">
                        @if($index === 0)
                        <td class="px-4 py-3 border-r border-gray-200 font-medium text-gray-900 align-top" rowspan="2">Refrigerant</td>
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
'''

start_idx = content.find(start_marker)
end_idx = content.find(end_marker, start_idx)

if start_idx != -1 and end_idx != -1:
    content = content[:start_idx] + new_tables + '\n                ' + content[end_idx:]
    with open('resources/views/data-entry/index.blade.php', 'w', encoding='utf-8') as f:
        f.write(content)
    print("Done")
else:
    print("Markers not found!")
