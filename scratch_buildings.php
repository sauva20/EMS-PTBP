<?php
require 'vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$c1 = \App\Models\Campus::where('name', 'Campus 1')->first();
$c2 = \App\Models\Campus::where('name', 'Campus 2')->first();
echo "Campus 1 Buildings: " . $c1->buildings()->pluck('name')->implode(', ') . "\n";
echo "Campus 2 Buildings: " . $c2->buildings()->pluck('name')->implode(', ') . "\n";
