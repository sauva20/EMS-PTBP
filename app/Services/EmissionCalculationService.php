<?php

namespace App\Services;

use App\Models\Building;
use App\Models\MonthlyEmission;
use App\Models\EmissionSource;
use App\Models\CampusMonthlyData;
use App\Models\ConversionFactor;

class EmissionCalculationService
{
    /**
     * Recalculate electricity emissions for a campus
     */
    public function recalculateCampusElectricity(string $campus, int $month, int $year)
    {
        // Get all buildings in this campus
        $buildingIds = Building::where('campus', $campus)->pluck('id')->toArray();
        if (empty($buildingIds)) return;

        // Get the Purchased Electricity source
        $electricitySource = EmissionSource::where('name', 'Purchased Electricity')->first();
        if (!$electricitySource) return;

        // Get all electricity usages for these buildings for the month
        $emissions = MonthlyEmission::where('period_month', $month)
            ->where('period_year', $year)
            ->where('emission_source_id', $electricitySource->id)
            ->whereIn('building_id', $buildingIds)
            ->get();

        if ($emissions->isEmpty()) return;

        // Total sub-meter reading
        $totalSubMeter = $emissions->sum('raw_usage');
        if ($totalSubMeter == 0) return;

        // Get Campus monthly data (Main Meter & GET Quota)
        $campusData = CampusMonthlyData::where('campus', $campus)
            ->where('period_month', $month)
            ->where('period_year', $year)
            ->first();
            
        $mainMeter = $campusData ? $campusData->main_meter_kwh : $totalSubMeter;
        $getQuota = $campusData ? $campusData->quota_kwh : 0;

        // Coal Portion for Campus
        $campusCoalKwh = max(0, $mainMeter - $getQuota);

        // Coal Emission Factor
        $factor = ConversionFactor::where('emission_source_id', $electricitySource->id)
            ->where('effective_date', '<=', "{$year}-{$month}-01")
            ->orderBy('effective_date', 'desc')
            ->first();
        $multiplier = $factor ? $factor->multiplier : 0;

        // Distribute back to buildings
        foreach ($emissions as $emission) {
            // Building percentage based on sub-meters
            $percentage = $emission->raw_usage / $totalSubMeter;
            
            // Apportion coal usage to this building
            $buildingCoalKwh = $percentage * $campusCoalKwh;
            
            // Calculate CO2e
            $co2e = $buildingCoalKwh * $multiplier;
            
            $emission->calculated_co2e = $co2e;
            $emission->save();
        }
    }
}
