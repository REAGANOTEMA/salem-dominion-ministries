<?php
// Include the map integration
require_once 'map_integration.php';

// Initialize and render the map
$mapIntegration = new MapIntegration();
echo $mapIntegration->generateMap();
?>
