<?php
require_once 'vendor/autoload.php';
require_once "config/Database.php";
require_once "models/Payment.php";

use Dompdf\Dompdf;
use Dompdf\Options;

// Setup Dompdf
$options = new Options();
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);

// Ambil data pembayaran
$database = new Database();
$db = $database->connect();
$paymentModel = new Payment($db);
$payments = $paymentModel->getPaymentsByStatus('berhasil');

// Buat HTML untuk PDF
ob_start(); // Start buffer
?>

<h2 style="text-align:center;">Log Pembayaran Berhasil</h2>
<table border="1" cellspacing="0" cellpadding="8" width="100%">
    <thead>
        <tr style="background-color:#f2f2f2;">
            <th>ID Pemesanan</th>
            <th>Metode Bayar</th>
            <th>Bukti Bayar</th>
            <th>Waktu Bayar</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $payments->fetch(PDO::FETCH_ASSOC)) : ?>
            <tr>
                <td><?= htmlspecialchars($row['id_pemesanan']) ?></td>
                <td><?= htmlspecialchars($row['metode_bayar']) ?></td>
                <td><?= htmlspecialchars($row['bukti_bayar']) ?></td>
                <td><?= $row['waktu_bayar'] ?></td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php
$html = ob_get_clean(); // Ambil hasil buffer

// Generate PDF
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Tampilkan di browser
$dompdf->stream("log_pembayaran.pdf", ["Attachment" => false]);
exit;
