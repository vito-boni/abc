<?php
// Pengaturan halaman
$page_title = "Edit Customer";
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
    $nama        = mysqli_real_escape_string($conn, $_POST['nama_customer']);
    $perusahaan  = mysqli_real_escape_string($conn, $_POST['perusahaan_customer']);
    $alamat      = mysqli_real_escape_string($conn, $_POST['alamat']);

    // Query update
    mysqli_query($conn, "UPDATE customer 
                         SET nama_customer='$nama', perusahaan_customer='$perusahaan', alamat='$alamat' 
                         WHERE id_customer=$id");

    header("Location: view.php");
    exit;
}

// Ambil data
$query = mysqli_query($conn, "SELECT * FROM customer WHERE id_customer = $id");
$data  = mysqli_fetch_assoc($query);

if (!$data) {
    header("Location: view.php");
    exit;
}
?>

<h2>Edit Customer</h2>
<p><a href="view.php">Back to List</a></p>

<form method="POST" action="">
    <label>Nama Customer:</label><br>
    <input type="text" name="nama_customer" value="<?= htmlspecialchars($data['nama_customer']); ?>" required><br><br>

    <label>Perusahaan Customer:</label><br>
    <input type="text" name="perusahaan_customer" value="<?= htmlspecialchars($data['perusahaan_customer']); ?>" required><br><br>

    <label>Alamat:</label><br>
    <textarea name="alamat" rows="4" cols="50" required><?= htmlspecialchars($data['alamat']); ?></textarea><br><br>

    <button type="submit" name="submit">Simpan Perubahan</button>
</form>

<?php
require_once "../../includes/footer.php";
?>
