<?php
include_once 'models/User.php';

class UserController {
    private $userModel;

    public function __construct($db) {
        if ($db === null) {
            die("Koneksi database ke UserController gagal!");
        }
        $this->userModel = new User($db);
    }

    // =====================
    // LOGIN
    // =====================
    public function login() {
        if (isset($_SESSION['user_id'])) {
            if ($_SESSION['role'] === 'admin') {
                header("Location: index.php?action=admin_dashboard");
            } else {
                header("Location: index.php?action=home");
            }
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email    = $_POST['email'];
            $password = $_POST['password'];

            $user = $this->userModel->getUserByEmail($email);

            if ($user && password_verify($password, $user['password'])) {

                $_SESSION['user_id'] = $user['id_user'];
                $_SESSION['nama']    = $user['nama'];
                $_SESSION['role']    = $user['role'];

                if ($user['role'] === 'admin') {
                    header("Location: index.php?action=admin_dashboard");
                } else {
                    header("Location: index.php?action=home");
                }
                exit();

            } else {
                $error = "Email atau password salah!";
                include 'views/login.php';
            }

        } else {
            include 'views/login.php';
        }
    }

    // =====================
    // REGISTER
    // =====================
    public function register() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $nama     = trim($_POST['nama']);
        $email    = trim($_POST['email']);
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];
        $no_telp  = trim($_POST['no_telp']);

        // VALIDASI WAJIB
        if (empty($nama) || empty($email) || empty($password)) {
            $error = "Nama, email, dan password wajib diisi!";
            include 'views/register.php';
            return;
        }

        // VALIDASI PASSWORD
        if (strlen($password) < 6) {
            $error = "Password minimal 6 karakter!";
            include 'views/register.php';
            return;
        }

        if (!preg_match('/[A-Za-z]/', $password) || !preg_match('/[0-9]/', $password)) {
            $error = "Password harus mengandung huruf dan angka!";
            include 'views/register.php';
            return;
        }

        // VALIDASI PASSWORD SAMA
        if ($password !== $confirm_password) {
            $error = "Konfirmasi password tidak cocok!";
            include 'views/register.php';
            return;
        }

        if ($this->userModel->emailExists($email)) {
            $error = "Email sudah terdaftar!";
            include 'views/register.php';
            return;
        }

        $no_telp = ($no_telp === '') ? null : $no_telp;

        if ($this->userModel->register($nama, $email, $password, $no_telp)) {
            header("Location: index.php?action=login");
            exit();
        } else {
            $error = "Registrasi gagal!";
            include 'views/register.php';
        }

    } else {
        include 'views/register.php';
    }
}



    // =====================
    // LOGOUT
    // =====================
    public function logout() {
        session_destroy();
        header("Location: index.php?action=home");
        exit();
    }
}
