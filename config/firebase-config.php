<?php
require dirname(__DIR__) . '/vendor/autoload.php';

use Kreait\Firebase\Factory;

try {
    $factory = (new Factory)
        ->withServiceAccount(__DIR__ . '/firebase-credentials.json')
        ->withDatabaseUri('https://ultah-81f17-default-rtdb.firebaseio.com');

    $database = $factory->createDatabase();
} catch (Exception $e) {
    error_log($e->getMessage());
    die("Error connecting to Firebase. Please check your configuration.");
}
?>
