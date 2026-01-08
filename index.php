<?php
session_start(); // Wajib paling atas

// 1. Load semua dependensi
require_once 'config/database.php';
require_once 'models/User.php';
require_once 'models/Event.php';
require_once 'controllers/EventController.php';
require_once 'controllers/UserController.php';

// 2. Koneksi Database
$database = new Database();
$db = $database->getConnection();

// 3. Inisialisasi Controller
$eventApp = new EventController($db);
$userApp  = new UserController($db);

// 4. Tangkap Parameter URL
$action = isset($_GET['action']) ? $_GET['action'] : 'home';
$id     = isset($_GET['id']) ? $_GET['id'] : null;

// 5. Tampilkan Navbar (Muncul di semua halaman)
include 'views/navbar.php';

// 6. Routing Utama
switch ($action) {
    case 'home':
        $events_terbaru = $eventApp->getEventTerbaru(3);
        include 'views/home.php';
        break;

    case 'katalog':
        $eventApp->tampilkanKatalog();
        break;

    case 'detail':
        $eventApp->detailEvent($id);
        break;

    case 'my_events':
        // Halaman ini otomatis dicek login-nya di dalam controller
        $eventApp->tampilkanEventSaya();
        break;

    case 'register':
        $userApp->register();
        break;

    case 'proses_daftar':
        $eventApp->prosesDaftar($id);
        break;

    case 'login':
        $userApp->login();
        break;

    case 'logout':
        $userApp->logout();
        break;

        case 'cancel_reg':
            // Mengambil ID registrasi dari URL
            $id_regist = $_GET['id'] ?? null;
            if ($id_regist) {
                $eventApp->prosesCancel($id_regist);
            }
            break;
    
    case 'upload_bukti':
    $eventApp->prosesUploadBukti();
    break;

    case 'admin_dashboard':
        // Proteksi: Hanya admin yang boleh masuk
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("Location: index.php?action=home");
            exit();
        }
        // Ambil semua data pendaftaran yang perlu diverifikasi
        $pendaftaran = $eventApp->getAllRegistrations(); 
        include 'views/admin_dashboard.php';
        break;

        case 'approve_payment':
            if ($_SESSION['role'] === 'admin') {
                $eventApp->approvePayment($_GET['id']);
            }
            break;
        
        case 'reject_payment':
            if ($_SESSION['role'] === 'admin') {
                $eventApp->rejectPayment($_GET['id']);
            }
            break;

    default:
        // Jika action tidak dikenal, lari ke home
        include 'views/home.php';
        break;
}