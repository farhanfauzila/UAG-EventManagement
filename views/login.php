<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - UAG Event</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #1d4372;
            --base: #f5f8f7;
            --accent: #d1e8fa;
        }

        body {
    font-family: 'Inter', sans-serif;
    /* Ubah dari linear-gradient ke background-color solid atau buat gradiennya 'fixed' */
    background: #1d4372; /* Warna dasar biru tua kamu */
    background: linear-gradient(135deg, #1d4372 0%, #0a1d36 100%);
    background-attachment: fixed; /* KUNCI PERBAIKAN: Background tidak ikut bergeser saat di-scroll */
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 40px 0;
    margin: 0;
    position: relative;
    overflow-x: hidden;
}

        /* Elemen Dekoratif Background */
        body::before {
            content: "";
            position: absolute;
            width: 500px;
            height: 500px;
            background: var(--accent);
            border-radius: 50%;
            filter: blur(120px);
            opacity: 0.1;
            top: -100px;
            right: -100px;
        }

        .login-container {
            z-index: 10;
            width: 100%;
            max-width: 450px;
            padding: 15px;
        }

        .card-login {
            background: rgba(255, 255, 255, 0.95);
            border: none;
            border-radius: 30px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.3);
            backdrop-filter: blur(10px);
        }

        .brand-logo {
            font-weight: 800;
            color: var(--primary);
            letter-spacing: -1px;
            font-size: 1.5rem;
        }

        .form-label {
            font-weight: 600;
            color: var(--primary);
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-control {
            border-radius: 12px;
            padding: 12px 15px;
            border: 2px solid #eee;
            transition: 0.3s;
        }

        .form-control:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 4px rgba(209, 232, 250, 0.5);
        }

        .btn-login {
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 12px;
            font-weight: 700;
            transition: 0.4s;
            box-shadow: 0 10px 20px rgba(29, 67, 114, 0.2);
        }

        .btn-login:hover {
            background: #2a5a96;
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(29, 67, 114, 0.3);
            color: white;
        }

        .input-group-text {
            background: white;
            border: 2px solid #eee;
            border-left: none;
            border-radius: 0 12px 12px 0;
            cursor: pointer;
            color: var(--primary);
        }

        .divider {
            height: 1px;
            background: #eee;
            margin: 25px 0;
            position: relative;
        }

        .divider::after {
            content: "OR";
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 0 15px;
            font-size: 0.7rem;
            color: #ccc;
            font-weight: 800;
        }

        .footer-link {
            color: var(--primary);
            font-weight: 700;
            text-decoration: none;
            transition: 0.3s;
        }

        .footer-link:hover {
            color: #4361ee;
        }

        /* Animasi Muncul */
        .animate-up {
            animation: fadeInUp 0.8s ease-out;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

    <div class="login-container animate-up">

        <div class="card card-login">
            <div class="card-body p-4 p-md-5">
                <div class="text-center mb-4">
                    <h3 class="fw-800 text-dark mb-1">Welcome Back!</h3>
                    <p class="text-muted small">Silakan masuk ke akun mahasiswamu</p>
                </div>

                <?php if (isset($_GET['status']) && $_GET['status'] === 'success'): ?>
                    <div class="alert alert-success border-0 rounded-4 py-2 small text-center mb-4">
                        <i class="bi bi-check-circle-fill me-2"></i> Akun berhasil dibuat!
                    </div>
                <?php endif; ?>

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger border-0 rounded-4 py-2 small text-center mb-4">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= $error ?>
                    </div>
                <?php endif; ?>

                <form action="index.php?action=login" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Email Kampus</label>
                        <input type="email" name="email" class="form-control" placeholder="nim@uag.ac.id" required>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label">Password</label>
                        <div class="input-group">
                            <input type="password" name="password" id="password" class="form-control" style="border-right: none;" placeholder="••••••" required>
                            <span class="input-group-text" onclick="togglePassword()">
                                <i class="bi bi-eye" id="toggleIcon"></i>
                            </span>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-login w-100 mb-3">
                        SIGN IN <i class="bi bi-arrow-right-short ms-1"></i>
                    </button>
                </form>
                
                <div class="divider"></div>
                
                <p class="text-center mb-4 small text-muted">Belum punya akun? 
                    <a href="index.php?action=register" class="footer-link">Daftar Sekarang</a>
                </p>

                <div class="text-center">
                    <a href="index.php" class="text-decoration-none text-muted small hover-primary">
                        <i class="bi bi-house-door me-1"></i> Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
    function togglePassword() {
        const passwordField = document.getElementById("password");
        const toggleIcon = document.getElementById("toggleIcon");
        
        if (passwordField.type === "password") {
            passwordField.type = "text";
            toggleIcon.classList.replace("bi-eye", "bi-eye-slash");
        } else {
            passwordField.type = "password";
            toggleIcon.classList.replace("bi-eye-slash", "bi-eye");
        }
    }
    </script>
</body>
</html>