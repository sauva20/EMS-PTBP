<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmissionSource extends Model
{
    protected $fillable = ['name', 'unit', 'is_get', 'is_active'];

    public function machineryCategories()
    {
        return $this->hasMany(MachineryCategory::class);
    }

    public function conversionFactors()
    {
        return $this->hasMany(ConversionFactor::class);
    }

    public function monthlyEmissions()
    {
        return $this->hasMany(MonthlyEmission::class);
    }
}
