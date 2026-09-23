<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::rename('campus_get_quotas', 'campus_monthly_data');
        
        Schema::table('campus_monthly_data', function (Blueprint $table) {
            $table->decimal('main_meter_kwh', 15, 2)->default(0)->after('period_year');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('campus_monthly_data', function (Blueprint $table) {
            //
        });
    }
};
