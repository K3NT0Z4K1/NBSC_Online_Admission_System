<?php
// Sample login credentials (use database in production)
$valid_username = 'admin';
$valid_password = 'admin123';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($username === $valid_username && $password === $valid_password) {
        $_SESSION['logged_in'] = true;
        header('Location: dashboard.php');
        exit;
    } else {
        echo "<script>alert('Invalid username or password.'); window.history.back();</script>";
    }
} else {
    header('Location: login.php');
    exit;
}
