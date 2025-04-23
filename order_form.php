<?php
session_start();
require_once "config/Database.php";
require_once "models/Jadwal.php";

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: auth/login.php");
    exit;
}

if (!isset($_GET['id'])) {
    echo "Jadwal tidak ditemukan.";
    exit;
}

$database = new Database();
$db = $database->connect();   

$jadwal = new Jadwal($db);
$data = $jadwal->getById($_GET['id']);

if (!$data) {
    echo "Jadwal tidak valid.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Form Pemesanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h2>Form Pemesanan Tiket</h2>
    <form method="POST" enctype="multipart/form-data" action="proses_order.php">
    <input type="hidden" name="id_user" value="<?= $_SESSION['user']['id'] ?>">
    <input type="hidden" name="id_jadwal" value="<?= $_GET['id'] ?>">

    <div class="mb-3">
        <label for="jumlah_tiket" class="form-label">Jumlah Tiket</label>
        <input type="number" name="jumlah_tiket" id="jumlah_tiket" class="form-control" required min="1">
    </div>

    <div id="penumpang-section" class="mb-4"></div>

    <div class="mb-3">
        <label for="metode_bayar" class="form-label">Metode Pembayaran</label>
        <select name="metode_bayar" id="metode_bayar" class="form-select" required>
            <option value="">-- Pilih Metode --</option>
            <option value="transfer">Transfer Bank</option>
            <option value="credit card">Kartu Kredit</option>
            <option value="e-wallet">E-Wallet</option>
        </select>
    </div>

    <!-- Opsi tambahan: Bank -->
    <div class="mb-3 d-none" id="bank-options">
        <label for="bank" class="form-label">Pilih Bank</label>
        <select name="bank" class="form-select">
            <option value="bca">BCA</option>
            <option value="bni">BNI</option>
            <option value="bri">BRI</option>
            <option value="mandiri">Mandiri</option>
        </select>
    </div>

    <!-- Opsi tambahan: E-Wallet -->
    <div class="mb-3 d-none" id="ewallet-options">
        <label for="ewallet" class="form-label">Pilih E-Wallet</label>
        <select name="ewallet" class="form-select">
            <option value="dana">DANA</option>
            <option value="ovo">OVO</option>
            <option value="gopay">GoPay</option>
            <option value="shopeepay">ShopeePay</option>
        </select>
    </div>

    <div class="mb-3">
        <label for="bukti_bayar" class="form-label">Bukti Pembayaran (Foto)</label>
        <input type="file" name="bukti_bayar" class="form-control" accept="image/*" required>
    </div>

    <button type="submit" class="btn btn-primary">Pesan Tiket</button>
</form>

</div>

<script>
    const metodeSelect = document.getElementById("metode_bayar");
    const bankOptions = document.getElementById("bank-options");
    const ewalletOptions = document.getElementById("ewallet-options");
    const jumlahTiketInput = document.getElementById("jumlah_tiket");
    const penumpangSection = document.getElementById("penumpang-section");

    metodeSelect.addEventListener("change", function () {
        const value = this.value;
        bankOptions.classList.add("d-none");
        ewalletOptions.classList.add("d-none");

        if (value === "transfer") {
            bankOptions.classList.remove("d-none");
        } else if (value === "e-wallet") {
            ewalletOptions.classList.remove("d-none");
        }
    });

    jumlahTiketInput.addEventListener("input", function () {
        const jumlah = parseInt(this.value);
        penumpangSection.innerHTML = "";

        if (!isNaN(jumlah) && jumlah > 0) {
            for (let i = 1; i <= jumlah; i++) {
                penumpangSection.innerHTML += `
                    <div class="card p-3 mb-3">
                        <h5>Penumpang ${i}</h5>
                        <div class="mb-2">
                            <label class="form-label">Nama Penumpang</label>
                            <input type="text" name="nama_penumpang[]" class="form-control" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Nomor Kursi</label>
                            <input type="text" name="nomor_kursi[]" class="form-control" required>
                        </div>
                    </div>
                `;
            }
        }
    });
</script>

</body>
</html>
