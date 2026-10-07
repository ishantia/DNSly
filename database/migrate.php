<?php

require_once __DIR__ . '/../public/index.php'; // to load env and autoloader

$db = \App\Services\Database::getInstance()->getConnection();

$sql = file_get_contents(__DIR__ . '/schema.sql');

try {
    $db->exec($sql);
    echo "Database schema created successfully.\n";
} catch (PDOException $e) {
    echo "Error creating schema: " . $e->getMessage() . "\n";
}
