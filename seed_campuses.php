<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$campuses = \App\Models\Building::select('campus')->distinct()->pluck('campus');
foreach($campuses as $c) { 
    if($c) \App\Models\Campus::firstOrCreate(['name' => $c]); 
}
echo "Seeded campuses!\n";
