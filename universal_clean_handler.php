<?php
// Universal Clean Error Handler - Include this in all pages
class UniversalCleanErrorHandler {
    public static function initialize() {
        // Set production error reporting
        error_reporting(0);
        ini_set('display_errors', 0);
        ini_set('log_errors', 1);
        ini_set('error_log', __DIR__ . '/uploads/clean_error.log');
        
        // Register error handlers
        set_error_handler([self::class, 'handleError']);
        set_exception_handler([self::class, 'handleException']);
        register_shutdown_function([self::class, 'handleShutdown']);
    }
    
    public static function handleError($errno, $errstr, $errfile, $errline) {
        // Only log errors, don't display them
        if (error_reporting() === 0) {
            return false;
        }
        
        // Clean error message
        $cleanMessage = self::cleanErrorMessage($errstr);
        $logMessage = "Error [{$errno}]: {$cleanMessage} on line {$errline}";
        
        // Log without file path
        error_log($logMessage);
        
        // Don't show errors to users
        return true;
    }
    
    public static function handleException($exception) {
        // Clean exception message
        $cleanMessage = self::cleanErrorMessage($exception->getMessage());
        $logMessage = "Exception: {$cleanMessage}";
        
        // Log exception
        error_log($logMessage);
        
        // Don't show exception details to users
        self::showGenericError();
    }
    
    public static function handleShutdown() {
        $error = error_get_last();
        if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
            $cleanMessage = self::cleanErrorMessage($error['message']);
            $logMessage = "Fatal Error: {$cleanMessage} on line {$error['line']}";
            
            error_log($logMessage);
            
            // Don't show fatal error details to users
            self::showGenericError();
        }
    }
    
    public static function cleanErrorMessage($message) {
        // Remove all file paths from error messages
        $patterns = [
            '/in [A-Za-z]:\\\\.*?\.php on line \d+/',
            '/in \/.*?\.php on line \d+/',
            '/C:\\\\xampp\\\\htdocs\\\\[^\\\\]*/',
            '/\/var\/www\/[^\/]*/',
            '/\/home\/[^\/]*/',
            '/\/Users\/[^\/]*/',
            '/\/opt\/lampp\/htdocs\/[^\/]*/',
            '/\/www\/[^\/]*/',
        ];
        
        foreach ($patterns as $pattern) {
            $message = preg_replace($pattern, '', $message);
        }
        
        // Remove stack traces that contain file paths
        $message = preg_replace('/Stack trace:.*$/s', '', $message);
        $message = preg_replace('/#.*\.php.*$/m', '', $message);
        
        // Remove specific error patterns that reveal paths
        $message = preg_replace('/thrown in .*?\.php on line \d+/', '', $message);
        $message = preg_replace('/called in .*?\.php on line \d+/', '', $message);
        
        // Clean up extra whitespace
        $message = trim(preg_replace('/\s+/', ' ', $message));
        
        return $message;
    }
    
    public static function showGenericError() {
        // Show a generic error message to users
        if (!headers_sent()) {
            http_response_code(500);
            header('Content-Type: text/html; charset=UTF-8');
        }
        
        echo '<!DOCTYPE html>
<html>
<head>
    <title>System Error - Salem Dominion Ministries</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f8fafc; margin: 0; padding: 20px; }
        .error-container { max-width: 600px; margin: 50px auto; background: white; padding: 40px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: center; }
        .error-icon { font-size: 4rem; color: #dc2626; margin-bottom: 20px; }
        .error-title { color: #1e293b; font-size: 1.5rem; margin-bottom: 15px; }
        .error-message { color: #64748b; margin-bottom: 30px; }
        .error-actions { display: flex; gap: 15px; justify-content: center; flex-wrap: wrap; }
        .btn { padding: 12px 25px; border-radius: 8px; text-decoration: none; font-weight: 600; transition: all 0.3s ease; }
        .btn-primary { background: #16a34a; color: white; }
        .btn-primary:hover { background: #15803d; }
        .btn-secondary { background: #64748b; color: white; }
        .btn-secondary:hover { background: #475569; }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-icon">⚠️</div>
        <h1 class="error-title">System Temporarily Unavailable</h1>
        <p class="error-message">We\'re experiencing technical difficulties. Please try again in a few moments.</p>
        <div class="error-actions">
            <a href="/" class="btn btn-primary">Go Home</a>
            <a href="javascript:history.back()" class="btn btn-secondary">Go Back</a>
        </div>
    </div>
</body>
</html>';
        exit;
    }
}

// Initialize the error handler
UniversalCleanErrorHandler::initialize();
?>
