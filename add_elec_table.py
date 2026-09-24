import re

with open('resources/views/data-entry/index.blade.php', 'r', encoding='utf-8') as f:
    content = f.read()

elec_tables = """
    <!-- ELECTRICITY BREAKDOWN TABLES -->
    <div class="overflow-x-auto w-full border border-gray-200 rounded-xl shadow-sm mt-6 mb-8">
        <h3 class="bg-gray-100 px-4 py-2 font-bold text-gray-700">CO2 Emission for Electricity (Supply: TNB)</h3>
        <table class="w-full text-sm text-left whitespace-nowrap" id="electricity-matrix" data-coal-factor="{{ $coalFactor }}">
            <thead class="bg-[#009B77] text-white">
                <tr>
                    <th class="px-4 py-3 border-b border-r border-[#008264] text-xs font-bold tracking-wider"></th>
                    @foreach($buildings as $building)
                        <th class="px-3 py-3 border-b border-r border-[#008264] text-center text-xs font-bold uppercase tracking-wider">{{ $building->name }}</th>
                    @endforeach
                    <th class="px-4 py-3 border-b border-[#008264] text-right text-xs font-bold uppercase tracking-wider">Total</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                <tr class="border-b border-gray-100">
                    <td class="px-4 py-2 border-r border-gray-200 font-medium text-gray-900">GET (kWh)</td>
                    @foreach($buildings as $building)
                        <td class="px-2 py-2 border-r border-gray-100 text-right text-gray-500 bg-gray-50">
                            <!-- Input for GET per building if needed in the future, currently just display -->
                        </td>
                    @endforeach
                    <td class="px-4 py-2 text-right bg-slate-50 font-bold text-gray-800"></td>
                </tr>
                <tr class="hover:bg-[#009B77]/5 transition-colors border-b border-gray-100">
                    <td class="px-4 py-2 border-r border-gray-200 font-medium text-gray-900">Purchased electricity (kWh)</td>
                    @php $elecSource = $sources->firstWhere('name', 'Purchased Electricity'); @endphp
                    @foreach($buildings as $building)
                        <td class="px-2 py-2 border-r border-gray-100 text-right">
                            @if($elecSource)
                            <input type="text" inputmode="decimal"
                                name="emissions[{{ $building->id }}][{{ $elecSource->id }}]" 
                                class="matrix-input w-full px-2 py-1 text-right text-sm border-gray-200 rounded input-purchased-elec"
                                value="{{ isset($existingEmissions[$building->id][$elecSource->id]) ? number_format($existingEmissions[$building->id][$elecSource->id], 2, '.', '') : '' }}"
                                placeholder="0"
                                oninput="calcElectricityMatrix()"
                            >
                            @endif
                        </td>
                    @endforeach
                    <td class="px-4 py-2 text-right bg-slate-50 font-bold text-gray-800" id="total_purchased_elec">0.00</td>
                </tr>
                <tr class="border-b border-gray-100">
                    <td class="px-4 py-2 border-r border-gray-200 font-medium text-gray-900">Total purchased electricity (kWh)</td>
                    @foreach($buildings as $building)
                        <td class="px-2 py-2 border-r border-gray-100 text-right bg-gray-50 cell-total-purchased">0.00</td>
                    @endforeach
                    <td class="px-4 py-2 text-right bg-slate-50 font-bold text-gray-800" id="grand_total_purchased_elec">0.00</td>
                </tr>
                <tr class="border-b border-gray-100">
                    <td class="px-4 py-2 border-r border-gray-200 font-medium text-gray-900">Percentage by building (%)</td>
                    @foreach($buildings as $building)
                        <td class="px-2 py-2 border-r border-gray-100 text-right bg-gray-50 cell-percentage">0.00</td>
                    @endforeach
                    <td class="px-4 py-2 text-right bg-slate-50 font-bold text-gray-800"></td>
                </tr>
                <tr class="border-b border-gray-100">
                    <td class="px-4 py-2 border-r border-gray-200 font-medium text-gray-900">Adjusted (kWh)</td>
                    @foreach($buildings as $building)
                        <td class="px-2 py-2 border-r border-gray-100 text-right bg-gray-50 cell-adjusted">0.00</td>
                    @endforeach
                    <td class="px-4 py-2 text-right bg-slate-50 font-bold text-gray-800"></td>
                </tr>
                <tr class="border-b border-gray-100">
                    <td class="px-4 py-2 border-r border-gray-200 font-medium text-gray-900">Purchased electricity- Coal (kWh)</td>
                    @foreach($buildings as $building)
                        <td class="px-2 py-2 border-r border-gray-100 text-right bg-gray-50 cell-purchased-coal">0.00</td>
                    @endforeach
                    <td class="px-4 py-2 text-right bg-slate-50 font-bold text-gray-800" id="total_purchased_coal">0.00</td>
                </tr>
                <tr class="border-b border-gray-100 bg-green-50/50">
                    <td class="px-4 py-2 border-r border-gray-200 font-medium text-green-800">CO2 Emission (Coal)</td>
                    @foreach($buildings as $building)
                        <td class="px-2 py-2 border-r border-gray-100 text-right text-green-700 font-medium cell-co2-coal">0.00</td>
                    @endforeach
                    <td class="px-4 py-2 text-right bg-green-100 font-bold text-green-800" id="total_co2_coal">0.00</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="overflow-x-auto w-full border border-gray-200 rounded-xl shadow-sm mt-6 mb-8">
        <h3 class="bg-gray-100 px-4 py-2 font-bold text-gray-700">CO2 Emission for Electricity (Supply: Self-generated PV)</h3>
        <table class="w-full text-sm text-left whitespace-nowrap" id="solar-matrix" data-factor="{{ $pvFactor }}">
            <thead class="bg-[#009B77] text-white">
                <tr>
                    <th class="px-4 py-3 border-b border-r border-[#008264] text-xs font-bold tracking-wider"></th>
                    @foreach($buildings as $building)
                        <th class="px-3 py-3 border-b border-r border-[#008264] text-center text-xs font-bold uppercase tracking-wider">{{ $building->name }}</th>
                    @endforeach
                    <th class="px-4 py-3 border-b border-[#008264] text-right text-xs font-bold uppercase tracking-wider">Total</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                <tr class="hover:bg-[#009B77]/5 transition-colors border-b border-gray-100">
                    <td class="px-4 py-2 border-r border-gray-200 font-medium text-gray-900">Solar Generated Electricity (kWh)</td>
                    @php $solarSource = $sources->firstWhere('name', 'Self-generated PV Electricity'); @endphp
                    @foreach($buildings as $building)
                        <td class="px-2 py-2 border-r border-gray-100 text-right">
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
                    <td class="px-4 py-2 text-right bg-slate-50 font-bold text-gray-800" id="total_solar_elec">0.00</td>
                </tr>
                <tr class="border-b border-gray-100 bg-green-50/50">
                    <td class="px-4 py-2 border-r border-gray-200 font-medium text-green-800">CO2 Emission for Self-PV</td>
                    @foreach($buildings as $building)
                        <td class="px-2 py-2 border-r border-gray-100 text-right text-green-700 font-medium cell-solar-co2">0.00</td>
                    @endforeach
                    <td class="px-4 py-2 text-right bg-green-100 font-bold text-green-800" id="total_solar_co2">0.00</td>
                </tr>
            </tbody>
        </table>
    </div>
"""

# Only insert if not already present
if '<!-- ELECTRICITY BREAKDOWN TABLES -->' not in content:
    # Insert right before <!-- NEW SEPARATE TABLES -->
    new_content = content.replace('<!-- NEW SEPARATE TABLES -->\n<div class="mt-8 space-y-8 mb-8">', elec_tables + '\n\n<!-- NEW SEPARATE TABLES -->\n<div class="mt-8 space-y-8 mb-8">')
    with open('resources/views/data-entry/index.blade.php', 'w', encoding='utf-8') as f:
        f.write(new_content)
    print("Added electricity tables")
else:
    print("Electricity tables already exist")
