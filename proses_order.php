<?php
session_start();
require_once "config/Database.php";
require_once "models/Payment.php";
require_once "models/Pemesanan.php";
require_once "models/Jadwal.php";

// Cek login
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_user = $_POST['id_user'] ?? null;
    $id_jadwal = $_POST['id_jadwal'] ?? null;
    $jumlah_tiket = $_POST['jumlah_tiket'] ?? null;
    $metode_bayar = $_POST['metode_bayar'] ?? null;
    $bukti_bayar = $_FILES['bukti_bayar'] ?? null;

    $nama_penumpang = $_POST['nama_penumpang'] ?? [];
    $nomor_kursi = $_POST['nomor_kursi'] ?? [];

    // Validasi input
    if (!$id_user || !$id_jadwal || !$jumlah_tiket || !$metode_bayar || !$bukti_bayar) {
        die("Data tidak lengkap.");
    }

    if (count($nama_penumpang) !== (int)$jumlah_tiket || count($nomor_kursi) !== (int)$jumlah_tiket) {
        die("Jumlah penumpang atau kursi tidak sesuai.");
    }

    // Koneksi DB
    $database = new Database();
    $db = $database->connect();

    // Cek jadwal
    $jadwalModel = new Jadwal($db);
    $jadwal = $jadwalModel->getJadwalById($id_jadwal);
    if (!$jadwal) {
        die("Jadwal tidak ditemukan.");
    }

    // Hitung total
    $total_bayar = $jadwal['harga'] * $jumlah_tiket;

    // Simpan pemesanan
    $pemesananModel = new Pemesanan($db);
    $id_pemesanan = $pemesananModel->createPemesanan(
        $id_user,
        $id_jadwal,
        $jumlah_tiket,
        $total_bayar,
        'belum bayar'
    );

    if ($id_pemesanan) {
        // Upload bukti bayar
        $upload_dir = "uploads/";
        $file_name = time() . "_" . basename($bukti_bayar['name']);
        $bukti_file = $upload_dir . $file_name;

        if (!move_uploaded_file($bukti_bayar['tmp_name'], $bukti_file)) {
            die("Gagal upload bukti pembayaran.");
        }

        // Simpan pembayaran
        $paymentModel = new Payment($db);
        $paymentModel->simpanPembayaran(
            $id_pemesanan,
            $metode_bayar,
            'pending',
            $file_name,
            date("Y-m-d H:i:s")
        );

        // Simpan penumpang
        $stmt = $db->prepare("INSERT INTO penumpang (id_pemesanan, nama_penumpang, nomor_kursi) VALUES (?, ?, ?)");
        for ($i = 0; $i < $jumlah_tiket; $i++) {
            $stmt->execute([
                $id_pemesanan,
                htmlspecialchars($nama_penumpang[$i]),
                htmlspecialchars($nomor_kursi[$i])
            ]);
        }

        // Redirect ke halaman sukses
        header("Location: success_order.php");
        exit;
    } else {
        die("Gagal menyimpan pemesanan.");
    }
}
?>
