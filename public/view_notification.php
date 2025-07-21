<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';

$user_id = $_SESSION['user_id'];

// Get notification ID from URL
$notification_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if (!$notification_id) {
    header('Location: notifications.php');
    exit();
}

// Get the specific notification
try {
    $stmt = $pdo->prepare("SELECT * FROM notifications WHERE id = ? AND user_id = ?");
    $stmt->execute([$notification_id, $user_id]);
    $notification = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$notification) {
        header('Location: notifications.php');
        exit();
    }

    // Mark as read if not already
    if (!$notification['is_read']) {
        $stmt = $pdo->prepare("UPDATE notifications SET is_read = TRUE WHERE id = ? AND user_id = ?");
        $stmt->execute([$notification_id, $user_id]);
        $notification['is_read'] = 1; // Update local variable
    }

} catch (PDOException $e) {
    error_log("Notification view error: " . $e->getMessage());
    header('Location: notifications.php');
    exit();
}

include '../includes/header.php';
?>

<style>
    .notification-detail-card {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        transition: all 0.3s ease;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .status-read {
        background-color: #f0f9ff;
        color: #0369a1;
        border: 1px solid #bae6fd;
    }

    .status-unread {
        background-color: #fef3c7;
        color: #92400e;
        border: 1px solid #fcd34d;
    }

    .action-button {
        display: inline-flex;
        align-items: center;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s ease;
        border: 1px solid transparent;
    }

    .action-button-primary {
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        color: white;
    }

    .action-button-primary:hover {
        background: linear-gradient(135deg, #1d4ed8, #1e3a8a);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
    }

    .action-button-secondary {
        background: #f8fafc;
        color: #475569;
        border-color: #e2e8f0;
    }

    .action-button-secondary:hover {
        background: #f1f5f9;
        border-color: #cbd5e1;
    }

    .fade-in {
        animation: fadeIn 0.6s ease-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(30px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .detail-section {
        padding: 1.5rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .detail-section:last-child {
        border-bottom: none;
    }

    .icon-wrapper {
        width: 3rem;
        height: 3rem;
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        color: white;
        margin-bottom: 1rem;
    }
</style>

<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 py-8">
    <div class="container mx-auto px-4 max-w-3xl">

        <!-- Actions Section -->

        <div class="flex flex-wrap gap-3 mb-3">
            <a href="notification.php" class="action-button action-button-primary">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Notifications
            </a>
        </div>

        <!-- Main Content Card -->
        <div class="notification-detail-card rounded-2xl overflow-hidden fade-in">

            <!-- Header Section -->
            <div class="detail-section bg-gradient-to-r from-blue-500 to-purple-600 text-white">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="icon-wrapper bg-white/20 backdrop-blur-sm">
                            <i class="fas fa-bell"></i>
                        </div>
                        <h1 class="text-2xl font-bold mb-2">
                            <?= htmlspecialchars($notification['title']) ?>
                        </h1>
                        <div class="flex items-center space-x-4 text-blue-100">
                            <span class="flex items-center">
                                <i class="fas fa-calendar mr-2"></i>
                                <?= date('F j, Y', strtotime($notification['created_at'])) ?>
                            </span>
                            <span class="flex items-center">
                                <i class="fas fa-clock mr-2"></i>
                                <?= date('g:i A', strtotime($notification['created_at'])) ?>
                            </span>
                        </div>
                    </div>
                    <div class="flex flex-col items-end space-y-2">
                        <span class="status-badge <?= $notification['is_read'] ? 'status-read' : 'status-unread' ?>">
                            <i
                                class="fas <?= $notification['is_read'] ? 'fa-check-circle' : 'fa-exclamation-circle' ?> mr-1"></i>
                            <?= $notification['is_read'] ? 'Read' : 'Unread' ?>
                        </span>
                        <span class="text-xs text-blue-100">
                            ID: #<?= $notification['id'] ?>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Message Content Section -->
            <div class="detail-section">
                <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-envelope-open mr-2 text-blue-500"></i>
                    Message Content
                </h2>
                <div class="bg-gray-50 rounded-lg p-4 border-l-4 border-blue-500">
                    <p class="text-gray-700 leading-relaxed text-base">
                        <?= nl2br(htmlspecialchars($notification['message'])) ?>
                    </p>
                </div>
            </div>

            <!-- Metadata Section -->
            <div class="detail-section">
                <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                    Notification Details
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <label class="block text-sm font-medium text-gray-600 mb-1">Created</label>
                        <p class="text-gray-900 font-medium">
                            <?= date('l, F j, Y \a\t g:i A', strtotime($notification['created_at'])) ?>
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            <?= time_elapsed_string($notification['created_at']) ?>
                        </p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <label class="block text-sm font-medium text-gray-600 mb-1">Status</label>
                        <p class="text-gray-900 font-medium">
                            <?= $notification['is_read'] ? 'Read' : 'Unread' ?>
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            <?= $notification['is_read'] ? 'Marked as read' : 'Still unread' ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

    // Add smooth scroll behavior
    document.addEventListener('DOMContentLoaded', function () {
        // Add any additional interactive features here
        const actionButtons = document.querySelectorAll('.action-button');
        actionButtons.forEach(button => {
            button.addEventListener('mouseenter', function () {
                this.style.transform = 'translateY(-1px)';
            });
            button.addEventListener('mouseleave', function () {
                this.style.transform = 'translateY(0)';
            });
        });
    });
</script>

<?php
// Helper function for time elapsed
function time_elapsed_string($datetime, $full = false)
{
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $weeks = floor($diff->d / 7);
    $diff->d -= $weeks * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );

    // Add weeks to the string array manually
    if ($weeks) {
        $string['w'] = $weeks . ' week' . ($weeks > 1 ? 's' : '');
    } else {
        unset($string['w']);
    }

    foreach ($string as $k => &$v) {
        if ($k !== 'w') {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }
    }

    if (!$full)
        $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}

include '../includes/footer.php';
?>