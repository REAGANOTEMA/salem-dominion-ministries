<?php
// Production Error Handler - Removes file paths from error messages
class ProductionErrorHandler {
    public static function register() {
        set_error_handler([self::class, 'handleError']);
        set_exception_handler([self::class, 'handleException']);
        register_shutdown_function([self::class, 'handleShutdown']);
    }
    
    public static function handleError($errno, $errstr, $errfile, $errline) {
        // Don't show errors in production
        if (error_reporting() === 0) {
            return false;
        }
        
        // Log error without file path
        $cleanMessage = self::cleanErrorMessage($errstr);
        $logMessage = "Error [{$errno}]: {$cleanMessage} on line {$errline}";
        
        // Log to file if needed
        if (ini_get('log_errors')) {
            error_log($logMessage);
        }
        
        // Don't display errors to users
        return true;
    }
    
    public static function handleException($exception) {
        // Clean exception message
        $cleanMessage = self::cleanErrorMessage($exception->getMessage());
        $logMessage = "Exception: {$cleanMessage}";
        
        // Log exception
        if (ini_get('log_errors')) {
            error_log($logMessage);
        }
        
        // Don't show exception details to users
        self::showGenericError();
    }
    
    public static function handleShutdown() {
        $error = error_get_last();
        if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
            $cleanMessage = self::cleanErrorMessage($error['message']);
            $logMessage = "Fatal Error: {$cleanMessage} on line {$error['line']}";
            
            if (ini_get('log_errors')) {
                error_log($logMessage);
            }
            
            // Don't show fatal error details to users
            self::showGenericError();
        }
    }
    
    private static function cleanErrorMessage($message) {
        // Remove file paths from error messages
        $message = preg_replace('/in [A-Za-z]:\\\\.*?\.php on line \d+/', '', $message);
        $message = preg_replace('/in \/.*?\.php on line \d+/', '', $message);
        $message = preg_replace('/C:\\\\xampp\\\\htdocs\\\\[^\\\\]*/', '', $message);
        $message = preg_replace('/\/var\/www\/[^\/]*/', '', $message);
        $message = preg_replace('/\/home\/[^\/]*/', '', $message);
        
        // Remove stack traces that contain file paths
        $message = preg_replace('/Stack trace:.*$/s', '', $message);
        
        // Clean up extra whitespace
        $message = trim(preg_replace('/\s+/', ' ', $message));
        
        return $message;
    }
    
    private static function showGenericError() {
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

// Database Error Handler
class DatabaseErrorHandler {
    public static function handleQueryError($query, $error) {
        // Log database error without sensitive info
        $cleanError = ProductionErrorHandler::cleanErrorMessage($error);
        error_log("Database Error: {$cleanError}");
        
        // Return generic error message
        return "Database operation failed. Please try again.";
    }
    
    public static function handleConnectionError($error) {
        // Log connection error without sensitive info
        $cleanError = ProductionErrorHandler::cleanErrorMessage($error);
        error_log("Database Connection Error: {$cleanError}");
        
        // Return generic error message
        return "Unable to connect to database. Please try again later.";
    }
}

// Register the error handler
ProductionErrorHandler::register();

// Set production error reporting
error_reporting(0);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/uploads/clean_error.log');
?>
