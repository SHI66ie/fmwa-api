<?php
require_once '../config.php';
require_once 'auth.php';

$auth = new Auth($pdo);
$auth->logout();

// Redirect to login page with logout message
header('Location: login.php?logout=1');
exit;
?>
