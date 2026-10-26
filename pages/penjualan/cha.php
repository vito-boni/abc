<?php
// Pengaturan halaman
$page_title = "Edit Faktur Penjualan";
$page_depth = 2;
require_once "../../config/connect.php";
require_once "../../services/math.php";
require_once "../../includes/header.php";

// Proteksi admin
if ($role !== 'admin') {
    header("Location: view.php");
    exit;
}

$no_faktur = mysqli_real_escape_string($conn, $_GET['no'] ?? '');

// Ambil faktur
$q_faktur = mysqli_query($conn, "SELECT * FROM faktur WHERE no_faktur = '$no_faktur'");
$faktur   = mysqli_fetch_assoc($q_faktur);

if (!$faktur) {
    header("Location: view.php");
    exit;
}

$error = "";

// Update data
if (isset($_POST['submit'])) {
    $tgl_faktur   = mysqli_real_escape_string($conn, $_POST['tgl_faktur']);
    $due_date     = mysqli_real_escape_string($conn, $_POST['due_date']);
    $metode_bayar = mysqli_real_escape_string($conn, $_POST['metode_bayar']);
    $id_customer  = (int) $_POST['id_customer'];
    $id_company   = (int) $_POST['id_company'];

    // Hitung subtotal
    $q_detail = mysqli_query($conn, "SELECT SUM(qty * price) AS subtotal FROM detail_faktur WHERE no_faktur = '$no_faktur'");
    $d_sub    = mysqli_fetch_assoc($q_detail);
    $subtotal = $d_sub['subtotal'] ?? 0;

    $ppn         = hitungPpn($subtotal);
    $grand_total = hitungGrandTotal($subtotal, $ppn);

    // Query update
    $upd = "UPDATE faktur 
            SET tgl_faktur='$tgl_faktur', due_date='$due_date', metode_bayar='$metode_bayar', 
                ppn=$ppn, grand_total=$grand_total, id_customer=$id_customer, id_company=$id_company 
            WHERE no_faktur='$no_faktur'";

    if (mysqli_query($conn, $upd)) {
        header("Location: view.php");
        exit;
    } else {
        $error = "Gagal: " . mysqli_error($conn);
    }
}

// Data relasi
$customers = mysqli_query($conn, "SELECT * FROM customer ORDER BY nama_customer ASC");
$companies = mysqli_query($conn, "SELECT * FROM company ORDER BY nama_company ASC");
$details   = mysqli_query($conn, "SELECT df.*, p.nama_produk FROM detail_faktur df JOIN produk p ON df.id_produk = p.id_produk WHERE df.no_faktur = '$no_faktur'");
?>

<h2>Edit Faktur Penjualan</h2>
<p><a href="view.php">Back to List</a></p>

<!-- Cek error -->
<?php if ($error): ?>
    <p style="color: red;"><?= $error; ?></p>
<?php endif; ?>

<form method="POST" action="">
    <label>Nomor Faktur:</label><br>
    <input type="text" value="<?= htmlspecialchars($faktur['no_faktur']); ?>" disabled><br><br>

    <label>Tanggal Faktur:</label><br>
    <input type="date" name="tgl_faktur" value="<?= $faktur['tgl_faktur']; ?>" required><br><br>

    <label>Jatuh Tempo (Due Date):</label><br>
    <input type="date" name="due_date" value="<?= $faktur['due_date']; ?>" required><br><br>

    <label>Metode Bayar:</label><br>
    <select name="metode_bayar" required>
        <option value="Transfer Bank" <?= $faktur['metode_bayar'] === 'Transfer Bank' ? 'selected' : ''; ?>>Transfer Bank</option>
        <option value="Cash" <?= $faktur['metode_bayar'] === 'Cash' ? 'selected' : ''; ?>>Cash</option>
        <option value="Kredit" <?= $faktur['metode_bayar'] === 'Kredit' ? 'selected' : ''; ?>>Kredit</option>
        <option value="Giro" <?= $faktur['metode_bayar'] === 'Giro' ? 'selected' : ''; ?>>Giro</option>
    </select><br><br>

    <label>Pilih Perusahaan (Company):</label><br>
    <select name="id_company" required>
        <?php while ($cp = mysqli_fetch_assoc($companies)): ?>
            <option value="<?= $cp['id_company']; ?>" <?= $faktur['id_company'] == $cp['id_company'] ? 'selected' : ''; ?>>
                <?= htmlspecialchars($cp['nama_company']); ?>
            </option>
        <?php endwhile; ?>
    </select><br><br>

    <label>Pilih Pelanggan (Customer):</label><br>
    <select name="id_customer" required>
        <?php while ($c = mysqli_fetch_assoc($customers)): ?>
            <option value="<?= $c['id_customer']; ?>" <?= $faktur['id_customer'] == $c['id_customer'] ? 'selected' : ''; ?>>
                <?= htmlspecialchars($c['nama_customer']); ?> - <?= htmlspecialchars($c['perusahaan_customer']); ?>
            </option>
        <?php endwhile; ?>
    </select><br><br>

    <h3>Daftar Item Faktur</h3>
    <table border="1" cellpadding="8" cellspacing="0">
        <tr>
            <th>Produk</th>
            <th>Qty</th>
            <th>Harga Satuan</th>
            <th>Subtotal</th>
        </tr>
        <?php while ($d = mysqli_fetch_assoc($details)): ?>
        <tr>
            <td><?= htmlspecialchars($d['nama_produk']); ?></td>
            <td><?= $d['qty']; ?></td>
            <td>Rp <?= number_format($d['price'], 0, ',', '.'); ?></td>
            <td>Rp <?= number_format($d['qty'] * $d['price'], 0, ',', '.'); ?></td>
        </tr>
        <?php endwhile; ?>
    </table><br>

    <button type="submit" name="submit">Simpan Perubahan</button>
</form>

<?php
require_once "../../includes/footer.php";
?>
