<?php
require_once 'backend/config/database.php';
$db = new Database();

echo "Checking table structures...\n";

// Check gallery table structure
$result = $db->query("DESCRIBE gallery");
if ($result['success']) {
    echo "\n--- Gallery Table Structure ---\n";
    foreach ($result['data'] as $column) {
        echo "✅ {$column['Field']} ({$column['Type']})\n";
    }
}

// Check news table structure
$result = $db->query("DESCRIBE news");
if ($result['success']) {
    echo "\n--- News Table Structure ---\n";
    foreach ($result['data'] as $column) {
        echo "✅ {$column['Field']} ({$column['Type']})\n";
    }
}

echo "\nTable structure check completed.\n";
?>
