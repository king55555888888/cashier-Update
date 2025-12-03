<?php
// 1. AKTIFKAN PELAPORAN ERROR LENGKAP
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h3>MULAI PENGECEKAN...</h3>";

// 2. CEK KONEKSI DATABASE
$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_kasir_pro"; // <--- PASTIKAN INI SAMA DENGAN PHPMYADMIN ANDA

echo "Mencoba koneksi ke database <b>$db</b>... <br>";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("<h1 style='color:red'>GAGAL KONEKSI!</h1> Penyebab: " . mysqli_connect_error() . "<br>Solusi: Cek file koneksi.php, pastikan nama database benar.");
}
echo "<span style='color:green'>✔ Koneksi Berhasil.</span><hr>";

// 3. CEK TABEL PRODUK
echo "Mencoba mengisi data ke tabel <b>produk</b>... <br>";

$test_insert = mysqli_query($conn, "INSERT INTO produk (nama_produk, harga, stok) VALUES ('Produk Tes', 1000, 10)");

if ($test_insert) {
    echo "<h1 style='color:green'>SUKSES!</h1> Data berhasil masuk. Masalahnya bukan di Database, tapi di Form HTML/Modal Anda.";
    // Hapus lagi data tesnya
    mysqli_query($conn, "DELETE FROM produk WHERE nama_produk='Produk Tes'");
} else {
    echo "<h1 style='color:red'>GAGAL INSERT!</h1>";
    echo "Penyebab Error MySQL: <b>" . mysqli_error($conn) . "</b>";
    echo "<br><br><b>SOLUSI:</b><br>";
    echo "1. Jika errornya <i>'Table doesn't exist'</i> -> Anda belum bikin tabel, atau nama tabel salah.<br>";
    echo "2. Jika errornya <i>'Unknown column'</i> -> Nama kolom di tabel beda dengan kodingan.<br>";
}

echo "<hr><h3>PENGECEKAN SELESAI.</h3>";
?>