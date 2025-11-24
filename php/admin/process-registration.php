<?php
require_once '../../includes/config.php';

// Ensure user is admin
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . '/admin/manage-registrations.php');
    exit();
}

// Validate CSRF token
if (!validateCSRFToken($_POST['csrf_token'])) {
    $_SESSION['error_message'] = 'Invalid CSRF token.';
    header('Location: ' . BASE_URL . '/admin/manage-registrations.php');
    exit();
}

$user_id = (int)$_POST['user_id'];
$action = $_POST['action'];

if (empty($user_id) || !in_array($action, ['approve', 'reject'])) {
    $_SESSION['error_message'] = 'Invalid request.';
    header('Location: ' . BASE_URL . '/admin/manage-registrations.php');
    exit();
}

try {
    // Get user information first
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ? AND is_approved = 0");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();

    if (!$user) {
        $_SESSION['error_message'] = 'User not found or already processed.';
        header('Location: ' . BASE_URL . '/admin/manage-registrations.php');
        exit();
    }

    if ($action === 'approve') {
        // Approve the user
        $stmt = $pdo->prepare("UPDATE users SET is_approved = 1 WHERE id = ?");
        $stmt->execute([$user_id]);

        // Send approval email
        $subject = "Your Account Has Been Approved";
        $body = "Hello {$user['first_name']},<br><br>
                Great news! Your FurCare Pet Grooming account has been approved by our administrator.<br><br>
                You can now log in to your account and start booking appointments for your furry friends.<br><br>
                <a href='" . BASE_URL . "/public/login.php'>Click here to log in</a><br><br>
                If you have any questions, please don't hesitate to contact us.<br><br>
                Best regards,<br>
                The FurCare Team";

        if (sendEmail($user['email'], $subject, $body)) {
            $_SESSION['success_message'] = "User {$user['first_name']} {$user['last_name']} has been approved and notified via email.";
        } else {
            $_SESSION['success_message'] = "User {$user['first_name']} {$user['last_name']} has been approved, but email notification failed.";
        }
    } else {
        // Reject the user (delete the account)
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$user_id]);

        // Optionally send rejection email
        $subject = "Registration Update";
        $body = "Hello {$user['first_name']},<br><br>
                Thank you for your interest in FurCare Pet Grooming.<br><br>
                Unfortunately, we were unable to approve your registration at this time.<br><br>
                If you believe this is an error or have any questions, please contact us directly.<br><br>
                Best regards,<br>
                The FurCare Team";

        sendEmail($user['email'], $subject, $body);

        $_SESSION['success_message'] = "User registration for {$user['first_name']} {$user['last_name']} has been rejected and account deleted.";
    }

    header('Location: ' . BASE_URL . '/admin/manage-registrations.php');
    exit();
} catch (PDOException $e) {
    $_SESSION['error_message'] = 'An error occurred while processing the request.';
    header('Location: ' . BASE_URL . '/admin/manage-registrations.php');
    exit();
}
?>
