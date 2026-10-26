<?php
// Pengaturan halaman
$page_title = "Data Pelanggan";
$page_depth = 2;
require_once "../../config/connect.php";
require_once "../../includes/header.php";

$id = (int) ($_GET['id'] ?? 0);

// Ambil data
$query = mysqli_query($conn, "SELECT * FROM customer WHERE id_customer = $id");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    header("Location: view.php");
    exit;
}
?>

<h2>Detail Customer</h2>
<p><a href="view.php">Back to List</a></p>

<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>ID Customer</th>
        <td><?= $data['id_customer']; ?></td>
    </tr>
    <tr>
        <th>Nama Customer</th>
        <td><?= htmlspecialchars($data['nama_customer']); ?></td>
    </tr>
    <tr>
        <th>Perusahaan</th>
        <td><?= htmlspecialchars($data['perusahaan_customer']); ?></td>
    </tr>
    <tr>
        <th>Alamat</th>
        <td><?= htmlspecialchars($data['alamat']); ?></td>
    </tr>
</table>

<?php
require_once "../../includes/footer.php";
?>
