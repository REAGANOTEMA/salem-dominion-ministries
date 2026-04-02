<?php
require_once 'backend/config/database.php';
$db = new Database();

echo "Checking contact_messages table structure...\n";

// Check contact_messages table structure
$result = $db->query("DESCRIBE contact_messages");
if ($result['success']) {
    echo "\n--- Contact Messages Table Structure ---\n";
    foreach ($result['data'] as $column) {
        echo "✅ {$column['Field']} ({$column['Type']})\n";
    }
} else {
    echo "❌ Failed to get contact_messages table structure\n";
}

echo "\nContact messages table check completed.\n";
?>
