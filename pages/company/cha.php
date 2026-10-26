<?php
// Pengaturan halaman
$page_title = "Edit Company";
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
    $nama   = mysqli_real_escape_string($conn, $_POST['nama_company']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $telp   = mysqli_real_escape_string($conn, $_POST['no_telp']);
    $fax    = mysqli_real_escape_string($conn, $_POST['fax']);

    // Query update
    mysqli_query($conn, "UPDATE company 
                         SET nama_company='$nama', alamat='$alamat', no_telp='$telp', fax='$fax' 
                         WHERE id_company=$id");

    header("Location: view.php");
    exit;
}

// Ambil data
$query = mysqli_query($conn, "SELECT * FROM company WHERE id_company = $id");
$data  = mysqli_fetch_assoc($query);

if (!$data) {
    header("Location: view.php");
    exit;
}
?>

<h2>Edit Company</h2>
<p><a href="view.php">Back to List</a></p>

<form method="POST" action="">
    <label>Nama Company:</label><br>
    <input type="text" name="nama_company" value="<?= htmlspecialchars($data['nama_company']); ?>" required><br><br>

    <label>Alamat:</label><br>
    <textarea name="alamat" rows="4" cols="50" required><?= htmlspecialchars($data['alamat']); ?></textarea><br><br>

    <label>No Telepon:</label><br>
    <input type="text" name="no_telp" value="<?= htmlspecialchars($data['no_telp']); ?>" required><br><br>

    <label>Fax:</label><br>
    <input type="text" name="fax" value="<?= htmlspecialchars($data['fax']); ?>" required><br><br>

    <button type="submit" name="submit">Simpan Perubahan</button>
</form>

<?php
require_once "../../includes/footer.php";
?>
