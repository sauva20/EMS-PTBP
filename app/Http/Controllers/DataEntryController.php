<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Building;
use App\Models\EmissionSource;
use App\Models\MachineryCategory;
use App\Models\MonthlyEmission;
use App\Models\ConversionFactor;

class DataEntryController extends Controller
{
    public function index(Request $request)
    {
        $buildings = Building::orderBy('id')->get();
        // Hide GET and PV from matrix since they are handled at the Campus level or unused in Excel
        $sources = EmissionSource::whereNotIn('name', [
            'Purchased Electricity (GET)', 
            'Self-generated PV Electricity'
        ])->get();
        
        $factors = ConversionFactor::where('effective_date', '<=', date('Y-m-d'))
            ->orderBy('effective_date', 'desc')
            ->get()
            ->unique('emission_source_id')
            ->pluck('multiplier', 'emission_source_id');
            
        $months = [
            1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
            5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
            9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
        ];
        
        $years = [];
        $currentYear = date('Y');
        for ($i = $currentYear - 2; $i <= $currentYear + 2; $i++) {
            $years[$i] = $i;
        }

        $selectedMonth = $request->get('month', date('n'));
        $selectedYear = $request->get('year', date('Y'));

        $existingEmissions = MonthlyEmission::where('period_month', $selectedMonth)
            ->where('period_year', $selectedYear)
            ->get()
            ->groupBy('building_id')
            ->map(function ($group) {
                return $group->keyBy('emission_source_id')->map(function ($item) {
                    return $item->raw_usage;
                });
            })->toArray();

        $existingCo2 = MonthlyEmission::where('period_month', $selectedMonth)
            ->where('period_year', $selectedYear)
            ->get()
            ->groupBy('building_id')
            ->map(function ($group) {
                return $group->keyBy('emission_source_id')->map(function ($item) {
                    return $item->calculated_co2e;
                });
            })->toArray();

        $campusData = \App\Models\CampusMonthlyData::where('period_month', $selectedMonth)
            ->where('period_year', $selectedYear)
            ->get()
            ->keyBy('campus');

        return view('data-entry.index', compact('buildings', 'sources', 'factors', 'months', 'years', 'selectedMonth', 'selectedYear', 'existingEmissions', 'existingCo2', 'campusData'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2020',
            'emissions' => 'array',
        ]);

        $month = $validated['month'];
        $year = $validated['year'];
        $emissionsInput = $validated['emissions'] ?? [];

        foreach ($emissionsInput as $building_id => $sourceData) {
            foreach ($sourceData as $source_id => $raw_usage) {
                if ($raw_usage === null || $raw_usage === '') continue;

                // Remove commas from raw usage if user formatted it
                $raw_usage = str_replace(',', '', $raw_usage);
                $raw_usage = (float) $raw_usage;

                if ($raw_usage <= 0) continue;

                $source = EmissionSource::find($source_id);
                $buildingModel = Building::find($building_id);
                
                // For matrix entry, we don't use machinery specific IDs
                $machinery_id = null; 

                // For Non-Electricity, calculate immediately
                $co2e = null;
                if ($source->name !== 'Purchased Electricity') {
                    $factor = ConversionFactor::where('emission_source_id', $source_id)
                        ->where('effective_date', '<=', "{$year}-{$month}-01")
                        ->orderBy('effective_date', 'desc')
                        ->first();
                    
                    $multiplier = $factor ? $factor->multiplier : 0;

                    // --- HARDCODE OVERRIDES BASED ON USER'S EXCEL ---
                    if ($source->name === 'Stationary Energy (Diesel)') {
                        if ($buildingModel->name === 'B1') {
                            $multiplier = 0.00317; // Boiler
                        } else {
                            $multiplier = 0.00268; // GenSet/Forklift
                        }
                    } elseif ($source->name === 'Stationary Energy (LPG)') {
                        $multiplier = 0.0014; // All buildings use 1.4 kg/kg
                    }
                    // ------------------------------------------------

                    // Mimic Excel behavior: round each building's CO2 to 2 decimal places
                    $co2e = round($raw_usage * $multiplier, 2);
                }

                MonthlyEmission::updateOrCreate([
                    'period_month' => $month,
                    'period_year' => $year,
                    'building_id' => $building_id,
                    'emission_source_id' => $source_id,
                    'machinery_category_id' => $machinery_id,
                ], [
                    'raw_usage' => $raw_usage,
                    'calculated_co2e' => $co2e
                ]);
            }
        }

        // Recalculate Electricity for all campuses
        $service = new \App\Services\EmissionCalculationService();
        $service->recalculateCampusElectricity('Campus 1', $month, $year);
        $service->recalculateCampusElectricity('Campus 2', $month, $year);

        return redirect()->route('data-entry.index', ['month' => $month, 'year' => $year])
            ->with('success', 'Matrix data saved successfully for ' . $month . '/' . $year . '!');
    }

    public function storeQuota(Request $request)
    {
        // Remove commas before validation
        $request->merge([
            'main_meter_kwh' => $request->filled('main_meter_kwh') ? str_replace(',', '', $request->main_meter_kwh) : null,
            'quota_kwh' => $request->filled('quota_kwh') ? str_replace(',', '', $request->quota_kwh) : 0
        ]);

        $validated = $request->validate([
            'campus' => 'required|string',
            'period_month' => 'required|integer|between:1,12',
            'period_year' => 'required|integer|min:2020',
            'main_meter_kwh' => 'nullable|numeric|min:0',
            'quota_kwh' => 'required|numeric|min:0',
        ]);

        \App\Models\CampusMonthlyData::updateOrCreate([
            'campus' => $validated['campus'],
            'period_month' => $validated['period_month'],
            'period_year' => $validated['period_year'],
        ], [
            'main_meter_kwh' => $validated['main_meter_kwh'],
            'quota_kwh' => $validated['quota_kwh']
        ]);

        // Trigger recalculation for the campus
        $service = new \App\Services\EmissionCalculationService();
        $service->recalculateCampusElectricity($validated['campus'], $validated['period_month'], $validated['period_year']);

        return redirect()->route('data-entry.index', ['month' => $validated['period_month'], 'year' => $validated['period_year']])
            ->with('success', 'Campus GET Quota and Main Meter updated. Electricity emissions for all buildings in this campus have been recalculated.');
    }
}
