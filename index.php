<?php
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
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f8;
            margin: 0;
            padding: 0;
            color: #333;
        }

        header {
            background-color: #007BFF;
            color: white;
            padding: 20px 0;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .container {
            max-width: 1200px;
            margin: 30px auto;
            background-color: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }

        h1 {
            margin: 0;
            font-size: 28px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            overflow-x: auto;
        }

        th, td {
            padding: 14px 10px;
            text-align: center;
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

        .logout-btn, .login-btn, .register-btn {
            color: white;
        }

        @media screen and (max-width: 768px) {
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
        <div class="position-absolute top-0 end-0 m-3">
            <?php if (isset($_SESSION['user'])): ?>
                <span class="text-white me-2">Hai, <?= $_SESSION['user']['nama'] ?></span>
                <a href="auth/logout.php" class="btn btn-danger logout-btn">Logout</a>
            <?php else: ?>
                <a href="auth/login.php" class="btn btn-outline-light me-2 login-btn">Login</a>
                <a href="auth/register.php" class="btn btn-warning register-btn">Register</a>
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
                        <td><?= htmlspecialchars($row['nama_kereta'] ?? '') ?></td>
                        <td><?= htmlspecialchars($row['jenis'] ?? 'Tidak Diketahui') ?></td>
                        <td><?= htmlspecialchars($row['asal'] ?? '') ?></td>
                        <td><?= htmlspecialchars($row['tujuan'] ?? '') ?></td>
                        <td><?= date('d M Y', strtotime($row['tanggal_berangkat'])) ?></td>
                        <td><?= htmlspecialchars($row['waktu_berangkat'] ?? '') ?></td>
                        <td><?= htmlspecialchars($row['waktu_sampai'] ?? '') ?></td>
                        <td>Rp <?= number_format($row['harga'], 2, ',', '.') ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</body>
</html>
