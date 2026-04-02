<?php
/**
 * Enhanced Email Configuration for Salem Dominion Ministries
 * Complete email system with multiple accounts and settings
 */

// Email Account Configuration
define('EMAIL_ACCOUNTS', [
    'general' => [
        'username' => 'visit@salemdominionministries.com',
        'password' => 'Lovely2God',
        'from_name' => 'Salem Dominion Ministries',
        'from_email' => 'visit@salemdominionministries.com',
        'reply_to' => 'visit@salemdominionministries.com',
        'smtp_server' => 'mail.salemdominionministries.com',
        'smtp_port' => 587,
        'imap_server' => 'mail.salemdominionministries.com',
        'pop3_server' => 'mail.salemdominionministries.com',
        'encryption' => 'tls',
        'department' => 'General Inquiries',
        'description' => 'Main church email for general inquiries and visitor information'
    ],
    'ministers' => [
        'username' => 'ministers@salemdominionministries.com',
        'password' => 'Lovely2God',
        'from_name' => 'Ministry Team',
        'from_email' => 'ministers@salemdominionministries.com',
        'reply_to' => 'ministers@salemdominionministries.com',
        'smtp_server' => 'mail.salemdominionministries.com',
        'smtp_port' => 587,
        'imap_server' => 'mail.salemdominionministries.com',
        'pop3_server' => 'mail.salemdominionministries.com',
        'encryption' => 'tls',
        'department' => 'Ministry Team',
        'description' => 'Email for ministers and pastoral staff coordination'
    ],
    'children' => [
        'username' => 'childrenministry@salemdominionministries.com',
        'password' => 'Lovely2God',
        'from_name' => 'Children Ministry',
        'from_email' => 'childrenministry@salemdominionministries.com',
        'reply_to' => 'childrenministry@salemdominionministries.com',
        'smtp_server' => 'mail.salemdominionministries.com',
        'smtp_port' => 587,
        'imap_server' => 'mail.salemdominionministries.com',
        'pop3_server' => 'mail.salemdominionministries.com',
        'encryption' => 'tls',
        'department' => 'Children Ministry',
        'description' => 'Email for children ministry programs and activities'
    ]
]);

// Email Templates
define('EMAIL_TEMPLATES', [
    'welcome' => [
        'subject' => 'Welcome to Salem Dominion Ministries!',
        'template' => 'welcome_email.html',
        'from_account' => 'general'
    ],
    'event_registration' => [
        'subject' => 'Event Registration Confirmation',
        'template' => 'event_registration.html',
        'from_account' => 'general'
    ],
    'pastor_booking' => [
        'subject' => 'Pastor Booking Confirmation',
        'template' => 'pastor_booking.html',
        'from_account' => 'ministers'
    ],
    'newsletter' => [
        'subject' => 'Weekly Newsletter - Salem Dominion Ministries',
        'template' => 'newsletter.html',
        'from_account' => 'general'
    ],
    'prayer_request' => [
        'subject' => 'Prayer Request Received',
        'template' => 'prayer_request.html',
        'from_account' => 'ministers'
    ],
    'children_program' => [
        'subject' => 'Children Ministry Program Update',
        'template' => 'children_program.html',
        'from_account' => 'children'
    ],
    'donation_receipt' => [
        'subject' => 'Donation Receipt - Salem Dominion Ministries',
        'template' => 'donation_receipt.html',
        'from_account' => 'general'
    ]
]);

// Email Settings
define('EMAIL_SETTINGS', [
    'default_charset' => 'UTF-8',
    'default_encoding' => '8bit',
    'word_wrap' => 78,
    'mail_type' => 'html',
    'validate_email' => true,
    'bcc_batch_mode' => false,
    'bcc_batch_size' => 200
]);

// Enhanced Email Class
class EnhancedEmailSystem {
    private $config;
    private $last_error;
    private $debug_mode;
    
    public function __construct() {
        $this->config = EMAIL_ACCOUNTS;
        $this->debug_mode = defined('DEBUG_MODE') && DEBUG_MODE;
    }
    
    /**
     * Send email using specified account
     */
    public function sendEmail($to, $subject, $message, $account_type = 'general', $attachments = [], $cc = [], $bcc = []) {
        try {
            $account = $this->config[$account_type] ?? $this->config['general'];
            
            // Create PHPMailer instance
            $mail = $this->createMailerInstance($account);
            
            // Set recipients
            $mail->setFrom($account['from_email'], $account['from_name']);
            $mail->addReplyTo($account['reply_to'], $account['from_name']);
            
            // Add to recipients
            if (is_array($to)) {
                foreach ($to as $recipient) {
                    $mail->addAddress($recipient);
                }
            } else {
                $mail->addAddress($to);
            }
            
            // Add CC recipients
            if (!empty($cc)) {
                foreach ($cc as $recipient) {
                    $mail->addCC($recipient);
                }
            }
            
            // Add BCC recipients
            if (!empty($bcc)) {
                foreach ($bcc as $recipient) {
                    $mail->addBCC($recipient);
                }
            }
            
            // Set content
            $mail->Subject = $subject;
            $mail->Body = $message;
            $mail->AltBody = $this->stripHtmlTags($message);
            
            // Add attachments
            if (!empty($attachments)) {
                foreach ($attachments as $attachment) {
                    if (is_array($attachment)) {
                        $mail->addAttachment($attachment['path'], $attachment['name'] ?? basename($attachment['path']));
                    } else {
                        $mail->addAttachment($attachment);
                    }
                }
            }
            
            // Send email
            $result = $mail->send();
            
            if ($this->debug_mode) {
                $this->logEmailActivity('sent', $account_type, $to, $subject);
            }
            
            return $result;
            
        } catch (Exception $e) {
            $this->last_error = $e->getMessage();
            $this->logEmailActivity('error', $account_type, $to, $subject, $e->getMessage());
            return false;
        }
    }
    
    /**
     * Send email using template
     */
    public function sendTemplateEmail($to, $template_key, $data = [], $account_type = 'general', $attachments = []) {
        if (!isset(EMAIL_TEMPLATES[$template_key])) {
            $this->last_error = "Template '{$template_key}' not found";
            return false;
        }
        
        $template = EMAIL_TEMPLATES[$template_key];
        $account = $this->config[$template['from_account']] ?? $this->config['general'];
        
        // Load template
        $template_path = __DIR__ . '/email_templates/' . $template['template'];
        if (!file_exists($template_path)) {
            $this->last_error = "Template file not found: {$template_path}";
            return false;
        }
        
        // Process template
        $message = $this->processTemplate($template_path, $data);
        $subject = $this->processSubject($template['subject'], $data);
        
        return $this->sendEmail($to, $subject, $message, $template['from_account'], $attachments);
    }
    
    /**
     * Create PHPMailer instance
     */
    private function createMailerInstance($account) {
        $mail = new PHPMailer\PHPMailer\PHPMailer(true);
        
        // Server settings
        $mail->isSMTP();
        $mail->Host = $account['smtp_server'];
        $mail->SMTPAuth = true;
        $mail->Username = $account['username'];
        $mail->Password = $account['password'];
        $mail->SMTPSecure = $account['encryption'];
        $mail->Port = $account['smtp_port'];
        
        // General settings
        $mail->CharSet = EMAIL_SETTINGS['default_charset'];
        $mail->Encoding = EMAIL_SETTINGS['default_encoding'];
        $mail->isHTML(EMAIL_SETTINGS['mail_type'] === 'html');
        $mail->WordWrap = EMAIL_SETTINGS['word_wrap'];
        
        // Debug mode
        if ($this->debug_mode) {
            $mail->SMTPDebug = PHPMailer\PHPMailer\SMTP::DEBUG_SERVER;
            $mail->Debugoutput = function($str, $level) {
                error_log("SMTP Debug [$level]: $str");
            };
        }
        
        return $mail;
    }
    
    /**
     * Process email template
     */
    private function processTemplate($template_path, $data) {
        $template = file_get_contents($template_path);
        
        // Replace placeholders
        foreach ($data as $key => $value) {
            $template = str_replace('{{' . $key . '}}', $value, $template);
        }
        
        // Add common variables
        $common_vars = [
            'site_name' => 'Salem Dominion Ministries',
            'site_url' => APP_URL,
            'current_date' => date('F j, Y'),
            'current_year' => date('Y'),
            'church_address' => '123 Church Street, City, State',
            'church_phone' => '+1 (555) 123-4567',
            'church_email' => 'visit@salemdominionministries.com'
        ];
        
        foreach ($common_vars as $key => $value) {
            $template = str_replace('{{' . $key . '}}', $value, $template);
        }
        
        return $template;
    }
    
    /**
     * Process subject with variables
     */
    private function processSubject($subject, $data) {
        foreach ($data as $key => $value) {
            $subject = str_replace('{{' . $key . '}}', $value, $subject);
        }
        return $subject;
    }
    
    /**
     * Strip HTML tags for plain text version
     */
    private function stripHtmlTags($html) {
        return strip_tags($html);
    }
    
    /**
     * Get last error
     */
    public function getLastError() {
        return $this->last_error;
    }
    
    /**
     * Log email activity
     */
    private function logEmailActivity($action, $account_type, $to, $subject, $error = null) {
        $log_entry = [
            'timestamp' => date('Y-m-d H:i:s'),
            'action' => $action,
            'account_type' => $account_type,
            'to' => is_array($to) ? implode(', ', $to) : $to,
            'subject' => $subject,
            'error' => $error
        ];
        
        $log_file = __DIR__ . '/logs/email_activity.log';
        $log_dir = dirname($log_file);
        
        if (!is_dir($log_dir)) {
            mkdir($log_dir, 0755, true);
        }
        
        file_put_contents($log_file, json_encode($log_entry) . "\n", FILE_APPEND | LOCK_EX);
    }
    
    /**
     * Get email account information
     */
    public function getAccountInfo($account_type = null) {
        if ($account_type) {
            return $this->config[$account_type] ?? null;
        }
        return $this->config;
    }
    
    /**
     * Test email configuration
     */
    public function testEmailConfiguration($account_type = 'general') {
        try {
            $account = $this->config[$account_type] ?? $this->config['general'];
            $mail = $this->createMailerInstance($account);
            
            // Test connection
            $mail->SMTPConnect();
            $mail->SMTPClose();
            
            return [
                'success' => true,
                'message' => "Email configuration for {$account['department']} is working correctly"
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => "Email configuration test failed: " . $e->getMessage()
            ];
        }
    }
    
    /**
     * Send bulk email (newsletter)
     */
    public function sendBulkEmail($recipients, $subject, $message, $account_type = 'general', $batch_size = 50) {
        $results = [
            'sent' => 0,
            'failed' => 0,
            'errors' => []
        ];
        
        $batches = array_chunk($recipients, $batch_size);
        
        foreach ($batches as $batch) {
            foreach ($batch as $recipient) {
                if ($this->sendEmail($recipient, $subject, $message, $account_type)) {
                    $results['sent']++;
                } else {
                    $results['failed']++;
                    $results['errors'][] = [
                        'recipient' => $recipient,
                        'error' => $this->getLastError()
                    ];
                }
                
                // Small delay to prevent overwhelming the server
                usleep(100000); // 0.1 second
            }
            
            // Longer delay between batches
            sleep(1);
        }
        
        return $results;
    }
}

// Email Queue System for better performance
class EmailQueue {
    private $queue_file;
    private $processing_file;
    
    public function __construct() {
        $this->queue_file = __DIR__ . '/logs/email_queue.json';
        $this->processing_file = __DIR__ . '/logs/email_processing.lock';
    }
    
    /**
     * Add email to queue
     */
    public function addToQueue($to, $subject, $message, $account_type = 'general', $priority = 'normal') {
        $email = [
            'id' => uniqid(),
            'to' => $to,
            'subject' => $subject,
            'message' => $message,
            'account_type' => $account_type,
            'priority' => $priority,
            'attempts' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'status' => 'queued'
        ];
        
        $queue = $this->getQueue();
        $queue[] = $email;
        
        // Sort by priority
        usort($queue, function($a, $b) {
            $priorities = ['high' => 3, 'normal' => 2, 'low' => 1];
            return $priorities[$b['priority']] - $priorities[$a['priority']];
        });
        
        file_put_contents($this->queue_file, json_encode($queue), LOCK_EX);
        
        return $email['id'];
    }
    
    /**
     * Process email queue
     */
    public function processQueue() {
        if (file_exists($this->processing_file)) {
            return false; // Already processing
        }
        
        // Create processing lock
        file_put_contents($this->processing_file, time());
        
        try {
            $queue = $this->getQueue();
            $email_system = new EnhancedEmailSystem();
            $processed = 0;
            
            foreach ($queue as $key => $email) {
                if ($email['status'] === 'queued') {
                    if ($email_system->sendEmail($email['to'], $email['subject'], $email['message'], $email['account_type'])) {
                        $queue[$key]['status'] = 'sent';
                        $queue[$key]['sent_at'] = date('Y-m-d H:i:s');
                        $processed++;
                    } else {
                        $queue[$key]['attempts']++;
                        $queue[$key]['last_error'] = $email_system->getLastError();
                        
                        // Mark as failed after 3 attempts
                        if ($queue[$key]['attempts'] >= 3) {
                            $queue[$key]['status'] = 'failed';
                        }
                    }
                    
                    // Update queue file
                    file_put_contents($this->queue_file, json_encode($queue), LOCK_EX);
                    
                    // Small delay between emails
                    usleep(200000); // 0.2 second
                }
            }
            
            // Remove processing lock
            unlink($this->processing_file);
            
            return $processed;
            
        } catch (Exception $e) {
            // Remove processing lock on error
            if (file_exists($this->processing_file)) {
                unlink($this->processing_file);
            }
            throw $e;
        }
    }
    
    /**
     * Get queue
     */
    private function getQueue() {
        if (!file_exists($this->queue_file)) {
            return [];
        }
        
        $queue = json_decode(file_get_contents($this->queue_file), true);
        return is_array($queue) ? $queue : [];
    }
    
    /**
     * Get queue statistics
     */
    public function getQueueStats() {
        $queue = $this->getQueue();
        $stats = [
            'total' => count($queue),
            'queued' => 0,
            'sent' => 0,
            'failed' => 0
        ];
        
        foreach ($queue as $email) {
            $stats[$email['status']]++;
        }
        
        return $stats;
    }
}

// Initialize global email system
$GLOBALS['enhanced_email_system'] = new EnhancedEmailSystem();
$GLOBALS['email_queue'] = new EmailQueue();

// Helper functions
function send_church_email($to, $subject, $message, $account_type = 'general') {
    return $GLOBALS['enhanced_email_system']->sendEmail($to, $subject, $message, $account_type);
}

function send_template_email($to, $template_key, $data = [], $account_type = 'general') {
    return $GLOBALS['enhanced_email_system']->sendTemplateEmail($to, $template_key, $data, $account_type);
}

function queue_email($to, $subject, $message, $account_type = 'general', $priority = 'normal') {
    return $GLOBALS['email_queue']->addToQueue($to, $subject, $message, $account_type, $priority);
}

function test_email_config($account_type = 'general') {
    return $GLOBALS['enhanced_email_system']->testEmailConfiguration($account_type);
}

// Auto-process queue (run every 5 minutes)
if (!defined('EMAIL_QUEUE_AUTO_PROCESS') || EMAIL_QUEUE_AUTO_PROCESS) {
    register_shutdown_function(function() {
        // Only process queue if not in CLI mode and no direct output
        if (php_sapi_name() !== 'cli' && !headers_sent()) {
            $GLOBALS['email_queue']->processQueue();
        }
    });
}
?>
