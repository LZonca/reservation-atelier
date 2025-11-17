#!/usr/bin/env php
<?php

echo "\n=== Configuration PHP ===\n\n";
echo "Version PHP : " . PHP_VERSION . "\n";
echo "php.ini utilisé : " . php_ini_loaded_file() . "\n";
echo "Extension MongoDB : " . (extension_loaded('mongodb') ? 'OUI' : 'NON') . "\n";

if (extension_loaded('mongodb')) {
    echo "Version MongoDB : " . phpversion('mongodb') . "\n";
}

echo "\nClasse MongoDB\\Driver\\Manager : " . (class_exists('MongoDB\\Driver\\Manager') ? 'OUI' : 'NON') . "\n";

echo "\n=== Test Connexion Laravel ===\n\n";

// Charger Laravel
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    $connection = DB::connection('mongodb');
    echo "✓ Connexion MongoDB Laravel OK\n";
    echo "Base de données : " . $connection->getDatabaseName() . "\n";

    // Test de création d'un document
    $result = DB::connection('mongodb')->table('test_collection')->insert([
        'message' => 'Test depuis script',
        'created_at' => new DateTime()
    ]);

    echo "✓ Insertion test réussie\n";

    // Nettoyer
    DB::connection('mongodb')->table('test_collection')->delete();

} catch (Exception $e) {
    echo "✗ Erreur : " . $e->getMessage() . "\n";
    echo "Trace : " . $e->getTraceAsString() . "\n";
}

