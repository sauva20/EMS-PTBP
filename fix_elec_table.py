import re

with open('resources/views/data-entry/index.blade.php', 'r', encoding='utf-8') as f:
    content = f.read()

new_elec_table = """
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
                            <input type="text" id="inp_c1_get" class="w-full text-center border-0 bg-transparent font-bold focus:ring-0 p-0" placeholder="0.00" value="5,280,000.00" oninput="calcElectricityMatrix()">
                        </th>
                    @endif
                    @if($c2Count > 0)
                        <!-- Campus 2 GET row -->
                        <th colspan="{{ ceil($c2Count / 2) }}" class="px-4 py-1 border border-gray-300 font-bold text-center bg-gray-50">GET (kWh)</th>
                        <th colspan="{{ floor($c2Count / 2) }}" class="px-4 py-1 border border-gray-300 font-bold text-center bg-gray-50">
                            <input type="text" id="inp_c2_get" class="w-full text-center border-0 bg-transparent font-bold focus:ring-0 p-0" placeholder="0.00" value="4,137,000.00" oninput="calcElectricityMatrix()">
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
                <tr>
                    <td class="px-4 py-2 border border-gray-300 font-medium text-gray-900">Total purchased electricity (kWh)</td>
                    @if($c1Count > 0)
                        <td colspan="{{ $c1Count }}" class="px-4 py-2 border border-gray-300 text-center bg-gray-50" id="c1_total_purchased">0.00</td>
                    @endif
                    @if($c2Count > 0)
                        <td colspan="{{ $c2Count }}" class="px-4 py-2 border border-gray-300 text-center bg-gray-50" id="c2_total_purchased">0.00</td>
                    @endif
                    @if($otherBuildings->count() > 0)
                        <td colspan="{{ $otherBuildings->count() }}" class="px-4 py-2 border border-gray-300 text-center bg-gray-50"></td>
                    @endif
                    <td class="px-4 py-2 text-right border border-gray-300 font-bold text-gray-800" id="grand_total_purchased_elec">0.00</td>
                </tr>
                <tr>
                    <td class="px-4 py-2 border border-gray-300 font-medium text-gray-900">Percentage by building (%)</td>
                    @foreach($orderedBuildings as $building)
                        <td class="px-2 py-2 border border-gray-300 text-right bg-gray-50 cell-percentage">0.00</td>
                    @endforeach
                    <td class="px-4 py-2 text-right border border-gray-300 font-bold text-gray-800"></td>
                </tr>
                <tr>
                    <td class="px-4 py-2 border border-gray-300 font-medium text-gray-900">Adjusted (kWh)</td>
                    @foreach($orderedBuildings as $building)
                        <td class="px-2 py-2 border border-gray-300 text-right bg-gray-50 cell-adjusted">0.00</td>
                    @endforeach
                    <td class="px-4 py-2 text-right border border-gray-300 font-bold text-gray-800"></td>
                </tr>
                <tr>
                    <td class="px-4 py-2 border border-gray-300 font-medium text-gray-900">Purchased electricity- Coal (kWh)</td>
                    @if($c1Count > 0)
                        <td colspan="{{ $c1Count }}" class="px-4 py-2 border border-gray-300 text-center bg-gray-50" id="c1_total_coal">0.00</td>
                    @endif
                    @if($c2Count > 0)
                        <td colspan="{{ $c2Count }}" class="px-4 py-2 border border-gray-300 text-center bg-gray-50" id="c2_total_coal">0.00</td>
                    @endif
                    @if($otherBuildings->count() > 0)
                        <td colspan="{{ $otherBuildings->count() }}" class="px-4 py-2 border border-gray-300 text-center bg-gray-50"></td>
                    @endif
                    <td class="px-4 py-2 text-right border border-gray-300 font-bold text-gray-800" id="grand_total_coal">0.00</td>
                </tr>
                <tr>
                    <td class="px-4 py-2 border border-gray-300 font-medium text-gray-900">Purchased electricity- Coal (kWh)</td>
                    @foreach($orderedBuildings as $building)
                        <td class="px-2 py-2 border border-gray-300 text-right bg-gray-50 cell-purchased-coal">0.00</td>
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
"""

start_str = '<!-- ELECTRICITY BREAKDOWN TABLES -->'
end_str = '<!-- NEW SEPARATE TABLES -->'
parts = content.split(start_str)
if len(parts) > 1:
    before = parts[0]
    after = parts[1].split(end_str, 1)[1] if len(parts[1].split(end_str, 1)) > 1 else ''
    
    js_update = """
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
            
            let total = 0;
            let campusPurchased = { 'c1': 0, 'c2': 0, 'other': 0 };
            
            inputs.forEach(input => {
                const val = parseFloat(input.value.replace(/,/g, '')) || 0;
                total += val;
                const campus = input.dataset.campus;
                if(campus) campusPurchased[campus] += val;
            });
            
            const elTotal = document.getElementById('total_purchased_elec');
            if(elTotal) elTotal.textContent = total.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            
            const elGrandTotal = document.getElementById('grand_total_purchased_elec');
            if(elGrandTotal) elGrandTotal.textContent = total.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            
            const elC1TotPurchased = document.getElementById('c1_total_purchased');
            if (elC1TotPurchased) elC1TotPurchased.textContent = campusPurchased['c1'].toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            
            const elC2TotPurchased = document.getElementById('c2_total_purchased');
            if (elC2TotPurchased) elC2TotPurchased.textContent = campusPurchased['c2'].toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            
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
    """
    
    import re
    after = re.sub(r'function calcElectricityMatrix\(\)\s*\{[\s\S]*?function calcSolarMatrix', js_update + '\n        function calcSolarMatrix', after)
    
    new_content = before + new_elec_table + '\n<!-- NEW SEPARATE TABLES -->' + after
    with open('resources/views/data-entry/index.blade.php', 'w', encoding='utf-8') as f:
        f.write(new_content)
    print("Updated tables and JS")
else:
    print("Could not find boundaries")
