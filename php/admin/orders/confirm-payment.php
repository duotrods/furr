<?php
require_once __DIR__ . '/../../../includes/config.php';
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: payment-review.php');
    exit();
}

$order_id = (int) $_POST['order_id'];
$action = $_POST['action'] ?? '';
$admin_notes = sanitize($_POST['admin_notes'] ?? '');

// Get order details
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->execute([$order_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    $_SESSION['error_message'] = 'Order not found.';
    header('Location: ../../../admin/payment-review.php');
    exit();
}

if ($order['status'] !== 'payment_review') {
    $_SESSION['error_message'] = 'This order is not awaiting payment review.';
    header('Location: ../../../admin/order-details.php?id=' . $order_id);
    exit();
}

try {
    $pdo->beginTransaction();

    if ($action === 'confirm') {
        // Update order status to confirmed
        $stmt = $pdo->prepare("UPDATE orders SET status = 'confirmed' WHERE id = ?");
        $stmt->execute([$order_id]);

        // Send confirmation email to customer
        $stmt = $pdo->prepare("SELECT email, first_name FROM users WHERE id = ?");
        $stmt->execute([$order['user_id']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $subject = "Payment Confirmed for Order #" . str_pad($order_id, 5, '0', STR_PAD_LEFT);
            $body = "Hello " . htmlspecialchars($user['first_name']) . ",<br><br>
                        Your payment for Order #" . str_pad($order_id, 5, '0', STR_PAD_LEFT) . " has been confirmed.<br>
                        Total Amount: ₱" . number_format($order['total_amount'], 2) . "<br><br>
                        We will now process your order for shipping.<br><br>
                        Thank you for your purchase!<br><br>
                        The  Team";

            sendEmail($user['email'], $subject, $body);

            // Create notification
            $notificationTitle = "Payment Confirmed for Order #" . str_pad($order_id, 5, '0', STR_PAD_LEFT);
            $notificationMessage = "Your payment for Order #" . str_pad($order_id, 5, '0', STR_PAD_LEFT) . " has been confirmed.We will now process your order for shipping.   Thank you for your purchase!";
            createNotification($order['user_id'], $notificationTitle, $notificationMessage);
        }

        $_SESSION['success_message'] = 'Payment confirmed successfully.';
    } elseif ($action === 'reject') {
        // Update order status to pending and clear payment info
        $stmt = $pdo->prepare("UPDATE orders SET status = 'pending', 
                            payment_reference = NULL, 
                            payment_proof = NULL
                            WHERE id = ?");
        $stmt->execute([$order_id]);

        // Send rejection email to customer
        $stmt = $pdo->prepare("SELECT email, first_name FROM users WHERE id = ?");
        $stmt->execute([$order['user_id']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $subject = "Payment Issue with Order #" . str_pad($order_id, 5, '0', STR_PAD_LEFT);
            $body = "Hello " . htmlspecialchars($user['first_name']) . ",<br><br>
                        We encountered an issue with your payment for Order #" . str_pad($order_id, 5, '0', STR_PAD_LEFT) . ".<br>
                        Please check your payment details and submit again.
                    " . ($admin_notes ? "<strong>Admin Note:</strong> " . nl2br(htmlspecialchars($admin_notes)) . "<br><br>" : "") . "
                        You can review and resubmit your payment here:<br>
                        <a href=\"" . BASE_URL . "/public/payment-upload.php?id=" . $order_id . "\" style=\"color: #2563eb; text-decoration: underline;\">
                            Submit Payment Again
                        </a><br><br>
                        The Team";

            sendEmail($user['email'], $subject, $body);

            // Create notification - fixed version
            $notificationTitle = "Payment Issue with Order #" . str_pad($order_id, 5, '0', STR_PAD_LEFT);
            $notificationMessage = "Your payment for Order #" . str_pad($order_id, 5, '0', STR_PAD_LEFT) . " was rejected. " .
                ($admin_notes ? "Note: " . strip_tags($admin_notes) : "Please check your payment details.");

            createNotification($order['user_id'], $notificationTitle, $notificationMessage);
        }
        $_SESSION['success_message'] = 'Payment rejected. Customer notified to resubmit payment.';
    }

    $pdo->commit();
} catch (Exception $e) {
    $pdo->rollBack();
    $_SESSION['error_message'] = 'An error occurred while processing your request.';
    error_log("Payment confirmation error: " . $e->getMessage());
}

header('Location: ../../../admin/order-details.php?id=' . $order_id);
exit();
