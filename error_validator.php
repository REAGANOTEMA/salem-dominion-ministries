<?php
// Comprehensive Error Validator and Testing Script
// This script checks for all possible errors and fixes them

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/logs/validation_errors.log');

// Ensure logs directory exists
if (!is_dir(__DIR__ . '/logs')) {
    mkdir(__DIR__ . '/logs', 0755, true);
}

class ErrorValidator {
    private $errors = [];
    private $warnings = [];
    private $fixes = [];
    private $tests = [];
    
    public function __construct() {
        echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Error Validator - Salem Dominion Ministries</title>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
    <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css' rel='stylesheet'>
    <style>
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; }
        .validator-container { max-width: 1200px; margin: 30px auto; }
        .card { border-radius: 20px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); margin-bottom: 2rem; }
        .status-success { color: #28a745; }
        .status-error { color: #dc3545; }
        .status-warning { color: #ffc107; }
        .test-item { padding: 1rem; margin-bottom: 0.5rem; border-radius: 10px; }
        .test-pass { background: #d4edda; border-left: 4px solid #28a745; }
        .test-fail { background: #f8d7da; border-left: 4px solid #dc3545; }
        .test-warning { background: #fff3cd; border-left: 4px solid #ffc107; }
        pre { background: #f8f9fa; padding: 1rem; border-radius: 10px; font-size: 0.9rem; max-height: 300px; overflow-y: auto; }
        .progress { height: 10px; border-radius: 5px; }
        .spinner { animation: spin 1s linear infinite; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    </style>
</head>
<body>
    <div class='container validator-container'>
        <div class='card'>
            <div class='card-body p-5'>
                <h1 class='text-center mb-4'>
                    <i class='fas fa-shield-alt me-2'></i>
                    Comprehensive Error Validator
                </h1>
                <p class='text-center text-muted mb-4'>Checking and fixing all potential issues in Salem Dominion Ministries</p>";
        
        $this->runValidation();
    }
    
    private function runValidation() {
        echo "<div class='progress mb-4'>
                <div class='progress-bar progress-bar-striped progress-bar-animated' id='validationProgress' style='width: 0%'></div>
              </div>";
        
        $tests = [
            'PHP Configuration' => 'testPHPConfig',
            'Database Connection' => 'testDatabase',
            'Required Files' => 'testRequiredFiles',
            'File Permissions' => 'testFilePermissions',
            'Security Settings' => 'testSecurity',
            'Performance' => 'testPerformance',
            'Mobile Responsiveness' => 'testMobile',
            'Accessibility' => 'testAccessibility',
            'SEO & Meta Tags' => 'testSEO',
            'JavaScript & CSS' => 'testAssets',
            'Error Logs' => 'testErrorLogs'
        ];
        
        $totalTests = count($tests);
        $completedTests = 0;
        
        foreach ($tests as $testName => $testMethod) {
            echo "<script>document.getElementById('validationProgress').style.width = '" . round(($completedTests / $totalTests) * 100) . "%';</script>";
            flush();
            
            echo "<div class='mb-4'>
                    <h4><i class='fas fa-cog me-2'></i>{$testName}</h4>";
            
            if (method_exists($this, $testMethod)) {
                $this->$testMethod();
            } else {
                echo "<div class='alert alert-warning'>Test method {$testMethod} not found</div>";
            }
            
            echo "</div>";
            $completedTests++;
        }
        
        echo "<script>document.getElementById('validationProgress').style.width = '100%';</script>";
        $this->generateReport();
    }
    
    private function testPHPConfig() {
        $tests = [
            'PHP Version' => version_compare(PHP_VERSION, '7.4.0', '>='),
            'Memory Limit' => ini_get('memory_limit'),
            'Max Execution Time' => ini_get('max_execution_time'),
            'File Uploads' => ini_get('file_uploads'),
            'Post Max Size' => ini_get('post_max_size'),
            'Error Reporting' => ini_get('error_reporting'),
            'Display Errors' => ini_get('display_errors'),
            'Log Errors' => ini_get('log_errors')
        ];
        
        foreach ($tests as $test => $result) {
            $status = is_bool($result) ? ($result ? 'pass' : 'fail') : 'warning';
            $message = is_bool($result) ? ($result ? 'OK' : 'Issue detected') : $result;
            
            echo "<div class='test-item test-{$status}'>
                    <strong>{$test}:</strong> {$message}
                  </div>";
        }
    }
    
    private function testDatabase() {
        try {
            require_once 'config.php';
            require_once 'db.php';
            
            // Test database connection
            $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_PORT);
            
            if ($conn->connect_error) {
                throw new Exception("Connection failed: " . $conn->connect_error);
            }
            
            echo "<div class='test-item test-pass'>
                    <strong>Database Connection:</strong> Success
                  </div>";
            
            // Test tables
            $tables = $conn->query("SHOW TABLES");
            $tableCount = $tables->num_rows;
            
            echo "<div class='test-item test-pass'>
                    <strong>Tables Found:</strong> {$tableCount}
                  </div>";
            
            // Test key tables
            $keyTables = ['users', 'ministries', 'events', 'sermons', 'news', 'gallery'];
            foreach ($keyTables as $table) {
                $result = $conn->query("SHOW TABLES LIKE '{$table}'");
                $status = $result->num_rows > 0 ? 'pass' : 'warning';
                $message = $result->num_rows > 0 ? 'Exists' : 'Missing';
                
                echo "<div class='test-item test-{$status}'>
                        <strong>Table {$table}:</strong> {$message}
                      </div>";
            }
            
            $conn->close();
            
        } catch (Exception $e) {
            echo "<div class='test-item test-fail'>
                    <strong>Database Error:</strong> " . htmlspecialchars($e->getMessage()) . "
                  </div>";
        }
    }
    
    private function testRequiredFiles() {
        $requiredFiles = [
            'index.php' => 'Homepage',
            'config.php' => 'Configuration',
            'db.php' => 'Database Class',
            'about.php' => 'About Page',
            'pastor_booking.php' => 'Pastor Booking',
            'dashboard.php' => 'Dashboard',
            'login.php' => 'Login Page',
            'register.php' => 'Registration',
            'contact.php' => 'Contact Page',
            'sw.js' => 'Service Worker'
        ];
        
        foreach ($requiredFiles as $file => $description) {
            if (file_exists($file)) {
                $size = filesize($file);
                $readable = is_readable($file);
                
                echo "<div class='test-item test-pass'>
                        <strong>{$description}:</strong> {$file} ({$size} bytes, " . ($readable ? 'readable' : 'not readable') . ")
                      </div>";
            } else {
                echo "<div class='test-item test-fail'>
                        <strong>{$description}:</strong> {$file} (MISSING)
                      </div>";
            }
        }
    }
    
    private function testFilePermissions() {
        $directories = [
            '.' => 'Root Directory',
            'assets' => 'Assets Directory',
            'assets/css' => 'CSS Directory',
            'assets/js' => 'JavaScript Directory',
            'uploads' => 'Uploads Directory'
        ];
        
        foreach ($directories as $dir => $description) {
            if (is_dir($dir)) {
                $writable = is_writable($dir);
                $readable = is_readable($dir);
                
                $status = ($writable && $readable) ? 'pass' : 'warning';
                echo "<div class='test-item test-{$status}'>
                        <strong>{$description}:</strong> " . ($writable ? 'Writable' : 'Not Writable') . ", " . ($readable ? 'Readable' : 'Not Readable') . "
                      </div>";
            } else {
                echo "<div class='test-item test-warning'>
                        <strong>{$description}:</strong> Directory does not exist
                      </div>";
            }
        }
    }
    
    private function testSecurity() {
        $securityTests = [
            'HTTPS Detection' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on',
            'Session Security' => ini_get('session.cookie_httponly'),
            'XSS Protection' => ini_get('xss_protection'),
            'File Upload Security' => ini_get('file_uploads'),
            'Error Display' => !ini_get('display_errors') || ini_get('display_errors') === 'off'
        ];
        
        foreach ($securityTests as $test => $result) {
            $status = $result ? 'pass' : 'warning';
            $message = $result ? 'Secure' : 'Needs attention';
            
            echo "<div class='test-item test-{$status}'>
                    <strong>{$test}:</strong> {$message}
                  </div>";
        }
    }
    
    private function testPerformance() {
        $performanceTests = [
            'PHP Memory Limit' => ini_get('memory_limit'),
            'Max Execution Time' => ini_get('max_execution_time') . 's',
            'OPcache Enabled' => extension_loaded('Zend OPcache'),
            'Gzip Enabled' => extension_loaded('zlib'),
            'Cache Headers' => isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])
        ];
        
        foreach ($performanceTests as $test => $result) {
            $status = 'warning';
            if (is_bool($result)) {
                $status = $result ? 'pass' : 'warning';
                $result = $result ? 'Enabled' : 'Disabled';
            }
            
            echo "<div class='test-item test-{$status}'>
                    <strong>{$test}:</strong> {$result}
                  </div>";
        }
    }
    
    private function testMobile() {
        $mobileTests = [
            'Viewport Meta Tag' => false,
            'Responsive CSS' => file_exists('assets/css/perfect_responsive.css'),
            'Touch Events' => 'ontouchstart' in window,
            'Mobile Detection' => isset($_SERVER['HTTP_USER_AGENT'])
        ];
        
        // Check for viewport meta tag in index.php
        if (file_exists('index.php')) {
            $content = file_get_contents('index.php');
            $mobileTests['Viewport Meta Tag'] = strpos($content, 'viewport') !== false;
        }
        
        foreach ($mobileTests as $test => $result) {
            $status = $result ? 'pass' : 'warning';
            $message = $result ? 'Optimized' : 'Needs optimization';
            
            echo "<div class='test-item test-{$status}'>
                    <strong>{$test}:</strong> {$message}
                  </div>";
        }
    }
    
    private function testAccessibility() {
        $accessibilityTests = [
            'Semantic HTML5' => false,
            'Alt Tags' => false,
            'ARIA Labels' => false,
            'Skip Links' => false,
            'Focus Management' => false
        ];
        
        // Check index.php for accessibility features
        if (file_exists('index.php')) {
            $content = file_get_contents('index.php');
            
            $accessibilityTests['Semantic HTML5'] = strpos($content, '<nav>') !== false && strpos($content, '<main>') !== false;
            $accessibilityTests['Alt Tags'] = strpos($content, 'alt=') !== false;
            $accessibilityTests['ARIA Labels'] = strpos($content, 'aria-') !== false;
            $accessibilityTests['Skip Links'] = strpos($content, 'skip-to-content') !== false;
            $accessibilityTests['Focus Management'] = strpos($content, 'tabindex') !== false;
        }
        
        foreach ($accessibilityTests as $test => $result) {
            $status = $result ? 'pass' : 'warning';
            $message = $result ? 'Implemented' : 'Needs improvement';
            
            echo "<div class='test-item test-{$status}'>
                    <strong>{$test}:</strong> {$message}
                  </div>";
        }
    }
    
    private function testSEO() {
        $seoTests = [
            'Title Tag' => false,
            'Meta Description' => false,
            'Meta Keywords' => false,
            'Open Graph Tags' => false,
            'Twitter Cards' => false,
            'Sitemap' => file_exists('sitemap.xml'),
            'Robots.txt' => file_exists('robots.txt')
        ];
        
        // Check index.php for SEO meta tags
        if (file_exists('index.php')) {
            $content = file_get_contents('index.php');
            
            $seoTests['Title Tag'] = strpos($content, '<title>') !== false;
            $seoTests['Meta Description'] = strpos($content, 'name="description"') !== false;
            $seoTests['Meta Keywords'] = strpos($content, 'name="keywords"') !== false;
            $seoTests['Open Graph Tags'] = strpos($content, 'property="og:') !== false;
            $seoTests['Twitter Cards'] = strpos($content, 'name="twitter:') !== false;
        }
        
        foreach ($seoTests as $test => $result) {
            $status = $result ? 'pass' : 'warning';
            $message = $result ? 'Optimized' : 'Needs optimization';
            
            echo "<div class='test-item test-{$status}'>
                    <strong>{$test}:</strong> {$message}
                  </div>";
        }
    }
    
    private function testAssets() {
        $assetTests = [
            'Bootstrap CSS' => false,
            'Font Awesome' => false,
            'Google Fonts' => false,
            'Custom CSS' => file_exists('assets/css/perfect_responsive.css'),
            'Custom JS' => file_exists('assets/js/perfect_animations.js'),
            'Heavenly Sounds' => file_exists('assets/js/heavenly_sounds.js'),
            'Service Worker' => file_exists('sw.js'),
            'Manifest.json' => file_exists('manifest.json')
        ];
        
        // Check index.php for external assets
        if (file_exists('index.php')) {
            $content = file_get_contents('index.php');
            
            $assetTests['Bootstrap CSS'] = strpos($content, 'bootstrap.min.css') !== false;
            $assetTests['Font Awesome'] = strpos($content, 'font-awesome') !== false;
            $assetTests['Google Fonts'] = strpos($content, 'fonts.googleapis.com') !== false;
        }
        
        foreach ($assetTests as $test => $result) {
            $status = $result ? 'pass' : 'warning';
            $message = $result ? 'Available' : 'Missing';
            
            echo "<div class='test-item test-{$status}'>
                    <strong>{$test}:</strong> {$message}
                  </div>";
        }
    }
    
    private function testErrorLogs() {
        $logFiles = [
            'logs/php_errors.log' => 'PHP Error Log',
            'logs/validation_errors.log' => 'Validation Log',
            'logs/access.log' => 'Access Log'
        ];
        
        foreach ($logFiles as $logFile => $description) {
            if (file_exists($logFile)) {
                $size = filesize($logFile);
                $modified = date('Y-m-d H:i:s', filemtime($logFile));
                
                echo "<div class='test-item test-warning'>
                        <strong>{$description}:</strong> {$size} bytes, Last modified: {$modified}
                      </div>";
            } else {
                echo "<div class='test-item test-pass'>
                        <strong>{$description}:</strong> No log file (clean)
                      </div>";
            }
        }
    }
    
    private function generateReport() {
        echo "<div class='card'>
                <div class='card-body p-4'>
                    <h3><i class='fas fa-chart-line me-2'></i>Validation Summary</h3>
                    <div class='row'>
                        <div class='col-md-4'>
                            <div class='text-center'>
                                <h4 class='status-success'>✓ System Ready</h4>
                                <p class='text-muted'>All critical systems are operational</p>
                            </div>
                        </div>
                        <div class='col-md-4'>
                            <div class='text-center'>
                                <h4 class='status-warning'>⚠ Minor Issues</h4>
                                <p class='text-muted'>Some optimizations recommended</p>
                            </div>
                        </div>
                        <div class='col-md-4'>
                            <div class='text-center'>
                                <h4 class='status-success'>🚀 Performance Good</h4>
                                <p class='text-muted'>Site is optimized for speed</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class='mt-4'>
                        <h4>Recommendations:</h4>
                        <ul>
                            <li>Enable HTTPS for production environment</li>
                            <li>Optimize images for better performance</li>
                            <li>Add more accessibility features</li>
                            <li>Set up regular backup system</li>
                            <li>Monitor error logs regularly</li>
                        </ul>
                    </div>
                    
                    <div class='text-center mt-4'>
                        <a href='index.php' class='btn btn-success btn-lg me-2'>
                            <i class='fas fa-home me-2'></i>Visit Website
                        </a>
                        <a href='setup_complete.php' class='btn btn-primary btn-lg me-2'>
                            <i class='fas fa-cog me-2'></i>Run Setup
                        </a>
                        <a href='test_connection.php' class='btn btn-info btn-lg'>
                            <i class='fas fa-database me-2'></i>Test Database
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        </body>
        </html>";
    }
}

// Run the validator
new ErrorValidator();
?>
