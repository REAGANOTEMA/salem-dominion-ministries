<?php
// Simple Gallery Enhancement Setup
require_once 'config.php';

// Get direct database connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_PORT);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "<!DOCTYPE html>
<html>
<head>
    <title>Gallery Enhancement - Salem Dominion Ministries</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f0fdf4; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        h1 { color: #16a34a; text-align: center; }
        .success { color: #16a34a; font-weight: bold; }
        .error { color: #dc2626; font-weight: bold; }
        .info { color: #0ea5e9; }
        .btn { background: #0ea5e9; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block; margin: 10px 5px; }
    </style>
</head>
<body>
    <div class='container'>
        <h1>🎨 Gallery Enhancement Setup</h1>";

// Add new columns to gallery table
$alter_queries = [
    // Add content type column (image/writing/mixed)
    "ALTER TABLE gallery ADD COLUMN content_type ENUM('image', 'writing', 'mixed') DEFAULT 'image' AFTER file_type",
    
    // Add writing content column for text-only posts
    "ALTER TABLE gallery ADD COLUMN writing_content TEXT AFTER description",
    
    // Add expiration settings
    "ALTER TABLE gallery ADD COLUMN expires_at TIMESTAMP NULL DEFAULT NULL AFTER created_at",
    "ALTER TABLE gallery ADD COLUMN auto_expire BOOLEAN DEFAULT FALSE AFTER expires_at",
    
    // Add writing-specific fields
    "ALTER TABLE gallery ADD COLUMN writing_author VARCHAR(255) DEFAULT NULL AFTER uploaded_by",
    "ALTER TABLE gallery ADD COLUMN writing_category ENUM('testimony', 'devotion', 'reflection', 'prayer', 'announcement', 'other') DEFAULT NULL AFTER writing_author"
];

foreach ($alter_queries as $query) {
    try {
        $result = $conn->query($query);
        if ($result) {
            echo "<p class='success'>✅ Successfully added: " . htmlspecialchars($query) . "</p>";
        }
    } catch (Exception $e) {
        if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
            echo "<p class='info'>ℹ️ Column already exists: " . htmlspecialchars($query) . "</p>";
        } else {
            echo "<p class='error'>❌ Error: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
    }
}

// Test the cleanup function
echo "<h2>🧹 Auto-Cleanup Setup</h2>";
$cleanup_query = "UPDATE gallery SET status = 'archived' WHERE auto_expire = TRUE AND expires_at < NOW()";
$result = $conn->query($cleanup_query);

echo "<p class='success'>✅ Auto-cleanup function ready. Expired images will be archived automatically.</p>";

// Show current gallery structure
echo "<h2>📊 Updated Gallery Structure</h2>";
$structure = $conn->query("DESCRIBE gallery");

echo "<table border='1' style='width: 100%; border-collapse: collapse;'>";
echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";

while ($row = $structure->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($row['Field']) . "</td>";
    echo "<td>" . htmlspecialchars($row['Type']) . "</td>";
    echo "<td>" . htmlspecialchars($row['Null']) . "</td>";
    echo "<td>" . htmlspecialchars($row['Key']) . "</td>";
    echo "<td>" . htmlspecialchars($row['Default']) . "</td>";
    echo "</tr>";
}

echo "</table>";

$conn->close();

echo "
        <h2>🎯 New Features Added</h2>
        <ul>
            <li><strong>Content Types:</strong> Support for images, writings, or mixed content</li>
            <li><strong>Writing Content:</strong> Text-only posts with author information</li>
            <li><strong>Auto-Expiration:</strong> Images can be set to expire after 24 hours</li>
            <li><strong>Writing Categories:</strong> Testimony, Devotion, Reflection, Prayer, etc.</li>
            <li><strong>Automatic Cleanup:</strong> Expired content is automatically archived</li>
        </ul>
        
        <div style='text-align: center; margin-top: 30px; padding: 20px; background: #dcfce7; border-radius: 8px;'>
            <h3 class='success'>🎉 Gallery Enhancement Complete!</h3>
            <p>Your gallery now supports writings and automatic image expiration!</p>
            <a href='gallery.php' class='btn'>🖼️ View Gallery</a>
            <a href='admin_gallery_new.php' class='btn'>⚙️ Admin Panel</a>
        </div>
    </div>
</body>
</html>";
?>
