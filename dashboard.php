<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get user info
$user_id = $_SESSION['user_id'];
$user = $db->query("SELECT * FROM users WHERE id = $user_id")->fetch_assoc();

if (!$user) {
    session_destroy();
    header('Location: login.php');
    exit;
}

// Redirect based on role
switch ($user['role']) {
    case 'admin':
        header('Location: admin_dashboard.php');
        exit;
    case 'pastor':
        header('Location: pastor_dashboard.php');
        exit;
    case 'member':
    default:
        header('Location: member_dashboard.php');
        exit;
}
?>