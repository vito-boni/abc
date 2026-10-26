<?php
// Pengaturan halaman
$page_title = "Dashboard";
$page_depth = 1;
require_once "../config/connect.php";
require_once "../includes/header.php";

// Hitung data
$total_faktur = mysqli_num_rows(mysqli_query($conn, "SELECT no_faktur FROM faktur"));
$total_customer = mysqli_num_rows(mysqli_query($conn, "SELECT id_customer FROM customer"));
$total_company = mysqli_num_rows(mysqli_query($conn, "SELECT id_company FROM company"));
$total_produk = mysqli_num_rows(mysqli_query($conn, "SELECT id_produk FROM produk"));

$q_rev = mysqli_query($conn, "SELECT SUM(grand_total) AS total_omset FROM faktur");
$d_rev = mysqli_fetch_assoc($q_rev);
$total_omset = $d_rev['total_omset'] ?? 0;
?>

<h2>Dashboard</h2>
<p>Selamat Datang, <b><?= htmlspecialchars($user); ?></b>!</p>

<p>Ringkasan Sistem:</p>
<ul>
    <li>Total Faktur: <?= $total_faktur; ?></li>
    <li>Total Pendapatan: Rp <?= number_format($total_omset, 0, ',', '.'); ?></li>
    <li>Total Customer: <?= $total_customer; ?></li>
    <li>Total Produk: <?= $total_produk; ?></li>
</ul>

<h3>Transaksi Terkini</h3>
<table border="1" cellpadding="8" cellspacing="0" width="100%">
    <tr>
        <th>No Faktur</th>
        <th>Tanggal</th>
        <th>Jatuh Tempo</th>
        <th>Customer</th>
        <th>Perusahaan</th>
        <th>Grand Total</th>
        <th>Aksi</th>
    </tr>
    <?php
    $res = mysqli_query($conn, "SELECT f.*, c.nama_customer, comp.nama_company 
                                FROM faktur f
                                JOIN customer c ON f.id_customer = c.id_customer
                                JOIN company comp ON f.id_company = comp.id_company
                                ORDER BY f.tgl_faktur DESC LIMIT 5");
    while ($r = mysqli_fetch_assoc($res)):
    ?>
    <tr>
        <td><?= htmlspecialchars($r['no_faktur']); ?></td>
        <td><?= $r['tgl_faktur']; ?></td>
        <td><?= $r['due_date']; ?></td>
        <td><?= htmlspecialchars($r['nama_customer']); ?></td>
        <td><?= htmlspecialchars($r['nama_company']); ?></td>
        <td>Rp <?= number_format($r['grand_total'], 0, ',', '.'); ?></td>
        <td>
            <a href="penjualan/print.php?no=<?= urlencode($r['no_faktur']); ?>" target="_blank">Cetak</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

<?php
require_once "../includes/footer.php";
?>