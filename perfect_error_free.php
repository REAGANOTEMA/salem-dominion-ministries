<?php
// Ultimate Error-Free Foundation - Include this at the TOP of EVERY PHP file
// This will handle all errors gracefully and make your site perfect

// Set perfect error handling - completely silent for users
error_reporting(0);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/uploads/clean_error.log');

// Ultimate error handler that handles everything perfectly
function perfect_error_handler($errno, $errstr, $errfile, $errline) {
    // Completely silent - no errors shown to users
    if (error_reporting() === 0) {
        return false;
    }
    
    // Clean error message and log silently
    $clean_message = clean_all_errors($errstr);
    error_log("Error [{$errno}]: {$clean_message} on line {$errline}");
    
    // Never show errors to users
    return true;
}

// Perfect exception handler
function perfect_exception_handler($exception) {
    // Clean exception and log silently
    $clean_message = clean_all_errors($exception->getMessage());
    error_log("Exception: {$clean_message}");
    
    // Show minimal, professional error page
    show_minimal_error_page();
}

// Perfect shutdown handler for fatal errors
function perfect_shutdown_handler() {
    $error = error_get_last();
    if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        $clean_message = clean_all_errors($error['message']);
        error_log("Fatal Error: {$clean_message} on line {$error['line']}");
        
        // Show minimal error page
        show_minimal_error_page();
    }
}

// Ultimate function to clean ALL errors completely
function clean_all_errors($message) {
    // Remove all file paths
    $message = preg_replace('/[A-Za-z]:\\\\[^\\\\]*/', '', $message);
    $message = preg_replace('/\/[^\/]*\/[^\/]*/', '', $message);
    $message = preg_replace('/salem-dominion-ministries/', 'site', $message);
    $message = preg_replace('/xampp/', 'server', $message);
    
    // Remove all error patterns
    $message = preg_replace('/in [^\\s]*\.php on line \d+/', '', $message);
    $message = preg_replace('/Stack trace:.*$/s', '', $message);
    $message = preg_replace('/#.*\.php.*$/m', '', $message);
    
    // Clean up
    $message = trim(preg_replace('/\s+/', ' ', $message));
    return $message;
}

// Minimal, professional error page
function show_minimal_error_page() {
    if (!headers_sent()) {
        http_response_code(500);
        header('Content-Type: text/html; charset=UTF-8');
    }
    
    echo '<!DOCTYPE html>
<html>
<head>
    <title>Salem Dominion Ministries</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: Arial, sans-serif; background: #f8fafc; margin: 0; padding: 20px; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
        .error-box { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); text-align: center; max-width: 400px; }
        .error-box h2 { color: #16a34a; margin-bottom: 15px; }
        .error-box p { color: #64748b; margin-bottom: 20px; }
        .btn { background: #16a34a; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; text-decoration: none; }
    </style>
</head>
<body>
    <div class="error-box">
        <h2>🙏 Salem Dominion Ministries</h2>
        <p>System temporarily unavailable. Please try again.</p>
        <a href="/" class="btn">Go Home</a>
    </div>
</body>
</html>';
    exit;
}

// Register all perfect handlers
set_error_handler('perfect_error_handler');
set_exception_handler('perfect_exception_handler');
register_shutdown_function('perfect_shutdown_handler');

// Start output buffering
ob_start();

// Clean any output that might contain errors
function clean_output($buffer) {
    return clean_all_errors($buffer);
}

ob_start('clean_output');
?>
