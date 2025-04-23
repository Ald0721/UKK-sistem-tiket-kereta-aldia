<?php
session_start();
require_once "config/Database.php";
require_once "models/Kereta.php";

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: auth/login.php");
    exit;
}

$database = new Database();
$db = $database->connect();
$keretaModel = new Kereta($db);

$action = $_GET['action'] ?? null;
$id = $_GET['id'] ?? null;

if ($action == 'delete' && $id) {
    $keretaModel->deleteKereta($id);
    header("Location: kereta.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_kereta = $_POST['nama_kereta'];
    $jenis = $_POST['jenis'];
    $kapasitas = $_POST['kapasitas'];

    if ($action == 'edit' && $id) {
        $keretaModel->updateKereta($id, $nama_kereta, $jenis, $kapasitas);
        header("Location: kereta.php");
        exit;
    } else {
        $keretaModel->createKereta($nama_kereta, $jenis, $kapasitas);
        header("Location: kereta.php");
        exit;
    }
}

$keretas = $keretaModel->getAllKereta();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Kereta</title>
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

<?php include 'sidebar.php'; ?>

<div class="content-wrapper">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4">
        <a class="navbar-brand" href="#">🚆 E-Tiket</a>
        <div class="ms-auto d-flex">
            <span class="navbar-text text-white me-3">Halo, <?= $_SESSION['user']['nama'] ?></span>
            <a href="auth/logout.php" class="btn btn-danger btn-sm">Logout</a>
        </div>
    </nav>

    <div class="container mt-4">
        <h3>Kelola Kereta</h3>
        <div class="mb-3">
            <a href="kereta.php?action=create" class="btn btn-primary">Tambah Kereta</a>
        </div>

        <!-- Add or Edit Kereta Form -->
        <?php if ($action === 'create' || $action === 'edit') : ?>
            <?php
            $kereta = null;
            if ($action === 'edit' && $id) {
                $kereta = $keretaModel->getKeretaById($id);
            }
            ?>
            <form method="POST">
                <div class="mb-3">
                    <label for="nama_kereta" class="form-label">Nama Kereta</label>
                    <input type="text" name="nama_kereta" id="nama_kereta" class="form-control" value="<?= $kereta['nama_kereta'] ?? '' ?>" required>
                </div>
                <div class="mb-3">
                    <label for="jenis" class="form-label">Jenis</label>
                    <select name="jenis" id="jenis" class="form-select" required>
                        <option value="vip" <?= isset($kereta['jenis']) && $kereta['jenis'] === 'vip' ? 'selected' : '' ?>>VIP</option>
                        <option value="bisnis" <?= isset($kereta['jenis']) && $kereta['jenis'] === 'bisnis' ? 'selected' : '' ?>>Bisnis</option>
                        <option value="ekonomi" <?= isset($kereta['jenis']) && $kereta['jenis'] === 'ekonomi' ? 'selected' : '' ?>>Ekonomi</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="kapasitas" class="form-label">Kapasitas</label>
                    <input type="number" name="kapasitas" id="kapasitas" class="form-control" value="<?= $kereta['kapasitas'] ?? '' ?>" required>
                </div>
                <button type="submit" class="btn btn-success"><?= $action === 'edit' ? 'Update' : 'Tambah' ?> Kereta</button>
            </form>
        <?php endif; ?>

        <h4 class="mt-4">Daftar Kereta</h4>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nama Kereta</th>
                    <th>Jenis</th>
                    <th>Kapasitas</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($kereta = $keretas->fetch(PDO::FETCH_ASSOC)) : ?>
                    <tr>
                        <td><?= htmlspecialchars($kereta['nama_kereta']) ?></td>
                        <td><?= htmlspecialchars($kereta['jenis']) ?></td>
                        <td><?= htmlspecialchars($kereta['kapasitas']) ?></td>
                        <td>
                            <a href="kereta.php?action=edit&id=<?= $kereta['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="kereta.php?action=delete&id=<?= $kereta['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus kereta ini?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
