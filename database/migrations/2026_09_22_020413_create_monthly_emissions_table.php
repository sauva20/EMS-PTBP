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
        Schema::create('monthly_emissions', function (Blueprint $table) {
            $table->id();
            $table->integer('period_month');
            $table->integer('period_year');
            $table->foreignId('building_id')->constrained()->onDelete('cascade');
            $table->foreignId('emission_source_id')->constrained()->onDelete('cascade');
            $table->foreignId('machinery_category_id')->nullable()->constrained()->onDelete('set null');
            $table->decimal('raw_usage', 15, 4);
            $table->decimal('calculated_co2e', 15, 6)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monthly_emissions');
    }
};
