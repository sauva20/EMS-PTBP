<?php
require 'vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$buildings = \App\Models\Building::orderBy('id')->get();
$campus1Buildings = $buildings->filter(fn($b) => in_array($b->name, ['B3', 'B5', 'B8', 'B9']));
$campus2Buildings = $buildings->filter(fn($b) => in_array($b->name, ['B1', 'B10', 'B11', 'ASM', 'B12']));
foreach($buildings as $building) {
    $c = $campus1Buildings->contains('id', $building->id) ? 'c1' : ($campus2Buildings->contains('id', $building->id) ? 'c2' : 'other');
    echo $building->name . ': ' . $c . "\n";
}
