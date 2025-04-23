<?php
session_start();
require_once "config/Database.php";
require_once "models/Pemesanan.php";

if (!isset($_SESSION['user'])) {
    header("Location: auth/login.php");
    exit;
}

$database = new Database();
$db = $database->connect();

$pemesananModel = new Pemesanan($db);
$orders = $pemesananModel->getByUserId($_SESSION['user']['id']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Pemesanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .content-wrapper {
            margin-left: 250px;
            padding: 20px;
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

        body {
            margin: 0;
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<?php include 'sidebar.php'; ?>

<!-- Main Content -->
<div class="content-wrapper">
    <div class="container-fluid mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>Riwayat Order</h3>
            <a href="jadwal_kereta.php" class="btn btn-primary">Go Order</a>
        </div>

        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Tgl Order</th>
                    <th>Asal</th>
                    <th>Tujuan</th>
                    <th>Tgl Berangkat</th>
                    <th>Waktu</th>
                    <th>Tiket</th>
                    <th>Harga</th>
                    <th>Total</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; while ($row = $orders->fetch(PDO::FETCH_ASSOC)): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= date('d M Y H:i', strtotime($row['waktu_pemesanan'])) ?></td>
                    <td><?= htmlspecialchars($row['asal']) ?></td>
                    <td><?= htmlspecialchars($row['tujuan']) ?></td>
                    <td><?= date('d M Y', strtotime($row['tanggal_berangkat'])) ?></td>
                    <td><?= htmlspecialchars($row['waktu_berangkat']) ?></td>
                    <td><?= $row['jumlah_tiket'] ?></td>
                    <td>Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
                    <td>Rp <?= number_format($row['total_bayar'], 0, ',', '.') ?></td>
                    <td>
                        <?php if ($row['status_bayar'] === 'pending'): ?>
                            <span class="badge bg-warning text-dark">Belum Bayar</span>
                        <?php else: ?>
                            <span class="badge bg-success">Lunas</span>
                            <!-- Print Button -->
                            <button onclick="printTicket(<?= $row['id'] ?>)" class="btn btn-info btn-sm">Print Ticket</button>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
// Function to print the ticket
function printTicket(id) {
    // Fetch ticket data using Ajax (ensure 'getTicket.php' is set to return the ticket HTML)
    fetch('getTicket.php?id=' + id)
        .then(response => response.text())
        .then(data => {
            // Open a new window to display the ticket content
            const printWindow = window.open('', '_blank', 'width=800,height=600');
            printWindow.document.write(data); // Write ticket data into the new window
            printWindow.document.close(); // Close the document stream to load the content
            printWindow.print(); // Trigger the print dialog
        })
        .catch(error => console.error('Error:', error));
}
</script>
</body>
</html>
