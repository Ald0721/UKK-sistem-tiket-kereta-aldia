<?php
session_start();
require_once "config/Database.php";
require_once "models/User.php";
require_once "models/Payment.php";
require_once "models/Pemesanan.php";

if (!isset($_SESSION['user'])) {
    header("Location: auth/login.php");
    exit;
}

$user = $_SESSION['user'];
$role = $user['role'];

$database = new Database();
$db = $database->connect();

$paymentModel = new Payment($db);
$pemesananModel = new Pemesanan($db);

// Statistik pembayaran
$paymentStats = $paymentModel->getMonthlyStats();

// Validasi pembayaran (admin)
if ($role === 'admin') {
    // Proses aksi validasi / tolak via GET
    if (isset($_GET['action'], $_GET['id']) && is_numeric($_GET['id'])) {
        $paymentId = $_GET['id'];
        if ($_GET['action'] === 'validate') {
            $paymentModel->updateStatus($paymentId, 'berhasil');
        } elseif ($_GET['action'] === 'reject') {
            $paymentModel->updateStatus($paymentId, 'gagal');
        }
        header("Location: dashboard.php");
        exit;
    }

    // Ambil daftar pembayaran pending
    $pendingPayments = $paymentModel->getPendingPayments();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - <?= ucfirst($role) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

<!-- Sidebar -->
<?php include 'sidebar.php'; ?>

<div class="content-wrapper">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4">
        <a class="navbar-brand" href="#">🚆 E-Tiket</a>
        <div class="ms-auto d-flex">
            <span class="navbar-text text-white me-3">Halo, <?= htmlspecialchars($_SESSION['user']['nama']) ?></span>
        </div>
    </nav>

    <div class="container mt-4">
        <?php if ($role === 'admin'): ?>
            <h3 class="mb-4">Dashboard Admin</h3>

            <!-- Statistik Pembayaran -->
            <div class="mt-5">
                <h5>Statistik Pembayaran Bulanan</h5>
                <canvas id="paymentChart" height="100"></canvas>
            </div>

            <script>
                const ctx = document.getElementById('paymentChart');
                const paymentChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: <?= json_encode(array_column($paymentStats, 'bulan')) ?>,
                        datasets: [{
                            label: 'Total Pembayaran (Rp)',
                            data: <?= json_encode(array_column($paymentStats, 'jumlah')) ?>,
                            backgroundColor: 'rgba(13, 110, 253, 0.7)'
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: { legend: { display: false } },
                        scales: { y: { beginAtZero: true } }
                    }
                });
            </script>

            <!-- Validasi Pembayaran -->
            <div class="mt-5">
                <h5>Validasi Order (Pembayaran Pending)</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID Pemesanan</th>
                            <th>Metode</th>
                            <th>Bukti</th>
                            <th>Waktu Bayar</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($pending = $pendingPayments->fetch(PDO::FETCH_ASSOC)) : ?>
                            <tr>
                                <td><?= htmlspecialchars($pending['id_pemesanan']) ?></td>
                                <td><?= htmlspecialchars($pending['metode_bayar']) ?></td>
                                <td>
                                    <?php if ($pending['bukti_bayar']): ?>
                                        <a href="uploads/<?= $pending['bukti_bayar'] ?>" target="_blank">Lihat</a>
                                    <?php else: ?>
                                        <em>Tidak ada</em>
                                    <?php endif; ?>
                                </td>
                                <td><?= $pending['waktu_bayar'] ?></td>
                                <td><?= $pending['status_proses'] ?></td>
                                <td>
                                    <a href="dashboard.php?action=validate&id=<?= $pending['id'] ?>" class="btn btn-success btn-sm">✔ Validasi</a>
                                    <a href="dashboard.php?action=reject&id=<?= $pending['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Tolak pembayaran ini?')">✘ Tolak</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

        <?php elseif ($role === 'user'): ?>
            <h3 class="mb-4">Dashboard Pengguna</h3>
            <p>Selamat datang di dashboard pengguna Anda. Di sini Anda dapat melihat status pemesanan dan informasi lainnya.</p>
        <?php endif; ?>
    </div>
</div>

<?php include 'footer.php'; ?>
</body>
</html>
