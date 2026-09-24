import re

with open('resources/views/data-entry/index.blade.php', 'r', encoding='utf-8') as f:
    content = f.read()

# We need to replace the Refrigerant table with a new one
refrigerant_block_pattern = re.compile(r'@if\(\$refrigerantSource\).*?<div class=\"overflow-x-auto w-full border border-gray-200 rounded-xl shadow-sm\">.*?<h3 class=\"bg-gray-100 px-4 py-2 font-bold text-gray-700 border-b border-gray-200\">Refrigerant Breakdown</h3>.*?</table>\n        </div>\n        @endif', re.DOTALL)

new_refrigerant_block = '''@if($refrigerantSource)
        <div class="overflow-x-auto w-full border border-gray-200 rounded-xl shadow-sm">
            <h3 class="bg-gray-100 px-4 py-2 font-bold text-gray-700 border-b border-gray-200">Refrigerant Breakdown</h3>
            <table class="w-full text-sm text-left whitespace-nowrap">
                <thead class="bg-[#009B77] text-white">
                    <tr>
                        <th class="px-4 py-3 border-b border-r border-[#008264] text-xs font-bold uppercase tracking-wider w-32">Building</th>
                        <th class="px-4 py-3 border-b border-r border-[#008264] text-right text-xs font-bold uppercase tracking-wider">Weight (kg)</th>
                        <th class="px-4 py-3 border-b border-[#008264] text-left text-xs font-bold uppercase tracking-wider">Type</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @foreach($buildings as $building)
                    @php
                        // Find if this building has an existing refrigerant entry
                        $existingRef = null;
                        $existingUsage = '';
                        foreach([$r22, $r134a, $r407c] as $cat) {
                            if($cat && isset($existingEmissions[$building->id][$refrigerantSource->id . '_' . $cat->id])) {
                                $existingRef = $cat->id;
                                $existingUsage = $existingEmissions[$building->id][$refrigerantSource->id . '_' . $cat->id];
                                break;
                            }
                        }
                    @endphp
                    <tr class="hover:bg-[#009B77]/5 transition-colors border-b border-gray-100">
                        <td class="px-4 py-3 border-r border-gray-200 font-medium text-gray-900 align-middle">{{ $building->name }}</td>
                        <td class="px-2 py-2 border-r border-gray-100 align-middle">
                            <input type="text" inputmode="decimal"
                                name="refrigerant[{{ $building->id }}][weight]" 
                                class="matrix-input w-full px-2 py-1.5 text-right text-sm border-gray-200 focus:border-[#009B77] focus:ring-[#009B77] rounded shadow-sm bg-white"
                                value="{{ $existingUsage !== '' ? number_format($existingUsage, 3, '.', ',') : '' }}"
                                placeholder="0.000"
                                oninput="let v = this.value.replace(/[^0-9.]/g, ''); let p = v.split('.'); p[0] = p[0].replace(/\\B(?=(\\d{3})+(?!\\d))/g, ','); this.value = p.join('.');"
                            >
                        </td>
                        <td class="px-2 py-2 border-gray-100 align-middle">
                            <select name="refrigerant[{{ $building->id }}][type]" class="w-full px-2 py-1.5 text-sm border-gray-200 focus:border-[#009B77] focus:ring-[#009B77] rounded shadow-sm bg-white">
                                <option value="">-- Select Type --</option>
                                @foreach([$r22, $r134a, $r407c] as $cat)
                                    @if($cat)
                                        <option value="{{ $cat->id }}" {{ $existingRef == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif'''

new_content = refrigerant_block_pattern.sub(new_refrigerant_block, content)

if content != new_content:
    with open('resources/views/data-entry/index.blade.php', 'w', encoding='utf-8') as f:
        f.write(new_content)
    print('Updated Refrigerant table in index.blade.php')
else:
    print('Could not find the Refrigerant table block to replace')
