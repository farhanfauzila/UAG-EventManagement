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
            $sql = "CALL sp_daftar_event(:id_user, :id_event, :status, NULL)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id_user', $id_user);
            $stmt->bindParam(':id_event', $id_event);
            $stmt->bindParam(':status', $status);
            
            return $stmt->execute();
        } catch (Exception $e) {
            // Jika kuota penuh, PostgreSQL akan melempar EXCEPTION yang ditangkap di sini
            die("Gagal Daftar: " . $e->getMessage()); 
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
        $sql = "SELECT * FROM view_event_user WHERE id_user = :id_user ORDER BY waktu_regist DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_user', $id_user);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function hapusPendaftaran($id_regist, $id_user) {
        // Trigger trg_pendaftaran_batal di DB akan otomatis mengembalikan kuota
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
        $sql = "SELECT * FROM view_pendaftar_event ORDER BY waktu_regist DESC";
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
    
    // Tambahkan $tipe di parameter pertama agar fungsi bisa membaca variabel tersebut
    public function getFilteredEvents($tipe = null, $event_status = null, $payment_type = null)
    {
        // Gunakan CURRENT_DATE untuk PostgreSQL
        $sql = "SELECT *, 
                CASE 
                    WHEN tanggal_selesai < CURRENT_DATE THEN 'completed'
                    ELSE status_event 
                END AS status_tampil
                FROM events WHERE 1=1";
        $params = [];
    
        if (!empty($tipe)) {
            $sql .= " AND tipe_event = :tipe";
            $params[':tipe'] = $tipe;
        }
    
        // FILTER STATUS EVENT
        if (!empty($event_status)) {
            if ($event_status === 'ongoing') {
                $sql .= " AND status_event = 'ongoing' AND tanggal_selesai >= CURRENT_DATE";
            } elseif ($event_status === 'completed') {
                $sql .= " AND (status_event = 'completed' OR tanggal_selesai < CURRENT_DATE)";
            } else {
                $sql .= " AND status_event = :status_event";
                $params[':status_event'] = $event_status;
            }
        }
    
        if (!empty($payment_type)) {
            if ($payment_type === 'free') {
                $sql .= " AND harga = 0";
            } elseif ($payment_type === 'paid') {
                $sql .= " AND harga > 0";
            }
        }
    
        // Gunakan id_event atau status_tampil untuk sorting
        $sql .= " ORDER BY id_event DESC";
    
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllEvents() {
        // Tambahkan CASE WHEN di sini juga agar Admin mendapatkan status otomatis
        $sql = "SELECT *, 
                CASE 
                    WHEN tanggal_selesai < CURRENT_DATE THEN 'completed'
                    ELSE status_event 
                END AS status_tampil
                FROM events 
                ORDER BY id_event DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

public function saveEvent($nama, $desc, $tipe, $tgl_m, $tgl_s, $lok, $kuota, $harga, $poster) {
    $sql = "INSERT INTO events (nama_event, deskripsi, tipe_event, tanggal_mulai, tanggal_selesai, lokasi, kuota, harga, poster_event) 
            VALUES (:nama, :desc, :tipe, :tgl_m, :tgl_s, :lok, :kuota, :harga, :poster)";
    
    $stmt = $this->db->prepare($sql);
    return $stmt->execute([
        ':nama'  => $nama,
        ':desc'  => $desc,
        ':tipe'  => $tipe,
        ':tgl_m' => $tgl_m,
        ':tgl_s' => $tgl_s,
        ':lok'   => $lok,
        ':kuota' => $kuota,
        ':harga' => $harga,
        ':poster'=> $poster
    ]);
}

public function updateEvent($data) {
    $sql = "UPDATE events SET 
            nama_event = :nama, 
            lokasi = :lokasi, 
            kuota = :kuota, 
            harga = :harga, 
            status_event = :status";
    
    // Tambahkan update poster jika ada file baru
    if (isset($data['poster_event'])) {
        $sql .= ", poster_event = :poster";
    }

    $sql .= " WHERE id_event = :id";
    
    $stmt = $this->db->prepare($sql);
    $params = [
        ':nama'   => $data['nama_event'],
        ':lokasi' => $data['lokasi'],
        ':kuota'  => $data['kuota'],
        ':harga'  => $data['harga'],
        ':status' => $data['status_event'],
        ':id'     => $data['id_event']
    ];

    if (isset($data['poster_event'])) {
        $params[':poster'] = $data['poster_event'];
    }

    return $stmt->execute($params);
}

public function updateSertifikat($id_event, $url) {
    $sql = "UPDATE events SET url_sertifikat = :url WHERE id_event = :id_event";
    $stmt = $this->db->prepare($sql);
    return $stmt->execute([
        ':url' => $url,
        ':id_event' => $id_event
    ]);
}

public function deleteEvent($id) {
    try {
        $this->db->beginTransaction();

        // 1. Hapus semua pendaftaran yang terkait dengan event ini dulu
        $sql1 = "DELETE FROM registrations WHERE id_event = :id";
        $stmt1 = $this->db->prepare($sql1);
        $stmt1->execute([':id' => $id]);

        // 2. Baru hapus event utamanya
        $sql2 = "DELETE FROM events WHERE id_event = :id";
        $stmt2 = $this->db->prepare($sql2);
        $stmt2->execute([':id' => $id]);

        $this->db->commit();
        return true;
    } catch (Exception $e) {
        $this->db->rollBack();
        return false;
    }
}


public function getStatistikKeuangan() {
    $this->db->query("REFRESH MATERIALIZED VIEW mv_event_financial_summary");
    $sql = "SELECT * FROM mv_event_financial_summary";
    $stmt = $this->db->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

}