<?php
// Create Perfect Logo with Heavenly Design
error_reporting(0);
ini_set('display_errors', 0);

// Create a beautiful SVG logo with heavenly design
$logo_svg = '<?xml version="1.0" encoding="UTF-8"?>
<svg width="200" height="200" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
  <!-- Background Circle with Heavenly Gradient -->
  <defs>
    <radialGradient id="heavenlyGradient" cx="50%" cy="50%" r="50%">
      <stop offset="0%" style="stop-color:#ffffff;stop-opacity:1" />
      <stop offset="70%" style="stop-color:#dbeafe;stop-opacity:1" />
      <stop offset="100%" style="stop-color:#3b82f6;stop-opacity:1" />
    </radialGradient>
    
    <linearGradient id="goldGradient" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" style="stop-color:#fbbf24;stop-opacity:1" />
      <stop offset="50%" style="stop-color:#f59e0b;stop-opacity:1" />
      <stop offset="100%" style="stop-color:#d97706;stop-opacity:1" />
    </linearGradient>
    
    <filter id="glow">
      <feGaussianBlur stdDeviation="3" result="coloredBlur"/>
      <feMerge>
        <feMergeNode in="coloredBlur"/>
        <feMergeNode in="SourceGraphic"/>
      </feMerge>
    </filter>
  </defs>
  
  <!-- Outer Circle -->
  <circle cx="100" cy="100" r="95" fill="url(#heavenlyGradient)" stroke="#1e3a8a" stroke-width="2"/>
  
  <!-- Inner Decorative Circle -->
  <circle cx="100" cy="100" r="85" fill="none" stroke="url(#goldGradient)" stroke-width="1" opacity="0.8"/>
  
  <!-- Cross - Central Symbol -->
  <g transform="translate(100, 100)">
    <!-- Vertical Cross Bar -->
    <rect x="-8" y="-35" width="16" height="70" fill="url(#goldGradient)" filter="url(#glow)"/>
    <!-- Horizontal Cross Bar -->
    <rect x="-25" y="-8" width="50" height="16" fill="url(#goldGradient)" filter="url(#glow)"/>
    <!-- Cross Top Extension -->
    <rect x="-4" y="-45" width="8" height="15" fill="url(#goldGradient)" filter="url(#glow)"/>
  </g>
  
  <!-- Dove - Peace Symbol -->
  <g transform="translate(100, 60) scale(0.8)">
    <path d="M -20,-5 Q -10,-15 0,-10 Q 10,-15 20,-5 Q 15,0 10,5 Q 5,10 0,5 Q -5,10 -10,5 Q -15,0 -20,-5" 
          fill="#ffffff" stroke="#3b82f6" stroke-width="1" opacity="0.9"/>
  </g>
  
  <!-- Church Name Arc -->
  <path id="namePath" d="M 30,120 Q 100,90 170,120" fill="none"/>
  <text font-family="Great Vibes, cursive" font-size="16" fill="#1e3a8a" font-weight="bold">
    <textPath href="#namePath" startOffset="50%" text-anchor="middle">
      Salem Dominion
    </textPath>
  </text>
  
  <!-- Ministries Text -->
  <text x="100" y="140" font-family="Montserrat, sans-serif" font-size="8" fill="#1e3a8a" text-anchor="middle" font-weight="600">
    MINISTRIES
  </text>
  
  <!-- Decorative Stars -->
  <g opacity="0.6">
    <circle cx="40" cy="40" r="2" fill="#fbbf24"/>
    <circle cx="160" cy="40" r="2" fill="#fbbf24"/>
    <circle cx="40" cy="160" r="2" fill="#fbbf24"/>
    <circle cx="160" cy="160" r="2" fill="#fbbf24"/>
    <circle cx="30" cy="100" r="1.5" fill="#fbbf24"/>
    <circle cx="170" cy="100" r="1.5" fill="#fbbf24"/>
  </g>
  
  <!-- Bottom Banner -->
  <rect x="20" y="155" width="160" height="25" rx="12" fill="#1e3a8a" opacity="0.8"/>
  <text x="100" y="172" font-family="Montserrat, sans-serif" font-size="10" fill="#ffffff" text-anchor="middle" font-weight="500">
    Iganga • Uganda
  </text>
  
  <!-- Divine Light Rays -->
  <g opacity="0.3">
    <path d="M 100,20 L 95,35 L 105,35 Z" fill="#fbbf24"/>
    <path d="M 180,100 L 165,95 L 165,105 Z" fill="#fbbf24"/>
    <path d="M 100,180 L 95,165 L 105,165 Z" fill="#fbbf24"/>
    <path d="M 20,100 L 35,95 L 35,105 Z" fill="#fbbf24"/>
  </g>
</svg>';

// Save the SVG logo
file_put_contents('public/images/logo_perfect.svg', $logo_svg);

// Create PNG version using GD (if available)
if (extension_loaded('gd')) {
    // Create image canvas
    $img = imagecreatetruecolor(200, 200);
    
    // Enable alpha blending
    imagealphablending($img, true);
    imagesavealpha($img, true);
    
    // Fill background with white
    $white = imagecolorallocate($img, 255, 255, 255);
    imagefill($img, 0, 0, $white);
    
    // Colors
    $blue = imagecolorallocate($img, 30, 58, 138);
    $light_blue = imagecolorallocate($img, 59, 130, 246);
    $gold = imagecolorallocate($img, 251, 191, 36);
    
    // Draw outer circle
    imagefilledellipse($img, 100, 100, 190, 190, $light_blue);
    imageellipse($img, 100, 100, 190, 190, $blue);
    
    // Draw cross
    imagefilledrectangle($img, 92, 65, 108, 135, $gold);
    imagefilledrectangle($img, 75, 92, 125, 108, $gold);
    imagefilledrectangle($img, 96, 55, 104, 65, $gold);
    
    // Add text
    $text_color = imagecolorallocate($img, 30, 58, 138);
    imagettftext($img, 8, 0, 100, 145, $text_color, 'arial.ttf', 'SALEM DOMINION');
    imagettftext($img, 6, 0, 100, 160, $text_color, 'arial.ttf', 'MINISTRIES');
    
    // Save as PNG
    imagepng($img, 'public/images/logo_perfect.png');
    imagedestroy($img);
}

echo "Perfect logo created successfully!";
?>
