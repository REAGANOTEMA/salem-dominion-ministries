<?php
// Test API directly by simulating the request
$_SERVER['REQUEST_URI'] = '/salem-dominion-ministries/api/gallery';
$_SERVER['REQUEST_METHOD'] = 'GET';

echo "=== Testing API Directly ===\n";

// Include the API index file
ob_start();
include 'backend/api/index.php';
$output = ob_get_clean();

echo "API Output: " . $output . "\n";
?>
