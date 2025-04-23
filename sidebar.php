<?php
if (!isset($_SESSION)) session_start();
$user = $_SESSION['user'];
$role = $user['role'];
?>

<style>
    .sidebar {
        height: 100%;
        width: 250px;
        position: fixed;
        top: 0;
        left: 0;
        background-color: #343a40;
        color: white;
        padding-top: 20px;
    }

    .sidebar a {
        color: white;
        padding: 10px;
        text-decoration: none;
        display: block;
    }

    .sidebar a:hover {
        background-color: #007BFF;
    }

    .content {
        margin-left: 250px;
        padding: 20px;
        flex-grow: 1;
    }
</style>

<!-- Sidebar -->
<div class="sidebar">
    <h3 class="text-center text-white mb-4">E-Tiket</h3>
    <a href="dashboard.php">Dashboard</a>

    <?php if ($role === 'admin'): ?>
        <h5 class="text-white ms-3">Kelola</h5>
        <a href="kereta.php">🚄 Kelola Kereta</a>
        <a href="jadwal.php">🗓️ Kelola Jadwal</a>
        <a href="payment.php">💳 Log Pembayaran</a>
        <a href="auth/logout.php" class="btn btn-danger btn-sm">Logout</a>
    <?php elseif ($role === 'user'): ?>
        <a href="order_history.php">📄 Riwayat Pemesanan</a>
        <a href="jadwal_kereta.php">🚆 Lihat Jadwal</a>
        <a href="auth/logout.php" class="btn btn-danger btn-sm">Logout</a>
    <?php endif; ?>
</div>
