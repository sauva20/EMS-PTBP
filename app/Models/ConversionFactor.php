<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConversionFactor extends Model
{
    protected $fillable = ['emission_source_id', 'multiplier', 'effective_date'];

    protected $casts = [
        'effective_date' => 'date',
    ];

    public function emissionSource()
    {
        return $this->belongsTo(EmissionSource::class);
    }
}
