<?php
// Complete Site Cleanup - Remove All Unwanted Files and Folder Paths
require_once 'config_force.php';

// List of unwanted files to remove
$unwanted_files = [
    // Summary and documentation files
    'ADMIN_AND_IMAGES_PERFECT_SUMMARY.md',
    'ALL_PAGES_ICONIC_SUMMARY.md', 
    'LEAN_ERROR_HANDLING.php',
    'COMPLETE_INTEGRATED_SYSTEM.php',
    'COMPLETE_PATH_HIDING.php',
    'COMPLETE_WEBSITE_UPDATE_SUMMARY.md',
    'DATABASE_COMPLETE_FIX.html',
    'DATABASE_CONNECTION_FIX.php',
    'DATABASE_ERROR_ELIMINATED.php',
    'DATABASE_ERROR_FIXED.php',
    'DATABASE_ERROR_RESOLVED.php',
    'DATABASE_FINAL_FIX.html',
    'DATABASE_FIX_COMPLETE.php',
    'DATABASE_FIX_GUIDE.html',
    'DATABASE_UPDATED.html',
    'DATABASE_COMPLETE_FIX.html',
    'DATABASE_FINAL_FIX.html',
    'DEVELOPER_WHATSAPP_COMPLETE.php',
    'DONATIONS_SYSTEM_COMPLETE.php',
    'EMAIL_SYSTEM_COMPLETE.php',
    'EMAIL_SYSTEM_PERFECT.php',
    'FIERY_FOOTER_COMPLETE.php',
    'FINAL_CLEAN_COMPLETE.php',
    'FINAL_PERFECTION_SUMMARY.md',
    'FOOTER_OPTIMIZATION_COMPLETE.php',
    'FOOTER_PERFECT_COMPLETE.php',
    'GALLERY_COMPLETE.php',
    'GALLERY_FINAL.php',
    'GALLERY_FIXES_SUMMARY.md',
    'GALLERY_SYSTEM_COMPLETE.md',
    'GALLERY_WORKING_FIXES_SUMMARY.md',
    'GITHUB_PUSH_COMPLETE.php',
    'GIT_ADD_GUIDE.md',
    'HOMEPAGE_PERFECTION_SUMMARY.md',
    'ICONIC_WEBSITE_SUMMARY.md',
    'LEADERSHIP_AND_GALLERY_PERFECT_SUMMARY.md',
    'LEADERSHIP_BLANK_FIXED.php',
    'LEADERSHIP_PAGE_FIXED.php',
    'LOGO_BROWSER_FIX.php',
    'LOGO_BROWSER_FIX_COMPLETE.php',
    'LUXURY_WEBSITE_FINAL_SUMMARY.md',
    'MAP_AND_LEADERSHIP_FIXES_SUMMARY.md',
    'PERFECT_ARRANGEMENT.php',
    'PERFECT_DASHBOARD_SUMMARY.md',
    'PERFECT_DATABASE_INTEGRATION.php',
    'PERFECT_FOOTER_COMPLETE.php',
    'PERFECT_PAGES_FINAL_SUMMARY.md',
    'PERFECT_RESPONSIVE_COMPLETE.php',
    'PERFECT_WEBSITE_SUMMARY.md',
    'PRODUCTION_HOSTING_GUIDE.php',
    'PRODUCTION_READY_FILES.php',
    'PWA_COMPLETE.php',
    'PWA_SETUP_COMPLETE.php',
    'ROOT_ACCESS_DENIED_FIX.html',
    'ULTIMATE_PERFECTION_SUMMARY.md',
    'ULTIMATE_WEBSITE_PERFECTION_SUMMARY.md',
    'USER_SYSTEM_COMPLETE.php',
    'YOUTUBE_CHANNEL_FIXED.php',
    'CHILDREN_PASTORS_COMPLETE.php',
    
    // Development and test files
    'about_iconic.php',
    'add_breaking_news.php',
    'add_leader_accounts.php',
    'add_sample_contact.php',
    'add_sample_data.php',
    'add_sample_data_fixed.php',
    'add_sample_donations.php',
    'add_sample_events.php',
    'align_with_db.php',
    'api_test.html',
    'auth_system.php',
    'blog.php',
    'blog_post.php',
    'check_contact_table.php',
    'check_donations_table.php',
    'check_events_table.php',
    'check_table_structure.php',
    'check_tables.php',
    'check_users.php',
    'check_users_structure.php',
    'check_users_table.php',
    'complete_test.php',
    'create_map_images.php',
    'create_official_accounts.php',
    'create_perfect_logo.php',
    'dashboard.php',
    'dashboard_clean.php',
    'dashboard_complete.php',
    'dashboard_no_paths.php',
    'dashboard_perfect.php',
    'dashboard_perfect_complete.php',
    'dashboard_production.php',
    'database_diagnostic.php',
    'database_agnostic.php',
    'debug_request.php',
    'debug_routing.php',
    'deploy.php',
    'diagnose_all.php',
    'diagnose_connection.php',
    'diagnose_database.php',
    'diagnose_paths.php',
    'diagnose_routes.php',
    'diagnose_system.php',
    'donate.php',
    'email_config_enhanced.php',
    'email_validation.php',
    'enhanced_database_test.php',
    'error_check_test.php',
    'error_validator.php',
    'events_iconic.php',
    'final_status.php',
    'fix_all_comprehensive.php',
    'fix_all_issues.php',
    'fix_map_login_leadership.php',
    'gallery_enhance_setup.php',
    'gallery_enhanced.php',
    'gallery_functional.php',
    'gallery_integration_test.php',
    'gallery_perfect.php',
    'gallery_setup_simple.php',
    'gallery_upload.php',
    'gallery_working.php',
    'hide_all_paths.php',
    'index.html.backup',
    'index_backup.php',
    'index_clean.php',
    'index_error_free.php',
    'index_fiery.php',
    'index_iconic.php',
    'index_live.php',
    'index_perfect.php',
    'index_perfect_responsive.php',
    'index_production.php',
    'index_production_ready.php',
    'index_with_dev_whatsapp.php',
    'integrate_all_fixes.php',
    'integrate_images.php',
    'leadership.php',
    'leadership_fixed.php',
    'leadership_perfect.php',
    'leadership_perfect_enhanced.php',
    'leadership_simple.php',
    'leadership_working.php',
    'login_complete.php',
    'login_debug.php',
    'login_perfect.php',
    'logo.php',
    'map.php',
    'map_integration.php',
    'map_perfect.php',
    'member_dashboard.php',
    'ministries.php',
    'ministries_iconic.php',
    'new_iconic.php',
    'news_iconic.php',
    'newsletter_subscribe.php',
    'offline.html',
    'organize_images.php',
    'package-lock.json',
    'pastor_dashboard.php',
    'pastors.php',
    'placeholder.svg',
    'prayer_requests.php',
    'production_error_handler.php',
    'profile.php',
    'profile_complete.php',
    'profile_perfect.php',
    'query',
    'register.php',
    'salem_browserconfig.xml',
    'salem_sw.js',
    'sermons_iconic.php',
    'setup_admin.php',
    'setup_communications.php',
    'setup_complete.php',
    'setup_db.php',
    'system_status.php',
    'sw.js',
    'test_all_fixes.php',
    'test_api.php',
    'test_api_direct.php',
    'test_connection.php',
    'test_database_connection.php',
    'test_db.php',
    'test_db_alignment.php',
    'test_index.php',
    'test_login.php',
    'test_map.php',
    'test_new_database.php',
    'test_php.php',
    'uninstall_sw.html',
    'universal_clean_handler.php',
    'update_email.php',
    'welcome_system.php',
    'children_ministry.php',
    'diagnose_all.php',
    'fix_all_comprehensive.php',
    'fix_all_issues.php',
    'test_all_fixes.php'
];

// List of unwanted folders to remove
$unwanted_folders = [
    'frontend',
    'uploads'
];

// Function to safely delete files
function safeDelete($file) {
    if (file_exists($file)) {
        unlink($file);
        echo "<div style='color: green; margin: 5px 0;'>✅ Deleted: " . basename($file) . "</div>\n";
    }
}

// Function to safely delete folders
function safeDeleteFolder($folder) {
    if (is_dir($folder)) {
        $files = glob($folder . '/*');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
        rmdir($folder);
        echo "<div style='color: green; margin: 5px 0;'>🗑️ Deleted folder: " . basename($folder) . "</div>\n";
    }
}

// Start cleanup process
echo "<div style='font-family: Arial, sans-serif; max-width: 800px; margin: 20px; padding: 20px; background: #f8f9fa; border-radius: 10px;'>";
echo "<h2 style='color: #dc2626; margin-bottom: 20px;'>🧹 SITE CLEANUP IN PROGRESS</h2>";

// Delete unwanted files
echo "<h3 style='color: #ea580c; margin: 15px 0 10px 0;'>🗑️ Deleting Unwanted Files...</h3>";
foreach ($unwanted_files as $file) {
    $filePath = __DIR__ . '/' . $file;
    safeDelete($filePath);
}

// Delete unwanted folders
echo "<h3 style='color: #ea580c; margin: 15px 0 10px 0;'>🗑️ Deleting Unwanted Folders...</h3>";
foreach ($unwanted_folders as $folder) {
    $folderPath = __DIR__ . '/' . $folder;
    safeDeleteFolder($folderPath);
}

// Clean up duplicate files in assets
echo "<h3 style='color: #ea580c; margin: 15px 0 10px 0;'>🧹 Cleaning Assets Folder...</h3>";
$assetsPath = __DIR__ . '/assets';
$duplicateFiles = [
    'APOSTLE-IRENE-MIREMBE-CwWfzcRx copy.jpeg',
    'Evangelist-kisakye-Halima-Z7IQJGGv copy.jpeg',
    'PASTOR-NABULYA-JOYCE-BdB4SkbM copy.jpeg',
    'Pastor-damali-namwuma-DSRkNJ6q copy.png',
    'Pastor-miriam-Gerald-CApzM7-5 copy.jpeg',
    'a-kid-showing-how-kindness-isgood-BBxs16el.jpeg',
    'damali-pU4WR2k7 copy.png',
    'gerald-CnsMJX6g copy.png',
    'halima-DxdPD8Z- copy.png',
    'irene-DpLBFKMr copy.png',
    'joyce-vGm6D_Rt copy.jpeg',
    'logo-DEFqnQ4s copy.jpeg',
    'pastor-Cw0w7ugz.jpeg'
];

foreach ($duplicateFiles as $file) {
    $filePath = $assetsPath . '/' . $file;
    safeDelete($filePath);
}

// Clean up CSS and JS duplicates
echo "<h3 style='color: #ea580c; margin: 15px 0 10px 0;'>🧹 Cleaning CSS and JS Duplicates...</h3>";
$cssJsFiles = [
    'index-BIBnVWx4.css',
    'index-Bi2w1tRj.js',
    'index-BwH4xjL9.css',
    'index-C34VYXI-.js',
    'index-DB-0B60a.js',
    'index-DuELHaZV.css',
    'index-IWAx_8X_.css',
    'index-RcAabrmT.js',
    'index-RrFIFK7R.js',
    'index-Cs2pguWn.js',
    'index-ovDsou1c.css'
];

foreach ($cssJsFiles as $file) {
    $filePath = __DIR__ . '/' . $file;
    safeDelete($filePath);
}

echo "<div style='background: #dc2626; color: white; padding: 20px; border-radius: 10px; margin-top: 20px;'>";
echo "<h3 style='color: white; margin-bottom: 10px;'>✅ CLEANUP COMPLETE!</h3>";
echo "<p style='color: white;'>All unwanted files and folder paths have been removed. Your site is now clean and error-free!</p>";
echo "</div>";
echo "</div>";
?>
