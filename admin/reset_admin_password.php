<?php
// One-time script to reset the admin user's password to admin123
// IMPORTANT: Upload, run once in the browser, then delete this file from the server.

require_once '../config.php';

try {
    // New password you want to set
    $newPassword = 'admin123';

    // Hash the password using PHP's password_hash
    $hash = password_hash($newPassword, PASSWORD_DEFAULT);

    // Update the admin user
    $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE username = 'admin'");
    $stmt->execute([$hash]);

    if ($stmt->rowCount() > 0) {
        echo '<h2>Admin password reset successful</h2>';
        echo '<p>Username: <strong>admin</strong></p>';
        echo '<p>New password: <strong>' . htmlspecialchars($newPassword, ENT_QUOTES, 'UTF-8') . '</strong></p>';
    } else {
        echo '<h2>No admin user updated</h2>';
        echo '<p>Check that a user with username <strong>admin</strong> exists in the users table.</p>';
    }

    echo '<hr><p><strong>SECURITY NOTE:</strong> After confirming login works, DELETE this file: <code>admin/reset_admin_password.php</code>.</p>';
} catch (Exception $e) {
    echo '<h2>Error resetting password</h2>';
    echo '<pre>' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . '</pre>';
}
