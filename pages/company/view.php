<?php
// Pengaturan halaman
$page_title = "Data Company";
$page_depth = 2;
require_once "../../config/connect.php";
require_once "../../includes/header.php";

// Query company
$result = mysqli_query($conn, "SELECT * FROM company ORDER BY id_company ASC");
?>

<h2>Data Company</h2>
<p>
    <a href="../main.php">Dashboard</a>
    <?php if ($role === 'admin'): ?>
        | <a href="add.php">Tambah Company</a>
    <?php endif; ?>
</p>

<table border="1" cellpadding="8" cellspacing="0" width="100%">
    <tr>
        <th>ID</th>
        <th>Nama Company</th>
        <th>Alamat</th>
        <th>No Telp</th>
        <th>Fax</th>
        <?php if ($role === 'admin'): ?>
            <th>Aksi</th>
        <?php endif; ?>
    </tr>
    <?php while ($row = mysqli_fetch_assoc($result)): ?>
    <tr>
        <td><?= $row['id_company']; ?></td>
        <td><?= htmlspecialchars($row['nama_company']); ?></td>
        <td><?= htmlspecialchars($row['alamat']); ?></td>
        <td><?= htmlspecialchars($row['no_telp']); ?></td>
        <td><?= htmlspecialchars($row['fax']); ?></td>
        <?php if ($role === 'admin'): ?>
            <td>
                <a href="cha.php?id=<?= $row['id_company']; ?>">Edit</a> | 
                <a href="del.php?id=<?= $row['id_company']; ?>" onclick="return confirm('Hapus company ini?');">Hapus</a>
            </td>
        <?php endif; ?>
    </tr>
    <?php endwhile; ?>
</table>

<?php
require_once "../../includes/footer.php";
?>
