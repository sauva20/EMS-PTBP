<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonthlyEmission extends Model
{
    protected $fillable = [
        'period_month',
        'period_year',
        'building_id',
        'emission_source_id',
        'machinery_category_id',
        'raw_usage',
        'calculated_co2e',
    ];

    public function building()
    {
        return $this->belongsTo(Building::class);
    }

    public function emissionSource()
    {
        return $this->belongsTo(EmissionSource::class);
    }

    public function machineryCategory()
    {
        return $this->belongsTo(MachineryCategory::class);
    }
}
