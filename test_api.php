<?php
// Test API endpoints directly
require_once 'backend/config/database.php';
$db = new Database();

echo "Testing API endpoints...\n";

// Test gallery endpoint
echo "\n--- Testing Gallery API ---\n";
$result = $db->query("SELECT * FROM gallery WHERE status = 'published' ORDER BY created_at DESC LIMIT 8");
if ($result['success'] && count($result['data']) > 0) {
    echo "✅ Gallery data found: " . count($result['data']) . " items\n";
    foreach ($result['data'] as $item) {
        echo "  - {$item['title']}\n";
    }
} else {
    echo "❌ No gallery data found\n";
}

// Test news endpoint
echo "\n--- Testing News API ---\n";
$result = $db->query("SELECT * FROM news WHERE status = 'published' ORDER BY created_at DESC LIMIT 6");
if ($result['success'] && count($result['data']) > 0) {
    echo "✅ News data found: " . count($result['data']) . " items\n";
    foreach ($result['data'] as $item) {
        echo "  - {$item['title']}\n";
    }
} else {
    echo "❌ No news data found\n";
}

// Test breaking news
echo "\n--- Testing Breaking News API ---\n";
$result = $db->query("SELECT * FROM news WHERE status = 'published' AND is_breaking = 1 ORDER BY created_at DESC LIMIT 1");
if ($result['success'] && count($result['data']) > 0) {
    echo "✅ Breaking news found: {$result['data'][0]['title']}\n";
} else {
    echo "❌ No breaking news found\n";
}

echo "\nAPI testing completed.\n";
?>
