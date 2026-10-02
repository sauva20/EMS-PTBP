<?php
require 'vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$c1 = \App\Models\Building::where('campus_id', 1)->pluck('name')->implode(', ');
$c2 = \App\Models\Building::where('campus_id', 2)->pluck('name')->implode(', ');
echo "C1: $c1\nC2: $c2\n";
