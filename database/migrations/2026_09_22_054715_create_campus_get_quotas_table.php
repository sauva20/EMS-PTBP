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
        Schema::create('campus_get_quotas', function (Blueprint $table) {
            $table->id();
            $table->string('campus');
            $table->integer('period_month');
            $table->integer('period_year');
            $table->decimal('quota_kwh', 15, 2)->default(0);
            $table->timestamps();
            
            $table->unique(['campus', 'period_month', 'period_year']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campus_get_quotas');
    }
};
