<?php
class Database {
    private $host = "localhost";
    private $port = "5432";          
    private $db_name = "management_event";    // Sesuaikan dengan nama DB kamu
    private $username = "postgres";  // Biasanya default-nya 'postgres'
    private $password = "1234";   // Masukkan password pgAdmin kamu
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            // Perhatikan perubahan dari "mysql" menjadi "pgsql"
            $dsn = "pgsql:host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->db_name;
            
            $this->conn = new PDO($dsn, $this->username, $this->password);
            
            // Mengatur error mode agar jika ada typo di SQL langsung muncul pesannya
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
?>