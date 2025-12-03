<?php
include 'koneksi.php';
$error_msg = "";

if(isset($_POST['login'])){
    $user = htmlspecialchars($_POST['user']);
    $pass = $_POST['pass'];
    
    // Cek User
    $cek = mysqli_query($conn, "SELECT * FROM users WHERE username='$user' AND password='$pass'");
    
    if(mysqli_num_rows($cek) > 0){
        // Ambil data user untuk session nama/id jika perlu
        $d = mysqli_fetch_assoc($cek);
        $_SESSION['login'] = true;
        $_SESSION['username'] = $user;
        $_SESSION['nama'] = $d['nama']; // Asumsi ada kolom nama
        
        header("Location: dashboard.php");
        exit;
    } else {
        $error_msg = "Username atau Password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Login - Kasir Kalcer</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(45deg, #4e54c8, #8f94fb);
            height: 100vh;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* --- BACKGROUND ANIMATION (SQUARES) --- */
        .circles {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            overflow: hidden;
            z-index: 0;
        }
        .circles li {
            position: absolute;
            display: block;
            list-style: none;
            width: 20px; height: 20px;
            background: rgba(255, 255, 255, 0.2);
            animation: animate 25s linear infinite;
            bottom: -150px;
            border-radius: 15px;
        }
        .circles li:nth-child(1){ left: 25%; width: 80px; height: 80px; animation-delay: 0s; }
        .circles li:nth-child(2){ left: 10%; width: 20px; height: 20px; animation-delay: 2s; animation-duration: 12s; }
        .circles li:nth-child(3){ left: 70%; width: 20px; height: 20px; animation-delay: 4s; }
        .circles li:nth-child(4){ left: 40%; width: 60px; height: 60px; animation-delay: 0s; animation-duration: 18s; }
        .circles li:nth-child(5){ left: 65%; width: 20px; height: 20px; animation-delay: 0s; }
        .circles li:nth-child(6){ left: 75%; width: 110px; height: 110px; animation-delay: 3s; }
        .circles li:nth-child(7){ left: 35%; width: 150px; height: 150px; animation-delay: 7s; }
        .circles li:nth-child(8){ left: 50%; width: 25px; height: 25px; animation-delay: 15s; animation-duration: 45s; }
        .circles li:nth-child(9){ left: 20%; width: 15px; height: 15px; animation-delay: 2s; animation-duration: 35s; }
        .circles li:nth-child(10){ left: 85%; width: 150px; height: 150px; animation-delay: 0s; animation-duration: 11s; }

        @keyframes animate {
            0%{ transform: translateY(0) rotate(0deg); opacity: 1; border-radius: 0; }
            100%{ transform: translateY(-1000px) rotate(720deg); opacity: 0; border-radius: 50%; }
        }

        /* --- GLASSMORPHISM CARD --- */
        .glass-card {
            background: rgba(255, 255, 255, 0.15);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.18);
            width: 400px;
            padding: 40px;
            z-index: 10;
            color: white;
            position: relative;
        }

        .login-header { text-align: center; margin-bottom: 30px; }
        .login-icon { 
            font-size: 3rem; 
            background: white; 
            color: #4e54c8; 
            width: 80px; height: 80px; 
            line-height: 80px; 
            border-radius: 50%; 
            margin: 0 auto 15px; 
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        /* --- FORM INPUTS --- */
        .form-control {
            background: rgba(255, 255, 255, 0.9);
            border: none;
            border-radius: 50px;
            padding: 12px 20px 12px 45px;
            height: 50px;
            font-size: 0.9rem;
        }
        .form-control:focus {
            box-shadow: 0 0 0 4px rgba(255,255,255,0.3);
            background: white;
        }
        
        .input-group { position: relative; margin-bottom: 20px; }
        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #4e54c8;
            z-index: 5;
            font-size: 1.2rem;
        }

        .btn-login {
            background: linear-gradient(to right, #667eea, #764ba2);
            border: none;
            border-radius: 50px;
            padding: 12px;
            width: 100%;
            color: white;
            font-weight: 600;
            letter-spacing: 1px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            transition: 0.3s;
        }
        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.3);
            background: linear-gradient(to right, #764ba2, #667eea);
        }

        .footer-copy {
            text-align: center;
            font-size: 0.75rem;
            margin-top: 20px;
            opacity: 0.7;
            color: white;
        }
    </style>
</head>
<body>

    <ul class="circles">
        <li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li>
    </ul>

    <div class="glass-card animate__animated animate__fadeInUp">
        <div class="login-header">
            <div class="login-icon animate__animated animate__bounceIn animate__delay-1s">
                <i class="bi bi-shop"></i>
            </div>
            <h3 class="fw-bold mb-0">Kasir Kalcer</h3>
            <p class="small opacity-75">Silakan login untuk memulai</p>
        </div>

        <form method="POST">
            <div class="input-group">
                <i class="bi bi-person-fill input-icon"></i>
                <input type="text" name="user" class="form-control" placeholder="Username" required autocomplete="off">
            </div>
            
            <div class="input-group">
                <i class="bi bi-lock-fill input-icon"></i>
                <input type="password" name="pass" class="form-control" placeholder="Password" required>
            </div>

            <button type="submit" name="login" class="btn-login mt-3">
                MASUK SEKARANG <i class="bi bi-arrow-right-circle ms-2"></i>
            </button>
        </form>

        <div class="footer-copy">
            &copy; 2025 Kasir Kalcer ID. All Rights Reserved.
        </div>
    </div>

    <?php if(!empty($error_msg)): ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Gagal Masuk',
            text: '<?= $error_msg ?>',
            background: '#fff',
            confirmButtonColor: '#4e54c8'
        });
    </script>
    <?php endif; ?>

</body>
</html>