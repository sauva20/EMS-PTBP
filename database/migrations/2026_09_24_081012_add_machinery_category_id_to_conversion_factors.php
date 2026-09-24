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
        Schema::table('conversion_factors', function (Blueprint $table) {
            $table->foreignId('machinery_category_id')->nullable()->constrained('machinery_categories')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('conversion_factors', function (Blueprint $table) {
            $table->dropForeign(['machinery_category_id']);
            $table->dropColumn('machinery_category_id');
        });
    }
};
