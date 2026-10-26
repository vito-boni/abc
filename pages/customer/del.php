<?php
// Proteksi sesi
require_once "../../auth/guard.php";
require_once "../../config/connect.php";

// Proteksi admin
if (($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: view.php");
    exit;
}

// Hapus data
if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    mysqli_query($conn, "DELETE FROM customer WHERE id_customer = $id");
}

header("Location: view.php");
exit;
?>
