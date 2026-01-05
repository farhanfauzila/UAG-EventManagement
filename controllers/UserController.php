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

    public function login() {
        if (isset($_SESSION['user_id'])) {
            // Jika sudah login, cek role untuk redirect yang tepat
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
                    // Login Berhasil
                    $_SESSION['user_id'] = $user['id_user'];
                    $_SESSION['nama']    = $user['nama'];
                    $_SESSION['role']    = $user['role'];
    
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

    public function logout() {
        // Jangan session_start lagi karena sudah ada di index.php
        session_destroy();
        header("Location: index.php?action=home");
        exit();
    }

    }

    

    // Fungsi lainnya (index, edit, update, delete) biarkan seperti adanya
    // Hanya pastikan redirect-nya konsisten menggunakan ?action=...
