<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\EmissionSource;
use App\Models\MachineryCategory;

class MachineryCategoryExcelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $diesel = EmissionSource::where('name', 'Stationary Energy (Diesel)')->first();
        if ($diesel) {
            MachineryCategory::updateOrCreate(['name' => 'Boiler', 'emission_source_id' => $diesel->id]);
            MachineryCategory::updateOrCreate(['name' => 'Forklift Diesel', 'emission_source_id' => $diesel->id]);
            MachineryCategory::updateOrCreate(['name' => 'GenSet Diesel', 'emission_source_id' => $diesel->id]);
        }

        $refrigerant = EmissionSource::where('name', 'IPPU/Refrigerant')->first();
        if ($refrigerant) {
            MachineryCategory::updateOrCreate(['name' => 'R22', 'emission_source_id' => $refrigerant->id]);
            MachineryCategory::updateOrCreate(['name' => 'R407C', 'emission_source_id' => $refrigerant->id]);
        }
    }
}
