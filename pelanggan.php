<?php 
include 'koneksi.php';
include 'header_template.php';

// Variabel Notifikasi SweetAlert
$swal_script = "";

// === LOGIKA TAMBAH ===
if(isset($_POST['simpan'])){
    $nama   = $_POST['nama'];
    $hp     = $_POST['hp'];
    $alamat = $_POST['alamat'];
    
    $simpan = mysqli_query($conn, "INSERT INTO pelanggan (nama_pelanggan, no_hp, alamat) VALUES ('$nama','$hp','$alamat')");
    
    if($simpan){
        $swal_script = "Swal.fire({icon: 'success', title: 'Berhasil!', text: 'Pelanggan baru ditambahkan', timer: 1500, showConfirmButton: false}).then(() => { window.location='pelanggan.php'; });";
    } else {
        $err = mysqli_error($conn);
        $swal_script = "Swal.fire({icon: 'error', title: 'Gagal', text: '$err'});";
    }
}

// === LOGIKA HAPUS ===
if(isset($_GET['hapus'])){
    $id = $_GET['hapus'];
    $hapus = mysqli_query($conn, "DELETE FROM pelanggan WHERE id='$id'");
    if($hapus){
        $swal_script = "Swal.fire({icon: 'success', title: 'Terhapus!', text: 'Data pelanggan dihapus', timer: 1500, showConfirmButton: false}).then(() => { window.location='pelanggan.php'; });";
    }
}

// === LOGIKA AMBIL DATA EDIT ===
$editData = null;
if(isset($_GET['edit'])){
    $id = $_GET['edit'];
    $editData = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM pelanggan WHERE id='$id'"));
}

// === LOGIKA UPDATE ===
if(isset($_POST['update'])){
    $id     = $_POST['id'];
    $nama   = $_POST['nama'];
    $hp     = $_POST['hp'];
    $alamat = $_POST['alamat'];

    $update = mysqli_query($conn, "UPDATE pelanggan SET nama_pelanggan='$nama', no_hp='$hp', alamat='$alamat' WHERE id='$id'");

    if($update){
        $swal_script = "Swal.fire({icon: 'success', title: 'Update Berhasil!', text: 'Data pelanggan diperbarui', timer: 1500, showConfirmButton: false}).then(() => { window.location='pelanggan.php'; });";
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
    body { font-family: 'Poppins', sans-serif; background-color: #f4f6f9; }
    
    .card-custom {
        border: none; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        background: white; overflow: hidden;
    }
    
    /* Avatar Inisial */
    .avatar-circle {
        width: 45px; height: 45px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-weight: bold; font-size: 1.2rem;
        box-shadow: 0 4px 10px rgba(118, 75, 162, 0.3);
    }

    /* Table Styling */
    .table-custom thead th {
        background-color: #f8f9fa; color: #6c757d; font-weight: 600;
        text-transform: uppercase; font-size: 0.85rem; border-bottom: 2px solid #eee; padding: 15px;
    }
    .table-custom tbody tr { transition: 0.3s; }
    .table-custom tbody tr:hover { background-color: #f8faff; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
    .table-custom td { padding: 15px; vertical-align: middle; border-bottom: 1px solid #eee; }

    /* Buttons */
    .btn-add {
        background: linear-gradient(45deg, #11998e, #38ef7d); border: none; color: white;
        border-radius: 50px; padding: 10px 25px; font-weight: 600; box-shadow: 0 4px 15px rgba(56, 239, 125, 0.4);
        transition: 0.3s;
    }
    .btn-add:hover { transform: translateY(-3px); box-shadow: 0 6px 20px rgba(56, 239, 125, 0.6); color: white; }

    .btn-wa {
        background-color: #e3fcef; color: #00a884; border: none; padding: 5px 12px; border-radius: 8px; font-weight: 600; font-size: 0.85rem; text-decoration: none; transition: 0.2s;
    }
    .btn-wa:hover { background-color: #00a884; color: white; }

    /* Search Input */
    .search-box {
        border-radius: 50px; border: 1px solid #eee; padding: 10px 20px; width: 250px; transition: 0.3s;
    }
    .search-box:focus { outline: none; border-color: #764ba2; box-shadow: 0 0 0 4px rgba(118, 75, 162, 0.1); }
</style>

<div class="container mt-4 animate__animated animate__fadeIn">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0 text-dark">Data Pelanggan</h4>
            <p class="text-muted small mb-0">Kelola daftar member dan kontak</p>
        </div>
        <button class="btn btn-add animate__animated animate__pulse animate__infinite" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="bi bi-person-plus-fill me-2"></i>Pelanggan Baru
        </button>
    </div>

    <div class="card card-custom">
        <div class="card-body p-0">
            <div class="p-3 border-bottom d-flex justify-content-end bg-white">
                <input type="text" id="searchInput" class="search-box" placeholder="Cari nama atau no hp...">
            </div>

            <div class="table-responsive">
                <table class="table table-custom mb-0" id="customerTable">
                    <thead>
                        <tr>
                            <th class="ps-4">Pelanggan</th>
                            <th>Kontak (WhatsApp)</th>
                            <th>Alamat Domisili</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $data = mysqli_query($conn, "SELECT * FROM pelanggan ORDER BY id DESC");
                        while($d = mysqli_fetch_assoc($data)){
                            // Ambil Huruf Pertama untuk Avatar
                            $initial = strtoupper(substr($d['nama_pelanggan'], 0, 1));
                        ?>
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle me-3"><?= $initial ?></div>
                                    <div>
                                        <h6 class="fw-bold mb-0 text-dark"><?= $d['nama_pelanggan'] ?></h6>
                                        <small class="text-muted">ID: PLG-<?= sprintf("%03d", $d['id']) ?></small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <a href="https://wa.me/<?= $d['no_hp'] ?>" target="_blank" class="btn-wa">
                                    <i class="bi bi-whatsapp me-1"></i> <?= $d['no_hp'] ?>
                                </a>
                            </td>
                            <td class="text-secondary"><?= $d['alamat'] ?></td>
                            <td class="text-center">
                                <a href="pelanggan.php?edit=<?= $d['id'] ?>" class="btn btn-light text-warning btn-sm rounded-circle shadow-sm mx-1" title="Edit">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                <button onclick="konfirmasiHapus(<?= $d['id'] ?>)" class="btn btn-light text-danger btn-sm rounded-circle shadow-sm mx-1" title="Hapus">
                                    <i class="bi bi-trash-fill"></i>
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

<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header text-white" style="background: linear-gradient(45deg, #11998e, #38ef7d);">
                <h5 class="modal-title fw-bold"><i class="bi bi-person-plus me-2"></i>Tambah Member</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body p-4">
                    <div class="form-floating mb-3">
                        <input type="text" name="nama" class="form-control" id="addNama" placeholder="Nama" required>
                        <label for="addNama">Nama Lengkap</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="number" name="hp" class="form-control" id="addHp" placeholder="08xx" required>
                        <label for="addHp">Nomor WhatsApp / HP</label>
                    </div>
                    <div class="form-floating">
                        <textarea name="alamat" class="form-control" id="addAlamat" placeholder="Alamat" style="height: 100px"></textarea>
                        <label for="addAlamat">Alamat Lengkap</label>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="simpan" class="btn btn-success rounded-pill px-4 fw-bold">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php if($editData){ ?>
<div class="modal fade show" id="modalEdit" tabindex="-1" style="display:block; background:rgba(0,0,0,0.5); backdrop-filter: blur(5px);">
    <div class="modal-dialog modal-dialog-centered animate__animated animate__zoomIn">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header text-white" style="background: linear-gradient(45deg, #fce38a, #f38181);">
                <h5 class="modal-title fw-bold text-dark"><i class="bi bi-pencil-square me-2"></i>Edit Data</h5>
                <a href="pelanggan.php" class="btn-close"></a>
            </div>
            <form method="POST">
                <div class="modal-body p-4">
                    <input type="hidden" name="id" value="<?= $editData['id'] ?>">
                    
                    <div class="form-floating mb-3">
                        <input type="text" name="nama" class="form-control" value="<?= $editData['nama_pelanggan'] ?>" required>
                        <label>Nama Lengkap</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" name="hp" class="form-control" value="<?= $editData['no_hp'] ?>" required>
                        <label>Nomor HP</label>
                    </div>
                    <div class="form-floating">
                        <textarea name="alamat" class="form-control" style="height: 100px"><?= $editData['alamat'] ?></textarea>
                        <label>Alamat</label>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <a href="pelanggan.php" class="btn btn-light rounded-pill px-4">Batal</a>
                    <button type="submit" name="update" class="btn btn-warning rounded-pill px-4 fw-bold text-white">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php } ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // SweetAlert Script dari PHP
    <?= $swal_script ?>

    // Fungsi Cari Cepat (Javascript Search)
    document.getElementById('searchInput').addEventListener('keyup', function() {
        let val = this.value.toLowerCase();
        let rows = document.querySelectorAll('#customerTable tbody tr');
        
        rows.forEach(row => {
            let text = row.textContent.toLowerCase();
            if(text.includes(val)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    // Fungsi Konfirmasi Hapus
    function konfirmasiHapus(id) {
        Swal.fire({
            title: 'Hapus Pelanggan?',
            text: "Data riwayat transaksi pelanggan ini juga akan hilang!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location = 'pelanggan.php?hapus=' + id;
            }
        })
    }
</script>

<?php 
// Penutup DIV Layout (Jika ada di header_template)
echo "</div></div></div></body></html>"; 
?>