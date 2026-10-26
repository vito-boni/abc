<?php
// Pengaturan halaman
$page_title = "Tambah Produk";
$page_depth = 2;
require_once "../../config/connect.php";
require_once "../../includes/header.php";

// Proteksi admin
if ($role !== 'admin') {
    header("Location: view.php");
    exit;
}

// Simpan data
if (isset($_POST['submit'])) {
    $nama  = mysqli_real_escape_string($conn, $_POST['nama_produk']);
    $price = (int) $_POST['price'];
    $jenis = mysqli_real_escape_string($conn, $_POST['jenis']);
    $stock = (int) $_POST['stock'];

    // Query insert
    mysqli_query($conn, "INSERT INTO produk (nama_produk, price, jenis, stock) 
                         VALUES ('$nama', $price, '$jenis', $stock)");

    header("Location: view.php");
    exit;
}
?>

<h2>Tambah Produk</h2>
<p><a href="view.php">Back to List</a></p>

<form method="POST" action="">
    <label>Nama Produk:</label><br>
    <input type="text" name="nama_produk" required><br><br>

    <label>Jenis:</label><br>
    <input type="text" name="jenis" required><br><br>

    <label>Harga (Rp):</label><br>
    <input type="number" name="price" min="0" required><br><br>

    <label>Stok:</label><br>
    <input type="number" name="stock" min="0" value="1" required><br><br>

    <button type="submit" name="submit">Simpan Produk</button>
</form>

<?php
require_once "../../includes/footer.php";
?>
