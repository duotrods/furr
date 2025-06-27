<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Enable detailed error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

$to = 'recipient@example.com'; // Replace with actual email
$subject = 'Test Email from FurCare';
$body = '<h1>Hello!</h1><p>This is a test email.</p>';

// Debug output before sending
echo "<pre>Attempting to send email to: $to\n";
echo "Using SMTP server: " . MAIL_HOST . ":" . MAIL_PORT . "\n";
echo "From: " . MAIL_FROM . "\n\n";

// Send email
if (sendEmail($to, $subject, $body)) {
    echo "Email sent successfully!";
} else {
    echo "Failed to send email. Check below for details:\n";
    
    // Last error (if your sendEmail function stores it)
    if (isset($_SESSION['mail_error'])) {
        echo htmlspecialchars($_SESSION['mail_error']);
    }
}
echo "</pre>";
?>