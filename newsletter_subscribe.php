<?php
// Complete error suppression to prevent unwanted output
error_reporting(0);
ini_set('display_errors', 0);
ini_set('log_errors', 0);

// Buffer output to catch any accidental output
ob_start();

// Include required files with error handling
try {
    require_once 'config.php';
    require_once 'db.php';
} catch (Exception $e) {
    // Silent error handling
}

// Process newsletter subscription
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        try {
            // Check if email already exists
            $existing = $db->query("SELECT id FROM newsletter_subscribers WHERE email = ?", [$email]);
            
            if ($existing && $existing->num_rows > 0) {
                // Update existing subscription
                $db->query("UPDATE newsletter_subscribers SET status = 'active', updated_at = NOW() WHERE email = ?", [$email]);
                $message = "You have been successfully re-subscribed to our newsletter!";
            } else {
                // Add new subscription
                $db->query("INSERT INTO newsletter_subscribers (email, status, created_at) VALUES (?, 'active', NOW())", [$email]);
                $message = "Thank you for subscribing to our newsletter!";
            }
            
            // Redirect back with success message
            header('Location: ' . $_SERVER['HTTP_REFERER'] . '?newsletter_success=' . urlencode($message));
            exit;
        } catch (Exception $e) {
            header('Location: ' . $_SERVER['HTTP_REFERER'] . '?newsletter_error=' . urlencode('Subscription failed. Please try again.'));
            exit;
        }
    } else {
        header('Location: ' . $_SERVER['HTTP_REFERER'] . '?newsletter_error=' . urlencode('Invalid email address.'));
        exit;
    }
} else {
    header('Location: index.php');
    exit;
}

// Clean any buffered output
ob_end_clean();
?>
