<?php
require_once 'backend/config/database.php';
$db = new Database();

echo "Checking donations table structure...\n";

// Check donations table structure
$result = $db->query("DESCRIBE donations");
if ($result['success']) {
    echo "\n--- Donations Table Structure ---\n";
    foreach ($result['data'] as $column) {
        echo "✅ {$column['Field']} ({$column['Type']})\n";
    }
} else {
    echo "❌ Failed to get donations table structure\n";
}

echo "\nDonations table check completed.\n";
?>
