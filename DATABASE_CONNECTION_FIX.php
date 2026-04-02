<?php
// Database Connection Fix for Hosting
echo "<!DOCTYPE html>
<html>
<head>
    <title>🔧 Database Connection Fix - Salem Dominion Ministries</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%); }
        .container { max-width: 1000px; margin: 0 auto; background: white; padding: 40px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        h1 { color: #dc2626; text-align: center; margin-bottom: 30px; font-size: 2.5rem; }
        h2 { color: #0ea5e9; border-bottom: 3px solid #0ea5e9; padding-bottom: 10px; margin-top: 30px; }
        .error { background: linear-gradient(135deg, #fef2f2 0%, #fecaca 100%); padding: 20px; border-radius: 10px; margin: 15px 0; border-left: 5px solid #dc2626; }
        .success { background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%); padding: 20px; border-radius: 10px; margin: 15px 0; border-left: 5px solid #16a34a; }
        .warning { background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); padding: 20px; border-radius: 10px; margin: 15px 0; border-left: 5px solid #f59e0b; }
        .btn { background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%); color: white; padding: 12px 25px; text-decoration: none; border-radius: 25px; display: inline-block; margin: 8px; font-weight: 600; transition: all 0.3s ease; }
        .btn:hover { transform: translateY(-2px); box-shadow: 0 10px 25px rgba(14, 165, 233, 0.3); }
        .btn-danger { background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%); }
        .btn-success { background: linear-gradient(135deg, #16a34a 0%, #15803d 100%); }
        .code-block { background: #1e293b; color: #e2e8f0; padding: 15px; border-radius: 10px; font-family: monospace; font-size: 0.85rem; margin: 10px 0; overflow-x: auto; }
        .file-list { background: #f8fafc; padding: 15px; border-radius: 10px; font-family: monospace; font-size: 0.9rem; }
        .checklist { list-style: none; padding: 0; }
        .checklist li { padding: 10px 0; border-bottom: 1px solid #e5e7eb; }
        .checklist li:before { content: '✅ '; margin-right: 10px; color: #16a34a; }
        .feature-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin: 20px 0; }
        .feature-card { background: #f8fafc; padding: 20px; border-radius: 10px; border-left: 4px solid #0ea5e9; }
    </style>
</head>
<body>
    <div class='container'>
        <h1>🔧 Database Connection Fix</h1>
        
        <div class='error'>
            <h2>❌ Database Connection Error</h2>
            <p><strong>Error:</strong> Access denied for user 'root'@'localhost' (using password: YES)</p>
            <p>This error occurs when your hosting platform doesn't recognize your database credentials or when localhost detection fails.</p>
        </div>

        <div class='warning'>
            <h2>🔍 Root Cause Analysis</h2>
            <div class='file-list'>
                <strong>Current Issue:</strong><br>
                • Your hosting platform is detecting as 'localhost'<br>
                • But trying to connect with 'root' user<br>
                • Production database credentials are not being used<br>
                • Environment detection is failing<br><br>
                
                <strong>Why This Happens:</strong><br>
                • Hosting platforms have different server names<br>
                • Database credentials are different from local<br>
                • .env file may not be loading correctly<br>
                • Server environment variables may override settings<br><br>
                
                <strong>Common Hosting Issues:</strong><br>
                • cPanel hosting uses different database host<br>
                • Database user is not 'root' on hosting<br>
                • Database name may be different<br>
                • Port may be different (not 3306)
            </div>
        </div>
        
        <div class='success'>
            <h2>🛠️ Quick Fix Solutions</h2>
            <div class='feature-grid'>
                <div class='feature-card'>
                    <h4>🔧 Solution 1: Update .env File</h4>
                    <p>Update your .env file with hosting database credentials from your hosting provider:</p>
                    <div class='code-block'>
# Database Configuration
DB_HOST=localhost
DB_USER=salemdominionmin_db
DB_PASSWORD=22uHzNYEHwUsFKdVz3wT
DB_NAME=salem_dominion_ministries
DB_CHARSET=utf8mb4
DB_PORT=3306
                    </div>
                </div>
                
                <div class='feature-card'>
                    <h4>🔧 Solution 2: Update config.php</h4>
                    <p>Modify your config.php to force production settings:</p>
                    <div class='code-block'>
// Force Production Settings
define('DB_HOST', 'localhost');
define('DB_USER', 'salemdominionmin_db');
define('DB_PASSWORD', '22uHzNYEHwUsFKdVz3wT');
define('DB_NAME', 'salem_dominion_ministries');
define('DB_CHARSET', 'utf8mb4');
define('DB_PORT', 3306);
                    </div>
                </div>
                
                <div class='feature-card'>
                    <h4>🔧 Solution 3: Get Hosting Credentials</h4>
                    <p>Contact your hosting provider for correct database credentials:</p>
                    <ul class='checklist'>
                        <li>Database Host (often localhost or specific IP)</li>
                        <li>Database User (usually not 'root')</li>
                        <li>Database Password</li>
                        <li>Database Name</li>
                        <li>Database Port (usually 3306)</li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class='warning'>
            <h2>📋 Step-by-Step Fix Process</h2>
            <div class='file-list'>
                <strong>Step 1: Get Hosting Database Info</strong><br>
                • Login to your hosting control panel (cPanel, Plesk, etc.)<br>
                • Find "MySQL Databases" or "Database Wizard"<br>
                • Note down: Host, User, Password, Database Name<br><br>
                
                <strong>Step 2: Update .env File</strong><br>
                • Edit .env file with hosting credentials<br>
                • Replace localhost with actual host if needed<br>
                • Replace root with actual database user<br>
                • Update password and database name<br><br>
                
                <strong>Step 3: Test Connection</strong><br>
                • Upload updated files to hosting<br>
                • Test database connection<br>
                • Check if error is resolved<br><br>
                
                <strong>Step 4: Common Hosting Hosts</strong><br>
                • cPanel: localhost or 127.0.0.1<br>
                • Some hosts: specific IP address<br>
                • Remote MySQL: hostname provided by host<br>
                • Port: Usually 3306, sometimes 2083
            </div>
        </div>
        
        <div class='success'>
            <h2>🔧 Alternative: Create New config.php</h2>
            <p>Create a production-ready config.php file that bypasses environment detection:</p>
            <div class='code-block'>
<?php
// Production Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'salemdominionmin_db');
define('DB_PASSWORD', '22uHzNYEHwUsFKdVz3wT');
define('DB_NAME', 'salem_dominion_ministries');
define('DB_CHARSET', 'utf8mb4');
define('DB_PORT', 3306);

// Test Connection
try {
    \$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_PORT);
    if (\$conn->connect_error) {
        die('Connection failed: ' . \$conn->connect_error);
    }
    echo 'Database connected successfully!';
    \$conn->close();
} catch (Exception \$e) {
    die('Connection error: ' . \$e->getMessage());
}
?>
            </div>
        </div>
        
        <div class='error'>
            <h2>⚠️ Important Security Note</h2>
            <p><strong>Never expose database credentials in public files!</strong></p>
            <ul class='checklist'>
                <li>Keep .env file outside public web directory</li>
                <li>Use strong, unique passwords</li>
                <li>Limit database user permissions</li>
                <li>Enable SSL for database connections if available</li>
                <li>Regular backup your database</li>
            </ul>
        </div>
        
        <div class='feature-grid'>
            <div class='feature-card'>
                <h4>🌐 Hosting Platform Specifics</h4>
                <p><strong>cPanel:</strong> Database host is usually 'localhost'</p>
                <p><strong>Plesk:</strong> May use specific database server</p>
                <p><strong>Shared Hosting:</strong> Database user is often your account name</p>
                <p><strong>VPS/Dedicated:</strong> Full control over database settings</p>
            </div>
            
            <div class='feature-card'>
                <h4>📞 Contact Information</h4>
                <p><strong>Hosting Support:</strong> Contact for correct credentials</p>
                <p><strong>Database Documentation:</strong> Check hosting knowledge base</p>
                <p><strong>cPanel Database:</strong> Usually in "MySQL Databases" section</p>
                <p><strong>Control Panel:</strong> Look for "Database Wizard"</p>
            </div>
            
            <div class='feature-card'>
                <h4>🔍 Testing Connection</h4>
                <p>Create a test file to verify database connection:</p>
                <div class='code-block'>
<?php
// Test Database Connection
include 'config.php';

try {
    \$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_PORT);
    if (\$conn->connect_error) {
        echo 'Connection failed: ' . \$conn->connect_error;
    } else {
        echo 'Database connected successfully!';
        \$conn->close();
    }
} catch (Exception \$e) {
    echo 'Connection error: ' . \$e->getMessage();
}
?>
                </div>
            </div>
        </div>
        
        <div style='text-align: center; margin-top: 40px; padding: 30px; background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%); border-radius: 15px; color: white;'>
            <h3 style='color: white; margin-bottom: 20px;'>🔧 Database Connection Fix Required!</h3>
            <p style='color: white; margin-bottom: 20px;'>Update your database credentials for hosting environment!</p>
            <a href='config.php' class='btn btn-danger' style='background: white; color: #dc2626;'>🔧 Edit config.php</a>
            <a href='.env' class='btn btn-danger' style='background: white; color: #dc2626;'>📝 Edit .env</a>
            <a href='index.php' class='btn btn-success' style='background: white; color: #16a34a;'>🏠 Back to Website</a>
        </div>
        
        <div style='text-align: center; margin-top: 30px; padding: 20px; background: #fef3c7; border-radius: 10px; border: 2px solid #f59e0b;'>
            <h3 style='color: #f59e0b;'>⚡ Quick Action Required</h3>
            <p style='color: #f59e0b; font-weight: 600;'>Update database credentials to fix connection error!</p>
            <p style='color: #f59e0b;'>Contact your hosting provider for correct database information.</p>
        </div>
    </div>
</body>
</html>";
?>
