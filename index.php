<?php
session_start();

require_once 'config/database.php';
require_once 'models/User.php';
require_once 'models/Event.php';
require_once 'controllers/EventController.php';
require_once 'controllers/UserController.php';

// Koneksi Database
$database = new Database();
$db = $database->getConnection();

// Inisialisasi Controller
$eventApp = new EventController($db);
$userApp = new UserController($db);

// Parameter URL
$action = isset($_GET['action']) ? $_GET['action'] : 'home';
$id = isset($_GET['id']) ? $_GET['id'] : null;

include 'views/navbar.php';

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
        $id_regist = $_GET['id'] ?? null;
        if ($id_regist) {
            $eventApp->prosesCancel($id_regist);
        }
        break;

    case 'upload_bukti':
        $eventApp->prosesUploadBukti();
        break;

    case 'admin_dashboard':
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("Location: index.php?action=home");
            exit();
        }

        $pendaftaran = $eventApp->getAllRegistrations();
        $events = $eventApp->getAllEvents();
        $statistik = $eventApp->getStatistikKeuangan();

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

    case 'create_event':
        $eventApp->create_event();
        break;

    case 'update_event':
        $eventApp->update_event();
        break;

    case 'upload_sertifikat':
        $eventApp->upload_sertifikat();
        break;

    case 'delete_event':
        $eventApp->delete_event();
        break;

    default:
        include 'views/home.php';
        break;
}