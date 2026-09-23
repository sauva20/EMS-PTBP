<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Accumulative Data') }} - {{ $year }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 overflow-x-auto">
                    
                    <div class="mb-4 flex justify-between items-center">
                        <h3 class="text-lg font-medium text-gray-900">CO2 EMISSION (TOTAL) (MT)</h3>
                        <form method="GET" action="{{ route('reports.accumulative') }}" class="flex gap-2">
                            <select name="year" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                @foreach([2024, 2025, 2026] as $y)
                                    <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                                @endforeach
                            </select>
                            <x-primary-button type="submit">Filter</x-primary-button>
                        </form>
                    </div>

                    <table class="min-w-full divide-y divide-gray-200 border border-gray-300">
                        <thead class="bg-gray-100">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-b border-r border-gray-300">Month / Plant</th>
                                
                                <!-- Buildings -->
                                @foreach($buildings as $building)
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-gray-700 uppercase tracking-wider border-b border-gray-300">
                                        {{ $building->name }}
                                    </th>
                                @endforeach
                                <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-gray-700 uppercase tracking-wider border-b border-r border-gray-300">Total Bldgs</th>
                                
                                <!-- Zones -->
                                @foreach($zones as $zone)
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-gray-700 uppercase tracking-wider border-b border-gray-300 bg-gray-50">
                                        {{ $zone }}
                                    </th>
                                @endforeach
                                <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-gray-700 uppercase tracking-wider border-b border-gray-300 bg-gray-200">
                                    Grand Total
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @php
                                $bldgTotals = array_fill_keys($buildings->pluck('name')->toArray(), 0);
                                $zoneTotals = array_fill_keys($zones->toArray(), 0);
                                $grandTotal = 0;
                            @endphp

                            @foreach($matrix as $m => $row)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 border-r border-gray-300">
                                        {{ $row['month'] }}
                                    </td>
                                    
                                    @php $rowBldgTotal = 0; @endphp
                                    @foreach($buildings as $building)
                                        @php
                                            $val = $row['buildings'][$building->name];
                                            $bldgTotals[$building->name] += $val;
                                            $rowBldgTotal += $val;
                                        @endphp
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-500">
                                            {{ $val > 0 ? number_format($val, 3) : '-' }}
                                        </td>
                                    @endforeach
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-right text-gray-700 border-r border-gray-300">
                                        {{ $rowBldgTotal > 0 ? number_format($rowBldgTotal, 3) : '-' }}
                                    </td>

                                    @foreach($zones as $zone)
                                        @php
                                            $val = $row['zones'][$zone];
                                            $zoneTotals[$zone] += $val;
                                        @endphp
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-500 bg-gray-50">
                                            {{ $val > 0 ? number_format($val, 3) : '-' }}
                                        </td>
                                    @endforeach
                                    
                                    @php $grandTotal += $row['total']; @endphp
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-right text-gray-900 bg-gray-200">
                                        {{ $row['total'] > 0 ? number_format($row['total'], 3) : '-' }}
                                    </td>
                                </tr>
                            @endforeach
                            
                            <!-- Totals Row -->
                            <tr class="bg-indigo-50 border-t-2 border-indigo-200">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 border-r border-gray-300">YTD TOTAL</td>
                                @foreach($buildings as $building)
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-right text-indigo-700">
                                        {{ $bldgTotals[$building->name] > 0 ? number_format($bldgTotals[$building->name], 3) : '-' }}
                                    </td>
                                @endforeach
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-right text-indigo-900 border-r border-gray-300">
                                    {{ $grandTotal > 0 ? number_format($grandTotal, 3) : '-' }}
                                </td>
                                
                                @foreach($zones as $zone)
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-right text-indigo-700">
                                        {{ $zoneTotals[$zone] > 0 ? number_format($zoneTotals[$zone], 3) : '-' }}
                                    </td>
                                @endforeach
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-black text-right text-indigo-900 bg-indigo-100">
                                    {{ $grandTotal > 0 ? number_format($grandTotal, 3) : '-' }}
                                </td>
                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
