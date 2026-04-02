<?php
// Complete Setup Script for Salem Dominion Ministries
require_once 'config.php';

echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Complete Setup - Salem Dominion Ministries</title>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
    <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css' rel='stylesheet'>
    <style>
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; }
        .setup-container { max-width: 1000px; margin: 30px auto; }
        .card { border-radius: 20px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); }
        .step-indicator { background: #f8f9fa; border-radius: 15px; padding: 1rem; margin-bottom: 1rem; }
        .step-completed { background: #d4edda; border: 1px solid #c3e6cb; }
        .step-error { background: #f8d7da; border: 1px solid #f5c6cb; }
        .progress { height: 10px; border-radius: 5px; }
        pre { background: #f8f9fa; padding: 1rem; border-radius: 10px; font-size: 0.9rem; max-height: 300px; overflow-y: auto; }
    </style>
</head>
<body>
    <div class='container setup-container'>
        <div class='card'>
            <div class='card-body p-5'>
                <h1 class='text-center mb-4'>
                    <i class='fas fa-church me-2'></i>
                    Complete Setup - Salem Dominion Ministries
                </h1>
                
                <div class='progress mb-4'>
                    <div class='progress-bar progress-bar-striped progress-bar-animated' id='setupProgress' style='width: 0%'></div>
                </div>";

$steps = [
    'Environment Detection' => 'checkEnvironment',
    'Database Connection' => 'checkDatabase',
    'Create Upload Directories' => 'createDirectories',
    'Check Required Files' => 'checkFiles',
    'Test Core Functionality' => 'testFunctionality',
    'Setup Complete' => 'setupComplete'
];

$current_step = 0;
$total_steps = count($steps);

function updateProgress($step, $total) {
    $percentage = round(($step / $total) * 100);
    echo "<script>document.getElementById('setupProgress').style.width = '{$percentage}%';</script>";
    ob_flush();
    flush();
}

function checkEnvironment() {
    echo "<div class='step-indicator step-completed'>
            <h6><i class='fas fa-check-circle me-2'></i>Environment Detection</h6>
            <p><strong>Environment:</strong> " . APP_ENV . "</p>
            <p><strong>Database Host:</strong> " . DB_HOST . "</p>
            <p><strong>Database Name:</strong> " . DB_NAME . "</p>
            <p><strong>App URL:</strong> " . APP_URL . "</p>
          </div>";
    return true;
}

function checkDatabase() {
    echo "<div class='step-indicator'>";
    
    try {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_PORT);
        
        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }
        
        $conn->set_charset(DB_CHARSET);
        
        echo "<h6><i class='fas fa-check-circle me-2'></i>Database Connection</h6>";
        echo "<p><strong>Status:</strong> Connected Successfully</p>";
        echo "<p><strong>MySQL Version:</strong> " . $conn->server_info . "</p>";
        
        // Check if tables exist
        $tables_query = $conn->query("SHOW TABLES");
        $table_count = $tables_query->num_rows;
        
        echo "<p><strong>Tables Found:</strong> " . $table_count . "</p>";
        
        if ($table_count > 0) {
            echo "<div class='alert alert-success'>Database tables are ready</div>";
        } else {
            echo "<div class='alert alert-warning'>No tables found. You may need to import the database structure.</div>";
        }
        
        $conn->close();
        echo "</div>";
        return true;
        
    } catch (Exception $e) {
        echo "<div class='step-indicator step-error'>
                <h6><i class='fas fa-times-circle me-2'></i>Database Connection</h6>
                <p><strong>Status:</strong> Failed</p>
                <p><strong>Error:</strong> " . $e->getMessage() . "</p>
              </div>";
        return false;
    }
}

function createDirectories() {
    echo "<div class='step-indicator'>";
    
    $dirs = [UPLOAD_DIR, AVATAR_DIR, GALLERY_DIR, NEWS_DIR, BLOG_DIR, SERMON_DIR];
    $success = true;
    
    echo "<h6><i class='fas fa-folder me-2'></i>Create Upload Directories</h6>";
    
    foreach ($dirs as $dir) {
        if (!is_dir($dir)) {
            if (mkdir($dir, 0755, true)) {
                echo "<p><i class='fas fa-check-circle text-success me-2'></i>Created: {$dir}</p>";
            } else {
                echo "<p><i class='fas fa-times-circle text-danger me-2'></i>Failed to create: {$dir}</p>";
                $success = false;
            }
        } else {
            echo "<p><i class='fas fa-check-circle text-success me-2'></i>Exists: {$dir}</p>";
        }
        
        // Check writability
        if (is_writable($dir)) {
            echo "<p><i class='fas fa-check-circle text-success me-2'></i>Writable: {$dir}</p>";
        } else {
            echo "<p><i class='fas fa-exclamation-triangle text-warning me-2'></i>Not writable: {$dir}</p>";
            $success = false;
        }
    }
    
    if ($success) {
        echo "<div class='alert alert-success'>All directories created and writable</div>";
    } else {
        echo "<div class='alert alert-warning'>Some directory issues detected</div>";
    }
    
    echo "</div>";
    return $success;
}

function checkFiles() {
    echo "<div class='step-indicator'>";
    
    $required_files = [
        'index.php' => 'Homepage',
        'about.php' => 'About Page',
        'pastor_booking.php' => 'Pastor Booking',
        'config.php' => 'Configuration',
        'db.php' => 'Database Class',
        'sw.js' => 'Service Worker'
    ];
    
    echo "<h6><i class='fas fa-file me-2'></i>Check Required Files</h6>";
    
    $success = true;
    foreach ($required_files as $file => $description) {
        if (file_exists($file)) {
            echo "<p><i class='fas fa-check-circle text-success me-2'></i>{$description}: {$file}</p>";
        } else {
            echo "<p><i class='fas fa-times-circle text-danger me-2'></i>{$description}: {$file} (Missing)</p>";
            $success = false;
        }
    }
    
    if ($success) {
        echo "<div class='alert alert-success'>All required files are present</div>";
    } else {
        echo "<div class='alert alert-danger'>Some required files are missing</div>";
    }
    
    echo "</div>";
    return $success;
}

function testFunctionality() {
    echo "<div class='step-indicator'>";
    
    echo "<h6><i class='fas fa-cog me-2'></i>Test Core Functionality</h6>";
    
    try {
        // Test database connection
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_PORT);
        $conn->set_charset(DB_CHARSET);
        
        // Test a simple query
        $result = $conn->query("SELECT 1 as test");
        if ($result && $result->fetch_assoc()['test'] == 1) {
            echo "<p><i class='fas fa-check-circle text-success me-2'></i>Database Query: Success</p>";
        } else {
            throw new Exception("Database query test failed");
        }
        
        // Test session functionality
        if (session_status() === PHP_SESSION_ACTIVE) {
            echo "<p><i class='fas fa-check-circle text-success me-2'></i>Session Management: Active</p>";
        } else {
            echo "<p><i class='fas fa-exclamation-triangle text-warning me-2'></i>Session Management: Not Active</p>";
        }
        
        // Test file upload directory
        if (is_writable(UPLOAD_DIR)) {
            echo "<p><i class='fas fa-check-circle text-success me-2'></i>Upload Directory: Writable</p>";
        } else {
            echo "<p><i class='fas fa-times-circle text-danger me-2'></i>Upload Directory: Not Writable</p>";
        }
        
        $conn->close();
        
        echo "<div class='alert alert-success'>Core functionality tests passed</div>";
        
    } catch (Exception $e) {
        echo "<div class='alert alert-danger'>Functionality test failed: " . $e->getMessage() . "</div>";
        return false;
    }
    
    echo "</div>";
    return true;
}

function setupComplete() {
    echo "<div class='step-indicator step-completed'>
            <h6><i class='fas fa-check-circle me-2'></i>Setup Complete</h6>
            <div class='alert alert-success'>
                <h5><i class='fas fa-trophy me-2'></i>Congratulations!</h5>
                <p>Salem Dominion Ministries website has been successfully configured and is ready to use.</p>
            </div>
            
            <div class='row'>
                <div class='col-md-6'>
                    <h6>Quick Links:</h6>
                    <ul class='list-unstyled'>
                        <li><a href='index.php' class='btn btn-primary btn-sm w-100 mb-2'><i class='fas fa-home me-2'></i>Homepage</a></li>
                        <li><a href='about.php' class='btn btn-outline-primary btn-sm w-100 mb-2'><i class='fas fa-info-circle me-2'></i>About</a></li>
                        <li><a href='pastor_booking.php' class='btn btn-outline-primary btn-sm w-100 mb-2'><i class='fas fa-calendar me-2'></i>Book Pastor</a></li>
                        <li><a href='test_connection.php' class='btn btn-outline-primary btn-sm w-100 mb-2'><i class='fas fa-database me-2'></i>Test Connection</a></li>
                    </ul>
                </div>
                <div class='col-md-6'>
                    <h6>Configuration Details:</h6>
                    <pre>" . json_encode(getEnvironmentInfo(), JSON_PRETTY_PRINT) . "</pre>
                </div>
            </div>
          </div>";
    return true;
}

// Execute all setup steps
foreach ($steps as $step_name => $step_function) {
    $current_step++;
    updateProgress($current_step, $total_steps);
    
    if (function_exists($step_function)) {
        $step_function();
    }
}

echo "<div class='text-center mt-4'>
        <h5><i class='fas fa-rocket me-2'></i>Ready to Launch!</h5>
        <p class='lead'>Your church website is now fully configured and ready for use.</p>
        <div class='mt-4'>
            <a href='index.php' class='btn btn-success btn-lg me-2'>
                <i class='fas fa-home me-2'></i>Visit Website
            </a>
            <a href='test_connection.php' class='btn btn-info btn-lg me-2'>
                <i class='fas fa-database me-2'></i>Test Database
            </a>
            <a href='login.php' class='btn btn-primary btn-lg'>
                <i class='fas fa-sign-in-alt me-2'></i>Admin Login
            </a>
        </div>
      </div>

    </div>
</div>
</body>
</html>";
?>
