<?php
// Koneksi database
$host = "localhost";
$user = "root";
$password = "";
$database = "db_lsp";

// Inisialisasi koneksi
$conn = mysqli_connect($host, $user, $password, $database);

// Cek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>