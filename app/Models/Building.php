<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    protected $fillable = ['name', 'campus', 'zone', 'is_active'];

    public function monthlyEmissions()
    {
        return $this->hasMany(MonthlyEmission::class);
    }
}
