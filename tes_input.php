<?php
// 1. KONEKSI LANGSUNG DI SINI (Biar tidak salah file)
$conn = mysqli_connect("localhost", "root", "", "db_kasir_pro");

// Cek koneksi
if (!$conn) {
    die("<h3>KONEKSI GAGAL!</h3> Penyebab: " . mysqli_connect_error());
}

// 2. PROSES INPUT
if (isset($_POST['tombol_simpan'])) {
    $nama  = $_POST['nama'];
    $harga = $_POST['harga'];
    $stok  = $_POST['stok'];

    $query = "INSERT INTO produk (nama_produk, harga, stok) VALUES ('$nama', '$harga', '$stok')";
    $hasil = mysqli_query($conn, $query);

    if ($hasil) {
        echo "<h2 style='color:green'>BERHASIL! DATA MASUK.</h2>";
        echo "<p>Silakan cek di phpMyAdmin, data pasti ada.</p>";
    } else {
        echo "<h2 style='color:red'>GAGAL!</h2>";
        echo "Penyebab Error: " . mysqli_error($conn);
    }
}
?>

<h3>Tes Input Produk Manual</h3>
<form method="POST" action="">
    <label>Nama Produk:</label><br>
    <input type="text" name="nama" required><br><br>

    <label>Harga:</label><br>
    <input type="number" name="harga" required><br><br>

    <label>Stok:</label><br>
    <input type="number" name="stok" required><br><br>

    <button type="submit" name="tombol_simpan">SIMPAN DATA</button>
</form>

<hr>
<h3>Data di Database:</h3>
<table border="1" cellpadding="5" cellspacing="0">
    <tr>
        <th>ID</th>
        <th>Nama</th>
        <th>Harga</th>
        <th>Stok</th>
    </tr>
    <?php
    $tampil = mysqli_query($conn, "SELECT * FROM produk ORDER BY id DESC");
    while ($data = mysqli_fetch_array($tampil)) {
        echo "<tr>";
        echo "<td>$data[id]</td>";
        echo "<td>$data[nama_produk]</td>";
        echo "<td>$data[harga]</td>";
        echo "<td>$data[stok]</td>";
        echo "</tr>";
    }
    ?>
</table>