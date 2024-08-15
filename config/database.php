<?php
class Database {
    private $host = "localhost";
    private $db_name = "admin_panel";
    private $username = "root";
    private $password = "";
    public $conn;
    public function getConnection() {

    $this->conn = null;
    try {
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->db_name);
        if ($this->conn->connect_error) {
            throw new Exception("Connection error: " . $this->conn->connect_error);
        }
    } catch (Exception $exception) {
        echo $exception->getMessage();
    }
    return $this->conn;
}
}
