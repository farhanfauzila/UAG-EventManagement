<?php
class User {
    public $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // =====================
    // Ambil user by email
    // =====================
    public function getUserByEmail($email) {
        $query = "SELECT * FROM users WHERE email = :email";
        $stmt  = $this->db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // =====================
    // Cek email sudah ada
    // =====================
    public function emailExists($email) {
        $query = "SELECT id_user FROM users WHERE email = :email";
        $stmt  = $this->db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    // =====================
    // Register user baru
    // =====================
    public function register($nama, $email, $password, $no_telp) {

        // HASH PASSWORD (PENTING)
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO users (nama, email, password, no_telp, role)
                  VALUES (:nama, :email, :password, :no_telp, 'peserta')";

        $stmt = $this->db->prepare($query);

        return $stmt->execute([
            ':nama'     => $nama,
            ':email'    => $email,
            ':password' => $hashedPassword,
            ':no_telp'  => $no_telp
        ]);
    }
}
