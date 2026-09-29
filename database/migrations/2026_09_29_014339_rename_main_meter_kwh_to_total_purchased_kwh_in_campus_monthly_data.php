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
        Schema::table('campus_monthly_data', function (Blueprint $table) {
            $table->renameColumn('main_meter_kwh', 'total_purchased_kwh');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('campus_monthly_data', function (Blueprint $table) {
            $table->renameColumn('total_purchased_kwh', 'main_meter_kwh');
        });
    }
};
