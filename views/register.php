<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Register - UAG Event</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card shadow border-0">
                <div class="card-body p-4">
                    <h3 class="text-center fw-bold mb-4">Register UAG Event</h3>

                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger">
                            <?= $error ?>
                        </div>
                    <?php endif; ?>

                    <form action="index.php?action=register" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email Kampus</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">No. Telepon</label>
                            <input type="text" name="no_telp" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Konfirmasi Password</label>
                            <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2">
                            Daftar
                        </button>
                    </form>

                    <hr>

                    <p class="text-center mb-0 small">
                        Sudah punya akun?
                        <a href="index.php?action=login">Login di sini</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
function togglePassword(id) {
    const input = document.getElementById(id);
    input.type = input.type === "password" ? "text" : "password";
}

// Validasi realtime password
const password = document.getElementById("password");
const confirm  = document.getElementById("confirm_password");
const helpText = document.getElementById("passwordHelp");

confirm.addEventListener("keyup", () => {
    if (confirm.value !== password.value) {
        helpText.classList.remove("d-none");
    } else {
        helpText.classList.add("d-none");
    }
});
</script>

</body>
</html>
