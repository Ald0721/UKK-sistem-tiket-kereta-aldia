<?php
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pemesanan Berhasil</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            text-align: center;
            padding: 50px;
        }
        .container {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            max-width: 500px;
            margin: 0 auto;
            padding: 40px;
        }
        h1 {
            color: #4CAF50;
            margin-bottom: 20px;
        }
        p {
            font-size: 18px;
            color: #333;
        }
        .btn {
            margin-top: 30px;
            background: #4CAF50;
            color: white;
            padding: 10px 25px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
        }
        .btn:hover {
            background: #45a049;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>✅ Pemesanan Berhasil!</h1>
    <p>Terima kasih telah melakukan pemesanan dan pembayaran.</p>
    <p>Kami akan segera memproses tiket Anda.</p>
    <a href="dashboard.php" class="btn">Kembali ke Dashboard</a>
</div>

</body>
</html>
