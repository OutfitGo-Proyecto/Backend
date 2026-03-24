<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

echo "PHP Version: " . PHP_VERSION . "\n";
echo "Laravel Version: " . $app->version() . "\n";

if (function_exists('fake')) {
    echo "fake() helper is DEFINED\n";
    try {
        echo "fake() output: " . fake()->name() . "\n";
    } catch (\Error $e) {
        echo "fake() call failed: " . $e->getMessage() . "\n";
    }
} else {
    echo "fake() helper is UNDEFINED\n";
}

if (class_exists(\Faker\Factory::class)) {
    echo "Faker\Factory class EXISTS\n";
} else {
    echo "Faker\Factory class MISSING\n";
}
