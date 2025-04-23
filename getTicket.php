<?php
require_once "config/Database.php";
require_once "models/Pemesanan.php";

if (isset($_GET['id'])) {
    $database = new Database();
    $db = $database->connect();
    $pemesananModel = new Pemesanan($db);
    
    // Get the order details by ID
    $order = $pemesananModel->getById($_GET['id']); // Assuming you have a method getById in Pemesanan class

    if ($order) {
        // Generate the ticket HTML
        $ticketHtml = '
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; margin: 0; padding: 0; }
                .ticket { width: 100%; max-width: 600px; margin: 20px auto; padding: 20px; border: 2px solid #000; }
                .ticket h1 { text-align: center; }
                .ticket p { margin: 5px 0; }
                .ticket .details { margin-top: 20px; }
                .ticket .details p { font-weight: bold; }
            </style>
        </head>
        <body>
            <div class="ticket">
                <h1>Tiket Kereta</h1>
                <p><strong>Nama Pemesan:</strong> ' . htmlspecialchars($order['nama_user']) . '</p>
                <p><strong>Asal:</strong> ' . htmlspecialchars($order['asal']) . '</p>
                <p><strong>Tujuan:</strong> ' . htmlspecialchars($order['tujuan']) . '</p>
                <p><strong>Tanggal Berangkat:</strong> ' . date('d M Y', strtotime($order['tanggal_berangkat'])) . '</p>
                <p><strong>Waktu Berangkat:</strong> ' . htmlspecialchars($order['waktu_berangkat']) . '</p>
                <p><strong>Jumlah Tiket:</strong> ' . $order['jumlah_tiket'] . '</p>
                <p><strong>Total Harga:</strong> Rp ' . number_format($order['total_bayar'], 0, ',', '.') . '</p>
                <div class="details">
                    <p><strong>Status Pembayaran:</strong> ' . ($order['status_bayar'] === 'pending' ? 'Belum Bayar' : 'Lunas') . '</p>
                </div>
            </div>
        </body>
        </html>';

        echo $ticketHtml;
    }
}
?>
