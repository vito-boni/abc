<?php
// Pengaturan halaman
$page_title = "Buat Faktur Penjualan";
$page_depth = 2;
require_once "../../config/connect.php";
require_once "../../services/math.php";
require_once "../../includes/header.php";

// Proteksi admin
if ($role !== 'admin') {
    header("Location: view.php");
    exit;
}

$error = "";

// Simpan faktur
if (isset($_POST['submit'])) {
    $no_faktur    = mysqli_real_escape_string($conn, trim($_POST['no_faktur']));
    $tgl_faktur   = mysqli_real_escape_string($conn, $_POST['tgl_faktur']);
    $due_date     = mysqli_real_escape_string($conn, $_POST['due_date']);
    $metode_bayar = mysqli_real_escape_string($conn, $_POST['metode_bayar']);
    $id_customer  = (int) $_POST['id_customer'];
    $id_company   = (int) $_POST['id_company'];
    $user_login   = $_SESSION['username'] ?? 'customer';

    $produk_ids   = $_POST['id_produk'] ?? [];
    $qtys         = $_POST['qty'] ?? [];

    if (empty($no_faktur)) {
        $error = "Nomor faktur wajib diisi.";
    } elseif (empty($produk_ids)) {
        $error = "Minimal pilih 1 produk.";
    } else {
        // Cek duplikasi
        $check = mysqli_query($conn, "SELECT no_faktur FROM faktur WHERE no_faktur = '$no_faktur'");
        if (mysqli_num_rows($check) > 0) {
            $error = "Nomor faktur sudah terdaftar.";
        } else {
            // Hitung subtotal
            $subtotal = 0;
            $items = [];
            for ($i = 0; $i < count($produk_ids); $i++) {
                $pid = (int) $produk_ids[$i];
                $qty = (int) $qtys[$i];
                if ($pid > 0 && $qty > 0) {
                    $q_prod = mysqli_query($conn, "SELECT price, stock FROM produk WHERE id_produk = $pid");
                    $d_prod = mysqli_fetch_assoc($q_prod);
                    if ($d_prod) {
                        $price = (int) $d_prod['price'];
                        $subtotal += ($price * $qty);
                        $items[] = [
                            'id_produk' => $pid,
                            'qty'       => $qty,
                            'price'     => $price
                        ];
                    }
                }
            }

            if (empty($items)) {
                $error = "Produk dan kuantitas tidak valid.";
            } else {
                // Hitung total
                $ppn = hitungPpn($subtotal);
                $grand_total = hitungGrandTotal($subtotal, $ppn);

                // Insert faktur
                $ins_faktur = "INSERT INTO faktur (no_faktur, tgl_faktur, due_date, metode_bayar, ppn, dp, grand_total, user, id_customer, id_company) 
                               VALUES ('$no_faktur', '$tgl_faktur', '$due_date', '$metode_bayar', $ppn, 0, $grand_total, '$user_login', $id_customer, $id_company)";
                
                if (mysqli_query($conn, $ins_faktur)) {
                    // Insert detail
                    foreach ($items as $item) {
                        $pid   = $item['id_produk'];
                        $qty   = $item['qty'];
                        $price = $item['price'];
                        mysqli_query($conn, "INSERT INTO detail_faktur (id_produk, no_faktur, qty, price) 
                                             VALUES ($pid, '$no_faktur', $qty, $price)");
                        // Update stok
                        mysqli_query($conn, "UPDATE produk SET stock = stock - $qty WHERE id_produk = $pid");
                    }

                    header("Location: view.php");
                    exit;
                } else {
                    $error = "Gagal menyimpan faktur: " . mysqli_error($conn);
                }
            }
        }
    }
}

// Data relasi
$customers = mysqli_query($conn, "SELECT * FROM customer ORDER BY nama_customer ASC");
$companies = mysqli_query($conn, "SELECT * FROM company ORDER BY nama_company ASC");
$products  = mysqli_query($conn, "SELECT * FROM produk WHERE stock > 0 ORDER BY nama_produk ASC");
$prod_list = [];
while ($p = mysqli_fetch_assoc($products)) {
    $prod_list[] = $p;
}
?>

<h2>Buat Faktur Penjualan</h2>
<p><a href="view.php">Back to List</a></p>

<!-- Cek error -->
<?php if ($error): ?>
    <p style="color: red;"><?= $error; ?></p>
<?php endif; ?>

<form method="POST" action="">
    <label>Nomor Faktur:</label><br>
    <input type="text" name="no_faktur" value="INV-<?= date('Ym'); ?>-<?= rand(1000, 9999); ?>" required><br><br>

    <label>Tanggal Faktur:</label><br>
    <input type="date" name="tgl_faktur" value="<?= date('Y-m-d'); ?>" required><br><br>

    <label>Jatuh Tempo (Due Date):</label><br>
    <input type="date" name="due_date" value="<?= date('Y-m-d', strtotime('+14 days')); ?>" required><br><br>

    <label>Metode Bayar:</label><br>
    <select name="metode_bayar" required>
        <option value="Transfer Bank">Transfer Bank</option>
        <option value="Cash">Cash (Tunai)</option>
        <option value="Kredit">Kredit</option>
        <option value="Giro">BNPL (Buy Now Pay Later)</option>
    </select><br><br>

    <label>Pilih Perusahaan (Company):</label><br>
    <select name="id_company" required>
        <option value="">-- Select Company --</option>
        <?php while ($cp = mysqli_fetch_assoc($companies)): ?>
            <option value="<?= $cp['id_company']; ?>"><?= htmlspecialchars($cp['nama_company']); ?></option>
        <?php endwhile; ?>
    </select><br><br>

    <label>Pilih Pelanggan (Customer):</label><br>
    <select name="id_customer" required>
        <option value="">-- Select Customer --</option>
        <?php while ($c = mysqli_fetch_assoc($customers)): ?>
            <option value="<?= $c['id_customer']; ?>"><?= htmlspecialchars($c['nama_customer']); ?> - <?= htmlspecialchars($c['perusahaan_customer']); ?></option>
        <?php endwhile; ?>
    </select><br><br>

    <h3>Detail Produk (Item)</h3>
    <table border="1" cellpadding="8" cellspacing="0">
        <tr>
            <th>Pilih Produk</th>
            <th>Kuantitas (Qty)</th>
        </tr>
        <?php for ($idx = 1; $idx <= 3; $idx++): ?>
        <tr>
            <td>
                <select name="id_produk[]">
                    <option value="">-- Select Product <?= $idx; ?> --</option>
                    <?php foreach ($prod_list as $prod): ?>
                        <option value="<?= $prod['id_produk']; ?>">
                            <?= htmlspecialchars($prod['nama_produk']); ?> (Stok: <?= $prod['stock']; ?> - Rp <?= number_format($prod['price'], 0, ',', '.'); ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </td>
            <td>
                <input type="number" name="qty[]" min="1" value="1">
            </td>
        </tr>
        <?php endfor; ?>
    </table><br>

    <button type="submit" name="submit">Simpan Faktur</button>
</form>

<?php
require_once "../../includes/footer.php";
?>
