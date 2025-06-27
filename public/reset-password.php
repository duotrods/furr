<?php require_once '../includes/config.php'; ?>

<?php
if (!isset($_GET['token']) || empty($_GET['token'])) {
    $_SESSION['error_message'] = 'Invalid password reset link.';
    header('Location: forgot-password.php');
    exit();
}

$token = $_GET['token'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE reset_token = ? AND reset_token_expires > NOW()");
$stmt->execute([$token]);
$user = $stmt->fetch();

if (!$user) {
    $_SESSION['error_message'] = 'Invalid or expired password reset link.';
    header('Location: forgot-password.php');
    exit();
}
?>

<?php require_once '../includes/header.php'; ?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto bg-white rounded-lg shadow-lg p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Reset Password</h1>
        
        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
            </div>
        <?php endif; ?>
        
        <form action="../php/auth/reset-password-process.php" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
            <input type="hidden" name="token" value="<?php echo $token; ?>">
            
            <div class="mb-4">
                <label for="password" class="block text-gray-700 font-semibold mb-2">New Password</label>
                <input type="password" id="password" name="password" required minlength="8"
                       class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500">
                <p class="text-gray-500 text-sm mt-1">Minimum 8 characters</p>
            </div>
            
            <div class="mb-4">
                <label for="confirm_password" class="block text-gray-700 font-semibold mb-2">Confirm New Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required minlength="8"
                       class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500">
            </div>
            
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-300">
                Update Password
            </button>
        </form>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>