<?php
// Simple PHP server starter
$host = '0.0.0.0';
$port = 8080;

echo "Salem Dominion Ministries - Live Server\n";
echo "====================================\n";
echo "Starting server on http://$host:$port\n";
echo "Document Root: " . __DIR__ . "\n";
echo "Press Ctrl+C to stop\n";
echo "====================================\n";

// Start PHP built-in server
$command = "php -S $host:$port -t " . __DIR__;
exec($command);
?>
