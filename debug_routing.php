<?php
// Debug routing
$_SERVER['REQUEST_URI'] = '/salem-dominion-ministries/api/gallery';
$_SERVER['REQUEST_METHOD'] = 'GET';

echo "=== Debug Routing ===\n";
echo "REQUEST_URI: " . $_SERVER['REQUEST_URI'] . "\n";

$request_uri = $_SERVER['REQUEST_URI'];
$path = parse_url($request_uri, PHP_URL_PATH);
echo "Parsed Path: " . $path . "\n";

// Remove subdirectory from path if present
$base_path = '/salem-dominion-ministries';
if (strpos($path, $base_path) === 0) {
    $path = substr($path, strlen($base_path));
    echo "After removing base: " . $path . "\n";
}

$path_parts = explode('/', trim($path, '/'));
echo "Path parts: " . print_r($path_parts, true) . "\n";

// Route handling - find the first non-empty part
$endpoint = '';
$id = null;

for ($i = 0; $i < count($path_parts); $i++) {
    $part = $path_parts[$i];
    if (!empty($part)) {
        if (empty($endpoint)) {
            // Skip 'api' if it's the first part
            if ($part !== 'api') {
                $endpoint = $part;
            }
        } elseif ($id === null) {
            $id = $part;
        }
    }
}

echo "Endpoint: " . $endpoint . "\n";
echo "ID: " . $id . "\n";
?>
