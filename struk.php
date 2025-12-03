<?php
include 'koneksi.php';

$no = $_GET['no'];
$trx = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM transaksi WHERE no_transaksi='$no'"));

// Mengambil data pelanggan
// Menggunakan isset/check untuk menghindari error jika id_pelanggan 0/null
$pelanggan_nama = 'Umum';
if(isset($trx['id_pelanggan']) && $trx['id_pelanggan'] > 0){
    $q_pel = mysqli_query($conn, "SELECT nama_pelanggan FROM pelanggan WHERE id='{$trx['id_pelanggan']}'");
    if(mysqli_num_rows($q_pel) > 0){
        $pelanggan_nama = mysqli_fetch_assoc($q_pel)['nama_pelanggan'];
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Struk <?= $no ?></title>
    <style>
        body {
            font-family: "Courier New", monospace;
            font-size: 12px;
            max-width: 300px; /* Lebar kertas thermal 58mm biasanya sekitar 48mm-58mm content */
            margin: 0 auto;
            padding: 10px;
            color: #000;
        }

        .center { text-align: center; }
        .line { border-bottom: 1px dashed #000; margin: 8px 0; }
        .flex { display: flex; justify-content: space-between; width: 100%; }
        .bold { font-weight: bold; }
        .title { font-size: 15px; font-weight: bold; margin-bottom: 5px; }
        .info small { display: block; margin-top: 2px; }
        .item { margin-bottom: 5px; }
        .footer { margin-top: 15px; font-size: 11px; }
    </style>
</head>

<body onload="window.print()">

    <div class="center">
        <div class="title">BAR KALCER AND EAT🍖♨️</div>
        <small>Jl. Merak No. 193</small>
        <small>Telp: 0812-3456-7890</small>
    </div>

    <div class="line"></div>

    <div class="info">
        <small>No Transaksi : <?= $trx['no_transaksi'] ?></small>
        <small>Tanggal       : <?= date("d/m/Y H:i", strtotime($trx['tanggal'])) ?></small>
        <small>Pelanggan     : <?= $pelanggan_nama ?></small>
    </div>

    <div class="line"></div>

    <?php
    $detail = mysqli_query($conn, "SELECT transaksi_detail.*, produk.nama_produk, produk.harga 
            FROM transaksi_detail 
            JOIN produk ON transaksi_detail.id_produk = produk.id 
            WHERE no_transaksi='$no'");

    while($d = mysqli_fetch_assoc($detail)) {
    ?>
        <div class="item">
            <div class="bold"><?= $d['nama_produk'] ?></div>
            <div class="flex">
                <span><?= $d['qty'] ?> x <?= number_format($d['harga']) ?></span>
                <span><?= number_format($d['subtotal']) ?></span>
            </div>
        </div>
    <?php } ?>

    <div class="line"></div>

    <?php 
    // Jika ada diskon, kita tampilkan rinciannya
    if($trx['diskon'] > 0) { 
        // Hitung Subtotal Asli (Total Bayar + Diskon)
        $subtotal_asli = $trx['total_bayar'] + $trx['diskon'];
    ?>
        <div class="flex">
            <span>Subtotal</span>
            <span><?= number_format($subtotal_asli) ?></span>
        </div>
        <div class="flex">
            <span>Diskon</span>
            <span>-<?= number_format($trx['diskon']) ?></span>
        </div>
        <div class="line" style="margin: 5px 0; border-bottom: 1px solid #000;"></div>
    <?php } ?>

    <div class="flex bold" style="font-size: 14px;">
        <span>Total Akhir</span>
        <span><?= number_format($trx['total_bayar']) ?></span>
    </div>
    
    <div class="flex" style="margin-top: 5px;">
        <span>Tunai</span>
        <span><?= number_format($trx['jumlah_uang']) ?></span>
    </div>
    <div class="flex">
        <span>Kembalian</span>
        <span><?= number_format($trx['kembalian']) ?></span>
    </div>

    <div class="line"></div>

    <div class="center footer">
        Terima Kasih<br>
        <small>Barang yang sudah dibeli tidak dapat ditukar</small>
        <small>--- Simpan struk ini sebagai bukti transaksi ---</small>
        <small>Wifi:KalcerIndah </small>
    
    </div>

</body>
</html>