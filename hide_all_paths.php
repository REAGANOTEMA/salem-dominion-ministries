<?php
// Universal Path Hider - Include this at the TOP of EVERY PHP file
// This will hide all file paths from error messages and make your site production-ready

// Set production error reporting - hide all errors from users
error_reporting(0);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/uploads/clean_error.log');

// Custom error handler that removes file paths
function hide_paths_error_handler($errno, $errstr, $errfile, $errline) {
    // Don't show any errors to users
    if (error_reporting() === 0) {
        return false;
    }
    
    // Remove all file paths from error message
    $clean_message = remove_all_paths($errstr);
    
    // Log clean error message (without file paths)
    $log_message = "Error [{$errno}]: {$clean_message} on line {$errline}";
    error_log($log_message);
    
    // Don't display error to user
    return true;
}

// Custom exception handler
function hide_paths_exception_handler($exception) {
    // Remove all file paths from exception message
    $clean_message = remove_all_paths($exception->getMessage());
    
    // Log clean exception
    error_log("Exception: {$clean_message}");
    
    // Show generic error page to user
    show_generic_error_page();
}

// Shutdown function for fatal errors
function hide_paths_shutdown_handler() {
    $error = error_get_last();
    if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        // Remove all file paths from fatal error message
        $clean_message = remove_all_paths($error['message']);
        
        // Log clean fatal error
        error_log("Fatal Error: {$clean_message} on line {$error['line']}");
        
        // Show generic error page
        show_generic_error_page();
    }
}

// Function to remove ALL file paths from error messages
function remove_all_paths($message) {
    // Remove Windows paths (C:\xampp\htdocs\...)
    $message = preg_replace('/[A-Za-z]:\\\\[^\\\\]*\\\\[^\\\\\\\\]*/', '', $message);
    $message = preg_replace('/[A-Za-z]:\\\\[^\\\\]*/', '', $message);
    
    // Remove Unix/Linux paths (/var/www/...)
    $message = preg_replace('/\/[^\/]*\/[^\/]*\/[^\/]*/', '', $message);
    $message = preg_replace('/\/var\/www\/[^\/]*/', '', $message);
    $message = preg_replace('/\/home\/[^\/]*/', '', $message);
    $message = preg_replace('/\/opt\/lampp\/htdocs\/[^\/]*/', '', $message);
    $message = preg_replace('/\/usr\/local\/[^\/]*/', '', $message);
    
    // Remove Mac paths (/Users/username/...)
    $message = preg_replace('/\/Users\/[^\/]*\/[^\/]*/', '', $message);
    
    // Remove specific path patterns
    $message = preg_replace('/salem-dominion-ministries/', 'your-website', $message);
    $message = preg_replace('/xampp/', 'server', $message);
    $message = preg_replace('/htdocs/', 'web-root', $message);
    $message = preg_replace('/www/', 'web-root', $message);
    
    // Remove "in [file].php on line X" patterns
    $message = preg_replace('/in [^\\s]*\.php on line \d+/', '', $message);
    $message = preg_replace('/thrown in [^\\s]*\.php on line \d+/', '', $message);
    $message = preg_replace('/called in [^\\s]*\.php on line \d+/', '', $message);
    
    // Remove entire stack traces
    $message = preg_replace('/Stack trace:.*$/s', '', $message);
    $message = preg_replace('/#.*\.php.*$/m', '', $message);
    
    // Remove any remaining file references
    $message = preg_replace('/\b[A-Za-z0-9_\-\.]*\.php\b/', 'script.php', $message);
    
    // Clean up extra whitespace
    $message = trim(preg_replace('/\s+/', ' ', $message));
    
    return $message;
}

// Generic error page for users
function show_generic_error_page() {
    if (!headers_sent()) {
        http_response_code(500);
        header('Content-Type: text/html; charset=UTF-8');
    }
    
    echo '<!DOCTYPE html>
<html>
<head>
    <title>System Error - Salem Dominion Ministries</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { 
            font-family: Arial, sans-serif; 
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); 
            margin: 0; 
            padding: 20px; 
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .error-container { 
            max-width: 600px; 
            background: white; 
            padding: 40px; 
            border-radius: 15px; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.1); 
            text-align: center; 
        }
        .error-icon { 
            font-size: 4rem; 
            color: #dc2626; 
            margin-bottom: 20px; 
        }
        .error-title { 
            color: #1e293b; 
            font-size: 1.8rem; 
            margin-bottom: 15px; 
        }
        .error-message { 
            color: #64748b; 
            margin-bottom: 30px; 
            font-size: 1.1rem;
            line-height: 1.6;
        }
        .error-actions { 
            display: flex; 
            gap: 15px; 
            justify-content: center; 
            flex-wrap: wrap; 
        }
        .btn { 
            padding: 12px 25px; 
            border-radius: 8px; 
            text-decoration: none; 
            font-weight: 600; 
            transition: all 0.3s ease; 
            border: none;
            cursor: pointer;
        }
        .btn-primary { 
            background: linear-gradient(135deg, #16a34a 0%, #15803d 100%); 
            color: white; 
        }
        .btn-primary:hover { 
            background: linear-gradient(135deg, #15803d 0%, #166534 100%); 
            transform: translateY(-2px);
        }
        .btn-secondary { 
            background: linear-gradient(135deg, #64748b 0%, #475569 100%); 
            color: white; 
        }
        .btn-secondary:hover { 
            background: linear-gradient(135deg, #475569 0%, #334155 100%); 
            transform: translateY(-2px);
        }
        @media (max-width: 600px) {
            .error-container { padding: 30px 20px; margin: 20px; }
            .error-title { font-size: 1.5rem; }
            .error-actions { flex-direction: column; }
            .btn { width: 100%; }
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-icon">⚠️</div>
        <h1 class="error-title">System Temporarily Unavailable</h1>
        <p class="error-message">
            We\'re experiencing technical difficulties. Our team has been notified and is working to resolve the issue. Please try again in a few moments.
        </p>
        <div class="error-actions">
            <a href="/" class="btn btn-primary">Go Home</a>
            <button onclick="history.back()" class="btn btn-secondary">Go Back</button>
        </div>
    </div>
</body>
</html>';
    exit;
}

// Register all error handlers
set_error_handler('hide_paths_error_handler');
set_exception_handler('hide_paths_exception_handler');
register_shutdown_function('hide_paths_shutdown_handler');

// Start output buffering to catch any accidental output
ob_start();

// Function to clean any output that might contain paths
function clean_output_buffer($buffer) {
    // Remove any file paths that might be in the output
    $buffer = remove_all_paths($buffer);
    return $buffer;
}

// Register output cleaning
ob_start('clean_output_buffer');
?>
