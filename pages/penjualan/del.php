<?php
// Proteksi sesi
require_once "../../auth/guard.php";
require_once "../../config/connect.php";

// Proteksi admin
if (($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: view.php");
    exit;
}

$no = mysqli_real_escape_string($conn, $_GET['no'] ?? '');

if (!empty($no)) {
    // Ambil detail
    $q_detail = mysqli_query($conn, "SELECT id_produk, qty FROM detail_faktur WHERE no_faktur = '$no'");
    while ($row = mysqli_fetch_assoc($q_detail)) {
        $pid = (int) $row['id_produk'];
        $qty = (int) $row['qty'];
        // Kembalikan stok
        mysqli_query($conn, "UPDATE produk SET stock = stock + $qty WHERE id_produk = $pid");
    }

    // Hapus detail
    mysqli_query($conn, "DELETE FROM detail_faktur WHERE no_faktur = '$no'");
    // Hapus faktur
    mysqli_query($conn, "DELETE FROM faktur WHERE no_faktur = '$no'");
}

header("Location: view.php");
exit;
?>
