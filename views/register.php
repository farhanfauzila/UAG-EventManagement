<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi - UAG Event</title>
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

        /* Dekorasi Cahaya di Background */
        body::before {
            content: "";
            position: absolute;
            width: 600px;
            height: 600px;
            background: var(--accent);
            border-radius: 50%;
            filter: blur(150px);
            opacity: 0.08;
            bottom: -200px;
            left: -200px;
        }

        .register-container {
            z-index: 10;
            width: 100%;
            max-width: 550px;
            padding: 15px;
        }

        .card-register {
            background: rgba(255, 255, 255, 0.95);
            border: none;
            border-radius: 30px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.3);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .brand-logo {
            font-weight: 800;
            color: white;
            letter-spacing: -1px;
            font-size: 1.8rem;
            text-shadow: 0 4px 10px rgba(0,0,0,0.2);
        }

        .form-label {
            font-weight: 600;
            color: var(--primary);
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .required { color: #dc3545; }

        .form-control {
            border-radius: 12px;
            padding: 10px 15px;
            border: 2px solid #eee;
            transition: 0.3s;
            font-size: 0.95rem;
        }

        .form-control:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 4px rgba(209, 232, 250, 0.5);
        }

        .input-group-text {
            background: white;
            border: 2px solid #eee;
            border-left: none;
            border-radius: 0 12px 12px 0;
            cursor: pointer;
            color: var(--primary);
        }

        .btn-register {
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 14px;
            font-weight: 700;
            transition: 0.4s;
            box-shadow: 0 10px 20px rgba(29, 67, 114, 0.2);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn-register:hover {
            background: #2a5a96;
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(29, 67, 114, 0.3);
            color: white;
        }

        .text-hint { font-size: 0.75rem; color: #6c757d; margin-top: 5px; display: block; }

        .divider {
            height: 1px;
            background: #eee;
            margin: 25px 0;
        }

        .footer-link {
            color: var(--primary);
            font-weight: 700;
            text-decoration: none;
        }

        .animate-fade {
            animation: fadeIn 0.8s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

<div class="register-container animate-fade">

    <div class="card card-register">
        <div class="card-body p-4 p-md-5">
            <div class="text-center mb-4">
                <h3 class="fw-bold text-dark mb-1">Create Account</h3>
                <p class="text-muted small">Gabung dengan komunitas mahasiswa UAG sekarang</p>
            </div>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger border-0 rounded-4 py-2 small text-center mb-4">
                    <i class="bi bi-exclamation-circle-fill me-2"></i> <?= $error ?>
                </div>
            <?php endif; ?>

            <form action="index.php?action=register" method="POST">
                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label">Nama Lengkap <span class="required">*</span></label>
                        <input type="text" name="nama" class="form-control" placeholder="Nama Lengkap" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Email Kampus <span class="required">*</span></label>
                        <input type="email" name="email" class="form-control" placeholder="nim@uag.ac.id" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">No. Telepon</label>
                        <input type="text" name="no_telp" class="form-control" placeholder="08xxxxxx">
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Password <span class="required">*</span></label>
                        <div class="input-group">
                            <input type="password" name="password" id="password" class="form-control" style="border-right:none;" 
                                   required pattern="(?=.*[A-Za-z])(?=.*\d).{6,}">
                            <span class="input-group-text" onclick="togglePassword('password')">
                                <i class="bi bi-eye" id="icon-password"></i>
                            </span>
                        </div>
                        <small class="text-hint">Min. 6 karakter, kombinasi huruf & angka.</small>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Konfirmasi Password <span class="required">*</span></label>
                        <div class="input-group">
                            <input type="password" name="confirm_password" id="confirm_password" class="form-control" style="border-right:none;" required>
                            <span class="input-group-text" onclick="togglePassword('confirm_password')">
                                <i class="bi bi-eye" id="icon-confirm_password"></i>
                            </span>
                        </div>
                        <small id="passwordHelp" class="text-danger d-none fw-bold" style="font-size: 0.7rem;">Password tidak cocok!</small>
                    </div>
                </div>

                <button type="submit" class="btn btn-register w-100 mt-4">
                    Register Account <i class="bi bi-person-plus ms-2"></i>
                </button>
            </form>

            <div class="divider"></div>

            <p class="text-center mb-4 small text-muted">
                Sudah punya akun? <a href="index.php?action=login" class="footer-link">Sign In</a>
            </p>

            <div class="text-center">
                <a href="index.php" class="text-decoration-none text-muted small">
                    <i class="bi bi-house-door me-1"></i> Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function togglePassword(id) {
    const input = document.getElementById(id);
    const icon = document.getElementById('icon-' + id);
    if (input.type === "password") {
        input.type = "text";
        icon.classList.replace('bi-eye', 'bi-eye-slash');
    } else {
        input.type = "password";
        icon.classList.replace('bi-eye-slash', 'bi-eye');
    }
}

const password = document.getElementById("password");
const confirm = document.getElementById("confirm_password");
const helpText = document.getElementById("passwordHelp");

confirm.addEventListener("keyup", () => {
    if (confirm.value !== password.value && confirm.value !== "") {
        helpText.classList.remove("d-none");
    } else {
        helpText.classList.add("d-none");
    }
});
</script>
</body>
</html>