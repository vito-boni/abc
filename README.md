# DOKUMENTASI SISTEM FAKTUR PENJUALAN
**Skema Sertifikasi Kompetensi Programmer**

---

## 1. Pendahuluan
Dokumen ini disusun untuk menjelaskan arsitektur, basis data, alur bisnis, serta algoritma teknis dari **Sistem Informasi Faktur Penjualan Berbasis Web**. Sistem ini dirancang untuk mengelola data master perusahaan (*company*), pelanggan (*customer*), produk, dan transaksi faktur penjualan beserta bukti cetak invoice.

---

## 2. Struktur Menu & Antarmuka Aplikasi
Aplikasi mengadopsi tata letak terstruktur 5 komponen utama:
1. **Header**: Menampilkan identitas sistem serta informasi pengguna yang sedang login beserta peran aksesnya (*role*).
2. **Navigation Bar**: Menu horizontal bagian atas untuk akses cepat ke Dashboard, Data Faktur, dan Master Data.
3. **Sidebar**: Menu vertikal samping untuk membedakan kategori menu:
   - Menu Transaksi
   - Master Data (Khusus Admin)
   - Menu Akun / Logout
4. **Content**: Area kerja utama untuk menampilkan tabel ringkasan data, statistik pendapatan, formulir input (CRUD), dan cetak bukti transaksi.
5. **Footer**: Informasi hak cipta.

---

## 3. Peran Pengguna (Hak Akses)
Aplikasi mendukung multi-user berbasis peran (*Role-Based Access Control*):
* **Admin**:
  - Mengelola data master (Tambah, Ubah, Hapus data Perusahaan, Pelanggan, dan Produk).
  - Membuat transaksi faktur penjualan baru.
  - Mengubah, menghapus, dan mencetak faktur penjualan.
* **Customer**:
  - Melihat riwayat faktur penjualan.
  - Mencetak / export bukti faktur penjualan.

---

## 4. Desain Basis Data (`db_lsp`)

### 4.1. Tabel `users`
Menyimpan akun pengguna aplikasi.
| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id_user` | INT (PK, Auto Increment) | ID unik pengguna |
| `username` | VARCHAR(50) (Unique) | Nama login pengguna |
| `password` | VARCHAR(255) | Kata sandi login |
| `role` | ENUM('admin','customer') | Hak akses |

### 4.2. Tabel `company`
Menyimpan profil perusahaan penerbit faktur.
| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id_company` | INT (PK, Auto Increment) | ID unik perusahaan |
| `nama_company` | VARCHAR(100) | Nama resmi perusahaan |
| `alamat` | TEXT | Alamat operasional |
| `no_telp` | VARCHAR(20) | Nomor telepon aktif |
| `fax` | VARCHAR(20) | Nomor mesin fax |

### 4.3. Tabel `customer`
Menyimpan data pelanggan/mitra pembeli.
| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id_customer` | INT (PK, Auto Increment) | ID unik pelanggan |
| `nama_customer` | VARCHAR(100) | Nama penanggung jawab / pemesan |
| `perusahaan_customer` | VARCHAR(100) | Nama instansi pelanggan |
| `alamat` | TEXT | Alamat pengiriman / penagihan |

### 4.4. Tabel `produk`
Katalog barang yang dijual.
| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id_produk` | INT (PK, Auto Increment) | ID unik barang |
| `nama_produk` | VARCHAR(100) | Nama barang/komoditas |
| `price` | INT | Harga jual satuan (Rupiah) |
| `jenis` | VARCHAR(50) | Kategori/jenis produk |
| `stock` | INT | Sisa kuantitas barang tersedia |

### 4.5. Tabel `faktur`
Data kepala (*header*) transaksi faktur penjualan.
| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `no_faktur` | VARCHAR(30) (PK) | Nomor dokumen unik faktur |
| `tgl_faktur` | DATE | Tanggal transaksi diterbitkan |
| `due_date` | DATE | Tanggal jatuh tempo pelunasan |
| `metode_bayar` | VARCHAR(50) | Metode pembayaran (Cash, Transfer, dll) |
| `ppn` | INT | Nilai pajak pertambahan nilai (11%) |
| `grand_total` | INT | Total akhir tagihan faktur |
| `id_customer` | INT (FK) | Relasi ke `customer.id_customer` |
| `id_company` | INT (FK) | Relasi ke `company.id_company` |

### 4.6. Tabel `detail_faktur`
Rincian item barang pada masing-masing faktur.
| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id_detail` | INT (PK, Auto Increment) | ID unik item |
| `id_produk` | INT (FK) | Relasi ke `produk.id_produk` |
| `no_faktur` | VARCHAR(30) (FK) | Relasi ke `faktur.no_faktur` |
| `qty` | INT | Jumlah unit barang yang dibeli |
| `price` | INT | Harga satuan saat transaksi terjadi |

---

## 5. Logika PPN
```php
<?php
// Hitung PPN
function hitungPpn($subtotal, $rate = 0.11) {
    return round($subtotal * $rate);
}

// Hitung Grandtotal
function hitungGrandTotal($subtotal, $ppn) {
    return $subtotal + $ppn;
}
?>
```

---

## 6. Petunjuk Pengujian Sistem

1. **Jalankan Web Server**:
   - Pastikan modul **Apache** dan **MySQL** aktif pada XAMPP.
2. **Akses Aplikasi**:
   - Buka peramban (browser) di tautan: `http://localhost/lsp/`
3. **Akun Uji Coba**:
   - **Admin**: `username: admin` | `password: admin`
   - **Customer**: `username: vito` | `password: vitovito`
4. **Pengujian Cetak**:
   - Masuk ke menu **Faktur Penjualan** $\rightarrow$ Klik tautan **Cetak** pada salah satu baris transaksi $\rightarrow$ Klik tombol **Print** untuk melihat pratinjau cetak.
