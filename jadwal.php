<?php
session_start();
require_once "config/Database.php";
require_once "models/Jadwal.php";
require_once "models/Kereta.php";

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: auth/login.php");
    exit;
}

$database = new Database();
$db = $database->connect();
$jadwalModel = new Jadwal($db);
$keretaModel = new Kereta($db);

$action = $_GET['action'] ?? null;
$id = $_GET['id'] ?? null;

if ($action == 'delete' && $id) {
    $jadwalModel->deleteJadwal($id);
    header("Location: jadwal.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_kereta = $_POST['id_kereta'];
    $asal = $_POST['asal'];
    $tujuan = $_POST['tujuan'];
    $tanggal_berangkat = $_POST['tanggal_berangkat'];
    $waktu_berangkat = $_POST['waktu_berangkat'];
    $waktu_sampai = $_POST['waktu_sampai'];
    $harga = $_POST['harga'];

    if ($action == 'edit' && $id) {
        $jadwalModel->updateJadwal($id, $id_kereta, $asal, $tujuan, $tanggal_berangkat, $waktu_berangkat, $waktu_sampai, $harga);
        header("Location: jadwal.php");
        exit;
    } else {
        $jadwalModel->createJadwal($id_kereta, $asal, $tujuan, $tanggal_berangkat, $waktu_berangkat, $waktu_sampai, $harga);
        header("Location: jadwal.php");
        exit;
    }
}

$jadwals = $jadwalModel->getAllJadwal();
$keretas = $keretaModel->getAllKereta();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Jadwal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Optional custom styling to make sure sidebar and content are well aligned */
        .content-wrapper {
            margin-left: 250px; /* adjust based on your sidebar width */
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 250px; /* Adjust as needed */
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

    <div class="sidebar">
        <?php include 'sidebar.php'; ?>
    </div>

    <div class="content-wrapper">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4">
        <a class="navbar-brand" href="#">🚆 E-Tiket</a>
        <div class="ms-auto d-flex">
            <span class="navbar-text text-white me-3">Halo, <?= $_SESSION['user']['nama'] ?></span>
            <a href="auth/logout.php" class="btn btn-danger btn-sm">Logout</a>
        </div>
    </nav>

        <div class="container mt-4">
            <h3>Kelola Jadwal</h3>
            <div class="mb-3">
                <a href="jadwal.php?action=create" class="btn btn-primary">Tambah Jadwal</a>
            </div>

            <!-- Form untuk menambah atau mengedit jadwal -->
            <?php if ($action === 'create' || $action === 'edit') : ?>
                <?php
                $jadwal = null;
                if ($action === 'edit' && $id) {
                    $jadwal = $jadwalModel->getJadwalById($id);
                }
                ?>
                <form method="POST">
                    <div class="mb-3">
                        <label for="id_kereta" class="form-label">Kereta</label>
                        <select name="id_kereta" class="form-select" required>
                            <option value="">Pilih Kereta</option>
                            <?php while ($kereta = $keretas->fetch(PDO::FETCH_ASSOC)) : ?>
                                <option value="<?= $kereta['id'] ?>" <?= isset($jadwal['id_kereta']) && $jadwal['id_kereta'] == $kereta['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($kereta['nama_kereta']) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="asal" class="form-label">Asal</label>
                        <input type="text" name="asal" class="form-control" value="<?= $jadwal['asal'] ?? '' ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="tujuan" class="form-label">Tujuan</label>
                        <input type="text" name="tujuan" class="form-control" value="<?= $jadwal['tujuan'] ?? '' ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="tanggal_berangkat" class="form-label">Tanggal Berangkat</label>
                        <input type="date" name="tanggal_berangkat" class="form-control" value="<?= $jadwal['tanggal_berangkat'] ?? '' ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="waktu_berangkat" class="form-label">Waktu Berangkat</label>
                        <input type="time" name="waktu_berangkat" class="form-control" value="<?= $jadwal['waktu_berangkat'] ?? '' ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="waktu_sampai" class="form-label">Waktu Sampai</label>
                        <input type="time" name="waktu_sampai" class="form-control" value="<?= $jadwal['waktu_sampai'] ?? '' ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="harga" class="form-label">Harga</label>
                        <input type="number" step="0.01" name="harga" class="form-control" value="<?= $jadwal['harga'] ?? '' ?>" required>
                    </div>
                    <button type="submit" class="btn btn-success"><?= $action === 'edit' ? 'Update' : 'Tambah' ?> Jadwal</button>
                </form>
            <?php endif; ?>

            <h4 class="mt-4">Daftar Jadwal</h4>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nama Kereta</th>
                        <th>Asal</th>
                        <th>Tujuan</th>
                        <th>Tanggal Berangkat</th>
                        <th>Waktu Berangkat</th>
                        <th>Waktu Sampai</th>
                        <th>Harga</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($jadwal = $jadwals->fetch(PDO::FETCH_ASSOC)) : ?>
                        <tr>
                            <td><?= htmlspecialchars($jadwal['nama_kereta']) ?></td>
                            <td><?= htmlspecialchars($jadwal['asal']) ?></td>
                            <td><?= htmlspecialchars($jadwal['tujuan']) ?></td>
                            <td><?= $jadwal['tanggal_berangkat'] ?></td>
                            <td><?= $jadwal['waktu_berangkat'] ?></td>
                            <td><?= $jadwal['waktu_sampai'] ?></td>
                            <td><?= number_format($jadwal['harga'], 2) ?></td>
                            <td>
                                <a href="jadwal.php?action=edit&id=<?= $jadwal['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="jadwal.php?action=delete&id=<?= $jadwal['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?')">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>
