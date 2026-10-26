<?php
// Proteksi halaman
require_once __DIR__ . "/../auth/guard.php";
$role = $_SESSION['role'] ?? 'customer';
$user = $_SESSION['username'] ?? 'User';

// Deteksi level
$base_url = (isset($page_depth) && $page_depth === 2) ? "../../" : "../";
?>
<!DOCTYPE html>
<html>
<head>
    <title><?= $page_title ?? 'Sistem Faktur'; ?></title>
</head>
<body>

    <!-- 1. Header -->
    <header>
        <h2>SISTEM FAKTUR PENJUALAN</h2>
        <p>User: <b><?= htmlspecialchars($user); ?></b> (<?= strtoupper($role); ?>)</p>
    </header>
    <hr>

    <!-- 2. Navigation Bar-->
    <nav>
        <a href="<?= $base_url; ?>pages/main.php">Dashboard</a> | 
        <a href="<?= $base_url; ?>pages/penjualan/view.php">Faktur Penjualan</a> | 
        <?php if ($role === 'admin'): ?>
            <a href="<?= $base_url; ?>pages/customer/view.php">Customer</a> | 
            <a href="<?= $base_url; ?>pages/company/view.php">Company</a> | 
            <a href="<?= $base_url; ?>pages/produk/view.php">Produk</a> | 
        <?php endif; ?>
        <a href="<?= $base_url; ?>auth/logout.php" onclick="return confirm('Logout?');">Logout</a>
    </nav>
    <hr>

    <!-- Struktur tabel -->
    <table width="100%" border="0" cellpadding="10" cellspacing="0">
        <tr valign="top">
            <!-- 3. Sidebar -->
            <td width="200">
                <aside>
                    <b>Menu Utama</b>
                    <ul>
                        <li><a href="<?= $base_url; ?>pages/main.php">Dashboard</a></li>
                        <li><a href="<?= $base_url; ?>pages/penjualan/view.php">Data Faktur</a></li>
                        <?php if ($role === 'admin'): ?>
                            <li><a href="<?= $base_url; ?>pages/penjualan/add.php">Tambah Faktur</a></li>
                        <?php endif; ?>
                    </ul>

                    <?php if ($role === 'admin'): ?>
                        <b>Master Data</b>
                        <ul>
                            <li><a href="<?= $base_url; ?>pages/customer/view.php">Data Customer</a></li>
                            <li><a href="<?= $base_url; ?>pages/company/view.php">Data Company</a></li>
                            <li><a href="<?= $base_url; ?>pages/penjualan/view.php">Data Faktur</a></li>
                            <li><a href="<?= $base_url; ?>pages/produk/view.php">Data Produk</a></li>
                        </ul>
                    <?php endif; ?>

                    <b>Akun</b>
                    <ul>
                        <li><a href="<?= $base_url; ?>auth/logout.php" onclick="return confirm('Logout?');">Logout</a></li>
                    </ul>
                </aside>
            </td>

            <!-- Garis batas -->
            <td width="1">
                <hr width="1" size="500">
            </td>

            <!-- 4. Content -->
            <td>
                <main>
