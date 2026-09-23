<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampusGetQuota extends Model
{
    protected $fillable = ['campus', 'period_month', 'period_year', 'quota_kwh'];
}
