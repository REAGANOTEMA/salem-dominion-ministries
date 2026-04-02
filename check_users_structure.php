<?php
require_once 'backend/config/database.php';
$db = new Database();
$conn = $db->getConnection();

$result = $conn->query("DESCRIBE users");
if ($result) {
    echo "Users table structure:\n";
    while ($row = $result->fetch_assoc()) {
        echo "- {$row['Field']}: {$row['Type']}\n";
    }
} else {
    echo "Error: " . $conn->error;
}
?>