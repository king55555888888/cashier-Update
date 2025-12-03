<?php 
include 'koneksi.php';
include 'header_template.php'; 

// --- 1. LOGIKA RESET DATA ---
if(isset($_POST['reset_data'])){
    $reset1 = mysqli_query($conn, "TRUNCATE TABLE transaksi");
    $reset2 = mysqli_query($conn, "TRUNCATE TABLE transaksi_detail");
    if($reset1 && $reset2){
        echo "<script>Swal.fire('Selesai', 'Data berhasil di-nol-kan.', 'success').then(() => window.location='dashboard.php');</script>";
    } else {
        echo "<script>Swal.fire('Gagal', 'Error database.', 'error');</script>";
    }
}

// --- 2. LOGIKA FILTER & DATA ---
$periode_pilih = isset($_GET['periode']) ? $_GET['periode'] : ''; 

if(!empty($periode_pilih)){
    $q_where = "WHERE DATE_FORMAT(tanggal, '%Y-%m') = '$periode_pilih'";
    $label_periode = date('F Y', strtotime($periode_pilih));
} else {
    $q_where = "";
    $label_periode = "Semua Waktu";
}

$pendapatan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total_bayar) as total FROM transaksi $q_where"))['total'] ?? 0;
$total_transaksi = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM transaksi $q_where"));
$pelanggan = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM pelanggan"));

// Data Grafik (Chart.js)
$labels = []; $totals = [];
$q_chart = mysqli_query($conn, "SELECT DATE(tanggal) as tgl, SUM(total_bayar) as total FROM transaksi GROUP BY DATE(tanggal) ORDER BY tgl DESC LIMIT 7");
while($row = mysqli_fetch_assoc($q_chart)){
    array_unshift($labels, date('d M', strtotime($row['tgl'])));
    array_unshift($totals, $row['total']);
}
$json_labels = json_encode($labels);
$json_totals = json_encode($totals);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body { background-color: #f0f2f5 !important; font-family: 'Outfit', sans-serif; overflow-x: hidden; }
        
        /* --- ANIMASI CUSTOM KEYFRAMES --- */
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-8px); }
            100% { transform: translateY(0px); }
        }
        @keyframes gradientMove {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        @keyframes pulse-red {
            0% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.4); }
            70% { box-shadow: 0 0 0 10px rgba(220, 53, 69, 0); }
            100% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0); }
        }

        /* --- KARTU STATISTIK --- */
        .card-stat {
            border: none; border-radius: 20px; position: relative; overflow: hidden;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); 
            background: white; box-shadow: 0 10px 30px rgba(0,0,0,0.05); z-index: 1;
        }
        .card-stat:hover { transform: translateY(-10px); box-shadow: 0 20px 40px rgba(0,0,0,0.1); }
        
        /* Dekorasi Blob */
        .blob-decoration { position: absolute; top: -50px; right: -50px; width: 150px; height: 150px; border-radius: 50%; filter: blur(50px); z-index: -1; opacity: 0.6; }
        
        /* Icon Melayang */
        .stat-icon { 
            width: 55px; height: 55px; border-radius: 18px; display: flex; align-items: center; justify-content: center; font-size: 1.6rem; margin-bottom: 10px;
            animation: float 4s ease-in-out infinite; /* Animasi Float */
        }
        
        /* Tema Warna */
        .theme-purple .blob-decoration { background: #a855f7; } .theme-purple .stat-icon { background: rgba(168,85,247,0.1); color: #a855f7; }
        .theme-orange .blob-decoration { background: #f97316; } .theme-orange .stat-icon { background: rgba(249,115,22,0.1); color: #f97316; animation-delay: 1s; }
        .theme-blue .blob-decoration { background: #3b82f6; } .theme-blue .stat-icon { background: rgba(59,130,246,0.1); color: #3b82f6; animation-delay: 2s; }

        /* --- WELCOME BAR --- */
        .welcome-box {
            background: linear-gradient(-45deg, #6366f1, #8b5cf6, #ec4899, #6366f1);
            background-size: 400% 400%;
            animation: gradientMove 15s ease infinite; /* Animasi Gradient Gerak */
            border-radius: 20px; color: white; padding: 25px;
            box-shadow: 0 10px 25px rgba(99, 102, 241, 0.3); position: relative; overflow: hidden;
        }
        
        /* --- RESET BUTTON ANIMATED --- */
        .btn-reset-mini {
            background: #fff5f5; color: #e53e3e; border: 1px dashed #fc8181; 
            font-weight: 700; font-size: 0.85rem; transition: 0.3s;
            position: relative; overflow: hidden;
        }
        .btn-reset-mini:hover { 
            background: #e53e3e; color: white; border-color: #e53e3e; 
            animation: pulse-red 2s infinite; /* Efek Denyut saat Hover */
        }

        .counter-value { font-variant-numeric: tabular-nums; }
        
        /* Efek Hover Table */
        .table-row-anim { transition: background-color 0.2s, transform 0.2s; }
        .table-row-anim:hover { background-color: #f8fafc; transform: scale(1.01); }
    </style>
</head>
<body>

<div class="container-fluid py-4 px-md-4">

    <div class="welcome-box mb-4 animate__animated animate__fadeInDown">
        <div class="d-flex flex-wrap justify-content-between align-items-center position-relative z-2">
            <div>
                <h3 class="fw-bold mb-0">🚀 Dashboard Utama</h3>
                <p class="mb-0 opacity-75">Laporan Periode: <strong><?= $label_periode ?></strong></p>
            </div>
            <form method="GET" class="d-flex gap-2 bg-white p-1 rounded-pill mt-3 mt-md-0 shadow-sm animate__animated animate__fadeInRight animate__delay-1s">
                <input type="month" name="periode" class="form-control border-0 rounded-pill ps-3 py-1" value="<?= $periode_pilih ?>" onchange="this.form.submit()">
                <?php if(!empty($periode_pilih)): ?>
                    <a href="dashboard.php" class="btn btn-dark rounded-circle d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;"><i class="bi bi-x-lg"></i></a>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4 animate__animated animate__zoomIn" style="animation-delay: 0.1s;">
            <div class="card-stat theme-purple p-3 h-100">
                <div class="blob-decoration"></div>
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted fw-bold text-uppercase">Pendapatan</small>
                        <h2 class="fw-bold mb-0 mt-1">Rp <span class="counter-value" data-target="<?= $pendapatan ?>">0</span></h2>
                    </div>
                    <div class="stat-icon"><i class="bi bi-wallet-fill"></i></div>
                </div>
            </div>
        </div>
        <div class="col-md-4 animate__animated animate__zoomIn" style="animation-delay: 0.2s;">
            <div class="card-stat theme-orange p-3 h-100">
                <div class="blob-decoration"></div>
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted fw-bold text-uppercase">Transaksi</small>
                        <h2 class="fw-bold mb-0 mt-1"><span class="counter-value" data-target="<?= $total_transaksi ?>">0</span> <span class="fs-6 text-muted fw-normal">Nota</span></h2>
                    </div>
                    <div class="stat-icon"><i class="bi bi-bag-check-fill"></i></div>
                </div>
            </div>
        </div>
        <div class="col-md-4 animate__animated animate__zoomIn" style="animation-delay: 0.3s;">
            <div class="card-stat theme-blue p-3 h-100">
                <div class="blob-decoration"></div>
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted fw-bold text-uppercase">Pelanggan</small>
                        <h2 class="fw-bold mb-0 mt-1"><span class="counter-value" data-target="<?= $pelanggan ?>">0</span> <span class="fs-6 text-muted fw-normal">Org</span></h2>
                    </div>
                    <div class="stat-icon"><i class="bi bi-people-fill"></i></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        
        <div class="col-lg-8 animate__animated animate__fadeInUp" style="animation-delay: 0.4s;">
            <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-white" style="min-height: 400px;">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0">📈 Grafik Penjualan</h5>
                    <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3">Live Data</span>
                </div>
                <div style="height: 320px;">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4 animate__animated animate__fadeInUp" style="animation-delay: 0.5s;">
            
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-3 bg-white">
                <div class="card-header bg-white border-0 pt-3 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0">Transaksi Terbaru</h6>
                    <a href="riwayat.php" class="small text-decoration-none fw-bold">Lihat Semua</a>
                </div>
                <div class="table-responsive">
                    <table class="table mb-0 small">
                        <tbody>
                            <?php 
                            $trx = mysqli_query($conn, "SELECT * FROM transaksi $q_where ORDER BY id DESC LIMIT 4");
                            if(mysqli_num_rows($trx) == 0) echo "<tr><td class='text-center p-3 text-muted'>Belum ada data</td></tr>";
                            while($t = mysqli_fetch_assoc($trx)){ ?>
                            <tr class="table-row-anim">
                                <td class="ps-3 border-bottom-0">
                                    <span class="fw-bold text-dark">#<?= $t['no_transaksi'] ?></span><br>
                                    <span class="text-muted" style="font-size: 0.75rem"><?= date('d/m H:i', strtotime($t['tanggal'])) ?></span>
                                </td>
                                <td class="text-end pe-3 border-bottom-0">
                                    <span class="fw-bold text-primary"><?= formatRp($t['total_bayar']) ?></span>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <button class="btn btn-reset-mini w-100 py-3 rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#modalReset">
                <i class="bi bi-trash3 me-1"></i> RESET DATABASE
            </button>
            <div class="text-center mt-2"><small class="text-muted" style="font-size: 0.7rem;">⚠️ Hati-hati, data hilang permanen.</small></div>

        </div>
    </div>
</div>

<div class="modal fade" id="modalReset" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4 animate__animated animate__tada">
            <div class="modal-body text-center p-4">
                <div class="mb-2 text-danger"><i class="bi bi-exclamation-circle fs-1"></i></div>
                <h6 class="fw-bold">Reset Semua Data?</h6>
                <p class="small text-muted mb-3">Semua riwayat transaksi akan dihapus menjadi 0.</p>
                <form method="POST" class="d-flex justify-content-center gap-2">
                    <button type="button" class="btn btn-sm btn-light border rounded-pill px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="reset_data" class="btn btn-sm btn-danger rounded-pill px-3 fw-bold">Ya, Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // 1. Animasi Angka (Counter Up)
    const counters = document.querySelectorAll('.counter-value');
    counters.forEach(counter => {
        const updateCount = () => {
            const target = +counter.getAttribute('data-target');
            const count = +counter.innerText.replace(/,/g, ''); 
            const inc = target / 30; 
            if (count < target) {
                counter.innerText = Math.ceil(count + inc).toLocaleString('en-US'); 
                setTimeout(updateCount, 25);
            } else {
                counter.innerText = target.toLocaleString('en-US');
            }
        };
        updateCount();
    });

    // 2. Grafik Chart JS dengan Animasi Draw
    const ctx = document.getElementById('salesChart').getContext('2d');
    let gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(99, 102, 241, 0.5)'); // Ungu transparant
    gradient.addColorStop(1, 'rgba(99, 102, 241, 0.0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?= $json_labels ?>,
            datasets: [{
                label: 'Omzet',
                data: <?= $json_totals ?>,
                backgroundColor: gradient,
                borderColor: '#6366f1',
                borderWidth: 3,
                pointRadius: 5,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#6366f1',
                pointHoverRadius: 8,
                fill: true,
                tension: 0.4 // Garis melengkung halus
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: {
                y: { duration: 2000, easing: 'easeOutQuart' } // Animasi chart naik pelan
            },
            plugins: { legend: {display: false} },
            scales: {
                x: { grid: {display: false} },
                y: { beginAtZero: true, border: {display: false} }
            }
        }
    });
</script>
</body>
</html>