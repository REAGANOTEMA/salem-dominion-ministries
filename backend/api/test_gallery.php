<?php
// Direct test of gallery API
require_once '../config/database.php';
$db = new Database();

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

// Test gallery endpoint
$status = $_GET['status'] ?? '';
$limit = $_GET['limit'] ?? '';

$query = "SELECT * FROM gallery";
$params = [];

if ($status) {
    $query .= " WHERE status = ?";
    $params[] = $status;
}

$query .= " ORDER BY created_at DESC";

if ($limit && is_numeric($limit)) {
    $query .= " LIMIT ?";
    $params[] = (int)$limit;
}

$result = $db->query($query, $params);

echo json_encode([
    'success' => true,
    'data' => $result['data'],
    'count' => count($result['data'])
]);
?>
