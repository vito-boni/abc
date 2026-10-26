<?php
// Pengaturan halaman
$page_title = "Tambah Customer";
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
    $nama        = mysqli_real_escape_string($conn, $_POST['nama_customer']);
    $perusahaan  = mysqli_real_escape_string($conn, $_POST['perusahaan_customer']);
    $alamat      = mysqli_real_escape_string($conn, $_POST['alamat']);

    // Query insert
    mysqli_query($conn, "INSERT INTO customer (nama_customer, perusahaan_customer, alamat) 
                         VALUES ('$nama', '$perusahaan', '$alamat')");

    header("Location: view.php");
    exit;
}
?>

<h2>Tambah Customer</h2>
<p><a href="view.php">Back to List</a></p>

<form method="POST" action="">
    <label>Nama Customer:</label><br>
    <input type="text" name="nama_customer" required><br><br>

    <label>Perusahaan Customer:</label><br>
    <input type="text" name="perusahaan_customer" required><br><br>

    <label>Alamat:</label><br>
    <textarea name="alamat" rows="4" cols="50" required></textarea><br><br>

    <button type="submit" name="submit">Simpan Customer</button>
</form>

<?php
require_once "../../includes/footer.php";
?>
