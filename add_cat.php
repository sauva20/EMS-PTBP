<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$s = \App\Models\EmissionSource::where('name', 'IPPU/Refrigerant')->first();
if ($s) {
    \App\Models\MachineryCategory::firstOrCreate(['emission_source_id' => $s->id, 'name' => 'R134A']);
    echo "Added R134A successfully.\n";
} else {
    echo "Source not found.\n";
}
