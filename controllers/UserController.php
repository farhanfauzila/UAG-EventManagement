<?php
include_once 'models/User.php';

class UserController
{
    private $userModel;

    public function __construct($db)
    {
        if ($db === null) {
            die("Koneksi database ke UserController gagal!");
        }
        $this->userModel = new User($db);
    }

    public function login()
    {
        if (isset($_SESSION['user_id'])) {
            if ($_SESSION['role'] === 'admin') {
                header("Location: index.php?action=admin_dashboard");
            } else {
                header("Location: index.php?action=home");
            }
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $user = $this->userModel->getUserByEmail($email);

            if ($user) {
                if ($password === $user['password']) {
                    $_SESSION['user_id'] = $user['id_user'];
                    $_SESSION['nama'] = $user['nama'];
                    $_SESSION['role'] = $user['role'];

                    // Redirect berdasarkan ROLE
                    if ($user['role'] === 'admin') {
                        header("Location: index.php?action=admin_dashboard");
                    } else {
                        header("Location: index.php?action=home");
                    }
                    exit();
                } else {
                    $error = "Password yang Anda masukkan salah!";
                    include 'views/login.php';
                }
            } else {
                $error = "Email tidak terdaftar!";
                include 'views/login.php';
            }
        } else {
            include 'views/login.php';
        }
    }


    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama = trim($_POST['nama']);
            $email = trim($_POST['email']);
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];
            $no_telp = trim($_POST['no_telp']);

            // 1. VALIDASI WAJIB (Kecuali no_telp)
            if (empty($nama) || empty($email) || empty($password) || empty($confirm_password)) {
                $error = "Semua kolom bertanda bintang (*) wajib diisi!";
                include 'views/register.php';
                return;
            }

            // 2. VALIDASI PASSWORD MINIMAL 6 KARAKTER
            if (strlen($password) < 6) {
                $error = "Password minimal harus 6 karakter!";
                include 'views/register.php';
                return;
            }

            // 3. VALIDASI KOMBINASI HURUF DAN ANGKA
            if (!preg_match('/[A-Za-z]/', $password) || !preg_match('/[0-9]/', $password)) {
                $error = "Password harus kombinasi huruf dan angka!";
                include 'views/register.php';
                return;
            }

            // 4. VALIDASI PASSWORD SAMA
            if ($password !== $confirm_password) {
                $error = "Konfirmasi password tidak cocok!";
                include 'views/register.php';
                return;
            }

            // 5. CEK APAKAH EMAIL SUDAH ADA
            if ($this->userModel->emailExists($email)) {
                $error = "Email sudah terdaftar, silakan gunakan email lain.";
                include 'views/register.php';
                return;
            }

            // 6. HANDLE OPSIONAL NO TELEPON
            $no_telp_final = (!empty($no_telp)) ? $no_telp : null;

            // 7. EKSEKUSI REGISTRASI
            if ($this->userModel->register($nama, $email, $password, $no_telp_final)) {
                header("Location: index.php?action=login&status=success");
                exit();
            } else {
                $error = "Terjadi kesalahan sistem saat mendaftar.";
                include 'views/register.php';
            }
        } else {
            include 'views/register.php';
        }
    }

    public function logout()
    {
        session_destroy();
        header("Location: index.php?action=home");
        exit();
    }
}
