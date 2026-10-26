<?php
// Pengaturan halaman
$page_title = "Tambah Company";
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
    $nama   = mysqli_real_escape_string($conn, $_POST['nama_company']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $telp   = mysqli_real_escape_string($conn, $_POST['no_telp']);
    $fax    = mysqli_real_escape_string($conn, $_POST['fax']);

    // Query insert
    mysqli_query($conn, "INSERT INTO company (nama_company, alamat, no_telp, fax) 
                         VALUES ('$nama', '$alamat', '$telp', '$fax')");

    header("Location: view.php");
    exit;
}
?>

<h2>Tambah Company</h2>
<p><a href="view.php">Back to List</a></p>

<form method="POST" action="">
    <label>Nama Company:</label><br>
    <input type="text" name="nama_company" required><br><br>

    <label>Alamat:</label><br>
    <textarea name="alamat" rows="4" cols="50" required></textarea><br><br>

    <label>No Telepon:</label><br>
    <input type="text" name="no_telp" required><br><br>

    <label>Fax:</label><br>
    <input type="text" name="fax" required><br><br>

    <button type="submit" name="submit">Simpan Company</button>
</form>

<?php
require_once "../../includes/footer.php";
?>
