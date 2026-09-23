<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MachineryCategory extends Model
{
    protected $fillable = ['name', 'emission_source_id', 'is_active'];

    public function emissionSource()
    {
        return $this->belongsTo(EmissionSource::class);
    }

    public function monthlyEmissions()
    {
        return $this->hasMany(MonthlyEmission::class);
    }
}
