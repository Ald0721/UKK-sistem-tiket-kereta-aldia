<?php
session_start();
require_once "config/Database.php";
require_once "models/Jadwal.php";

$database = new Database();
$db = $database->connect(); 

$jadwal = new Jadwal($db);
$result = $jadwal->getAllJadwal(); 
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Jadwal Kereta</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f8;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        header {
            background-color: #007BFF;
            color: white;
            padding: 20px 0;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            position: relative;
        }

        .container {
            max-width: 1200px;
            margin: 30px auto;
            background-color: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }

        table {
            width: 100%;
            margin-top: 20px;
        }

        th, td {
            text-align: center;
            padding: 14px 10px;
        }

        th {
            background-color: #343a40;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #eef6ff;
        }

        .top-right {
            position: absolute;
            top: 15px;
            right: 25px;
        }

        @media (max-width: 768px) {
            th, td {
                font-size: 13px;
                padding: 10px 5px;
            }

            .container {
                margin: 10px;
                padding: 15px;
            }
        }
    </style>
</head>
<body>

<header>
    <h1>Jadwal Kereta Api Tersedia</h1>
    <p>Temukan perjalanan kereta yang sesuai untuk Anda</p>
    <div class="top-right">
        <?php if (isset($_SESSION['user'])): ?>
            <span class="text-white me-2">Hai, <?= htmlspecialchars($_SESSION['user']['nama']) ?></span>
            <a href="auth/logout.php" class="btn btn-danger btn-sm">Logout</a>
        <?php else: ?>
            <a href="auth/login.php" class="btn btn-light btn-sm me-2">Login</a>
            <a href="auth/register.php" class="btn btn-warning btn-sm">Register</a>
        <?php endif; ?>
    </div>
</header>

<div class="container">
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Nama Kereta</th>
                <th>Jenis</th>
                <th>Asal</th>
                <th>Tujuan</th>
                <th>Tanggal</th>
                <th>Berangkat</th>
                <th>Sampai</th>
                <th>Harga</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch(PDO::FETCH_ASSOC)) : ?>
                <tr>
                    <td><?= htmlspecialchars($row['nama_kereta']) ?></td>
                    <td><?= htmlspecialchars($row['jenis']) ?></td>
                    <td><?= htmlspecialchars($row['asal']) ?></td>
                    <td><?= htmlspecialchars($row['tujuan']) ?></td>
                    <td><?= date('d M Y', strtotime($row['tanggal_berangkat'])) ?></td>
                    <td><?= htmlspecialchars($row['waktu_berangkat']) ?></td>
                    <td><?= htmlspecialchars($row['waktu_sampai']) ?></td>
                    <td>
                        Rp <?= number_format($row['harga'], 2, ',', '.') ?>
                        <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'user'): ?>
                            <br>
                            <a href="order_form.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-success mt-2">Order Now</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>
