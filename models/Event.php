<?php
class Event {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getSemuaEvent() {
        $sql = "SELECT * FROM events ORDER BY id_event DESC";
        $result = $this->db->prepare($sql);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getEventById($id) {
        $sql = "SELECT * FROM events WHERE id_event = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function cekPendaftaran($id_user, $id_event) {
        // Menggunakan nama tabel 'registrations'
        $query = "SELECT id_regist FROM registrations WHERE id_user = :id_user AND id_event = :id_event";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id_user', $id_user);
        $stmt->bindParam(':id_event', $id_event);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function daftarEvent($id_user, $id_event, $status) {
        try {
            $this->db->beginTransaction();

            // 1. Masukkan data ke tabel registrations
            // Tambahkan kutip ganda jika menggunakan PostgreSQL agar tidak error
            $query = "INSERT INTO registrations (id_user, id_event, waktu_regist, status_pembayaran) 
                      VALUES (:id_user, :id_event, NOW(), :status)";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id_user', $id_user);
            $stmt->bindParam(':id_event', $id_event);
            $stmt->bindParam(':status', $status);
            $stmt->execute();

            if ($status == 'free') {
                $queryUpdate = "UPDATE events SET kuota = kuota - 1 WHERE id_event = :id_event";
                $stmtUpdate = $this->db->prepare($queryUpdate);
                $stmtUpdate->bindParam(':id_event', $id_event);
                $stmtUpdate->execute();
            }
    
    
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            // Aktifkan baris di bawah ini HANYA jika ingin melihat detail error saat testing
            die("Error: " . $e->getMessage()); 
            return false;
        }
    }

    public function cancelPendaftaran($id_regist, $id_user) {
        // Pastikan hanya bisa cancel jika status masih pending dan milik user ybs
        $query = "DELETE FROM registrations WHERE id_regist = :id_regist AND id_user = :id_user AND status_pembayaran = 'pending'";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id_regist', $id_regist);
        $stmt->bindParam(':id_user', $id_user);
        return $stmt->execute();
    }

    public function getRegistrasiByUser($id_user) {
    // Tambahkan e.tanggal_selesai ke dalam deretan SELECT
    $sql = "SELECT r.*, e.nama_event, e.harga, e.tanggal_mulai, e.tanggal_selesai, e.lokasi, e.info_bayar, e.status_event
            FROM registrations r 
            JOIN events e ON r.id_event = e.id_event 
            WHERE r.id_user = :id_user 
            ORDER BY r.waktu_regist DESC";
    
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id_user', $id_user);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
    
public function hapusPendaftaran($id_regist, $id_user) {
    // Hanya hapus jika id_regist milik user tersebut DAN statusnya masih pending
    $sql = "DELETE FROM registrations 
            WHERE id_regist = :id_regist 
            AND id_user = :id_user 
            AND status_pembayaran = 'pending'";
            
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id_regist', $id_regist);
    $stmt->bindParam(':id_user', $id_user);
    
    return $stmt->execute();
}

    public function getEventTerbaru($limit) {
        $sql = "SELECT * FROM events ORDER BY id_event DESC LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateBuktiBayar($id_regist, $filename) {
        $sql = "UPDATE registrations SET bukti_pembayaran = :bukti WHERE id_regist = :id_regist";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':bukti', $filename);
        $stmt->bindParam(':id_regist', $id_regist);
        return $stmt->execute();
    }

    public function getAllRegistrations() {
        $sql = "SELECT r.*, u.nama as nama_mhs, e.nama_event 
                FROM registrations r
                JOIN users u ON r.id_user = u.id_user
                JOIN events e ON r.id_event = e.id_event
                ORDER BY r.waktu_regist DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateStatusBayar($id_regist, $status) {
        $sql = "UPDATE registrations SET status_pembayaran = :status WHERE id_regist = :id_regist";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id_regist', $id_regist);
        return $stmt->execute();
    }

    public function rejectStatusBayar($id_regist) {
        // Kosongkan kolom bukti_pembayaran agar dianggap belum upload
        $sql = "UPDATE registrations SET bukti_pembayaran = NULL WHERE id_regist = :id_regist";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_regist', $id_regist);
        return $stmt->execute();
    }
    
    public function getFilteredEvents($event_status = null, $payment_type = null)
{
    $sql = "SELECT * FROM events WHERE 1=1";
    $params = [];

    // FILTER STATUS EVENT
    if (!empty($event_status)) {
        $sql .= " AND status_event = :status_event";
        $params[':status_event'] = $event_status;
    }

    // FILTER TIPE PEMBAYARAN
    if (!empty($payment_type)) {
        if ($payment_type === 'free') {
            $sql .= " AND harga = 0";
        } elseif ($payment_type === 'paid') {
            $sql .= " AND harga > 0";
        }
    }

    $sql .= " ORDER BY id_event DESC";

    $stmt = $this->db->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

}