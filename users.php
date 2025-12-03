<?php 
include 'koneksi.php';
include 'header_template.php';

// --- LOGIKA TAMBAH USER ---
if(isset($_POST['simpan'])){
    $nama     = $_POST['nama'];
    $username = $_POST['username'];
    $password = $_POST['password']; 

    // Cek apakah username sudah ada
    $cek = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
    if(mysqli_num_rows($cek) > 0){
        echo "<script>alert('Username sudah digunakan, cari yang lain!');</script>";
    } else {
        // Simpan ke database
        $simpan = mysqli_query($conn, "INSERT INTO users (nama, username, password) VALUES ('$nama','$username','$password')");
        
        if($simpan){
            echo "<script>alert('Berhasil Menambah Admin Baru!'); window.location='users.php';</script>";
        } else {
            echo "<script>alert('Gagal: ".mysqli_error($conn)."');</script>";
        }
    }
}

// --- LOGIKA HAPUS USER ---
if(isset($_GET['hapus'])){
    $id = $_GET['hapus'];
    // Cegah menghapus diri sendiri (Opsional, tapi bagus)
    // Asumsi di login Anda menyimpan id user di session, jika tidak, bisa dihapus validasi ini
    
    mysqli_query($conn, "DELETE FROM users WHERE id='$id'");
    echo "<script>window.location='users.php';</script>";
}
?>

<div class="card shadow border-0">
    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold"><i class="bi bi-person-badge-fill me-2"></i>Kelola Admin</h5>
        <button class="btn btn-warning fw-bold" data-bs-toggle="modal" data-bs-target="#modalTambahUser">
            + Tambah Admin
        </button>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped align-middle">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Lengkap</th>
                    <th>Username</th>
                    <th>Password</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1;
                $data = mysqli_query($conn, "SELECT * FROM users ORDER BY id ASC");
                while($d = mysqli_fetch_assoc($data)){
                ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $d['nama'] ?></td>
                    <td><span class="badge bg-info text-dark"><?= $d['username'] ?></span></td>
                    <td>
                        <input type="password" value="<?= $d['password'] ?>" class="form-control form-control-sm border-0 bg-transparent" readonly style="width:100px;">
                    </td>
                    <td>
                        <a href="users.php?hapus=<?= $d['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus admin ini?')">
                            <i class="bi bi-trash"></i> Hapus
                        </a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <div class="alert alert-info mt-3 small">
            <i class="bi bi-info-circle me-1"></i> Admin yang terdaftar bisa login ke aplikasi ini.
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambahUser">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title">Tambah Admin Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="fw-bold">Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control" required placeholder="Contoh: Kasir Shift Pagi">
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold">Username</label>
                        <input type="text" name="username" class="form-control" required placeholder="Tanpa spasi">
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold">Password</label>
                        <input type="text" name="password" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="simpan" class="btn btn-dark">Simpan Admin</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</div></div></div></body></html>