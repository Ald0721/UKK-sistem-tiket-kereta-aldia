
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="../admin/dashboard.php">Perpus Digital - Admin</a>
        <a href="../logout.php" class="btn btn-danger btn-sm">Logout</a>
    </div>
</nav>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>📚 Daftar Buku</h3>
        <a href="tambah_buku.php" class="btn btn-success">+ Tambah Buku</a>
    </div>

    <table class="table table-bordered table-striped shadow-sm rounded-3 overflow-hidden">
        <thead class="table-primary text-center">
            <tr>
                <th>#</th>
                <th>Judul</th>
                <th>Penulis</th>
                <th>Kategori</th>
                <th>Jumlah</th>
                <th>File PDF</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
                                    <tr>
                    <td class="text-center">1</td>
                    <td>spongebob2</td>
                    <td>adk</td>
                    <td>gatau</td>
                    <td class="text-center">21 buku</td>
                    <td class="text-center">
                                                    <a href="../uploads/67f8950b249b9_1492354991_Contoh+Surat+Lampiran+Skripsi.pdf" target="_blank" class="btn btn-sm btn-outline-info">📄 Lihat File</a>
                                            </td>
                    <td class="text-center">
                        <a href="edit_buku.php?id=2" class="btn btn-sm btn-warning">✏️ Edit</a>
                        <a href="hapus_buku.php?id=2" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus buku ini?')">🗑️ Hapus</a>
                    </td>
                </tr>
                            </tbody>
    </table>
</div>
</body>
</html>
