<?php
class Jadwal {
    private $conn;
    private $table = "jadwal";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function createJadwal($id_kereta, $asal, $tujuan, $tanggal_berangkat, $waktu_berangkat, $waktu_sampai, $harga) {
        $query = "INSERT INTO " . $this->table . " (id_kereta, asal, tujuan, tanggal_berangkat, waktu_berangkat, waktu_sampai, harga) VALUES (:id_kereta, :asal, :tujuan, :tanggal_berangkat, :waktu_berangkat, :waktu_sampai, :harga)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_kereta', $id_kereta);
        $stmt->bindParam(':asal', $asal);
        $stmt->bindParam(':tujuan', $tujuan);
        $stmt->bindParam(':tanggal_berangkat', $tanggal_berangkat);
        $stmt->bindParam(':waktu_berangkat', $waktu_berangkat);
        $stmt->bindParam(':waktu_sampai', $waktu_sampai);
        $stmt->bindParam(':harga', $harga);
        return $stmt->execute();
    }

    public function getAllJadwal() {
        $query = "SELECT jadwal.*, kereta.nama_kereta, kereta.jenis 
                  FROM " . $this->table . " 
                  JOIN kereta ON jadwal.id_kereta = kereta.id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    

    public function getById($id) {
        $query = "SELECT jadwal.*, kereta.nama_kereta, kereta.jenis 
                  FROM " . $this->table . " 
                  JOIN kereta ON jadwal.id_kereta = kereta.id 
                  WHERE jadwal.id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function getJadwalById($id) {
        $query = "SELECT jadwal.*, kereta.nama_kereta, kereta.jenis 
                  FROM " . $this->table . " 
                  JOIN kereta ON jadwal.id_kereta = kereta.id 
                  WHERE jadwal.id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    
    public function updateJadwal($id, $id_kereta, $asal, $tujuan, $tanggal_berangkat, $waktu_berangkat, $waktu_sampai, $harga) {
        $query = "UPDATE " . $this->table . " SET id_kereta = :id_kereta, asal = :asal, tujuan = :tujuan, tanggal_berangkat = :tanggal_berangkat, waktu_berangkat = :waktu_berangkat, waktu_sampai = :waktu_sampai, harga = :harga WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':id_kereta', $id_kereta);
        $stmt->bindParam(':asal', $asal);
        $stmt->bindParam(':tujuan', $tujuan);
        $stmt->bindParam(':tanggal_berangkat', $tanggal_berangkat);
        $stmt->bindParam(':waktu_berangkat', $waktu_berangkat);
        $stmt->bindParam(':waktu_sampai', $waktu_sampai);
        $stmt->bindParam(':harga', $harga);
        return $stmt->execute();
    }

    public function deleteJadwal($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
    public function searchJadwal($keyword) {
        $query = "
            SELECT j.*, k.nama AS nama_kereta, k.jenis 
            FROM {$this->table} j
            JOIN kereta k ON j.id_kereta = k.id
            WHERE k.nama LIKE :keyword OR j.asal LIKE :keyword OR j.tujuan LIKE :keyword
            ORDER BY j.tanggal_berangkat ASC
        ";
    
        $stmt = $this->conn->prepare($query);
        $keyword = "%{$keyword}%";
        $stmt->bindParam(':keyword', $keyword);
        $stmt->execute();
    
        return $stmt;
    }
    
}
