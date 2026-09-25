<?php

namespace App\Http\Controllers;

use App\Models\Building;
use App\Models\MonthlyEmission;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function accumulative(Request $request)
    {
        $year = $request->input('year', date('Y'));

        $buildings = Building::where('is_active', true)->orderBy('name')->get();

        // Let's get the grouped zones as well
        $zones = $buildings->pluck('zone')->unique()->filter()->values();

        // Get all emissions for the year
        $emissions = MonthlyEmission::where('period_year', $year)
            ->with(['building', 'emissionSource'])
            ->get();

        $months = collect(range(1, 12))->mapWithKeys(function ($m) {
            return [$m => date('M', mktime(0, 0, 0, $m, 10))];
        });

        $reportData = [];

        $targetSources = [
            1 => ['title' => 'DIESEL', 'unit' => 'Liter'],
            2 => ['title' => 'LPG', 'unit' => 'kg'],
            3 => ['title' => 'ELECTRICITY', 'unit' => 'kWh'],
            6 => ['title' => 'PETROL', 'unit' => 'Liter'],
        ];

        foreach ($targetSources as $sourceId => $config) {
            $matrix = [];
            foreach ($months as $m => $mName) {
                $monthData = $emissions->where('period_month', $m)->where('emission_source_id', $sourceId);

                $row = [
                    'month' => $mName,
                    'buildings' => [],
                    'co2_buildings' => [],
                    'zones' => [],
                    'usage_total' => 0,
                    'co2_total' => 0,
                ];

                $usageTotal = 0;
                $co2Total = 0;

                foreach ($buildings as $building) {
                    $bUsage = $monthData->where('building_id', $building->id)->sum('usage');
                    $bCo2 = $monthData->where('building_id', $building->id)->sum('calculated_co2e');

                    $row['buildings'][$building->name] = $bUsage;
                    $row['co2_buildings'][$building->name] = $bCo2;

                    $usageTotal += $bUsage;
                    $co2Total += $bCo2;
                }

                foreach ($zones as $zone) {
                    $zBuildings = $buildings->where('zone', $zone)->pluck('id');
                    $zUsage = $monthData->whereIn('building_id', $zBuildings)->sum('usage');
                    $row['zones'][$zone] = $zUsage;
                }

                $row['usage_total'] = $usageTotal;
                $row['co2_total'] = $co2Total;
                $matrix[$m] = $row;
            }

            $reportData[] = [
                'title' => $config['title'],
                'unit' => $config['unit'],
                'matrix' => $matrix,
            ];
        }

        return view('reports.accumulative', compact('reportData', 'buildings', 'zones', 'year'));
    }
}
