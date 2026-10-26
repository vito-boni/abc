<?php
// Pengaturan halaman
$page_title = "Faktur Penjualan";
$page_depth = 2;
require_once "../../config/connect.php";
require_once "../../includes/header.php";

// Query faktur
$sql = "SELECT f.*, c.nama_customer, c.perusahaan_customer, comp.nama_company 
        FROM faktur f
        JOIN customer c ON f.id_customer = c.id_customer
        JOIN company comp ON f.id_company = comp.id_company
        ORDER BY f.tgl_faktur DESC";
$result = mysqli_query($conn, $sql);
?>

<h2>Faktur Penjualan</h2>
<p>
    <a href="../main.php">Dashboard</a>
    <?php if ($role === 'admin'): ?>
        | <a href="add.php">Buat Faktur Baru</a>
    <?php endif; ?>
</p>

<table border="1" cellpadding="8" cellspacing="0" width="100%">
    <tr>
        <th>No Faktur</th>
        <th>Tgl Faktur</th>
        <th>Jatuh Tempo</th>
        <th>Customer</th>
        <th>Company</th>
        <th>Metode Bayar</th>
        <th>Grand Total</th>
        <th>Aksi</th>
    </tr>
    <?php while ($row = mysqli_fetch_assoc($result)): ?>
    <tr>
        <td><?= htmlspecialchars($row['no_faktur']); ?></td>
        <td><?= $row['tgl_faktur']; ?></td>
        <td><?= $row['due_date']; ?></td>
        <td><?= htmlspecialchars($row['nama_customer']); ?> (<?= htmlspecialchars($row['perusahaan_customer']); ?>)</td>
        <td><?= htmlspecialchars($row['nama_company']); ?></td>
        <td><?= htmlspecialchars($row['metode_bayar']); ?></td>
        <td>Rp <?= number_format($row['grand_total'], 0, ',', '.'); ?></td>
        <td>
            <?php if ($role === 'admin'): ?>
                <a href="cha.php?no=<?= urlencode($row['no_faktur']); ?>">Edit</a>
                | <a href="del.php?no=<?= urlencode($row['no_faktur']); ?>" onclick="return confirm('Hapus faktur ini?');">Hapus</a>
            <?php endif; ?>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

<?php
require_once "../../includes/footer.php";
?>
