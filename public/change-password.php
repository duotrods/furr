<?php
require_once '../includes/config.php';
requireAuth();

$user_id = getUserId();
$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (!validateCSRFToken($_POST['csrf_token'])) {
        $errors[] = 'Invalid security token. Please try again.';
    } else {
        // Get form data
        $current_password = $_POST['current_password'] ?? '';
        $new_password = $_POST['new_password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        // Validate inputs
        if (empty($current_password)) {
            $errors[] = 'Current password is required.';
        }
        
        if (empty($new_password)) {
            $errors[] = 'New password is required.';
        } elseif (strlen($new_password) < 8) {
            $errors[] = 'New password must be at least 8 characters.';
        }
        
        if ($new_password !== $confirm_password) {
            $errors[] = 'New passwords do not match.';
        }

        // Verify current password if no errors
        if (empty($errors)) {
            $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
            $stmt->execute([$user_id]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($current_password, $user['password'])) {
                // Update password
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $update_stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
                
                if ($update_stmt->execute([$hashed_password, $user_id])) {
                    $success = true;
                    
                    // Send email notification
                    // sendPasswordChangeNotification($user_id);
                    
                    // Regenerate session for security
                    session_regenerate_id(true);
                } else {
                    $errors[] = 'Failed to update password. Please try again.';
                }
            } else {
                $errors[] = 'Current password is incorrect.';
            }
        }
    }
}

$csrf_token = generateCSRFToken();
?>

<?php include '../includes/header.php'; ?>

<div class="max-w-md mx-auto bg-white p-6 rounded shadow mt-8">
    <h2 class="text-2xl font-semibold mb-4">Change Password</h2>

    <!-- Display Errors -->
    <?php if (!empty($errors)): ?>
        <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
            <ul class="list-disc list-inside">
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <!-- Success Message -->
    <?php if ($success): ?>
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
            Password updated successfully.
        </div>
    <?php endif; ?>

    <form action="" method="POST" novalidate>
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">

        <div class="mb-4">
            <label for="current_password" class="block text-gray-700 font-medium mb-1">Current Password</label>
            <input type="password" id="current_password" name="current_password" required
                class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" />
        </div>

        <div class="mb-4">
            <label for="new_password" class="block text-gray-700 font-medium mb-1">New Password</label>
            <input type="password" id="new_password" name="new_password" required minlength="8"
                class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" />
        </div>

        <div class="mb-6">
            <label for="confirm_password" class="block text-gray-700 font-medium mb-1">Confirm New Password</label>
            <input type="password" id="confirm_password" name="confirm_password" required minlength="8"
                class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" />
        </div>

        <button type="submit"
            class="w-full bg-blue-600 text-white font-semibold py-2 rounded hover:bg-blue-700 transition">
            Change Password
        </button>
    </form>
</div>

<?php include '../includes/footer.php'; ?>
    
   