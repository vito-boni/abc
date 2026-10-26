<?php
// Pengaturan halaman
$page_title = "Data Customer";
$page_depth = 2;
require_once "../../config/connect.php";
require_once "../../includes/header.php";

// Query customer
$result = mysqli_query($conn, "SELECT * FROM customer ORDER BY id_customer ASC");
?>

<h2>Data Customer</h2>
<p>
    <a href="../main.php">Dashboard</a>
    <?php if ($role === 'admin'): ?>
        | <a href="add.php">Tambah Customer</a>
    <?php endif; ?>
</p>

<table border="1" cellpadding="8" cellspacing="0" width="100%">
    <tr>
        <th>ID</th>
        <th>Nama Customer</th>
        <th>Perusahaan</th>
        <th>Alamat</th>
        <?php if ($role === 'admin'): ?>
            <th>Aksi</th>
        <?php endif; ?>
    </tr>
    <?php while ($row = mysqli_fetch_assoc($result)): ?>
    <tr>
        <td><?= $row['id_customer']; ?></td>
        <td><?= htmlspecialchars($row['nama_customer']); ?></td>
        <td><?= htmlspecialchars($row['perusahaan_customer']); ?></td>
        <td><?= htmlspecialchars($row['alamat']); ?></td>
        <?php if ($role === 'admin'): ?>
            <td>
                <a href="cha.php?id=<?= $row['id_customer']; ?>">Edit</a> | 
                <a href="del.php?id=<?= $row['id_customer']; ?>" onclick="return confirm('Hapus customer ini?');">Hapus</a>
            </td>
        <?php endif; ?>
    </tr>
    <?php endwhile; ?>
</table>

<?php
require_once "../../includes/footer.php";
?>
