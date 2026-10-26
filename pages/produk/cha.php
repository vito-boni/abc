<?php
// Pengaturan halaman
$page_title = "Edit Produk";
$page_depth = 2;
require_once "../../config/connect.php";
require_once "../../includes/header.php";

// Proteksi admin
if ($role !== 'admin') {
    header("Location: view.php");
    exit;
}

$id = (int) $_GET['id'];

// Update data
if (isset($_POST['submit'])) {
    $nama  = mysqli_real_escape_string($conn, $_POST['nama_produk']);
    $price = (int) $_POST['price'];
    $jenis = mysqli_real_escape_string($conn, $_POST['jenis']);
    $stock = (int) $_POST['stock'];

    // Query update
    mysqli_query($conn, "UPDATE produk 
                         SET nama_produk='$nama', price=$price, jenis='$jenis', stock=$stock 
                         WHERE id_produk=$id");

    header("Location: view.php");
    exit;
}

// Ambil data
$query = mysqli_query($conn, "SELECT * FROM produk WHERE id_produk = $id");
$data  = mysqli_fetch_assoc($query);

if (!$data) {
    header("Location: view.php");
    exit;
}
?>

<h2>Edit Produk</h2>
<p><a href="view.php">Back to List</a></p>

<form method="POST" action="">
    <label>Nama Produk:</label><br>
    <input type="text" name="nama_produk" value="<?= htmlspecialchars($data['nama_produk']); ?>" required><br><br>

    <label>Jenis:</label><br>
    <input type="text" name="jenis" value="<?= htmlspecialchars($data['jenis']); ?>" required><br><br>

    <label>Harga (Rp):</label><br>
    <input type="number" name="price" min="0" value="<?= $data['price']; ?>" required><br><br>

    <label>Stok:</label><br>
    <input type="number" name="stock" min="0" value="<?= $data['stock']; ?>" required><br><br>

    <button type="submit" name="submit">Simpan Perubahan</button>
</form>

<?php
require_once "../../includes/footer.php";
?>
