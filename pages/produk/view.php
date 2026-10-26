<?php
// Pengaturan halaman
$page_title = "Data Produk";
$page_depth = 2;
require_once "../../config/connect.php";
require_once "../../includes/header.php";

// Query produk
$result = mysqli_query($conn, "SELECT * FROM produk ORDER BY id_produk ASC");
?>

<h2>Data Produk</h2>
<p>
    <a href="../main.php">Dashboard</a>
    <?php if ($role === 'admin'): ?>
        | <a href="add.php">Tambah Produk</a>
    <?php endif; ?>
</p>

<table border="1" cellpadding="8" cellspacing="0" width="100%">
    <tr>
        <th>ID</th>
        <th>Nama Produk</th>
        <th>Jenis</th>
        <th>Harga</th>
        <th>Stok</th>
        <?php if ($role === 'admin'): ?>
            <th>Aksi</th>
        <?php endif; ?>
    </tr>
    <?php while ($row = mysqli_fetch_assoc($result)): ?>
    <tr>
        <td><?= $row['id_produk']; ?></td>
        <td><?= htmlspecialchars($row['nama_produk']); ?></td>
        <td><?= htmlspecialchars($row['jenis']); ?></td>
        <td>Rp <?= number_format($row['price'], 0, ',', '.'); ?></td>
        <td><?= $row['stock']; ?></td>
        <?php if ($role === 'admin'): ?>
            <td>
                <a href="cha.php?id=<?= $row['id_produk']; ?>">Edit</a> | 
                <a href="del.php?id=<?= $row['id_produk']; ?>" onclick="return confirm('Hapus produk ini?');">Hapus</a>
            </td>
        <?php endif; ?>
    </tr>
    <?php endwhile; ?>
</table>

<?php
require_once "../../includes/footer.php";
?>
