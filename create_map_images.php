<?php
/**
 * Create placeholder images for map locations
 * This script creates simple placeholder images for the church locations
 */

// Create directory if it doesn't exist
$imagesDir = __DIR__ . '/assets/images';
if (!is_dir($imagesDir)) {
    mkdir($imagesDir, 0755, true);
}

// List of images to create
$images = [
    'church-main.jpg' => 'Main Church - Salem Dominion Ministries',
    'church-branch1.jpg' => 'Busowa Branch Church',
    'church-branch2.jpg' => 'Nakavule Branch Church',
    'prayer-center.jpg' => 'Prayer Center',
    'youth-center.jpg' => 'Youth Center',
    'map-preview.jpg' => 'Map Preview'
];

// Create simple placeholder images using GD
foreach ($images as $filename => $text) {
    $filepath = $imagesDir . '/' . $filename;
    
    if (!file_exists($filepath)) {
        // Create a simple 400x300 image
        $width = 400;
        $height = 300;
        $image = imagecreatetruecolor($width, $height);
        
        // Create gradient background
        for ($y = 0; $y < $height; $y++) {
            $color = imagecolorallocate(
                $image,
                255 - ($y * 255 / $height),
                215 - ($y * 100 / $height),
                0
            );
            imageline($image, 0, $y, $width, $y, $color);
        }
        
        // Add text
        $textColor = imagecolorallocate($image, 255, 255, 255);
        $fontSize = 16;
        $textX = $width / 2;
        $textY = $height / 2;
        
        // Use built-in font
        imagestring($image, 5, $textX - strlen($text) * 3, $textY - 10, $text, $textColor);
        
        // Add church icon (simple cross)
        $crossColor = imagecolorallocate($image, 255, 255, 255);
        imageline($image, $width/2 - 20, $height/2 - 40, $width/2 - 20, $height/2 - 20, $crossColor);
        imageline($image, $width/2 - 30, $height/2 - 30, $width/2 - 10, $height/2 - 30, $crossColor);
        imageline($image, $width/2 + 20, $height/2 - 40, $width/2 + 20, $height/2 - 20, $crossColor);
        imageline($image, $width/2 + 10, $height/2 - 30, $width/2 + 30, $height/2 - 30, $crossColor);
        
        // Save the image
        imagejpeg($image, $filepath, 90);
        imagedestroy($image);
        
        echo "Created: $filename\n";
    } else {
        echo "Exists: $filename\n";
    }
}

echo "Map images created successfully!\n";
?>
