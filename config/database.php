<?php
class Database {
    private $host = "localhost";
    private $port = "5432";
    private $db_name = "management_event";
    private $username = "postgres";
    private $password = "1234"; 
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->db_name}";
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            die("DB ERROR: " . $e->getMessage());
        }
        return $this->conn;
    }
}
