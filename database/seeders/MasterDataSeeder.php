<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MasterDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Buildings
        $buildings = [
            ['name' => 'B1', 'zone' => 'OP', 'campus' => 'Campus 1'],
            ['name' => 'B3', 'zone' => 'MP', 'campus' => 'Campus 1'],
            ['name' => 'B5', 'zone' => 'MT', 'campus' => 'Campus 1'],
            ['name' => 'B8', 'zone' => 'Central', 'campus' => 'Campus 1'],
            ['name' => 'B9', 'zone' => 'OP', 'campus' => 'Campus 1'],
            ['name' => 'B10', 'zone' => 'MP', 'campus' => 'Campus 1'],
            ['name' => 'B11', 'zone' => 'MT', 'campus' => 'Campus 2'],
            ['name' => 'ASM', 'zone' => 'Central', 'campus' => 'Campus 2'],
            ['name' => 'B12', 'zone' => 'OP', 'campus' => 'Campus 2'],
        ];
        foreach ($buildings as $building) {
            \App\Models\Building::updateOrCreate(['name' => $building['name']], $building);
        }

        // 2. Emission Sources
        $sources = [
            ['name' => 'Stationary Energy (Diesel)', 'unit' => 'Liter', 'is_get' => false],
            ['name' => 'Stationary Energy (LPG)', 'unit' => 'kg', 'is_get' => false],
            ['name' => 'Purchased Electricity', 'unit' => 'kWh', 'is_get' => false],
            ['name' => 'Purchased Electricity (GET)', 'unit' => 'kWh', 'is_get' => true],
            ['name' => 'Self-generated PV Electricity', 'unit' => 'kWh', 'is_get' => true],
            ['name' => 'Transportation Petrol', 'unit' => 'Liter', 'is_get' => false],
            ['name' => 'IPPU/Refrigerant', 'unit' => 'kg', 'is_get' => false],
            ['name' => 'Water Consumption', 'unit' => 'Liter', 'is_get' => false],
        ];
        foreach ($sources as $source) {
            \App\Models\EmissionSource::updateOrCreate(['name' => $source['name']], $source);
        }

        // 3. Machinery Categories (No longer heavily used in Matrix UI, but we keep for structural consistency if needed)
        // You can keep or ignore. I'll just remove the diesel categories for simplicity since we split the source itself.

        $petrol = \App\Models\EmissionSource::where('name', 'Transportation Petrol')->first();
        if ($petrol) {
            $categories = [
                ['name' => 'OS', 'emission_source_id' => $petrol->id],
                ['name' => 'OF', 'emission_source_id' => $petrol->id],
            ];
            foreach ($categories as $category) {
                \App\Models\MachineryCategory::updateOrCreate(['name' => $category['name'], 'emission_source_id' => $petrol->id], $category);
            }
        }

        // 4. Conversion Factors (Examples from BRD)
        $factors = [
            ['source' => 'Stationary Energy (Diesel)', 'multiplier' => 0.00317, 'effective_date' => '2025-01-01'], // Default 3.17 kgCO2e/liter (B1), others overridden in code
            ['source' => 'Stationary Energy (LPG)', 'multiplier' => 0.0014, 'effective_date' => '2025-01-01'], // Default 1.4 kg/kg (B5), others overridden
            ['source' => 'Purchased Electricity', 'multiplier' => 0.001208174, 'effective_date' => '2025-01-01'], // Coal: 0.001208174 tCO2e/kWh
            ['source' => 'Purchased Electricity (GET)', 'multiplier' => 0, 'effective_date' => '2025-01-01'], // Renewable: 0 tCO2e/kWh
            ['source' => 'Transportation Petrol', 'multiplier' => 0.0023, 'effective_date' => '2025-01-01'], // placeholder
            ['source' => 'IPPU/Refrigerant', 'multiplier' => 2.1, 'effective_date' => '2025-01-01'], // placeholder
        ];

        foreach ($factors as $factor) {
            $src = \App\Models\EmissionSource::where('name', $factor['source'])->first();
            if ($src) {
                \App\Models\ConversionFactor::firstOrCreate([
                    'emission_source_id' => $src->id,
                    'effective_date' => $factor['effective_date']
                ], [
                    'multiplier' => $factor['multiplier']
                ]);
            }
        }
    }
}
