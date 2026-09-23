<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampusMonthlyData extends Model
{
    protected $fillable = ['campus', 'period_month', 'period_year', 'main_meter_kwh', 'quota_kwh'];
}
