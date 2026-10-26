<?php
// Proteksi halaman
require_once "../../auth/guard.php";
require_once "../../config/connect.php";

$no_faktur = mysqli_real_escape_string($conn, $_GET['no'] ?? '');

// Query faktur
$sql = "SELECT f.*, 
               c.nama_customer, c.perusahaan_customer, c.alamat AS alamat_customer,
               comp.nama_company, comp.alamat AS alamat_company, comp.no_telp, comp.fax
        FROM faktur f
        JOIN customer c ON f.id_customer = c.id_customer
        JOIN company comp ON f.id_company = comp.id_company
        WHERE f.no_faktur = '$no_faktur'";
$query = mysqli_query($conn, $sql);
$faktur = mysqli_fetch_assoc($query);

if (!$faktur) {
    die("Faktur tidak ditemukan.");
}

// Query detail
$q_items = mysqli_query($conn, "SELECT df.*, p.nama_produk, p.jenis 
                                FROM detail_faktur df 
                                JOIN produk p ON df.id_produk = p.id_produk 
                                WHERE df.no_faktur = '$no_faktur'");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Cetak Faktur - <?= htmlspecialchars($faktur['no_faktur']); ?></title>
</head>
<body>

    <p align="right">
        <button onclick="window.print();">Print</button>
    </p>

    <table width="100%" border="0">
        <tr valign="top">
            <td width="60%">
                <h2><?= htmlspecialchars($faktur['nama_company']); ?></h2>
                <p>
                    <?= htmlspecialchars($faktur['alamat_company']); ?><br>
                    Telp: <?= htmlspecialchars($faktur['no_telp']); ?> | Fax: <?= htmlspecialchars($faktur['fax']); ?>
                </p>
            </td>
            <td width="40%" align="right">
                <h1>FAKTUR PENJUALAN</h1>
                <p>
                    <b>No:</b> <?= htmlspecialchars($faktur['no_faktur']); ?><br>
                    <b>Tanggal:</b> <?= $faktur['tgl_faktur']; ?>
                </p>
            </td>
        </tr>
    </table>
    <hr>

    <table width="100%" border="0">
        <tr valign="top">
            <td width="50%">
                <b>Kepada:</b><br>
                <b><?= htmlspecialchars($faktur['nama_customer']); ?></b><br>
                <?= htmlspecialchars($faktur['perusahaan_customer']); ?><br>
                <?= htmlspecialchars($faktur['alamat_customer']); ?>
            </td>
            <td width="50%" align="right">
                <b>Jatuh Tempo:</b> <?= $faktur['due_date']; ?><br>
                <b>Metode Bayar:</b> <?= htmlspecialchars($faktur['metode_bayar']); ?><br>
            </td>
        </tr>
    </table>
    <br>

    <table border="1" cellpadding="8" cellspacing="0" width="100%">
        <tr>
            <th>No</th>
            <th>Nama Produk</th>
            <th>Jenis</th>
            <th>Qty</th>
            <th>Harga Satuan</th>
            <th>Total</th>
        </tr>
        <?php 
        $no = 1; 
        $subtotal = 0;
        while ($item = mysqli_fetch_assoc($q_items)): 
            $total_item = $item['qty'] * $item['price'];
            $subtotal += $total_item;
        ?>
        <tr>
            <td align="center"><?= $no++; ?></td>
            <td><?= htmlspecialchars($item['nama_produk']); ?></td>
            <td><?= htmlspecialchars($item['jenis']); ?></td>
            <td align="center"><?= $item['qty']; ?></td>
            <td align="right">Rp <?= number_format($item['price'], 0, ',', '.'); ?></td>
            <td align="right">Rp <?= number_format($total_item, 0, ',', '.'); ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
    <br>

    <table border="0" align="right" cellpadding="4" cellspacing="0" width="300">
        <tr>
            <td align="right"><b>Subtotal:</b></td>
            <td align="right">Rp <?= number_format($subtotal, 0, ',', '.'); ?></td>
        </tr>
        <tr>
            <td align="right"><b>PPN (11%):</b></td>
            <td align="right">Rp <?= number_format($faktur['ppn'], 0, ',', '.'); ?></td>
        </tr>
        <tr>
            <td align="right"><b>Grand Total:</b></td>
            <td align="right"><b>Rp <?= number_format($faktur['grand_total'], 0, ',', '.'); ?></b></td>
        </tr>
    </table>
    <br clear="all"><br><br>

    <table width="100%" border="0">
        <tr>
            <td align="center" width="50%">
                Pelanggan/Penerima<br><br><br><br>
                ( <?= htmlspecialchars($faktur['nama_customer']); ?> )
            </td>
            <td align="center" width="50%">
                Hormat Kami<br><br><br><br>
                ( <?= htmlspecialchars($faktur['nama_company']); ?> )
            </td>
        </tr>
    </table>

</body>
</html>
