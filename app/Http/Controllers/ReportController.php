<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Building;
use App\Models\MonthlyEmission;
use Illuminate\Support\Facades\DB;

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

        // We need to build a matrix: 
        // Rows: Months 1-12
        // Columns: Buildings + Zones + Total
        // Data: Calculated CO2e (or raw usage, depending on toggle)

        $matrix = [];
        $months = collect(range(1, 12))->mapWithKeys(function ($m) {
            return [$m => date('M', mktime(0, 0, 0, $m, 10))];
        });

        foreach ($months as $m => $mName) {
            $monthData = $emissions->where('period_month', $m);
            
            $row = [
                'month' => $mName,
                'buildings' => [],
                'zones' => [],
                'total' => 0
            ];

            $monthTotal = 0;

            foreach ($buildings as $building) {
                $bTotal = $monthData->where('building_id', $building->id)->sum('calculated_co2e');
                $row['buildings'][$building->name] = $bTotal;
                $monthTotal += $bTotal;
            }

            foreach ($zones as $zone) {
                $zBuildings = $buildings->where('zone', $zone)->pluck('id');
                $zTotal = $monthData->whereIn('building_id', $zBuildings)->sum('calculated_co2e');
                $row['zones'][$zone] = $zTotal;
            }

            $row['total'] = $monthTotal;
            $matrix[$m] = $row;
        }

        return view('reports.accumulative', compact('matrix', 'buildings', 'zones', 'year'));
    }
}
