<?php
// Admin directory index - redirect to login or dashboard

session_start();

// Check if user is already logged in
if (isset($_SESSION['user_id']) && isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) {
    // User is logged in, redirect to dashboard
    header('Location: dashboard.php');
    exit;
} else {
    // User is not logged in, redirect to login page
    header('Location: login.php');
    exit;
}
