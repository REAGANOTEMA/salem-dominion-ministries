<?php
// Aggressive Cleanup - Remove ALL Unwanted Files
require_once 'config_force.php';

// Essential files to KEEP (only these)
$essential_files = [
    '.env',
    '.htaccess', 
    'favicon.ico',
    'manifest.json',
    'salem_browserconfig.xml',
    'salem_dominion_ministries.sql',
    'salem_sw.js',
    'sw.js',
    'package.json',
    'package-lock.json',
    'offline.html',
    'unregister_sw.html',
    'placeholder.svg',
    'query',
    
    // Perfect pages (keep only these versions)
    'index_final.php',
    'children.php', 
    'pastors.php',
    'about.php',
    'contact.php',
    'events.php',
    'gallery.php',
    'sermons.php',
    'register.php',
    'login.php',
    'book_pastor.php',
    'donations.php',
    'ministries.php',
    'profile.php',
    'map.php',
    'prayer_requests.php',
    'testimonials.php',
    'news.php',
    'blog.php',
    'logout.php',
    'api.php',
    'db.php',
    'session_helper.php',
    
    // Essential directories
    'assets/',
    'components/',
    'public/',
    'email_templates/',
    'uploads/',
    'icons/',
    'frontend/',
    '.git/'
];

echo "<div style='font-family: Arial, sans-serif; max-width: 900px; margin: 20px auto; padding: 20px; background: #f8f9fa; border-radius: 10px;'>";
echo "<h2 style='color: #dc2626; margin-bottom: 20px;'>🧹 AGGRESSIVE CLEANUP - Remove All Unwanted Files</h2>";

echo "<h3 style='color: #ea580c; margin: 15px 0;'>🗑️ Removing ALL Unwanted Files...</h3>";

// Get all files in directory
$all_files = scandir(__DIR__);
$deleted_count = 0;
$kept_count = 0;

foreach ($all_files as $file) {
    if ($file === '.' || $file === '..') {
        continue;
    }
    
    $filePath = __DIR__ . '/' . $file;
    
    if (in_array($file, $essential_files)) {
        echo "<div style='color: #16a34a; margin: 3px 0;'>✅ Keeping: " . $file . "</div>\n";
        $kept_count++;
    } else {
        if (is_file($filePath)) {
            unlink($filePath);
            echo "<div style='color: #dc2626; margin: 3px 0; font-weight: 600;'>🗑️ Deleted: " . $file . "</div>\n";
            $deleted_count++;
        } elseif (is_dir($filePath)) {
            // Delete directory and all contents
            $dir_files = scandir($filePath);
            foreach ($dir_files as $dir_file) {
                if ($dir_file === '.' || $dir_file === '..') {
                    continue;
                }
                $full_path = $filePath . '/' . $dir_file;
                if (is_file($full_path)) {
                    unlink($full_path);
                } elseif (is_dir($full_path)) {
                    // Recursively delete subdirectory
                    $sub_files = scandir($full_path);
                    foreach ($sub_files as $sub_file) {
                        if ($sub_file === '.' || $sub_file === '..') {
                            continue;
                        }
                        $sub_path = $full_path . '/' . $sub_file;
                        if (is_file($sub_path)) {
                            unlink($sub_path);
                        } elseif (is_dir($sub_path)) {
                            $sub_sub_files = scandir($sub_path);
                            foreach ($sub_sub_files as $sub_sub_file) {
                                if ($sub_sub_file === '.' || $sub_sub_file === '..') {
                                    continue;
                                }
                                $sub_sub_path = $sub_path . '/' . $sub_sub_file;
                                if (is_file($sub_sub_path)) {
                                    unlink($sub_sub_path);
                                }
                            }
                            rmdir($sub_path);
                        }
                    }
                    rmdir($full_path);
                }
            }
            rmdir($filePath);
            echo "<div style='color: #dc2626; margin: 3px 0; font-weight: 600;'>🗑️ Deleted directory: " . $file . "</div>\n";
            $deleted_count++;
        }
    }
}

echo "<div style='background: #dc2626; color: white; padding: 20px; border-radius: 10px; margin-top: 20px;'>";
echo "<h3 style='color: white; margin-bottom: 10px;'>✅ CLEANUP COMPLETE!</h3>";
echo "<p style='color: white; font-size: 18px; font-weight: bold;'>Deleted: " . $deleted_count . " files</p>";
echo "<p style='color: white; font-size: 18px; font-weight: bold;'>Kept: " . $kept_count . " essential files</p>";
echo "</div>";
echo "</div>";
?>
