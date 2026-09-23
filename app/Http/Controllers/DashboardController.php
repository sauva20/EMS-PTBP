<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\MonthlyEmission;
use App\Models\EmissionSource;
use App\Models\Building;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->input('year', date('Y'));

        // 1. Total Emissions Trend (Monthly)
        $monthlyTrend = MonthlyEmission::where('period_year', $year)
            ->select('period_month', DB::raw('SUM(calculated_co2e) as total'))
            ->groupBy('period_month')
            ->orderBy('period_month')
            ->pluck('total', 'period_month')
            ->toArray();
            
        // Fill missing months with 0
        $trendData = [];
        $months = collect(range(1, 12))->map(fn($m) => date('M', mktime(0,0,0,$m,10)))->toArray();
        for ($i = 1; $i <= 12; $i++) {
            $trendData[] = $monthlyTrend[$i] ?? 0;
        }

        // 2. Emission by Source (Pie Chart)
        $emissionsBySource = MonthlyEmission::where('period_year', $year)
            ->join('emission_sources', 'monthly_emissions.emission_source_id', '=', 'emission_sources.id')
            ->select('emission_sources.name', DB::raw('SUM(calculated_co2e) as total'))
            ->groupBy('emission_sources.name')
            ->get();
            
        $pieLabels = $emissionsBySource->pluck('name')->toArray();
        $pieData = $emissionsBySource->pluck('total')->toArray();

        // 3. Emission by Building (Bar Chart)
        $emissionsByBuilding = MonthlyEmission::where('period_year', $year)
            ->join('buildings', 'monthly_emissions.building_id', '=', 'buildings.id')
            ->select('buildings.name', DB::raw('SUM(calculated_co2e) as total'))
            ->groupBy('buildings.name')
            ->orderByDesc('total')
            ->get();
            
        $barLabels = $emissionsByBuilding->pluck('name')->toArray();
        $barData = $emissionsByBuilding->pluck('total')->toArray();

        return view('dashboard', compact('year', 'months', 'trendData', 'pieLabels', 'pieData', 'barLabels', 'barData'));
    }
}
