<?php 
include 'koneksi.php'; 

// --- PHP LOGIC (TIDAK BERUBAH, HANYA DIPINDAHKAN KE ATAS) ---
if(isset($_POST['proses_bayar'])){
    $pelanggan_id = $_POST['pelanggan_id'];
    $diskon       = $_POST['diskon'];
    $bayar        = $_POST['bayar'];
    $kembalian    = $_POST['kembalian'];
    $total_final  = $_POST['total_final'];
    $cart_data    = json_decode($_POST['cart_data'], true);
    
    $no_trx = "TRX-" . date("YmdHis");
    $tanggal = date("Y-m-d H:i:s");

    // Insert Header
    $q1 = mysqli_query($conn, "INSERT INTO transaksi (no_transaksi, tanggal, total_bayar, diskon, jumlah_uang, kembalian, id_pelanggan) VALUES ('$no_trx', '$tanggal', '$total_final', '$diskon', '$bayar', '$kembalian', '$pelanggan_id')");
    
    // Insert Detail & Update Stok
    if($q1){
        foreach($cart_data as $item){
            $id_produk = $item['id'];
            $qty = $item['qty'];
            $subtotal = $item['price'] * $qty;
            
            mysqli_query($conn, "INSERT INTO transaksi_detail (no_transaksi, id_produk, qty, subtotal) VALUES ('$no_trx', '$id_produk', '$qty', '$subtotal')");
            mysqli_query($conn, "UPDATE produk SET stok = stok - $qty WHERE id='$id_produk'");
        }
        echo "<script>window.location='struk.php?no=$no_trx';</script>";
    } else {
        echo "<script>alert('Gagal menyimpan transaksi');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kasir Kalcer Point of Sales</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --primary-color: #4361ee;
            --bg-color: #f3f4f6;
            --card-radius: 16px;
        }
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-color);
            height: 100vh;
            overflow: hidden; /* Mencegah scroll window utama */
        }
        
        /* Layout Grid */
        .main-layout {
            height: 100vh;
            display: flex;
        }
        .left-panel {
            flex: 1;
            display: flex;
            flex-direction: column;
            padding: 20px;
            overflow: hidden;
        }
        .right-panel {
            width: 400px;
            background: white;
            display: flex;
            flex-direction: column;
            border-left: 1px solid #e5e7eb;
            box-shadow: -5px 0 15px rgba(0,0,0,0.02);
        }

        /* Product Grid */
        .product-scroll-area {
            flex: 1;
            overflow-y: auto;
            padding-right: 5px;
        }
        .product-card {
            border: none;
            border-radius: var(--card-radius);
            background: white;
            transition: all 0.2s;
            cursor: pointer;
            overflow: hidden;
            position: relative;
        }
        .product-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.08);
            border: 1px solid var(--primary-color);
        }
        .product-img-placeholder {
            height: 100px;
            background: linear-gradient(135deg, #e0e7ff 0%, #f3f4f6 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-color);
            font-size: 2rem;
        }
        .stok-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(255,255,255,0.9);
            padding: 4px 8px;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: bold;
            color: #333;
        }

        /* Cart Area */
        .cart-header {
            padding: 20px;
            border-bottom: 1px solid #eee;
        }
        .cart-items {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
        }
        .cart-footer {
            padding: 20px;
            background: #f8f9fa;
            border-top: 1px solid #eee;
        }
        
        /* Custom Inputs */
        .form-control-lg { font-size: 1rem; }
        .btn-quick-money {
            font-size: 0.8rem;
            border: 1px solid #ddd;
            background: white;
            color: #555;
            padding: 5px 10px;
            border-radius: 50px;
            transition: 0.2s;
        }
        .btn-quick-money:hover { background: var(--primary-color); color: white; border-color: var(--primary-color); }
        
        /* Scrollbar Halus */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
    </style>
</head>
<body>

<div class="main-layout">
    <div class="left-panel">
        <header class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-0 text-dark"><i class="bi bi-shop me-2 text-primary"></i>Kasir Kalcer</h4>
                <small class="text-muted"><?= date('l, d F Y') ?></small>
            </div>
            <a href="dashboard.php" class="btn btn-light border btn-sm rounded-pill px-3 fw-bold">
                <i class="bi bi-house-door me-1"></i> Dashboard
            </a>
        </header>

        <div class="position-relative mb-4">
            <span class="position-absolute top-50 start-0 translate-middle-y ms-3 text-muted">
                <i class="bi bi-search"></i>
            </span>
            <input type="text" id="searchProduct" class="form-control form-control-lg ps-5 rounded-pill border-0 shadow-sm" placeholder="Cari menu atau scan barcode..." style="height: 50px;">
        </div>

        <div class="product-scroll-area">
            <div class="row g-3" id="product-list">
                <?php 
                $prod = mysqli_query($conn, "SELECT * FROM produk WHERE stok > 0 ORDER BY nama_produk ASC");
                while($p = mysqli_fetch_assoc($prod)){
                ?>
                <div class="col-6 col-md-4 col-lg-3 product-item" data-name="<?= strtolower($p['nama_produk']) ?>">
                    <div class="product-card h-100" onclick="addToCart(<?= $p['id'] ?>, '<?= addslashes($p['nama_produk']) ?>', <?= $p['harga'] ?>)">
                        <span class="stok-badge shadow-sm">Stok: <?= $p['stok'] ?></span>
                        <div class="product-img-placeholder">
                            <i class="bi bi-basket2-fill"></i>
                        </div>
                        <div class="p-3">
                            <h6 class="fw-bold text-dark mb-1 text-truncate"><?= $p['nama_produk'] ?></h6>
                            <p class="text-primary fw-bold mb-0">Rp <?= number_format($p['harga'],0,',','.') ?></p>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>

    <div class="right-panel">
        <div class="cart-header">
            <label class="small text-muted fw-bold text-uppercase mb-2">Pelanggan</label>
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0"><i class="bi bi-person"></i></span>
                <select id="pelanggan" class="form-select border-start-0 ps-0 shadow-none">
                    <option value="1">Pelanggan Umum</option>
                    <?php 
                    $plg = mysqli_query($conn, "SELECT * FROM pelanggan");
                    while($c = mysqli_fetch_assoc($plg)){
                        echo "<option value='$c[id]'>$c[nama_pelanggan]</option>";
                    }
                    ?>
                </select>
            </div>
        </div>

        <div class="cart-items" id="cart-container">
            <div class="d-flex flex-column align-items-center justify-content-center h-100 text-muted opacity-50">
                <i class="bi bi-cart-x" style="font-size: 3rem;"></i>
                <p class="mt-2">Belum ada item</p>
            </div>
        </div>

        <div class="cart-footer shadow-sm">
            <div class="d-flex justify-content-between mb-2">
                <span class="text-muted">Subtotal</span>
                <span class="fw-bold" id="subtotal-text">Rp 0</span>
            </div>
            
            <div class="input-group input-group-sm mb-3">
                <span class="input-group-text bg-white text-muted">Diskon</span>
                <input type="number" id="input-diskon" class="form-control text-end" placeholder="0">
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3 pt-2 border-top">
                <span class="h5 mb-0 fw-bold">Total</span>
                <span class="h4 mb-0 fw-bold text-primary" id="total-text">Rp 0</span>
            </div>

            <div class="mb-3">
                <label class="small text-muted mb-1">Uang Diterima</label>
                <div class="input-group mb-2">
                    <span class="input-group-text bg-white fw-bold">Rp</span>
                    <input type="number" id="input-bayar" class="form-control form-control-lg fw-bold text-end" placeholder="0">
                </div>
                <div class="d-flex gap-2 justify-content-end">
                    <button type="button" class="btn-quick-money" onclick="setMoney('pas')">Uang Pas</button>
                    <button type="button" class="btn-quick-money" onclick="setMoney(20000)">20k</button>
                    <button type="button" class="btn-quick-money" onclick="setMoney(50000)">50k</button>
                    <button type="button" class="btn-quick-money" onclick="setMoney(100000)">100k</button>
                </div>
            </div>

            <div class="d-flex justify-content-between mb-3 small fw-bold">
                <span>Kembalian</span>
                <span class="text-success" id="kembalian-text">Rp 0</span>
            </div>

            <form method="POST" id="form-checkout">
                <input type="hidden" name="cart_data" id="cart_data_input">
                <input type="hidden" name="total_final" id="total_final_input">
                <input type="hidden" name="diskon" id="diskon_input">
                <input type="hidden" name="bayar" id="bayar_input">
                <input type="hidden" name="kembalian" id="kembalian_input">
                <input type="hidden" name="pelanggan_id" id="pelanggan_input">
                
                <button type="button" onclick="prosesBayar()" class="btn btn-primary w-100 py-3 rounded-3 fw-bold shadow-sm">
                    <i class="bi bi-printer-fill me-2"></i> PROSES TRANSAKSI
                </button>
                <button type="submit" name="proses_bayar" id="btn-submit-real" style="display:none;"></button>
            </form>
        </div>
    </div>
</div>

<script>
    let cart = [];

    // --- LOGIKA KERANJANG ---
    function addToCart(id, name, price) {
        let existing = cart.find(item => item.id === id);
        if (existing) {
            existing.qty++;
        } else {
            cart.push({ id, name, price, qty: 1 });
        }
        renderCart();
    }

    function changeQty(index, delta) {
        cart[index].qty += delta;
        if (cart[index].qty <= 0) {
            cart.splice(index, 1);
        }
        renderCart();
    }

    function renderCart() {
        let container = document.getElementById('cart-container');
        let subtotal = 0;
        
        if (cart.length === 0) {
            container.innerHTML = `
                <div class="d-flex flex-column align-items-center justify-content-center h-100 text-muted opacity-50">
                    <i class="bi bi-cart-x" style="font-size: 3rem;"></i>
                    <p class="mt-2">Belum ada item</p>
                </div>`;
            updateTotals(0);
            return;
        }

        let html = '<div class="vstack gap-3">';
        cart.forEach((item, index) => {
            let itemTotal = item.price * item.qty;
            subtotal += itemTotal;
            html += `
            <div class="d-flex justify-content-between align-items-center bg-light p-2 rounded-3 border">
                <div class="overflow-hidden me-2" style="max-width: 140px;">
                    <div class="fw-bold text-dark text-truncate">${item.name}</div>
                    <small class="text-muted">@ ${item.price.toLocaleString('id-ID')}</small>
                </div>
                <div class="d-flex align-items-center bg-white rounded-pill border px-1">
                    <button class="btn btn-sm text-danger p-0 px-2" onclick="changeQty(${index}, -1)"><i class="bi bi-dash"></i></button>
                    <span class="mx-2 fw-bold small" style="min-width:20px; text-align:center;">${item.qty}</span>
                    <button class="btn btn-sm text-success p-0 px-2" onclick="changeQty(${index}, 1)"><i class="bi bi-plus"></i></button>
                </div>
                <div class="fw-bold ms-2 small">
                    ${itemTotal.toLocaleString('id-ID')}
                </div>
            </div>`;
        });
        html += '</div>';
        container.innerHTML = html;
        updateTotals(subtotal);
        
        // Auto scroll ke bawah keranjang
        container.scrollTop = container.scrollHeight;
    }

    // --- LOGIKA HITUNGAN ---
    document.getElementById('input-bayar').addEventListener('input', hitungKembalian);
    document.getElementById('input-diskon').addEventListener('input', function(){ renderCart(); }); // Re-render triggers calculation

    function updateTotals(subtotal) {
        let diskon = parseInt(document.getElementById('input-diskon').value) || 0;
        if(diskon > subtotal) diskon = subtotal;

        let totalAkhir = subtotal - diskon;

        document.getElementById('subtotal-text').innerText = 'Rp ' + subtotal.toLocaleString('id-ID');
        document.getElementById('subtotal-text').dataset.val = subtotal;
        
        document.getElementById('total-text').innerText = 'Rp ' + totalAkhir.toLocaleString('id-ID');
        document.getElementById('total-text').dataset.val = totalAkhir;

        hitungKembalian();
    }

    function hitungKembalian() {
        let totalAkhir = parseInt(document.getElementById('total-text').dataset.val) || 0;
        let bayar = parseInt(document.getElementById('input-bayar').value) || 0;
        let kembalian = bayar - totalAkhir;

        let textKembalian = kembalian >= 0 ? 'Rp ' + kembalian.toLocaleString('id-ID') : '-';
        document.getElementById('kembalian-text').innerText = textKembalian;
        
        // Warna kembalian
        if(kembalian >= 0) {
            document.getElementById('kembalian-text').className = "text-success fw-bold";
        } else {
            document.getElementById('kembalian-text').className = "text-danger fw-bold";
        }
    }

    // --- FITUR QUICK MONEY ---
    function setMoney(amount) {
        let totalAkhir = parseInt(document.getElementById('total-text').dataset.val) || 0;
        if(amount === 'pas') {
            document.getElementById('input-bayar').value = totalAkhir;
        } else {
            document.getElementById('input-bayar').value = amount;
        }
        hitungKembalian();
    }

    // --- SUBMIT ---
    function prosesBayar() {
        let totalAkhir = parseInt(document.getElementById('total-text').dataset.val) || 0;
        let bayar = parseInt(document.getElementById('input-bayar').value) || 0;

        if (cart.length === 0) {
            Swal.fire('Oops!', 'Keranjang masih kosong.', 'warning');
            return;
        }
        if (bayar < totalAkhir) {
            Swal.fire('Uang Kurang!', 'Pembayaran belum mencukupi.', 'error');
            return;
        }

        // Isi Hidden Input
        document.getElementById('cart_data_input').value = JSON.stringify(cart);
        document.getElementById('pelanggan_input').value = document.getElementById('pelanggan').value;
        document.getElementById('total_final_input').value = totalAkhir;
        document.getElementById('diskon_input').value = document.getElementById('input-diskon').value || 0;
        document.getElementById('bayar_input').value = bayar;
        document.getElementById('kembalian_input').value = bayar - totalAkhir;
        
        // Trigger Submit
        document.getElementById('btn-submit-real').click();
    }

    // --- FITUR PENCARIAN ---
    document.getElementById('searchProduct').addEventListener('keyup', function() {
        let val = this.value.toLowerCase();
        let items = document.querySelectorAll('.product-item');
        items.forEach(item => {
            if(item.dataset.name.includes(val)) item.style.display = 'block';
            else item.style.display = 'none';
        });
    });
</script>

</body>
</html>