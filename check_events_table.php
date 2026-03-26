<?php
require_once 'backend/config/database.php';
$db = new Database();

echo "Checking events table structure...\n";

// Check events table structure
$result = $db->query("DESCRIBE events");
if ($result['success']) {
    echo "\n--- Events Table Structure ---\n";
    foreach ($result['data'] as $column) {
        echo "✅ {$column['Field']} ({$column['Type']})\n";
    }
} else {
    echo "❌ Failed to get events table structure\n";
}

echo "\nEvents table check completed.\n";
?>
