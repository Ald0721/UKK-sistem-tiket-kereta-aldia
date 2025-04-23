<?php
session_start();
require_once "config/Database.php";
require_once "models/Payment.php";

// Cek login
if (!isset($_SESSION['user'])) {
    header("Location: auth/login.php");
    exit;
}

$database = new Database();
$db = $database->connect();
$paymentModel = new Payment($db);

// Ambil hanya pembayaran dengan status "berhasil"
$payments = $paymentModel->getPaymentsByStatus('berhasil');
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Log Pembayaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .content-wrapper {
            margin-left: 250px;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            height: 100%;
            background-color: #343a40;
            padding-top: 20px;
        }

        .sidebar a {
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            display: block;
        }

        .sidebar a:hover {
            background-color: #575757;
        }
    </style>
</head>
<body>
    <?php include 'sidebar.php'; ?>

    <div class="content-wrapper">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4">
            <a class="navbar-brand" href="#">🚆 E-Tiket</a>
            <div class="ms-auto d-flex">
                <span class="navbar-text text-white me-3">Halo, <?= $_SESSION['user']['nama'] ?></span>
                <a href="auth/logout.php" class="btn btn-danger btn-sm">Logout</a>
            </div>
        </nav>

        <div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Log Pembayaran (Berhasil)</h3>
    <a href="cetak_pembayaran.php" target="_blank" class="btn btn-success">Cetak PDF</a>
</div>


            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID Pemesanan</th>
                        <th>Metode Bayar</th>
                        <th>Bukti Bayar</th>
                        <th>Waktu Bayar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($payment = $payments->fetch(PDO::FETCH_ASSOC)) : ?>
                        <tr>
                            <td><?= htmlspecialchars($payment['id_pemesanan']) ?></td>
                            <td><?= htmlspecialchars($payment['metode_bayar']) ?></td>
                            <td><?= htmlspecialchars($payment['bukti_bayar']) ?></td>
                            <td><?= $payment['waktu_bayar'] ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
