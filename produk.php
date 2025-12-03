<?php 
include 'koneksi.php';
include 'header_template.php';

// Variabel untuk menampung notifikasi SweetAlert
$swal_script = "";

// --- LOGIKA TAMBAH PRODUK ---
if(isset($_POST['simpan'])){
    $nama  = $_POST['nama'];
    $harga = $_POST['harga'];
    $stok  = $_POST['stok'];

    $simpan = mysqli_query($conn, "INSERT INTO produk (nama_produk, harga, stok) VALUES ('$nama','$harga','$stok')");

    if($simpan){
        $swal_script = "Swal.fire({icon: 'success', title: 'Berhasil!', text: 'Produk baru ditambahkan', timer: 1500, showConfirmButton: false}).then(() => { window.location='produk.php'; });";
    } else {
        $err = mysqli_error($conn);
        $swal_script = "Swal.fire({icon: 'error', title: 'Gagal', text: '$err'});";
    }
}

// --- LOGIKA HAPUS PRODUK ---
if(isset($_GET['hapus'])){
    $id = $_GET['hapus'];
    $hapus = mysqli_query($conn, "DELETE FROM produk WHERE id='$id'");
    if($hapus){
        $swal_script = "Swal.fire({icon: 'success', title: 'Terhapus!', text: 'Produk berhasil dihapus', timer: 1500, showConfirmButton: false}).then(() => { window.location='produk.php'; });";
    }
}

// --- LOGIKA AMBIL DATA UNTUK EDIT ---
$editData = null;
if(isset($_GET['edit'])){
    $id = $_GET['edit'];
    $editData = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM produk WHERE id='$id'"));
}

// --- LOGIKA UPDATE PRODUK ---
if(isset($_POST['update'])){
    $id    = $_POST['id'];
    $nama  = $_POST['nama'];
    $harga = $_POST['harga'];
    $stok  = $_POST['stok'];

    $update = mysqli_query($conn, "UPDATE produk SET nama_produk='$nama', harga='$harga', stok='$stok' WHERE id='$id'");

    if($update){
        $swal_script = "Swal.fire({icon: 'success', title: 'Update Berhasil!', text: 'Data produk diperbarui', timer: 1500, showConfirmButton: false}).then(() => { window.location='produk.php'; });";
    } else {
        $err = mysqli_error($conn);
        $swal_script = "Swal.fire({icon: 'error', title: 'Gagal Update', text: '$err'});";
    }
}
?>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f4f6f9;
    }
    
    /* Card Styling */
    .card-custom {
        border: none;
        border-radius: 20px;
        box-shadow: 0 15px 30px rgba(0,0,0,0.05);
        overflow: hidden;
        background: white;
    }
    
    .card-header-custom {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 20px 30px;
        border-bottom: none;
    }

    /* Table Styling */
    .table-custom thead th {
        background-color: #f8f9fa;
        color: #555;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        border-bottom: 2px solid #eee;
        padding: 15px;
    }
    
    .table-custom tbody tr {
        transition: all 0.3s ease;
    }
    
    .table-custom tbody tr:hover {
        background-color: #f1f4ff;
        transform: scale(1.01);
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        z-index: 10;
        position: relative;
    }
    
    .table-custom td {
        padding: 15px;
        vertical-align: middle;
        border-bottom: 1px solid #eee;
    }

    /* Buttons */
    .btn-add {
        background: white;
        color: #764ba2;
        border-radius: 50px;
        padding: 8px 25px;
        font-weight: bold;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        transition: 0.3s;
        border: none;
    }
    
    .btn-add:hover {
        background: #f0f0f0;
        transform: translateY(-2px);
    }

    .btn-action {
        border-radius: 10px;
        padding: 5px 12px;
        transition: 0.3s;
    }
    .btn-action:hover {
        transform: translateY(-2px);
    }

    /* Badges */
    .badge-stok {
        padding: 8px 12px;
        border-radius: 12px;
        font-weight: 500;
    }
    .stok-aman { background: #d1fae5; color: #065f46; }
    .stok-limit { background: #fee2e2; color: #991b1b; }

    /* Modal Animation */
    .modal.fade .modal-dialog {
        transform: scale(0.8);
        transition: transform 0.3s ease-in-out;
    }
    .modal.show .modal-dialog {
        transform: scale(1);
    }
</style>

<div class="container mt-4 animate__animated animate__fadeIn">
    <div class="card card-custom">
        <div class="card-header-custom d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-0 fw-bold"><i class="bi bi-box-seam me-2"></i>Daftar Produk</h4>
                <small class="text-white-50">Kelola stok dan harga barang</small>
            </div>
            <button class="btn btn-add animate__animated animate__pulse animate__infinite" data-bs-toggle="modal" data-bs-target="#modalTambah">
                <i class="bi bi-plus-lg me-1"></i> Tambah Produk
            </button>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-custom mb-0">
                    <thead>
                        <tr>
                            <th class="text-center" width="5%">No</th>
                            <th width="10%">Gambar</th> <th>Nama Produk</th>
                            <th width="20%">Harga</th>
                            <th width="15%">Stok</th>
                            <th width="15%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        $data = mysqli_query($conn, "SELECT * FROM produk ORDER BY id DESC");
                        while($d = mysqli_fetch_assoc($data)){
                            // Cek stok untuk pewarnaan
                            $stokClass = ($d['stok'] > 10) ? 'stok-aman' : 'stok-limit';
                            $stokIcon  = ($d['stok'] > 10) ? 'bi-check-circle' : 'bi-exclamation-circle';
                        ?>
                        <tr class="animate__animated animate__fadeInUp" style="animation-delay: <?= $no * 0.1 ?>s">
                            <td class="text-center fw-bold text-muted"><?= $no++ ?></td>
                            <td>
                                <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                    <i class="bi bi-image text-secondary fs-4"></i>
                                </div>
                            </td>
                            <td class="fw-bold text-dark"><?= $d['nama_produk'] ?></td>
                            <td class="text-primary fw-bold">Rp <?= number_format($d['harga']) ?></td>
                            <td>
                                <span class="badge badge-stok <?= $stokClass ?>">
                                    <i class="bi <?= $stokIcon ?> me-1"></i> <?= $d['stok'] ?> Pcs
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="produk.php?edit=<?= $d['id'] ?>" class="btn btn-warning btn-sm btn-action text-white shadow-sm me-1" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <button onclick="konfirmasiHapus(<?= $d['id'] ?>)" class="btn btn-danger btn-sm btn-action shadow-sm" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header text-white" style="background: linear-gradient(45deg, #11998e, #38ef7d);">
                <h5 class="modal-title fw-bold"><i class="bi bi-plus-circle me-2"></i>Tambah Produk Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body p-4">
                    <div class="form-floating mb-3">
                        <input type="text" name="nama" class="form-control" id="namaAdd" placeholder="Nama" required>
                        <label for="namaAdd">Nama Produk</label>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group mb-3">
                                <span class="input-group-text bg-light">Rp</span>
                                <input type="number" name="harga" class="form-control" placeholder="Harga" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group mb-3">
                                <span class="input-group-text bg-light"><i class="bi bi-box"></i></span>
                                <input type="number" name="stok" class="form-control" placeholder="Stok" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="simpan" class="btn btn-success rounded-pill px-4 fw-bold">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php if($editData){ ?>
<div class="modal fade show" id="modalEdit" tabindex="-1" aria-modal="true" role="dialog" style="display:block; background:rgba(0,0,0,0.5); backdrop-filter: blur(5px);">
    <div class="modal-dialog modal-dialog-centered animate__animated animate__zoomIn">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header text-white" style="background: linear-gradient(45deg, #FF512F, #DD2476);">
                <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square me-2"></i>Edit Produk</h5>
                <a href="produk.php" class="btn-close btn-close-white"></a>
            </div>
            <form method="POST">
                <div class="modal-body p-4">
                    <input type="hidden" name="id" value="<?= $editData['id'] ?>">

                    <div class="form-floating mb-3">
                        <input type="text" name="nama" class="form-control" id="namaEdit" value="<?= $editData['nama_produk'] ?>" required>
                        <label for="namaEdit">Nama Produk</label>
                    </div>

                    <label class="small text-muted mb-1">Harga & Stok</label>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group mb-3">
                                <span class="input-group-text bg-light">Rp</span>
                                <input type="number" name="harga" class="form-control" value="<?= $editData['harga'] ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group mb-3">
                                <span class="input-group-text bg-light"><i class="bi bi-box"></i></span>
                                <input type="number" name="stok" class="form-control" value="<?= $editData['stok'] ?>" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <a href="produk.php" class="btn btn-secondary rounded-pill px-4">Batal</a>
                    <button type="submit" name="update" class="btn btn-danger rounded-pill px-4 fw-bold" style="background: #DD2476; border:none;">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php } ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Menjalankan SweetAlert dari PHP jika ada
    <?= $swal_script ?>

    // Fungsi Konfirmasi Hapus dengan SweetAlert
    function konfirmasiHapus(id) {
        Swal.fire({
            title: 'Yakin hapus?',
            text: "Data produk ini tidak bisa dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location = 'produk.php?hapus=' + id;
            }
        })
    }
</script>

<?php 
// Penutup DIV Layout dari header_template (jika ada)
echo "</div></div></div></body></html>"; 
?>