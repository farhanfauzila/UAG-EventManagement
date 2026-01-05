<?php
require_once 'models/Event.php';

class EventController {
    private $eventModel;

    public function __construct($db) {
        $this->eventModel = new Event($db);
    }

    public function tampilkanKatalog() {
        $data_event = $this->eventModel->getSemuaEvent(); 
        include 'views/katalog_event.php';
    }

    public function detailEvent($id) {
        $event = $this->eventModel->getEventById($id);
        
        $sudahDaftar = false;
        if (isset($_SESSION['user_id'])) {
            $sudahDaftar = $this->eventModel->cekPendaftaran($_SESSION['user_id'], $id);
        }
        
        include 'views/detail_event.php';
    }

    public function prosesDaftar($id_event) {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit();
        }
    
        $id_user = $_SESSION['user_id'];
        $event = $this->eventModel->getEventById($id_event);
        
        if (!$event) {
            die("Event tidak ditemukan.");
        }
    
        $status_awal = ($event['harga'] == 0) ? 'free' : 'pending';
    
        if ($this->eventModel->cekPendaftaran($id_user, $id_event)) {
            echo "<script>alert('Anda sudah terdaftar!'); window.location.href='index.php?action=detail&id=$id_event';</script>";
            return;
        }
    
        if ($this->eventModel->daftarEvent($id_user, $id_event, $status_awal)) {
            $msg = ($status_awal == 'free') ? 'Pendaftaran Berhasil!' : 'Pendaftaran disimpan. Silakan bayar.';
            echo "<script>alert('$msg'); window.location.href='index.php?action=my_events';</script>";
        } else {
            echo "<script>alert('Gagal mendaftar atau kuota penuh.'); window.location.href='index.php?action=detail&id=$id_event';</script>";
        }
    }

    public function tampilkanEventSaya() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit();
        }
        
        $id_user = $_SESSION['user_id'];
        $my_registrations = $this->eventModel->getRegistrasiByUser($id_user);
        include 'views/my_events.php';
    }

    public function getEventTerbaru($limit) {
        // Memanggil model untuk ambil data terbatas
        return $this->eventModel->getEventTerbaru($limit);
    }

    public function prosesCancel($id_regist) {
        // Pastikan user sudah login
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit();
        }
    
        $id_user = $_SESSION['user_id'];
    
        // Panggil model untuk menghapus
        if ($this->eventModel->hapusPendaftaran($id_regist, $id_user)) {
            echo "<script>alert('Pendaftaran berhasil dibatalkan.'); window.location.href='index.php?action=my_events';</script>";
        } else {
            echo "<script>alert('Gagal membatalkan pendaftaran atau status sudah tidak bisa dibatalkan.'); window.location.href='index.php?action=my_events';</script>";
        }
    }

    public function prosesUploadBukti() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['bukti_file'])) {
            $id_regist = $_POST['id_regist'];
            $file = $_FILES['bukti_file'];
            
            $allowed_types = ['image/jpeg', 'image/png', 'application/pdf'];
            $max_size = 2 * 1024 * 1024; // 2MB
    
            if (in_array($file['type'], $allowed_types) && $file['size'] <= $max_size) {
                $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                $new_name = "BUKTI_" . $_SESSION['user_id'] . "_" . time() . "." . $ext;
                $destination = "public/uploads/bukti_pembayaran/" . $new_name;
    
                if (move_uploaded_file($file['tmp_name'], $destination)) {
                    $this->eventModel->updateBuktiBayar($id_regist, $new_name);
                    echo "<script>alert('Bukti berhasil diupload! Tunggu verifikasi admin.'); window.location.href='index.php?action=my_events';</script>";
                } else {
                    echo "<script>alert('Gagal memindahkan file.');</script>";
                }
            } else {
                echo "<script>alert('Format file tidak didukung atau ukuran terlalu besar!');</script>";
            }
        }
    }

    public function getAllRegistrations() {
        return $this->eventModel->getAllRegistrations();
    }

    public function approvePayment($id_regist) {
        // 1. Update status jadi paid
        if ($this->eventModel->updateStatusBayar($id_regist, 'paid')) {
            echo "<script>alert('Pembayaran disetujui!'); window.location.href='index.php?action=admin_dashboard';</script>";
        }
    }
    
    public function rejectPayment($id_regist) {
        // 1. Hapus nama file bukti di database (set NULL) agar mhs bisa upload ulang
        // 2. Tetapkan status tetap pending
        if ($this->eventModel->rejectStatusBayar($id_regist)) {
            echo "<script>alert('Pembayaran ditolak. Mahasiswa diminta upload ulang.'); window.location.href='index.php?action=admin_dashboard';</script>";
        }
    }

    
} // Kurung kurawal class yang benar