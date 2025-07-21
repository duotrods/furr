<?php
require_once '../includes/config.php';
require_once '../includes/auth.php'; // Add this line to include the file where requireLogin() is defined   

$user_id = $_SESSION['user_id'];

// Get all notifications for the user 
try {
    $stmt = $pdo->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->execute([$user_id]);
    $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Mark all as read     
    $stmt = $pdo->prepare("UPDATE notifications SET is_read = TRUE WHERE user_id = ?");
    $stmt->execute([$user_id]);
} catch (PDOException $e) {
    $notifications = [];
    error_log("Notification fetch error: " . $e->getMessage());
}

include '../includes/header.php';
?>

<!-- Custom styles for enhanced UI -->
<style>
    .notification-card {
        transition: all 0.3s ease;
        border-left: 4px solid transparent;
    }

    .notification-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }

    .notification-unread {
        border-left-color: #3b82f6;
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
    }

    .notification-read {
        border-left-color: #e5e7eb;
        background: #ffffff;
    }

    .notification-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }

    .notification-new .notification-icon {
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        color: white;
    }

    .notification-read .notification-icon {
        background: #f3f4f6;
        color: #6b7280;
    }

    .fade-in {
        animation: fadeIn 0.5s ease-in;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .empty-state {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border: 2px dashed #cbd5e1;
        border-radius: 16px;
        padding: 3rem;
        text-align: center;
        margin: 2rem 0;
    }

    .stats-card {
        background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
        border-radius: 16px;
        padding: 1.5rem;
        color: white;
        margin-bottom: 2rem;
    }

    .notification-time {
        font-size: 0.75rem;
        background: rgba(0, 0, 0, 0.05);
        padding: 0.25rem 0.75rem;
        border-radius: 12px;
        white-space: nowrap;
    }
</style>

<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 py-8">
    <div class="container mx-auto px-4 max-w-4xl">
        <!-- Header Section -->
        <div class="mb-8 fade-in">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Notifications</h1>
                    <p class="text-gray-600">Stay updated with your latest activities</p>
                </div>
                <div class="notification-icon bg-gradient-to-br from-blue-500 to-purple-600 text-white">
                    <i class="fas fa-bell"></i>
                </div>
            </div>

            <!-- Stats Card -->
            <?php if (!empty($notifications)): ?>
                <div class="stats-card fade-in">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold mb-1">Total Notifications</h3>
                            <p class="text-2xl font-bold"><?= count($notifications) ?></p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm opacity-75">All notifications marked as read</p>
                            <div class="flex items-center mt-1">
                                <i class="fas fa-check-circle mr-2 text-green-300"></i>
                                <span class="text-sm">Updated just now</span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Main Content -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden fade-in">
            <?php if (empty($notifications)): ?>
                <!-- Empty State -->
                <div class="empty-state">
                    <div class="mb-6">
                        <div
                            class="w-24 h-24 mx-auto mb-4 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center">
                            <i class="fas fa-bell-slash text-3xl text-gray-400"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800 mb-2">No notifications yet</h3>
                        <p class="text-gray-600 max-w-md mx-auto">When you receive notifications, they'll appear here. We'll
                            keep you updated on important activities.</p>
                    </div>
                    <div class="flex justify-center space-x-4">
                        <button
                            class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors duration-200">
                            <i class="fas fa-cog mr-2"></i>Notification Settings
                        </button>
                        <button
                            class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                            <i class="fas fa-question-circle mr-2"></i>Learn More
                        </button>
                    </div>
                </div>
            <?php else: ?>
                <!-- Notifications List -->
                <div class="p-6">
                    <div class="space-y-4">
                        <?php foreach ($notifications as $index => $notification): ?>
                            <div class="notification-card <?= $notification['is_read'] ? 'notification-read' : 'notification-unread notification-new' ?> rounded-xl p-5 fade-in"
                                style="animation-delay: <?= $index * 0.1 ?>s">
                                <div class="flex items-start space-x-4">
                                    <!-- Notification Icon -->
                                    <div class="notification-icon <?= $notification['is_read'] ? '' : 'notification-new' ?>">
                                        <i class="fas fa-info-circle"></i>
                                    </div>

                                    <!-- Content -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-start justify-between mb-2">
                                            <h3 class="font-semibold text-lg text-gray-900 leading-tight">
                                                <?= htmlspecialchars($notification['title']) ?>
                                                <?php if (!$notification['is_read']): ?>
                                                    <span class="inline-block w-2 h-2 bg-blue-500 rounded-full ml-2"></span>
                                                <?php endif; ?>
                                            </h3>
                                            <span class="notification-time text-gray-500 ml-4">
                                                <?= date('M j, Y g:i A', strtotime($notification['created_at'])) ?>
                                            </span>
                                        </div>
                                        <p class="text-gray-700 leading-relaxed">
                                            <?= htmlspecialchars($notification['message']) ?>
                                        </p>

                                        <!-- Action buttons for each notification -->
                                        <div class="flex items-center mt-3 space-x-3">
                                            <a href="view_notification.php?id=<?= $notification['id'] ?>"
                                                class="text-sm text-blue-600 hover:text-blue-800 font-medium transition-colors duration-200">
                                                <i class="fas fa-eye mr-1"></i>View Details
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    // Add smooth scrolling and enhanced interactions
    document.addEventListener('DOMContentLoaded', function () {
        // Add hover effects and smooth animations
        const notificationCards = document.querySelectorAll('.notification-card');

        notificationCards.forEach(card => {
            card.addEventListener('mouseenter', function () {
                this.style.transform = 'translateY(-4px)';
            });

            card.addEventListener('mouseleave', function () {
                this.style.transform = 'translateY(0)';
            });
        });

        // Add click handlers for action buttons (you can customize these)
        const actionButtons = document.querySelectorAll('[data-action]');
        actionButtons.forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                const action = this.getAttribute('data-action');
                // Add your action handling logic here
                console.log('Action:', action);
            });
        });
    });
</script>

<?php include '../includes/footer.php'; ?>