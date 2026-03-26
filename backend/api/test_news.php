<?php
// Direct test of news API
require_once '../config/database.php';
$db = new Database();

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

// Test news endpoint
$status = $_GET['status'] ?? '';
$limit = $_GET['limit'] ?? '';
$breaking = $_GET['breaking'] ?? '';

$query = "SELECT * FROM news";
$params = [];

// Handle breaking news endpoint
if ($breaking) {
    $query .= " WHERE status = 'published' AND is_breaking = 1";
    $query .= " ORDER BY created_at DESC LIMIT 1";
} else {
    if ($status) {
        $query .= " WHERE status = ?";
        $params[] = $status;
    }
    
    $query .= " ORDER BY created_at DESC";
    
    if ($limit && is_numeric($limit)) {
        $query .= " LIMIT ?";
        $params[] = (int)$limit;
    }
}

$result = $db->query($query, $params);

echo json_encode([
    'success' => true,
    'data' => $result['data'],
    'count' => count($result['data'])
]);
?>
