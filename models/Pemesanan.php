<?php
class Pemesanan {
    private $conn;
    private $table = "pemesanan";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $stmt = $this->conn->prepare("
            SELECT p.*, u.nama, j.asal, j.tujuan 
            FROM {$this->table} p
            JOIN users u ON p.id_user = u.id
            JOIN jadwal j ON p.id_jadwal = j.id
            ORDER BY p.waktu_pemesanan DESC
        ");
        $stmt->execute();
        return $stmt;
    }

    public function getByUserId($id_user) {
        $stmt = $this->conn->prepare("
            SELECT 
                p.*, 
                j.asal, j.tujuan, j.tanggal_berangkat, j.waktu_berangkat, j.harga 
            FROM {$this->table} p 
            JOIN jadwal j ON p.id_jadwal = j.id
            WHERE p.id_user = :id_user
            ORDER BY p.waktu_pemesanan DESC
        ");
        $stmt->bindParam(':id_user', $id_user);
        $stmt->execute();
        return $stmt;
    }
    
    public function create($id_user, $id_jadwal, $jumlah_tiket, $total_bayar, $status_bayar = 'belum bayar') {
        $stmt = $this->conn->prepare("INSERT INTO {$this->table} (id_user, id_jadwal, jumlah_tiket, total_bayar, status_bayar) VALUES (:user, :jadwal, :jumlah, :total, :status)");
        $stmt->bindParam(':user', $id_user);
        $stmt->bindParam(':jadwal', $id_jadwal);
        $stmt->bindParam(':jumlah', $jumlah_tiket);
        $stmt->bindParam(':total', $total_bayar);
        $stmt->bindParam(':status', $status_bayar);
        return $stmt->execute();
    }

    public function updateStatusBayar($id_pemesanan, $status_bayar) {
        $query = "UPDATE pemesanan SET status_bayar = :status_bayar WHERE id = :id_pemesanan";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':id_pemesanan', $id_pemesanan);
        $stmt->bindParam(':status_bayar', $status_bayar);
        
        return $stmt->execute();
    }


    public function getAllPemesanan() {
        $query = "
            SELECT 
                pemesanan.*,
                users.nama AS nama_user,
                jadwal.asal,
                jadwal.tujuan,
                jadwal.tanggal_berangkat,
                jadwal.waktu_berangkat,
                kereta.nama_kereta
            FROM pemesanan
            JOIN users ON pemesanan.id_user = users.id
            JOIN jadwal ON pemesanan.id_jadwal = jadwal.id
            JOIN kereta ON jadwal.id_kereta = kereta.id
            ORDER BY pemesanan.waktu_pemesanan DESC
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    public function createPemesanan($id_user, $id_jadwal, $jumlah_tiket, $total_bayar, $status_bayar) {
        $query = "INSERT INTO pemesanan (id_user, id_jadwal, jumlah_tiket, total_bayar, status_bayar) 
                  VALUES (:id_user, :id_jadwal, :jumlah_tiket, :total_bayar, :status_bayar)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_user', $id_user);
        $stmt->bindParam(':id_jadwal', $id_jadwal);
        $stmt->bindParam(':jumlah_tiket', $jumlah_tiket);
        $stmt->bindParam(':total_bayar', $total_bayar);
        $stmt->bindParam(':status_bayar', $status_bayar);
    
        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT p.*, u.nama AS nama_user, j.asal, j.tujuan, j.tanggal_berangkat, j.waktu_berangkat, j.harga
                                     FROM {$this->table} p
                                     JOIN users u ON p.id_user = u.id
                                     JOIN jadwal j ON p.id_jadwal = j.id
                                     WHERE p.id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
}
