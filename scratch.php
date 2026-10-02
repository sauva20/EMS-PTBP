<?php
require 'vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$factor = '7.3526E-06';
$f = (float) str_replace(',', '.', $factor);
\App\Models\ConversionFactor::updateOrCreate(
    ['emission_source_id' => 5, 'machinery_category_id' => null, 'effective_date' => '2026-02-01'],
    ['multiplier' => $f]
);
echo "Value inside DB: " . \App\Models\ConversionFactor::where('effective_date', '2026-02-01')->value('multiplier') . "\n";
