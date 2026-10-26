<?php
// Mulai sesi
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cek login
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    $prefix = file_exists('auth/login.php') ? 'auth/' : (file_exists('../auth/login.php') ? '../auth/' : '../../auth/');
    header("Location: " . $prefix . "login.php");
    exit;
}
?>