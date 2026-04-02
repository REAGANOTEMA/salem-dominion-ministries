<?php
require_once 'backend/config/database.php';
$db = new Database();

echo "Checking users table structure...\n";

// Check users table structure
$result = $db->query("DESCRIBE users");
if ($result['success']) {
    echo "\n--- Users Table Structure ---\n";
    foreach ($result['data'] as $column) {
        echo "✅ {$column['Field']} ({$column['Type']})\n";
    }
} else {
    echo "❌ Failed to get users table structure\n";
}

echo "\nUsers table check completed.\n";
?>
