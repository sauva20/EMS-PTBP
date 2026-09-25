<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Accumulative Data') }} - {{ $year }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-[1600px] mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="GET" action="{{ route('reports.accumulative') }}" class="flex gap-4 items-end">
                    <div>
                        <x-input-label for="year" value="Year" />
                        <select name="year" id="year" class="mt-1 block w-48 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            @foreach([2024, 2025, 2026] as $y)
                                <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                            @endforeach
                        </select>
                    </div>
                    <x-primary-button type="submit">Filter</x-primary-button>
                </form>
            </div>

            @php
                $colors = [
                    'DIESEL' => ['bg' => 'bg-blue-500', 'text' => 'text-white', 'border' => 'border-blue-600', 'light' => 'bg-blue-200'],
                    'LPG' => ['bg' => 'bg-yellow-400', 'text' => 'text-black', 'border' => 'border-yellow-500', 'light' => 'bg-yellow-200'],
                    'ELECTRICITY' => ['bg' => 'bg-green-500', 'text' => 'text-white', 'border' => 'border-green-600', 'light' => 'bg-green-200'],
                    'PETROL' => ['bg' => 'bg-purple-500', 'text' => 'text-white', 'border' => 'border-purple-600', 'light' => 'bg-purple-200'],
                ];
            @endphp

            @foreach($reportData as $data)
            @php
                $color = $colors[$data['title']] ?? ['bg' => 'bg-gray-500', 'text' => 'text-white', 'border' => 'border-gray-600', 'light' => 'bg-gray-200'];
            @endphp
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 overflow-x-auto">
                    <div class="flex gap-6 min-w-max">
                        
                        <!-- Table 1: Consumption by Building -->
                        <div>
                            <table class="w-full text-xs border-collapse">
                                <thead>
                                    <tr>
                                        <th colspan="{{ $buildings->count() + 2 }}" class="px-2 py-2 border {{ $color['border'] }} {{ $color['bg'] }} {{ $color['text'] }} text-center font-bold uppercase">
                                            {{ $data['title'] }} CONSUMPTION ({{ $data['unit'] }})
                                        </th>
                                    </tr>
                                    <tr class="bg-gray-100">
                                        <th class="px-2 py-1 border border-gray-400 text-center font-bold">Month / Plant</th>
                                        @foreach($buildings as $building)
                                            <th class="px-2 py-1 border border-gray-400 text-center font-bold">{{ $building->name }}</th>
                                        @endforeach
                                        <th class="px-2 py-1 border border-gray-400 text-center font-bold w-20">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $bldgTotals = array_fill_keys($buildings->pluck('name')->toArray(), 0);
                                        $grandUsageTotal = 0;
                                    @endphp
                                    @foreach($data['matrix'] as $m => $row)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-2 py-1 border border-gray-300 font-bold text-center">{{ $row['month'] }}</td>
                                            @foreach($buildings as $building)
                                                @php $bldgTotals[$building->name] += $row['buildings'][$building->name]; @endphp
                                                <td class="px-2 py-1 border border-gray-300 text-right">
                                                    {{ $row['buildings'][$building->name] > 0 ? number_format($row['buildings'][$building->name], 2) : '-' }}
                                                </td>
                                            @endforeach
                                            @php $grandUsageTotal += $row['usage_total']; @endphp
                                            <td class="px-2 py-1 border border-gray-300 text-right font-bold">
                                                {{ $row['usage_total'] > 0 ? number_format($row['usage_total'], 2) : '-' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr class="bg-gray-100">
                                        <td class="px-2 py-1 border border-gray-400 font-bold text-center">Total</td>
                                        @foreach($buildings as $building)
                                            <td class="px-2 py-1 border border-gray-400 text-right font-bold">
                                                {{ $bldgTotals[$building->name] > 0 ? number_format($bldgTotals[$building->name], 2) : '-' }}
                                            </td>
                                        @endforeach
                                        <td class="px-2 py-1 border border-gray-400 text-right font-bold">
                                            {{ $grandUsageTotal > 0 ? number_format($grandUsageTotal, 2) : '-' }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Table 2: Consumption by Zone -->
                        <div>
                            <table class="w-full text-xs border-collapse">
                                <thead>
                                    <tr>
                                        <th colspan="{{ $zones->count() + 2 }}" class="px-2 py-2 border {{ $color['border'] }} {{ $color['bg'] }} {{ $color['text'] }} text-center font-bold uppercase">
                                            {{ $data['title'] }} CONSUMPTION ({{ $data['unit'] }})
                                        </th>
                                    </tr>
                                    <tr class="bg-gray-100">
                                        <th class="px-2 py-1 border border-gray-400 text-center font-bold">Month / Plant</th>
                                        @foreach($zones as $zone)
                                            <th class="px-2 py-1 border border-gray-400 text-center font-bold">{{ $zone }}</th>
                                        @endforeach
                                        <th class="px-2 py-1 border border-gray-400 text-center font-bold w-20">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $zoneTotals = array_fill_keys($zones->toArray(), 0);
                                        $grandUsageTotal = 0;
                                    @endphp
                                    @foreach($data['matrix'] as $m => $row)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-2 py-1 border border-gray-300 font-bold text-center">{{ $row['month'] }}</td>
                                            @foreach($zones as $zone)
                                                @php $zoneTotals[$zone] += $row['zones'][$zone]; @endphp
                                                <td class="px-2 py-1 border border-gray-300 text-right">
                                                    {{ $row['zones'][$zone] > 0 ? number_format($row['zones'][$zone], 2) : '-' }}
                                                </td>
                                            @endforeach
                                            @php $grandUsageTotal += $row['usage_total']; @endphp
                                            <td class="px-2 py-1 border border-gray-300 text-right font-bold">
                                                {{ $row['usage_total'] > 0 ? number_format($row['usage_total'], 2) : '-' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr class="bg-gray-100">
                                        <td class="px-2 py-1 border border-gray-400 font-bold text-center">Total</td>
                                        @foreach($zones as $zone)
                                            <td class="px-2 py-1 border border-gray-400 text-right font-bold">
                                                {{ $zoneTotals[$zone] > 0 ? number_format($zoneTotals[$zone], 2) : '-' }}
                                            </td>
                                        @endforeach
                                        <td class="px-2 py-1 border border-gray-400 text-right font-bold">
                                            {{ $grandUsageTotal > 0 ? number_format($grandUsageTotal, 2) : '-' }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Table 3: CO2 Emission -->
                        <div>
                            <table class="w-full text-xs border-collapse">
                                <thead>
                                    <tr>
                                        <th colspan="{{ $buildings->count() + 2 }}" class="px-2 py-2 border {{ $color['border'] }} {{ $color['light'] }} text-black text-center font-bold uppercase">
                                            CO2 EMISSION ({{ $data['title'] }}) (kg)
                                        </th>
                                    </tr>
                                    <tr class="bg-gray-100">
                                        <th class="px-2 py-1 border border-gray-400 text-center font-bold">Month / Plant</th>
                                        @foreach($buildings as $building)
                                            <th class="px-2 py-1 border border-gray-400 text-center font-bold">{{ $building->name }}</th>
                                        @endforeach
                                        <th class="px-2 py-1 border border-gray-400 text-center font-bold w-20">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $co2Totals = array_fill_keys($buildings->pluck('name')->toArray(), 0);
                                        $grandCo2Total = 0;
                                    @endphp
                                    @foreach($data['matrix'] as $m => $row)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-2 py-1 border border-gray-300 font-bold text-center">{{ $row['month'] }}</td>
                                            @foreach($buildings as $building)
                                                @php $co2Totals[$building->name] += $row['co2_buildings'][$building->name]; @endphp
                                                <td class="px-2 py-1 border border-gray-300 text-right">
                                                    {{ $row['co2_buildings'][$building->name] > 0 ? number_format($row['co2_buildings'][$building->name], 2) : '-' }}
                                                </td>
                                            @endforeach
                                            @php $grandCo2Total += $row['co2_total']; @endphp
                                            <td class="px-2 py-1 border border-gray-300 text-right font-bold">
                                                {{ $row['co2_total'] > 0 ? number_format($row['co2_total'], 2) : '-' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr class="bg-gray-100">
                                        <td class="px-2 py-1 border border-gray-400 font-bold text-center">Total</td>
                                        @foreach($buildings as $building)
                                            <td class="px-2 py-1 border border-gray-400 text-right font-bold">
                                                {{ $co2Totals[$building->name] > 0 ? number_format($co2Totals[$building->name], 2) : '-' }}
                                            </td>
                                        @endforeach
                                        <td class="px-2 py-1 border border-gray-400 text-right font-bold">
                                            {{ $grandCo2Total > 0 ? number_format($grandCo2Total, 2) : '-' }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
            @endforeach

        </div>
    </div>
</x-app-layout>
