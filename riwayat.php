<?php 
include 'koneksi.php';
include 'header_template.php';
?>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
    body { font-family: 'Poppins', sans-serif; background-color: #f4f7f6; }
    
    .card-history {
        border: none;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        background: white;
        overflow: hidden;
    }

    .table-modern thead th {
        background-color: #f8f9fa;
        color: #64748b;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #edf2f7;
        padding: 16px;
    }

    .table-modern tbody td {
        padding: 16px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        color: #334155;
        font-size: 0.95rem;
    }

    .table-modern tbody tr:hover {
        background-color: #f8fafc;
    }

    .badge-success-soft {
        background-color: #d1fae5;
        color: #065f46;
        padding: 6px 12px;
        border-radius: 30px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .search-input {
        background-color: #f1f5f9;
        border: none;
        border-radius: 50px;
        padding: 10px 20px 10px 40px;
        width: 300px;
        transition: 0.3s;
    }
    .search-input:focus {
        background-color: white;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        outline: none;
    }
    .search-icon {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
    }
</style>

<div class="container-fluid py-4 px-md-5">
    
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1 text-dark">Riwayat Transaksi</h3>
            <p class="text-muted small mb-0">Semua data penjualan tercatat di sini</p>
        </div>
        
        <div class="position-relative mt-3 mt-md-0">
            <i class="bi bi-search search-icon"></i>
            <input type="text" id="searchInput" class="search-input" placeholder="Cari No. Transaksi / Pelanggan...">
        </div>
    </div>

    <div class="card card-history">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-modern mb-0" id="historyTable">
                    <thead>
                        <tr>
                            <th class="ps-4">No. Transaksi</th>
                            <th>Waktu</th>
                            <th>Pelanggan</th>
                            <th>Total Belanja</th>
                            <th>Pembayaran</th>
                            <th class="text-center">Status</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $query = "SELECT transaksi.*, pelanggan.nama_pelanggan 
                                  FROM transaksi 
                                  LEFT JOIN pelanggan ON transaksi.id_pelanggan = pelanggan.id 
                                  ORDER BY transaksi.id DESC";
                        $data = mysqli_query($conn, $query);

                        if(mysqli_num_rows($data) == 0){
                            echo "<tr><td colspan='7' class='text-center py-5 text-muted'>Belum ada riwayat transaksi.</td></tr>";
                        }

                        while($d = mysqli_fetch_assoc($data)){
                        ?>
                        <tr>
                            <td class="ps-4 fw-bold text-primary">
                                #<?= $d['no_transaksi'] ?>
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="fw-bold"><?= date('d M Y', strtotime($d['tanggal'])) ?></span>
                                    <small class="text-muted"><?= date('H:i', strtotime($d['tanggal'])) ?> WIB</small>
                                </div>
                            </td>
                            <td>
                                <?php if($d['nama_pelanggan']): ?>
                                    <span class="fw-bold text-dark"><?= $d['nama_pelanggan'] ?></span>
                                <?php else: ?>
                                    <span class="text-muted fst-italic">Umum</span>
                                <?php endif; ?>
                            </td>
                            <td class="fw-bold text-success fs-6">
                                Rp <?= number_format($d['total_bayar']) ?>
                            </td>
                            <td>
                                <div class="small text-muted">
                                    Tunai: Rp <?= number_format($d['jumlah_uang']) ?> <br>
                                    Kembali: Rp <?= number_format($d['kembalian']) ?>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge-success-soft">
                                    <i class="bi bi-check-circle-fill me-1"></i> Lunas
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <a href="struk.php?no=<?= $d['no_transaksi'] ?>" target="_blank" class="btn btn-outline-dark btn-sm rounded-pill px-3 fw-bold" title="Cetak Ulang">
                                    <i class="bi bi-printer me-1"></i> Struk
                                </a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    // Fitur Pencarian Cepat (Javascript)
    document.getElementById('searchInput').addEventListener('keyup', function() {
        let val = this.value.toLowerCase();
        let rows = document.querySelectorAll('#historyTable tbody tr');
        
        rows.forEach(row => {
            let text = row.textContent.toLowerCase();
            if(text.includes(val)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>

</div></div></div></body></html>