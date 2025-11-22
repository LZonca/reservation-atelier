<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo 'Clients: ' . \App\Models\Client::count() . "\n";
echo 'Ateliers: ' . \App\Models\Atelier::count() . "\n";
echo 'Salles: ' . \App\Models\Salle::count() . "\n";

