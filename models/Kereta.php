<?php
class Kereta {
    private $conn;
    private $table = "kereta";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function createKereta($nama_kereta, $jenis, $kapasitas) {
        $query = "INSERT INTO " . $this->table . " (nama_kereta, jenis, kapasitas) VALUES (:nama_kereta, :jenis, :kapasitas)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nama_kereta', $nama_kereta);
        $stmt->bindParam(':jenis', $jenis);
        $stmt->bindParam(':kapasitas', $kapasitas);
        return $stmt->execute();
    }

    public function getAllKereta() {
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function getKeretaById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateKereta($id, $nama_kereta, $jenis, $kapasitas) {
        $query = "UPDATE " . $this->table . " SET nama_kereta = :nama_kereta, jenis = :jenis, kapasitas = :kapasitas WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':nama_kereta', $nama_kereta);
        $stmt->bindParam(':jenis', $jenis);
        $stmt->bindParam(':kapasitas', $kapasitas);
        return $stmt->execute();
    }

    public function deleteKereta($id) {
        // Hapus semua jadwal yang terkait dengan kereta
        $query = "DELETE FROM jadwal WHERE id_kereta = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    
        // Hapus data kereta setelah jadwal terkait dihapus
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}    
