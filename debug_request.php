<?php
// Debug request parsing
echo "=== Request Debug ===\n";
echo "REQUEST_URI: " . $_SERVER['REQUEST_URI'] . "\n";
echo "REQUEST_METHOD: " . $_SERVER['REQUEST_METHOD'] . "\n";
echo "QUERY_STRING: " . $_SERVER['QUERY_STRING'] . "\n";

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
echo "Parsed Path: " . $path . "\n";

$path_parts = explode('/', trim($path, '/'));
echo "Path Parts: " . print_r($path_parts, true) . "\n";

$endpoint = $path_parts[1] ?? '';
echo "Endpoint: " . $endpoint . "\n";

// Test direct gallery access
echo "\n=== Testing Gallery Directly ===\n";
require_once 'backend/config/database.php';
$db = new Database();

$result = $db->query("SELECT * FROM gallery WHERE status = 'published' ORDER BY created_at DESC LIMIT 3");
if ($result['success'] && count($result['data']) > 0) {
    echo "✅ Gallery data found: " . count($result['data']) . " items\n";
    foreach ($result['data'] as $item) {
        echo "  - {$item['title']}\n";
    }
} else {
    echo "❌ No gallery data found\n";
}
?>
