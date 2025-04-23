<?php
class Payment {
    private $conn;
    private $table = "payment";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function createPayment($id_pemesanan, $metode_bayar, $status_proses, $bukti_bayar) {
        try {
            $query = "INSERT INTO " . $this->table . " (id_pemesanan, metode_bayar, status_proses, bukti_bayar) 
                      VALUES (:id_pemesanan, :metode_bayar, :status_proses, :bukti_bayar)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_pemesanan', $id_pemesanan);
            $stmt->bindParam(':metode_bayar', $metode_bayar);
            $stmt->bindParam(':status_proses', $status_proses);
            $stmt->bindParam(':bukti_bayar', $bukti_bayar);

            if ($stmt->execute()) {
                return $this->conn->lastInsertId(); // Return the ID of the newly created payment
            } else {
                return false;
            }
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }


    public function getAllPayments() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY waktu_bayar DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    public function getMonthlyStats() {
        $query = "
            SELECT 
                DATE_FORMAT(waktu_bayar, '%Y-%m') AS bulan,
                COUNT(*) AS total_transaksi,
                SUM(CASE WHEN status_proses = 'berhasil' THEN 1 ELSE 0 END) AS sukses,
                SUM(CASE WHEN status_proses = 'gagal' THEN 1 ELSE 0 END) AS gagal
            FROM " . $this->table . "
            GROUP BY bulan
            ORDER BY bulan DESC
            LIMIT 12
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getPaymentsByStatus($status)
{
    $query = "SELECT * FROM payment WHERE status_proses = :status ORDER BY waktu_bayar DESC";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':status', $status);
    $stmt->execute();
    return $stmt;
}
public function getPendingPayments()
{
    $query = "SELECT * FROM payment WHERE status_proses = 'pending' ORDER BY waktu_bayar ASC";
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt;
}

public function updateStatus($id, $status)
{
    $query = "UPDATE payment SET status_proses = :status WHERE id = :id";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':id', $id);
    return $stmt->execute();
}
public function simpanPembayaran($id_pemesanan, $metode_bayar, $status_proses, $bukti_bayar, $waktu_bayar) {
    $query = "INSERT INTO " . $this->table . " (id_pemesanan, metode_bayar, status_proses, bukti_bayar, waktu_bayar) 
              VALUES (:id_pemesanan, :metode_bayar, :status_proses, :bukti_bayar, :waktu_bayar)";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':id_pemesanan', $id_pemesanan);
    $stmt->bindParam(':metode_bayar', $metode_bayar);
    $stmt->bindParam(':status_proses', $status_proses);
    $stmt->bindParam(':bukti_bayar', $bukti_bayar);
    $stmt->bindParam(':waktu_bayar', $waktu_bayar);

    return $stmt->execute();
}

}
