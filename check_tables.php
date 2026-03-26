<?php
require_once 'backend/config/database.php';
$db = new Database();

echo "Checking database tables...\n";

// Check if gallery table exists
$result = $db->query("SHOW TABLES LIKE 'gallery'");
if ($result['success'] && count($result['data']) > 0) {
    echo "✅ Gallery table exists\n";
    
    // Check if gallery has data
    $data = $db->query("SELECT COUNT(*) as count FROM gallery");
    if ($data['success'] && $data['data'][0]['count'] > 0) {
        echo "✅ Gallery table has data\n";
    } else {
        echo "⚠️ Gallery table is empty\n";
    }
} else {
    echo "❌ Gallery table does not exist\n";
}

// Check if news table exists
$result = $db->query("SHOW TABLES LIKE 'news'");
if ($result['success'] && count($result['data']) > 0) {
    echo "✅ News table exists\n";
    
    // Check if news has data
    $data = $db->query("SELECT COUNT(*) as count FROM news");
    if ($data['success'] && $data['data'][0]['count'] > 0) {
        echo "✅ News table has data\n";
    } else {
        echo "⚠️ News table is empty\n";
    }
} else {
    echo "❌ News table does not exist\n";
}

// Check if users table exists
$result = $db->query("SHOW TABLES LIKE 'users'");
if ($result['success'] && count($result['data']) > 0) {
    echo "✅ Users table exists\n";
} else {
    echo "❌ Users table does not exist\n";
}

echo "Database check completed.\n";
?>
