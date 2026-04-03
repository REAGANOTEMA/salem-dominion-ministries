<?php
// Create a simple logo file for browser display
header('Content-Type: image/jpeg');
header('Cache-Control: public, max-age=31536000');
header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 31536000) . ' GMT');

// Read the actual logo file
$logo_file = 'assets/images/APOSTLE-IRENE-MIREMBE-CwWfzcRx.jpeg';
if (file_exists($logo_file)) {
    readfile($logo_file);
} else {
    // Create a simple placeholder if file doesn't exist
    echo 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8/5+hHgAADAAABAA';
}
?>
