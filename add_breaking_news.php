<?php
require_once 'backend/config/database.php';
$db = new Database();

echo "Adding breaking news item...\n";

// Update one news item to be breaking news
$result = $db->update(
    "UPDATE news SET is_breaking = 1 WHERE id = 1",
    []
);

if ($result['success']) {
    echo "✅ Updated news item to breaking news\n";
} else {
    echo "❌ Failed to update breaking news\n";
}

// Verify the update
$check = $db->query("SELECT * FROM news WHERE is_breaking = 1");
if ($check['success'] && count($check['data']) > 0) {
    echo "✅ Breaking news found: {$check['data'][0]['title']}\n";
} else {
    echo "❌ No breaking news found\n";
}
?>
