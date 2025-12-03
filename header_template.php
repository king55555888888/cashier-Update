<?php 
if(session_status() === PHP_SESSION_NONE) session_start();
if(!isset($_SESSION['login'])){ header("Location: index.php"); exit; } 

// Dapatkan nama file saat ini untuk penanda menu aktif
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kasir Kalcer - Dashboard</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --primary: #4361ee;
            --primary-dark: #3a0ca3;
            --secondary: #4cc9f0;
            --bg-body: #f3f4f6;
            --sidebar-width: 280px;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-body);
            color: #334155;
            overflow-x: hidden;
        }

        /* --- NAVBAR STYLING --- */
        .navbar-custom {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(0,0,0,0.05);
            padding: 0.8rem 0;
            z-index: 1000;
        }
        .brand-text {
            font-weight: 800;
            background: linear-gradient(45deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: -0.5px;
            font-size: 1.5rem;
        }

        /* --- SIDEBAR STYLING --- */
        .sidebar-card {
            background: white;
            border-radius: 20px;
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.03);
            position: sticky;
            top: 100px; /* Jarak dari atas saat scroll */
            padding: 1.5rem;
            height: calc(100vh - 120px);
            overflow-y: auto;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px;
            background: #f8fafc;
            border-radius: 16px;
            margin-bottom: 2rem;
            border: 1px solid #e2e8f0;
        }

        .menu-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #94a3b8;
            font-weight: 700;
            margin-bottom: 10px;
            margin-top: 15px;
            padding-left: 10px;
        }

        .nav-link-custom {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            color: #64748b;
            text-decoration: none;
            border-radius: 12px;
            font-weight: 500;
            margin-bottom: 5px;
            transition: all 0.3s ease;
        }

        .nav-link-custom i {
            font-size: 1.2rem;
            margin-right: 12px;
            transition: 0.3s;
        }

        /* Hover State */
        .nav-link-custom:hover {
            background-color: #eff6ff;
            color: var(--primary);
            transform: translateX(5px);
        }

        /* Active State */
        .nav-link-custom.active {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3);
        }
        
        /* Logout Button */
        .btn-logout {
            margin-top: auto;
            background-color: #fee2e2;
            color: #ef4444;
        }
        .btn-logout:hover {
            background-color: #ef4444;
            color: white;
        }

        /* POS Button Highlighting */
        .nav-link-pos {
            background: #10b981;
            color: white !important;
            font-weight: bold;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
        }
        .nav-link-pos:hover {
            background: #059669;
            transform: translateY(-2px);
        }

        /* Responsive Fix */
        @media (max-width: 768px) {
            .sidebar-card {
                position: static;
                height: auto;
                margin-bottom: 20px;
            }
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-custom sticky-top">
    <div class="container">
        <a class="navbar-brand brand-text" href="dashboard.php">
            <i class="bi bi-rocket-takeoff-fill me-2"></i>KASIR Kalcer.
        </a>
        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="bi bi-list fs-2 text-primary"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item me-3">
                    <span class="text-muted small"><i class="bi bi-clock me-1"></i> <?= date('d M Y') ?></span>
                </li>
                <li class="nav-item">
                    <a class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm" href="kasir.php">
                        <i class="bi bi-cart-fill me-2"></i> Buka Kasir
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4 mb-5">
    <div class="row g-4">
        
        <div class="col-lg-3">
            <div class="sidebar-card d-flex flex-column">
                
                <div class="user-profile shadow-sm">
                    <img src="https://ui-avatars.com/api/?name=Admin+Kalcer&background=4361ee&color=fff&bold=true" class="rounded-circle" width="45">
                    <div style="line-height: 1.2;">
                        <span class="d-block fw-bold text-dark">Administrator</span>
                        <small class="text-success fw-bold" style="font-size: 0.7rem;">● Online</small>
                    </div>
                </div>

                <div class="menu-label">Menu Utama</div>
                
                <a href="dashboard.php" class="nav-link-custom <?= $current_page == 'dashboard.php' ? 'active' : '' ?>">
                    <i class="bi bi-grid-1x2-fill"></i> Dashboard
                </a>
                
                <a href="kasir.php" class="nav-link-custom nav-link-pos mt-2 mb-2">
                    <i class="bi bi-basket2-fill"></i> Point of Sales
                </a>

                <div class="menu-label">Manajemen Data</div>

                <a href="produk.php" class="nav-link-custom <?= $current_page == 'produk.php' ? 'active' : '' ?>">
                    <i class="bi bi-box-seam-fill"></i> Produk & Stok
                </a>
                
                <a href="pelanggan.php" class="nav-link-custom <?= $current_page == 'pelanggan.php' ? 'active' : '' ?>">
                    <i class="bi bi-people-fill"></i> Data Pelanggan
                </a>

                <div class="menu-label">Laporan</div>

                <a href="riwayat.php" class="nav-link-custom <?= $current_page == 'riwayat.php' ? 'active' : '' ?>">
                    <i class="bi bi-receipt-cutoff"></i> Riwayat Transaksi
                </a>

                <div class="menu-label">Pengaturan</div>

                <a href="users.php" class="nav-link-custom <?= $current_page == 'users.php' ? 'active' : '' ?>">
                    <i class="bi bi-shield-lock-fill"></i> Kelola Admin
                </a>

                <a href="logout.php" class="nav-link-custom btn-logout mt-4 justify-content-center">
                    <i class="bi bi-box-arrow-right"></i> Keluar Sistem
                </a>

            </div>
        </div>

        <div class="col-lg-9">