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
        // Hide GET from matrix since it is handled at the Campus level
        $sources = EmissionSource::whereNotIn('name', [
            'Purchased Electricity (GET)'
        ])->get();

        $factors = ConversionFactor::where('effective_date', '<=', date('Y-m-d'))
            ->orderBy('effective_date', 'desc')
            ->get()
            ->unique('emission_source_id')
            ->pluck('multiplier', 'emission_source_id');

        $months = [
            1 => 'January',
            2 => 'February',
            3 => 'March',
            4 => 'April',
            5 => 'May',
            6 => 'June',
            7 => 'July',
            8 => 'August',
            9 => 'September',
            10 => 'October',
            11 => 'November',
            12 => 'December'
        ];

        $years = [];
        $currentYear = date('Y');
        for ($i = $currentYear - 2; $i <= $currentYear + 2; $i++) {
            $years[$i] = $i;
        }

        $selectedMonth = $request->get('month', date('n'));
        $selectedYear = $request->get('year', date('Y'));

        $machineryCategories = MachineryCategory::all()->groupBy('emission_source_id');

        $existingEmissions = MonthlyEmission::where('period_month', $selectedMonth)
            ->where('period_year', $selectedYear)
            ->get()
            ->groupBy('building_id')
            ->map(function ($group) {
                return $group->keyBy(function ($item) {
                    return $item->emission_source_id . '_' . ($item->machinery_category_id ?? '0');
                })->map(function ($item) {
                    return $item->raw_usage;
                });
            })->toArray();

        $existingCo2 = MonthlyEmission::where('period_month', $selectedMonth)
            ->where('period_year', $selectedYear)
            ->get()
            ->groupBy('building_id')
            ->map(function ($group) {
                return $group->keyBy(function ($item) {
                    return $item->emission_source_id . '_' . ($item->machinery_category_id ?? '0');
                })->map(function ($item) {
                    return $item->calculated_co2e;
                });
            })->toArray();

        $campusData = \App\Models\CampusMonthlyData::where('period_month', $selectedMonth)
            ->where('period_year', $selectedYear)
            ->get()
            ->keyBy('campus');

        $coalSource = \App\Models\EmissionSource::where('name', 'Purchased Electricity')->first();
        $renewableSource = \App\Models\EmissionSource::where('name', 'Purchased Electricity (GET)')->first();

        $coalFactor = $coalSource ? \App\Models\ConversionFactor::where('emission_source_id', $coalSource->id)->orderBy('effective_date', 'desc')->value('multiplier') : 0;
        $renewableFactor = $renewableSource ? \App\Models\ConversionFactor::where('emission_source_id', $renewableSource->id)->orderBy('effective_date', 'desc')->value('multiplier') : 0;

        $dieselSource = \App\Models\EmissionSource::where('name', 'Stationary Energy (Diesel)')->first();
        $boilerCat = $dieselSource ? \App\Models\MachineryCategory::where('emission_source_id', $dieselSource->id)->where('name', 'Boiler')->first() : null;
        $boilerFactor = 0.00317; // Default
        if ($dieselSource && $boilerCat) {
            $dbBoilerFactor = \App\Models\ConversionFactor::where('emission_source_id', $dieselSource->id)
                ->where('machinery_category_id', $boilerCat->id)
                ->orderBy('effective_date', 'desc')
                ->value('multiplier');
            if ($dbBoilerFactor !== null) {
                $boilerFactor = (float) $dbBoilerFactor;
            }
        }
        
        $pvSource = \App\Models\EmissionSource::where('name', 'Self-generated PV Electricity')->first();
        $pvFactor = 0.0000735;
        if ($pvSource) {
            $dbPvFactor = \App\Models\ConversionFactor::where('emission_source_id', $pvSource->id)->orderBy('effective_date', 'desc')->value('multiplier');
            if ($dbPvFactor !== null) {
                $pvFactor = (float) $dbPvFactor;
            }
        }

        return view('data-entry.index', compact('buildings', 'sources', 'machineryCategories', 'factors', 'months', 'years', 'selectedMonth', 'selectedYear', 'existingEmissions', 'existingCo2', 'campusData', 'coalFactor', 'renewableFactor', 'boilerFactor', 'pvFactor'));
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
            foreach ($sourceData as $source_id => $catData) {
                // If it's an array, it means it's broken down by machinery category (or 0 for null)
                if (is_array($catData)) {
                    foreach ($catData as $cat_id => $raw_usage) {
                        if ($raw_usage === null || $raw_usage === '')
                            continue;

                        $raw_usage = str_replace(',', '', $raw_usage);
                        $raw_usage = (float) $raw_usage;

                        // We will allow 0 usage to override if needed, but standard logic skips it.
                        // Actually, if it's 0 or empty, we might want to delete or just update to 0. Let's update to 0.

                        $source = EmissionSource::find($source_id);
                        $machinery_id = ($cat_id == '0') ? null : $cat_id;
                        $machinery = $machinery_id ? MachineryCategory::find($machinery_id) : null;

                        $co2e = null;
                        if ($source->name !== 'Purchased Electricity') {
                            $factor = ConversionFactor::where('emission_source_id', $source_id)
                                ->where('effective_date', '<=', "{$year}-{$month}-01")
                                ->orderBy('effective_date', 'desc')
                                ->first();

                            $multiplier = $factor ? $factor->multiplier : 0;

                            // --- HARDCODE OVERRIDES BASED ON USER'S EXCEL ---
                            if ($source->name === 'Stationary Energy (Diesel)') {
                                if ($machinery && $machinery->name === 'Boiler') {
                                    $dbBoilerFactor = \App\Models\ConversionFactor::where('emission_source_id', $source_id)
                                        ->where('machinery_category_id', $machinery->id)
                                        ->orderBy('effective_date', 'desc')
                                        ->value('multiplier');
                                    $multiplier = $dbBoilerFactor !== null ? (float) $dbBoilerFactor : 0.00317;
                                } else {
                                    $multiplier = 0.00268; // GenSet/Forklift
                                }
                            } elseif ($source->name === 'Stationary Energy (LPG)') {
                                $multiplier = 0.0014;
                            } elseif ($source->name === 'Self-generated PV Electricity') {
                                $dbPvFactor = \App\Models\ConversionFactor::where('emission_source_id', $source_id)->orderBy('effective_date', 'desc')->value('multiplier');
                                $multiplier = $dbPvFactor !== null ? (float) $dbPvFactor : 0.0000735;
                            }
                            // ------------------------------------------------

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
                } else {
                    // Backwards compatibility if somehow it's not arrayed
                    $raw_usage = $catData;
                    if ($raw_usage === null || $raw_usage === '')
                        continue;

                    $raw_usage = str_replace(',', '', $raw_usage);
                    $raw_usage = (float) $raw_usage;

                    $source = EmissionSource::find($source_id);
                    $co2e = null;
                    if ($source->name !== 'Purchased Electricity') {
                        $factor = ConversionFactor::where('emission_source_id', $source_id)
                            ->where('effective_date', '<=', "{$year}-{$month}-01")
                            ->orderBy('effective_date', 'desc')
                            ->first();

                        $multiplier = $factor ? $factor->multiplier : 0;
                        if ($source->name === 'Stationary Energy (LPG)')
                            $multiplier = 0.0014;
                        $co2e = round($raw_usage * $multiplier, 2);
                    }

                    MonthlyEmission::updateOrCreate([
                        'period_month' => $month,
                        'period_year' => $year,
                        'building_id' => $building_id,
                        'emission_source_id' => $source_id,
                        'machinery_category_id' => null,
                    ], [
                        'raw_usage' => $raw_usage,
                        'calculated_co2e' => $co2e
                    ]);
                }
            }
        }

        $refrigerantInput = $request->input('refrigerant', []);
        $refrigerantSource = \App\Models\EmissionSource::where('name', 'IPPU/Refrigerant')->first();
        if ($refrigerantSource) {
            foreach ($refrigerantInput as $building_id => $data) {
                \App\Models\MonthlyEmission::where('period_month', $month)
                    ->where('period_year', $year)
                    ->where('building_id', $building_id)
                    ->where('emission_source_id', $refrigerantSource->id)
                    ->delete();

                $weight = $data['weight'] ?? null;
                $type = $data['type'] ?? null;

                if ($weight !== null && $weight !== '' && $type) {
                    $weight = (float) str_replace(',', '', $weight);

                    $factor = \App\Models\ConversionFactor::where('emission_source_id', $refrigerantSource->id)
                        ->where('effective_date', '<=', "{$year}-{$month}-01")
                        ->orderBy('effective_date', 'desc')
                        ->first();
                    $multiplier = $factor ? $factor->multiplier : 0;
                    $co2e = round($weight * $multiplier, 2);

                    \App\Models\MonthlyEmission::create([
                        'period_month' => $month,
                        'period_year' => $year,
                        'building_id' => $building_id,
                        'emission_source_id' => $refrigerantSource->id,
                        'machinery_category_id' => $type,
                        'raw_usage' => $weight,
                        'calculated_co2e' => $co2e
                    ]);
                }
            }
        }

        // Process CampusMonthlyData (Electricity GET and Main Meter)
        $campusDataInput = $request->input('campus_data', []);
        foreach ($campusDataInput as $campusName => $cData) {
            $mainMeter = isset($cData['main_meter_kwh']) && $cData['main_meter_kwh'] !== '' ? (float) str_replace(',', '', $cData['main_meter_kwh']) : null;
            $quota = isset($cData['get_kwh']) && $cData['get_kwh'] !== '' ? (float) str_replace(',', '', $cData['get_kwh']) : 0;
            
            \App\Models\CampusMonthlyData::updateOrCreate([
                'campus' => $campusName,
                'period_month' => $month,
                'period_year' => $year,
            ], [
                'main_meter_kwh' => $mainMeter,
                'quota_kwh' => $quota
            ]);
        }

        // Recalculate Electricity for all campuses
        $service = new \App\Services\EmissionCalculationService();
        $service->recalculateCampusElectricity('Campus 1', $month, $year);
        $service->recalculateCampusElectricity('Campus 2', $month, $year);

        return redirect()->route('data-entry.index', ['month' => $month, 'year' => $year])
            ->with('success', 'Matrix data saved successfully for ' . $month . '/' . $year . '!');
    }

    public function updateElectricityFactors(Request $request)
    {
        $request->validate([
            'coal_factor' => 'required|numeric|min:0',
            'renewable_factor' => 'required|numeric|min:0',
            'pv_factor' => 'required|numeric|min:0',
            'month' => 'required|integer',
            'year' => 'required|integer',
        ]);

        $coalSource = \App\Models\EmissionSource::where('name', 'Purchased Electricity')->first();
        $renewableSource = \App\Models\EmissionSource::where('name', 'Purchased Electricity (GET)')->first();
        $pvSource = \App\Models\EmissionSource::where('name', 'Self-generated PV Electricity')->first();

        if ($coalSource) {
            \App\Models\ConversionFactor::updateOrCreate([
                'emission_source_id' => $coalSource->id,
                'effective_date' => '2020-01-01',
            ], [
                'multiplier' => $request->coal_factor
            ]);
        }

        if ($renewableSource) {
            \App\Models\ConversionFactor::updateOrCreate([
                'emission_source_id' => $renewableSource->id,
                'effective_date' => '2020-01-01',
            ], [
                'multiplier' => $request->renewable_factor
            ]);
        }
        
        if ($pvSource) {
            \App\Models\ConversionFactor::updateOrCreate([
                'emission_source_id' => $pvSource->id,
                'effective_date' => '2020-01-01',
            ], [
                'multiplier' => $request->pv_factor
            ]);
        }

        // Recalculate for all campuses in the current viewed month
        $service = new \App\Services\EmissionCalculationService();
        $campuses = \App\Models\Campus::pluck('name');
        foreach ($campuses as $campus) {
            $service->recalculateCampusElectricity($campus, $request->month, $request->year);
        }

        return response()->json(['success' => true]);
    }

    public function updateDieselFactors(Request $request)
    {
        $request->validate([
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2020',
            'boiler_factor' => 'required|numeric|min:0',
        ]);

        $dieselSource = \App\Models\EmissionSource::where('name', 'Stationary Energy (Diesel)')->first();
        $boilerCat = $dieselSource ? \App\Models\MachineryCategory::where('emission_source_id', $dieselSource->id)->where('name', 'Boiler')->first() : null;

        if ($dieselSource && $boilerCat) {
            \App\Models\ConversionFactor::updateOrCreate([
                'emission_source_id' => $dieselSource->id,
                'machinery_category_id' => $boilerCat->id,
                'effective_date' => '2020-01-01',
            ], [
                'multiplier' => $request->boiler_factor
            ]);
        }

        // Recalculate Boiler CO2
        if ($dieselSource && $boilerCat) {
            $emissions = \App\Models\MonthlyEmission::where('emission_source_id', $dieselSource->id)
                ->where('machinery_category_id', $boilerCat->id)
                ->where('period_month', $request->month)
                ->where('period_year', $request->year)
                ->get();
                
            foreach ($emissions as $emission) {
                $emission->calculated_co2e = round($emission->raw_usage * $request->boiler_factor, 2);
                $emission->save();
            }
        }

        return response()->json(['success' => true]);
    }

    public function storeQuota(Request $request)
    {
        $validated = $request->validate([
            'period_month' => 'required|integer|between:1,12',
            'period_year' => 'required|integer|min:2020',
            'campus' => 'required|string',
            'main_meter_kwh' => 'nullable|string',
            'quota_kwh' => 'required|string',
        ]);

        $month = $validated['period_month'];
        $year = $validated['period_year'];
        $campusName = $validated['campus'];
        
        $mainMeter = isset($validated['main_meter_kwh']) && $validated['main_meter_kwh'] !== '' ? (float) str_replace(',', '', $validated['main_meter_kwh']) : null;
        $quota = isset($validated['quota_kwh']) && $validated['quota_kwh'] !== '' ? (float) str_replace(',', '', $validated['quota_kwh']) : 0;
        
        \App\Models\CampusMonthlyData::updateOrCreate([
            'campus' => $campusName,
            'period_month' => $month,
            'period_year' => $year,
        ], [
            'main_meter_kwh' => $mainMeter,
            'quota_kwh' => $quota
        ]);

        // Recalculate Electricity for this campus
        $service = new \App\Services\EmissionCalculationService();
        $service->recalculateCampusElectricity($campusName, $month, $year);

        return redirect()->route('data-entry.index', ['month' => $month, 'year' => $year])
            ->with('success', "Campus setup for {$campusName} saved successfully!");
    }
}
