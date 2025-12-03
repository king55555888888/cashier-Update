<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "db_kasir_pro");

if (!$conn) { die("Koneksi Gagal: " . mysqli_connect_error()); }

// Fungsi Helper untuk Rupiah
function formatRp($angka){
    return "Rp " . number_format($angka, 0, ',', '.');
}
?>