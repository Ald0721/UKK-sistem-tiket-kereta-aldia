<?php
class User {
    private $conn;
    private $table = "users";

    public function __construct($db) {
        $this->conn = $db;
    }

    // ✅ Register user baru
    public function register($email, $nama, $password, $role = 'user') {
        if ($this->emailExists($email)) return false;

        $query = "INSERT INTO {$this->table} (email, nama, password, role) 
                  VALUES (:email, :nama, :password, :role)";
        $stmt = $this->conn->prepare($query);

        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':nama', $nama);
        $stmt->bindParam(':password', $hashed);
        $stmt->bindParam(':role', $role);

        return $stmt->execute();
    }

    // ✅ Login & ambil user (tanpa password)
    public function login($email, $password) {
        $user = $this->findByEmail($email);
        if ($user && password_verify($password, $user['password'])) {
            unset($user['password']);
            return $user;
        }
        return false;
    }

    // ✅ Cek apakah email sudah ada
    public function emailExists($email) {
        $stmt = $this->conn->prepare("SELECT 1 FROM {$this->table} WHERE email = :email LIMIT 1");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetchColumn() !== false;
    }

    // ✅ Ambil data user berdasarkan ID
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT id, nama, email, role FROM {$this->table} WHERE id = :id LIMIT 1");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ✅ Ambil user berdasarkan email (tambahanmu)
    public function findByEmail($email) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE email = :email LIMIT 1");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
